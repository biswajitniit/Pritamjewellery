<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Finishproductreceivedentry;
use App\Models\Finishproductreceivedentryitem;
use App\Models\Issuetokarigaritem;
use App\Models\Karigar;
use App\Models\Location;
use App\Models\Vouchertype;
use Illuminate\Support\Facades\Auth;

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
        // $issuetokarigaritems = Issuetokarigaritem::with('karigar')->where('finish_product_received', 'No')->get();
        $issuetokarigaritems = Issuetokarigaritem::select('kid')->where('finish_product_received', 'No')->distinct()->get();
        $locations = Location::get();
        return view('finishproductreceivedentry.add', compact('issuetokarigaritems', 'locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'karigar_id'                => 'required',
            ],
            [
                'karigar_id.required' => 'Selection of karigar is Required', // custom message
            ]
        );

        // Get voucher type FIRST (before validation to auto-generate vou_no)
        $voucherType = Vouchertype::where('voucher_type', 'finished_goods_entry')
            ->where('location_id', $request->location_id)
            ->first();

        if (!$voucherType) {
            return back()->withErrors(['Voucher type not found for this location.']);
        }

        // Generate next voucher number
        $nextNo = (int)$voucherType->lastno + 1;
        $voucherNo = str_pad($nextNo, 3, '0', STR_PAD_LEFT);

        // Final formatted voucher number
        //$voucherNoPadded = $voucherType->prefix . '/' . $voucherNo . '/' . $voucherType->applicable_year;
        $voucherNoPadded = $voucherType->prefix . '/' . $voucherType->lastno . '/' . $voucherType->applicable_year;



        $karigar = Karigar::where('kid', $request->karigar_id)->first();
        if (!$karigar) {
            return back()->withErrors(['Karigar not found.']);
        }

        $finishproductreceivedentry = Finishproductreceivedentry::create([
            'karigar_id'           => strip_tags($karigar->id),
            'karigar_name'         => strip_tags($request->karigar_name),
            'bal'                  => strip_tags($request->bal),
            'voucher_date'         => strip_tags($request->voucher_date),
            'location_id'          => strip_tags($request->location_id),
            'voucher_no'           => $voucherNoPadded,
            'voucher_purity'       => strip_tags($request->voucher_purity),
            'voucher_net_wt'       => strip_tags($request->voucher_net_wt),
            'voucher_loss'         => strip_tags($request->voucher_loss),
            'voucher_total_wt'     => strip_tags($request->voucher_total_wt),
            'voucher_stone_wt'     => strip_tags($request->voucher_stone_wt),
            'voucher_mina'         => strip_tags($request->voucher_mina),
            'voucher_kundan'       => strip_tags($request->voucher_kundan),
            'created_by'           => Auth::user()->name
        ]);
        $lastInsertedId = $finishproductreceivedentry->id;

        foreach ($request->item_code as $key => $val) {
            Finishproductreceivedentryitem::create([
                'fprentries_id'              => $lastInsertedId,
                'job_no'                     => strip_tags($request->job_no[$key]),
                'item_code'                  => strip_tags($request->item_code[$key]),
                'design'                     => strip_tags($request->design[$key]),
                'description'                => strip_tags($request->description[$key]),
                'size'                       => strip_tags($request->size[$key]),
                'uom'                        => strip_tags($request->uom[$key]),
                'qty'                        => strip_tags($request->qty[$key]),
                'receive_qty_from_karigar'   => strip_tags($request->hidden_qty[$key]),
                'purity'                     => strip_tags($request->purity[$key]),
                'gross_wt'                   => strip_tags($request->gross_wt[$key]),
                'st_weight'                  => strip_tags($request->st_weight[$key]),
                'k_excess'                   => strip_tags($request->k_excess[$key]),
                'mina'                       => strip_tags($request->mina[$key]),
                'loss_percentage'            => strip_tags($request->loss_percentage[$key]),
                'loss_wt'                    => strip_tags($request->loss_wt[$key]),
                'pure'                       => strip_tags($request->pure[$key]),
                'net'                        => strip_tags($request->net[$key])
            ]);
        }

        // Update the voucher type's lastno (as INTEGER, not padded)
        $voucherType->lastno = $voucherNo;
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

    public function getissuetokarigaritems(Request $request)
    {
        $issuetokarigaritems = Issuetokarigaritem::where('kid', $request->kid)
            ->where('finish_product_received', 'No')
            ->get();

        $html = '';
        $count = 1;
        $bal = 0;

        foreach ($issuetokarigaritems as $item) {
            $purity = GetProductPurity($item->item_code);

            for ($i = 0; $i < $item->qty; $i++) {
                $html .= '<div class="row g-2 mb-2 row_id_' . $count . '">';

                //     $html .= '<div class="col-md-1">
                //                    <input type="text" name="job_no[]" id="job_no_' . $count . '" class="form-control form-control-sm rounded-0" value="' . $item->job_no . '" readonly>
                //               </div>';

                $html .= '<input type="hidden" name="job_no[]" value="' . $item->job_no . '">';

                $html .= '<div class="col-md-1">
                            <input type="text" name="item_code[]" id="item_code_' . $count . '" class="form-control form-control-sm rounded-0" value="' . $item->item_code . '" readonly>
                        </div>';

                $html .= '<div class="col-md-1-5">
                            <input type="hidden" name="design[]" value="' . $item->design . '">
                            <input type="text" name="description[]" id="description_' . $count . '" class="form-control form-control-sm rounded-0" value="' . $item->description . '" readonly>
                        </div>';

                $html .= '<div class="col-md-0-8">
                            <input type="text" name="size[]" id="size_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $item->size . '" readonly>
                        </div>';

                $html .= '<div class="col-md-0-8">
                            <input type="text" name="uom[]" id="uom_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $item->uom . '" readonly>
                        </div>';

                $html .= '<div class="col-md-0-8">
                            <input type="hidden" name="hidden_qty[]" id="hidden_qty_' . $count . '" class="gettotalqty" value="1">
                            <input type="text" name="qty[]" id="qty_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="1">
                        </div>';

                $html .= '<div class="col-md-0-8">
                            <input type="text" name="purity[]" id="purity_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $purity->purity . '">
                        </div>';

                $html .= '<div class="col-md-0-8">
                            <input type="text" name="gross_wt[]" id="gross_wt_' . $count . '" class="form-control form-control-sm rounded-0 text-end" onkeyup="netwtcalculation(' . $count . ')">
                        </div>';

                $html .= '<div class="col-md-0-8">
                            <input type="text" name="st_weight[]" id="st_weight_' . $count . '" class="form-control form-control-sm rounded-0 text-end" onkeyup="netwtcalculation(' . $count . ')">
                        </div>';

                $html .= '<div class="col-md-0-8">
                            <input type="text" name="k_excess[]" id="k_excess_' . $count . '" class="form-control form-control-sm rounded-0 text-end" onkeyup="netwtcalculation(' . $count . ')">
                        </div>';

                $html .= '<div class="col-md-0-8">
                            <input type="text" name="mina[]" id="mina_' . $count . '" class="form-control form-control-sm rounded-0 text-end" onkeyup="netwtcalculation(' . $count . ')">
                        </div>';

                $html .= '<div class="col-md-0-7">
                            <input type="text" name="loss_percentage[]" id="loss_percentage_' . $count . '" class="form-control form-control-sm rounded-0 text-end" onkeyup="losswtcalculation(' . $count . ')">
                        </div>';

                $html .= '<div class="col-md-0-7">
                            <input type="text" name="loss_wt[]" id="loss_wt_' . $count . '" class="form-control form-control-sm rounded-0 text-end">
                        </div>';

                $html .= '<div class="col-md-0-8">
                            <input type="text" name="pure[]" id="pure_' . $count . '" class="form-control form-control-sm rounded-0 text-end">
                        </div>';

                $html .= '<div class="col-md-0-8">
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
    }



    /*
    public function getissuetokarigaritems(Request $request)
    {
        $issuetokarigaritems = Issuetokarigaritem::where('kid', $request->kid)
            ->where('finish_product_received', 'No')
            ->get();

        $html = '';
        $count = 1;
        $bal = 0;

        foreach ($issuetokarigaritems as $item) {
            $purity = GetProductPurity($item->item_code);

            $html .= '<div class="row g-2 mb-2 row_id_' . $count . '">';

            $html .= '<div class="col-md-1">
            <input type="text" name="job_no[]" id="job_no_' . $count . '" class="form-control form-control-sm rounded-0" value="' . $item->job_no . '" readonly>
        </div>';

            $html .= '<div class="col-md-1-5">
            <input type="text" name="item_code[]" id="item_code_' . $count . '" class="form-control form-control-sm rounded-0" value="' . $item->item_code . '" readonly>
        </div>';

            $html .= '<div class="col-md-1-5">
            <input type="hidden" name="design[]" value="' . $item->design . '">
            <input type="text" name="description[]" id="description_' . $count . '" class="form-control form-control-sm rounded-0" value="' . $item->description . '" readonly>
        </div>';

            $html .= '<div class="col-md-0-8">
            <input type="text" name="size[]" id="size_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $item->size . '" readonly>
        </div>';

            $html .= '<div class="col-md-0-8">
            <input type="text" name="uom[]" id="uom_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $item->uom . '" readonly>
        </div>';

            $html .= '<div class="col-md-0-8">
            <input type="hidden" name="hidden_qty[]" id="hidden_qty_' . $count . '" class="gettotalqty" value="' . $item->qty . '">
            <input type="text" name="qty[]" id="qty_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $item->qty . '">
        </div>';

            $html .= '<div class="col-md-0-8">
            <input type="text" name="purity[]" id="purity_' . $count . '" class="form-control form-control-sm rounded-0 text-end" value="' . $purity->purity . '">
        </div>';

            $html .= '<div class="col-md-0-8">
            <input type="text" name="gross_wt[]" id="gross_wt_' . $count . '" class="form-control form-control-sm rounded-0 text-end">
        </div>';

            $html .= '<div class="col-md-0-8">
            <input type="text" name="st_weight[]" id="st_weight_' . $count . '" class="form-control form-control-sm rounded-0 text-end">
        </div>';

            $html .= '<div class="col-md-0-8">
            <input type="text" name="k_excess[]" id="k_excess_' . $count . '" class="form-control form-control-sm rounded-0 text-end">
        </div>';

            $html .= '<div class="col-md-0-8">
            <input type="text" name="mina[]" id="mina_' . $count . '" class="form-control form-control-sm rounded-0 text-end">
        </div>';

            $html .= '<div class="col-md-0-8">
            <input type="text" name="pure[]" id="pure_' . $count . '" class="form-control form-control-sm rounded-0 text-end">
        </div>';

            $html .= '<div class="col-md-0-8">
            <input type="text" name="net[]" id="net_' . $count . '" class="form-control form-control-sm rounded-0 text-end">
        </div>';

            $html .= '</div>'; // End of row

            $bal += $item->qty;
            $count++;
        }

        return response()->json([
            'ohtml' => $html,
            'obal'  => $bal,
        ]);
    }*/
    public function getkarigardetailsissuetokarigaritems(Request $request)
    {
        $karigar = Karigar::where('kid', $request->kid)->first();
        return response()->json([
            "kname" => $karigar->kname,
        ]);
    }
}
