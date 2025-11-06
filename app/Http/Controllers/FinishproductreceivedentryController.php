<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Finishproductreceivedentry;
use App\Models\Finishproductreceivedentryitem;
use App\Models\Issuetokarigaritem;
use App\Models\Karigar;
use App\Models\Location;
use App\Models\Product;
use App\Models\Qualitycheck;
use App\Models\Qualitycheckitem;
use App\Models\StockEffect;
use App\Models\Stone;
use App\Models\Vouchertype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinishproductreceivedentryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $finishproductreceivedentrys = Finishproductreceivedentry::with('karigar')->get();

        return view('finishproductreceivedentry.list', compact('finishproductreceivedentrys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $karigars = Karigar::whereIn('kid', function ($query) {
        //     $query->select('kid')
        //         ->from('issuetokarigaritems')
        //         ->where('finish_product_received', 'No')
        //         ->where('quality_check', 'Yes');
        // })->get();

        $karigars = DB::table('qualitychecks as qc')
            ->join('karigars as k', 'k.id', '=', 'qc.karigar_id')
            ->select('k.kid', 'k.kname', 'qc.status', DB::raw('COUNT(qc.id) as total_qc'))
            ->where('qc.status', 'Processing')
            ->groupBy('k.kid', 'k.kname', 'qc.status')
            ->get();

        //$locations = Location::get();
        $locations = Location::whereHas('qualitychecks', function ($query) {
            $query->where('status', 'Processing');
        })->get(['id', 'location_name']);

        return view('finishproductreceivedentry.add', compact('karigars', 'locations'));
    }

    /**
     * Create both Customer & Company stock effect entries.
     */
    public function createStockEffectEntries(array $data, Company $company, string $karigarName): void
    {
        // Karigar Stock Effect
        StockEffect::create(array_merge($data, [
            'ledger_name' => $karigarName,
            'ledger_code' => $data['karigar_id'],
            'ledger_type' => 'Karigar',
            'net_wt' => '-' . $data['net_wt'],
            'pure_wt' => '-' . $data['pure_wt'],
        ]));

        // Company Stock Effect
        // StockEffect::create(array_merge($data, [
        //     'ledger_name' => $company->cust_name,
        //     'ledger_code' => $company->id,
        //     'ledger_type' => 'Vendor',
        //     'net_wt' => '+' . $data['net_wt'],
        //     'pure_wt' => '+' . $data['pure_wt'],
        // ]));
        
        // Company Stock Effect
        StockEffect::create(array_merge($data, [
            'ledger_name' => 'Bhagya Laxmi Jewellers',
            'ledger_code' => 2,
            'ledger_type' => 'Vendor',
            'net_wt' => '+' . $data['net_wt'],
            'pure_wt' => '+' . $data['pure_wt'],
        ]));        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'karigar_id' => 'required',
            ],
            [
                'karigar_id.required' => 'Selection of karigar is Required', // custom message
            ]
        );

        // 🔹 Lock voucher type row to prevent duplicate voucher numbers
        $voucherType = Vouchertype::where('voucher_type', 'quality_check')
            ->where('location_id', $request->location_id)
            ->lockForUpdate() // prevents race conditions
            ->first();

        if (! $voucherType) {
            return back()->withErrors(['Voucher type not found for this location.']);
        }

        // 🔹 Generate next voucher number (padded)
        $nextNo = (int) $voucherType->lastno + 1;
        $voucherNo = str_pad($nextNo, 3, '0', STR_PAD_LEFT);

        $karigar = Karigar::where('kid', $request->karigar_id)->first();
        if (! $karigar) {
            return back()->withErrors(['Karigar not found.']);
        }

        $finishproductreceivedentry = Finishproductreceivedentry::create([
            'karigar_id' => strip_tags($karigar->id),
            'karigar_name' => strip_tags($request->karigar_name),
            'bal' => strip_tags($request->bal),
            'voucher_date' => strip_tags($request->voucher_date),
            'location_id' => strip_tags($request->location_id),
            'voucher_no' => $request->voucher_no,
            'voucher_purity' => strip_tags($request->voucher_purity),
            'voucher_net_wt' => strip_tags($request->voucher_net_wt),
            'voucher_loss' => strip_tags($request->voucher_loss),
            'voucher_total_wt' => strip_tags($request->voucher_total_wt),
            'voucher_stone_wt' => strip_tags($request->voucher_stone_wt),
            'voucher_mina' => strip_tags($request->voucher_mina),
            'voucher_kundan' => strip_tags($request->voucher_kundan),
            'created_by' => Auth::user()->name,
        ]);
        $lastInsertedId = $finishproductreceivedentry->id;

        $voucher = $request->voucher_no;
        $parts = explode('/', $voucher);
        $middle = $parts[1]; // "002"

        $count = 1;

        $financialYearId = getFinancialYearIdByDate($request->voucher_date);

        if (! $financialYearId) {
            throw new \Exception('No financial year found for the given date: ' . $request->voucher_date);
        }

        foreach ($request->item_code as $key => $val) {

            $get_product = Product::where('item_code', $request->item_code[$key])->first();

            $barcodegenerator =
                str_pad($get_product->id, 5, '0', STR_PAD_LEFT)  // e.g. 00042
                . $middle                                        // e.g. 002
                . str_pad($count, 3, '0', STR_PAD_LEFT)          // e.g. 001
                . date('y');

            Finishproductreceivedentryitem::create([
                'financial_year_id' => $financialYearId,
                'fprentries_id' => $lastInsertedId,
                'barcode' => $barcodegenerator,
                'job_no' => strip_tags($request->job_no[$key]),
                'item_code' => strip_tags($request->item_code[$key]),
                'design' => strip_tags($request->design[$key]),
                'description' => strip_tags($request->description[$key]),
                'size' => strip_tags($request->size[$key]),
                'uom' => strip_tags($request->uom[$key]),
                'qty' => strip_tags($request->qty[$key]),
                'receive_qty_from_karigar' => strip_tags($request->hidden_qty[$key]),
                'purity' => strip_tags($request->purity[$key]),
                'gross_wt' => strip_tags($request->gross_wt[$key]),
                'st_weight' => strip_tags($request->st_weight[$key]),
                'k_excess' => strip_tags($request->k_excess[$key]),
                'mina' => strip_tags($request->mina[$key]),
                'loss_percentage' => strip_tags($request->loss_percentage[$key]),
                'loss_wt' => strip_tags($request->loss_wt[$key]),
                'pure' => strip_tags($request->pure[$key]),
                'net' => strip_tags($request->net[$key]),
            ]);
            $count++;

            // Update Issuetokarigaritem
            Issuetokarigaritem::where('kid', $karigar->kid)
                ->where('item_code', $request->item_code[$key])
                ->where('bal_qty', '0')
                ->update([
                    'finish_product_received' => 'Yes',
                ]);

            // Update Qualitycheck & Qualitycheckitem
            Qualitycheck::where('karigar_id', $karigar->id)
                ->where('item_code', $request->item_code[$key])
                ->where('status', 'Processing')
                ->update([
                    'status' => 'Complete',
                ]);

            Qualitycheckitem::where('karigar_id', $karigar->id)
                ->where('item_code', $request->item_code[$key])
                ->where('status', 'Processing')
                ->update([
                    'status' => 'Complete',
                ]);
        }

        // -------------------------
        // Update Stock
        // -------------------------
        $locationName = Location::where('id', $request->input('location_id'))->value('location_name');
        $firstPurity = isset($request->purity[0]) ? strip_tags($request->purity[0]) : 0.0;
        $company = Company::firstOrFail();

        // Define baseData (previously undefined)
        $baseData = [
            'location_name' => $locationName,
            'vou_no' => strip_tags($request->input('voucher_no')),
            // add any other base fields your StockEffect expects
        ];

        // Ensure numeric casts when calculating
        $netWt = (float) $request->input('voucher_net_wt');
        $purityVal = (float) $firstPurity;
        $metalPureWt = round(($netWt * $purityVal) / 100, 3);

        $data = array_merge($baseData, [
            'metal_receive_entries_date' => strip_tags($request->input('voucher_date')),
            'metal_category' => 'Metals',
            'metal_name' => 'GOLD',
            'karigar_id' => $karigar->id,
            'net_wt' => $netWt,
            'purity' => $purityVal,
            'pure_wt' => $metalPureWt,
        ]);

        // Call helper to create stock effect entries
        $this->createStockEffectEntries($data, $company, $request->input('karigar_name'));

        // 🔹 Update the voucher type's lastno with padded value
        $voucherType->lastno = $voucherNo; // ✅ stores 001, 002, etc.
        $voucherType->save();

        return redirect()->route('finishproductreceivedentries.index')->withSuccess('Finished Product Received record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /* public function getissuetokarigaritems(Request $request)
    {
        $issuetokarigaritems = Issuetokarigaritem::where('kid', $request->kid)
            ->where('finish_product_received', 'No')
            ->where('quality_check', 'Yes')
            ->get();

        $html = '';
        $count = 1;
        $bal = 0;

        foreach ($issuetokarigaritems as $item) {
            $purity = GetProductPurity($item->item_code);

            $isReadOnly = substr($item->item_code, -2) === '00';
            $readonlyAttr = $isReadOnly ? 'readonly' : '';
            $defaultValue = $isReadOnly ? '0.00' : '';


            $loss_percentage = Karigar::where('kid', $item->kid)->value('karigar_loss') ?? Product::where('item_code', $item->item_code)->value('loss') ?? 0;


            for ($i = 0; $i < $item->qty; $i++) {
                $html .= '<div class="row g-2 mb-2 row_id_' . $count . '">';

                //     $html .= '<div class="col-md-1">
                //                    <input type="text" name="job_no[]" id="job_no_' . $count . '" class="form-control form-control-sm rounded-0" value="' . $item->job_no . '" readonly>
                //               </div>';

                $html .= '<input type="hidden" name="job_no[]" value="' . $item->job_no . '">';

                $html .= '<div class="col-md-1-5">
                            <input type="text" name="item_code[]" id="item_code_' . $count . '" class="form-control form-control-sm rounded-0" value="' . $item->item_code . '" readonly>
                        </div>';

                $html .= '<div class="col-md-1-5">
                            <input type="hidden" name="design[]" value="' . $item->design . '">
                            <input type="text" name="description[]" id="description_' . $count . '" class="form-control form-control-sm rounded-0" value="' . $item->description . '" readonly>
                        </div>';

                $html .= '<div class="col-md-0-7">
                            <input type="text" name="size[]" id="size_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $item->size . '" readonly>
                        </div>';

                $html .= '<div class="col-md-0-7">
                            <input type="text" name="uom[]" id="uom_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $item->uom . '" readonly>
                        </div>';

                $html .= '<div class="col-md-0-7">
                            <input type="hidden" name="hidden_qty[]" id="hidden_qty_' . $count . '" class="gettotalqty" value="1">
                            <input type="text" name="qty[]" id="qty_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="1">
                        </div>';

                $html .= '<div class="col-md-0-8">
                            <input type="text" name="purity[]" id="purity_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $purity->purity . '">
                        </div>';

                $html .= '<div class="col-md-0-8">
                            <input type="text" name="gross_wt[]" id="gross_wt_' . $count . '" class="form-control form-control-sm rounded-0 text-end" onkeyup="netwtcalculation(' . $count . ')">
                        </div>';

                // Stone Wt
                $html .= '<div class="col-md-0-8">
                            <input type="text" name="st_weight[]" id="st_weight_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $defaultValue . '" onkeyup="netwtcalculation(' . $count . ')" ' . $readonlyAttr . '>
                        </div>';

                // K.Excess
                $html .= '<div class="col-md-0-8">
                            <input type="text" name="k_excess[]" id="k_excess_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $defaultValue . '" onkeyup="netwtcalculation(' . $count . ')" ' . $readonlyAttr . '>
                        </div>';

                // Mina
                $html .= '<div class="col-md-0-8">
                    <input type="text" name="mina[]" id="mina_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $defaultValue . '" onkeyup="netwtcalculation(' . $count . ')" ' . $readonlyAttr . '>
                </div>';

                $html .= '<div class="col-md-0-7">
                            <input type="text" name="loss_percentage[]" id="loss_percentage_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $loss_percentage . '" onkeyup="losswtcalculation(' . $count . ')">
                        </div>';

                $html .= '<div class="col-md-0-7">
                            <input type="text" name="loss_wt[]" id="loss_wt_' . $count . '" class="form-control form-control-sm rounded-0 text-end" readonly>
                        </div>';

                $html .= '<div class="col-md-0-8">
                            <input type="text" name="pure[]" id="pure_' . $count . '" class="form-control form-control-sm rounded-0 text-end">
                        </div>';

                $html .= '<div class="col-md-0-7">
                            <input type="text" name="net[]" id="net_' . $count . '" class="form-control form-control-sm rounded-0 text-end">
                        </div>';

                $html .= '</div>'; // End of row

                $count++;
                $bal++;
            }
        }

        return response()->json([
            'ohtml' => $html,
            'obal'  => $bal,
        ]);
    }*/

    /*
    // FINAL CODE
    public function getissuetokarigaritems(Request $request)
    {
        $issuetokarigaritems = Issuetokarigaritem::where('kid', $request->kid)
            ->where('finish_product_received', 'No')
            ->where('quality_check', 'Yes')
            ->get();

        $html = '';
        $count = 1;
        $bal = 0;

        foreach ($issuetokarigaritems as $item) {
            $purity = GetProductPurity($item->item_code);

            $isReadOnly = substr($item->item_code, -2) === '00';
            $readonlyAttr = $isReadOnly ? 'readonly' : '';
            $defaultValue = $isReadOnly ? '0.00' : '';

            // 🔑 Fetch all gross_wt_items for this job_no + item_code
            $grossWts = \DB::table('qualitycheckitems')
                ->where('job_no', $item->job_no)
                ->where('item_code', $item->item_code)
                ->orderBy('id') // keep consistent order
                ->pluck('gross_wt_items')
                ->toArray();

            $loss_percentage = Karigar::where('kid', $item->kid)->value('karigar_loss')
                ?? Product::where('item_code', $item->item_code)->value('loss')
                ?? 0;

            for ($i = 0; $i < $item->qty; $i++) {
                $html .= '<div class="row g-2 mb-2 row_id_' . $count . '">';

                $html .= '<input type="hidden" name="job_no[]" value="' . $item->job_no . '">';

                $html .= '<div class="col-md-1-5">
                        <input type="text" name="item_code[]" id="item_code_' . $count . '" class="form-control form-control-sm rounded-0" value="' . $item->item_code . '" readonly>
                    </div>';

                $html .= '<div class="col-md-1-5">
                        <input type="hidden" name="design[]" value="' . $item->design . '">
                        <input type="text" name="description[]" id="description_' . $count . '" class="form-control form-control-sm rounded-0" value="' . $item->description . '" readonly>
                    </div>';

                $html .= '<div class="col-md-0-7">
                        <input type="text" name="size[]" id="size_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $item->size . '" readonly>
                    </div>';

                $html .= '<div class="col-md-0-7">
                        <input type="text" name="uom[]" id="uom_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $item->uom . '" readonly>
                    </div>';

                $html .= '<div class="col-md-0-7">
                        <input type="hidden" name="hidden_qty[]" id="hidden_qty_' . $count . '" class="gettotalqty" value="1">
                        <input type="text" name="qty[]" id="qty_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="1">
                    </div>';

                $html .= '<div class="col-md-0-8">
                        <input type="text" name="purity[]" id="purity_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $purity->purity . '">
                    </div>';

                // ✅ Now gross_wt comes from qualitycheckitems.gross_wt_items (mapped by index)
                $html .= '<div class="col-md-0-8">
                                <input type="text" name="gross_wt[]"
                                    id="gross_wt_' . $count . '"
                                    class="form-control form-control-sm rounded-0 text-end"
                                    value="' . ($grossWts[$i] ?? '') . '"
                                    onkeyup="netwtcalculation(' . $count . ')">
                            </div>';

                // Stone Wt
                $html .= '<div class="col-md-0-8">
                        <input type="text" name="st_weight[]" id="st_weight_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $defaultValue . '" onkeyup="netwtcalculation(' . $count . ')" ' . $readonlyAttr . '>
                    </div>';

                // K.Excess
                $html .= '<div class="col-md-0-8">
                        <input type="text" name="k_excess[]" id="k_excess_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $defaultValue . '" onkeyup="netwtcalculation(' . $count . ')" ' . $readonlyAttr . '>
                    </div>';

                // Mina
                $html .= '<div class="col-md-0-8">
                        <input type="text" name="mina[]" id="mina_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $defaultValue . '" onkeyup="netwtcalculation(' . $count . ')" ' . $readonlyAttr . '>
                    </div>';

                $html .= '<div class="col-md-0-7">
                        <input type="text" name="loss_percentage[]" id="loss_percentage_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $loss_percentage . '" onkeyup="losswtcalculation(' . $count . ')">
                    </div>';

                $html .= '<div class="col-md-0-7">
                        <input type="text" name="loss_wt[]" id="loss_wt_' . $count . '" class="form-control form-control-sm rounded-0 text-end" readonly>
                    </div>';

                $html .= '<div class="col-md-0-8">
                        <input type="text" name="pure[]" id="pure_' . $count . '" class="form-control form-control-sm rounded-0 text-end">
                    </div>';

                $html .= '<div class="col-md-0-7">
                        <input type="text" name="net[]" id="net_' . $count . '" class="form-control form-control-sm rounded-0 text-end">
                    </div>';

                $html .= '</div>'; // End of row

                $count++;
                $bal++;
            }
        }

        return response()->json([
            'ohtml' => $html,
            'obal'  => $bal,
        ]);
    }*/

    /*
    public function getissuetokarigaritems(Request $request)
    {
        $getkid = Karigar::where('kid', $request->kid)->first();

        if (! $getkid) {
            return response()->json([
                'ohtml' => '',
                'obal' => 0,
                'error' => 'Karigar not found.',
            ], 404);
        }

        // ✅ Replace Issuetokarigaritem with Qualitycheck
        $qualitychecks = Qualitycheck::where('karigar_id', $getkid->id)
            ->where('status', 'Processing') // optional filter if you only want processing ones
            ->get();


        $html = '';
        $count = 1;
        $bal = 0;

        foreach ($qualitychecks as $item) {
            $purity = GetProductPurity($item->item_code);

            $isReadOnly = substr($item->item_code, -2) === '00';
            $readonlyAttr = $isReadOnly ? 'readonly' : '';
            $defaultValue = $isReadOnly ? '0.00' : '';

            // 🔑 Fetch all gross_wt_items for this job_no + item_code
            $grossWts = \DB::table('qualitycheckitems')
                ->where('job_no', $item->job_no)
                ->where('item_code', $item->item_code)
                ->orderBy('id')
                ->pluck('gross_wt_items')
                ->toArray();

            $loss_percentage = Karigar::where('id', $item->karigar_id)->value('karigar_loss')
                ?? Product::where('item_code', $item->item_code)->value('loss')
                ?? 0;

            // since qualitychecks table has receive_qty or order_qty,
            // use receive_qty as loop count (default 1 if null)
            $qty = $item->receive_qty ?? 1;

            for ($i = 0; $i < $qty; $i++) {
                $html .= '<div class="row g-2 mb-2 row_id_' . $count . '">';

                $html .= '<input type="hidden" name="job_no[]" value="' . $item->job_no . '">';

                $html .= '<div class="col-md-1-5">
                        <input type="text" name="item_code[]" id="item_code_' . $count . '" class="form-control form-control-sm rounded-0" value="' . $item->item_code . '" readonly>
                    </div>';

                $html .= '<div class="col-md-1-5">
                        <input type="hidden" name="design[]" value="' . $item->design . '">
                        <input type="text" name="description[]" id="description_' . $count . '" class="form-control form-control-sm rounded-0" value="' . $item->description . '" readonly>
                    </div>';

                $html .= '<div class="col-md-0-7">
                        <input type="text" name="size[]" id="size_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $item->size . '" readonly>
                    </div>';

                $html .= '<div class="col-md-0-7">
                        <input type="text" name="uom[]" id="uom_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $item->uom . '" readonly>
                    </div>';

                $html .= '<div class="col-md-0-7">
                        <input type="hidden" name="hidden_qty[]" id="hidden_qty_' . $count . '" class="gettotalqty" value="1">
                        <input type="text" name="qty[]" id="qty_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="1">
                    </div>';

                $html .= '<div class="col-md-0-8">
                        <input type="text" name="purity[]" id="purity_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $purity->purity . '">
                    </div>';

                // ✅ gross_wt from qualitycheckitems
                $html .= '<div class="col-md-0-8">
                        <input type="text" name="gross_wt[]" id="gross_wt_' . $count . '"
                            class="form-control form-control-sm rounded-0 text-end"
                            value="' . ($grossWts[$i] ?? '') . '"
                            onkeyup="netwtcalculation(' . $count . ')">
                    </div>';

                // stone wt
                $html .= '<div class="col-md-0-8">
                        <input type="text" name="st_weight[]" id="st_weight_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $defaultValue . '" onkeyup="netwtcalculation(' . $count . ')" ' . $readonlyAttr . '>
                    </div>';

                // K.Excess
                $html .= '<div class="col-md-0-8">
                        <input type="text" name="k_excess[]" id="k_excess_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $defaultValue . '" onkeyup="netwtcalculation(' . $count . ')" ' . $readonlyAttr . '>
                    </div>';

                // Mina
                $html .= '<div class="col-md-0-8">
                        <input type="text" name="mina[]" id="mina_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $defaultValue . '" onkeyup="netwtcalculation(' . $count . ')" ' . $readonlyAttr . '>
                    </div>';

                $html .= '<div class="col-md-0-7">
                        <input type="text" name="loss_percentage[]" id="loss_percentage_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $loss_percentage . '" onkeyup="losswtcalculation(' . $count . ')">
                    </div>';

                $html .= '<div class="col-md-0-7">
                        <input type="text" name="loss_wt[]" id="loss_wt_' . $count . '" class="form-control form-control-sm rounded-0 text-end" readonly>
                    </div>';

                $html .= '<div class="col-md-0-8">
                        <input type="text" name="pure[]" id="pure_' . $count . '" class="form-control form-control-sm rounded-0 text-end">
                    </div>';

                $html .= '<div class="col-md-0-7">
                        <input type="text" name="net[]" id="net_' . $count . '" class="form-control form-control-sm rounded-0 text-end">
                    </div>';

                $html .= '</div>'; // End row

                $count++;
                $bal++;
            }
        }

        return response()->json([
            'ohtml' => $html,
            'obal' => $bal,
        ]);
    } */


    public function getissuetokarigaritems(Request $request)
    {
        $getkid = Karigar::where('kid', $request->kid)->first();

        if (! $getkid) {
            return response()->json([
                'ohtml' => '',
                'obal'  => 0,
                'error' => 'Karigar not found.',
            ], 404);
        }

        // ✅ Fetch only qualitychecks that have non-rejected items
        $qualitychecks = Qualitycheck::where('karigar_id', $getkid->id)
            ->where('status', 'Processing')
            ->whereHas('items', function ($q) {
                $q->where('remark_items', '!=', 'Reject');
            })
            ->with(['items' => function ($q) {
                $q->where('remark_items', '!=', 'Reject');
            }])
            ->get();

        $html = '';
        $count = 1;
        $bal = 0;

        foreach ($qualitychecks as $qc) {
            foreach ($qc->items as $item) {
                $purity = GetProductPurity($item->item_code);

                $isReadOnly = substr($item->item_code, -2) === '00';
                $readonlyAttr = $isReadOnly ? 'readonly' : '';
                $defaultValue = $isReadOnly ? '0.00' : '';

                $loss_percentage = Karigar::where('id', $item->karigar_id)->value('karigar_loss')
                    ?? Product::where('item_code', $item->item_code)->value('loss')
                    ?? 0;

                $html .= '<div class="row g-2 mb-2 row_id_' . $count . '">';

                $html .= '<input type="hidden" name="job_no[]" value="' . $item->job_no . '">';

                $html .= '<div class="col-md-1-5">
                    <input type="text" name="item_code[]" id="item_code_' . $count . '" class="form-control form-control-sm rounded-0" value="' . $item->item_code . '" readonly>
                </div>';

                $html .= '<div class="col-md-1-5">
                    <input type="hidden" name="design[]" value="' . $item->design . '">
                    <input type="text" name="description[]" id="description_' . $count . '" class="form-control form-control-sm rounded-0" value="' . $item->description . '" readonly>
                </div>';

                $html .= '<div class="col-md-0-7">
                    <input type="text" name="size[]" id="size_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $item->size . '" readonly>
                </div>';

                $html .= '<div class="col-md-0-7">
                    <input type="text" name="uom[]" id="uom_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $item->uom . '" readonly>
                </div>';

                $html .= '<div class="col-md-0-7">
                    <input type="hidden" name="hidden_qty[]" id="hidden_qty_' . $count . '" class="gettotalqty" value="1">
                    <input type="text" name="qty[]" id="qty_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="1">
                </div>';

                $html .= '<div class="col-md-0-8">
                    <input type="text" name="purity[]" id="purity_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $purity->purity . '">
                </div>';

                $html .= '<div class="col-md-0-8">
                    <input type="text" name="gross_wt[]" id="gross_wt_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $item->gross_wt_items . '" onkeyup="netwtcalculation(' . $count . ')">
                </div>';

                $html .= '<div class="col-md-0-8">
                    <input type="text" name="st_weight[]" id="st_weight_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $defaultValue . '" onkeyup="netwtcalculation(' . $count . ')" ' . $readonlyAttr . '>
                </div>';

                $html .= '<div class="col-md-0-8">
                    <input type="text" name="k_excess[]" id="k_excess_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $defaultValue . '" onkeyup="netwtcalculation(' . $count . ')" ' . $readonlyAttr . '>
                </div>';

                $html .= '<div class="col-md-0-8">
                    <input type="text" name="mina[]" id="mina_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $defaultValue . '" onkeyup="netwtcalculation(' . $count . ')" ' . $readonlyAttr . '>
                </div>';

                $html .= '<div class="col-md-0-7">
                    <input type="text" name="loss_percentage[]" id="loss_percentage_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $loss_percentage . '" onkeyup="losswtcalculation(' . $count . ')">
                </div>';

                $html .= '<div class="col-md-0-7">
                    <input type="text" name="loss_wt[]" id="loss_wt_' . $count . '" class="form-control form-control-sm rounded-0 text-end" readonly>
                </div>';

                $html .= '<div class="col-md-0-8">
                    <input type="text" name="pure[]" id="pure_' . $count . '" class="form-control form-control-sm rounded-0 text-end">
                </div>';

                $html .= '<div class="col-md-0-7">
                    <input type="text" name="net[]" id="net_' . $count . '" class="form-control form-control-sm rounded-0 text-end">
                </div>';

                $html .= '</div>'; // End row

                $count++;
                $bal++;
            }
        }

        return response()->json([
            'ohtml' => $html,
            'obal'  => $bal,
        ]);
    }


    public function getkarigardetailsissuetokarigaritems(Request $request)
    {
        $karigar = Karigar::where('kid', $request->kid)->first();

        return response()->json([
            'kname' => $karigar->kname,
        ]);
    }
}
