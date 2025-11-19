<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Finishedproductpdi;
use App\Models\Karigar;
use App\Models\Location;
use App\Models\Qualitycheckitem;
use App\Models\StockEffect;
use App\Models\Vouchertype;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FinishedproductpdiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $purity = $request->query('purity');
        $kid = $request->query('kid');

        // $qualitycheckitems = Qualitycheckitem::with('karigar')
        //     ->when($purity, function ($query, $purity) {
        //         return $query->where('purity', $purity);
        //     })
        //     ->when($kid && $kid !== 'all', function ($query) use ($kid) {
        //         return $query->where('karigar_id', $kid);
        //     })
        //     ->where('pdi_list', 'No')
        //     ->where('remark_items', 'Accept')
        //     ->paginate(100);
        
        $qualitycheckitems = Qualitycheckitem::with('karigar')
            ->leftJoin('finishproductreceivedentryitems as fpri', function ($join) {
                $join->on('fpri.item_code', '=', 'qualitycheckitems.item_code')
                     ->on('fpri.job_no', '=', 'qualitycheckitems.job_no');
            })
            ->select('qualitycheckitems.*', 'fpri.gross_wt')
            ->when($purity, function ($query, $purity) {
                return $query->where('qualitycheckitems.purity', $purity);
            })
            ->when($kid && $kid !== 'all', function ($query) use ($kid) {
                return $query->where('qualitycheckitems.karigar_id', $kid);
            })
            ->where('qualitycheckitems.pdi_list', 'No')
            ->where('qualitycheckitems.remark_items', 'Accept')
            ->paginate(100);        
        
        

        // Active karigars with pending (pdi_list = No)
        $karigars = Karigar::where('is_active', 'Yes')
            ->whereHas('qualitycheckitems', function ($query) {
                $query->where('pdi_list', 'No');
            })
            ->orderBy('kname')
            ->get();

        // Distinct purity values where pdi_list = No
        $purities = Qualitycheckitem::where('pdi_list', 'No')

            ->select('purity')
            ->groupBy('purity')
            ->orderBy('purity')
            ->pluck('purity');

        $locations = Location::get();
        return view('finishedproductpdis.list', compact('karigars', 'qualitycheckitems', 'purities', 'purity', 'kid', 'locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ✅ Validate all input before doing anything
        $validator = Validator::make($request->all(), [
            'location_id' => 'required|integer|exists:locations,id',
            'date'        => 'required|date',
            'selectstockout' => 'required|array|min:1',
            'selectstockout.*' => 'string',
            'total_wt'    => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // 🔒 Use transaction for complete rollback safety
        DB::beginTransaction();
        try {

            // Lock voucher row for concurrency safety
            $voucherType = Vouchertype::where('voucher_type', 'finished_product_pdi_list')
                ->where('location_id', $request->location_id)
                ->lockForUpdate()
                ->first();

            if (! $voucherType) {
                return back()->withInput()->withErrors(['Voucher type not found for this location.']);
            }

            // 🔹 Generate next voucher number
            $nextNo = (int) $voucherType->lastno + 1;
            $voucherNo = str_pad($nextNo, 3, '0', STR_PAD_LEFT);


            $items = $request->input('selectstockout'); // This is an array
            foreach ($items as $item) {

                list($id, $amount) = explode(',', $item);

                $qualitycheckitems = Qualitycheckitem::where('id', $id)->first();
                $karigars = Karigar::where('id', $qualitycheckitems->karigar_id)->first();

                $getRate = GetItemcodeRate($qualitycheckitems->item_code);
                $getALab = GetItemcodeAlabStoneChg($qualitycheckitems->item_code);
                if ($getALab && $getALab->category != 'Stone') {
                    $getalabs = $getALab->pcs * $getALab->amount;
                } else {
                    $getalabs = 0.00;
                }

                $getAStone = GetItemcodeAlabStoneChg($qualitycheckitems->item_code);
                if ($getAStone && $getAStone->category != 'Stone') {
                    $getastones = $getAStone->pcs * $getAStone->amount;
                } else {
                    $getastones = 0.00;
                }

                $getLoss = GetItemcodeLoss($qualitycheckitems->item_code);


                Finishedproductpdi::create([
                    'location_id'          => $request->location_id,
                    'vou_no'               => $request->vou_no,
                    'date'                 => $request->date,
                    'job_no'               => strip_tags($qualitycheckitems->job_no),
                    'item_code'            => strip_tags($qualitycheckitems->item_code),
                    'qty'                  => strip_tags($qualitycheckitems->order_qty),
                    'size'                 => strip_tags($qualitycheckitems->size),
                    'uom'                  => strip_tags($qualitycheckitems->uom),
                    'net_wt'               => $qualitycheckitems->qualitycheckitems,
                    'purity'               => $qualitycheckitems->purity,
                    'rate'                 => @$getRate->lab_charge,
                    'a_lab'                => @$getalabs, // sum of amount product stone details
                    'stone_chg'            => @$getastones,
                    'loss'                 => @$getLoss->loss,
                    'kid'                  => strip_tags($karigars->kid),
                    'delivered_stock_out'  => 'No'
                ]);

                Qualitycheckitem::where('id', $id)
                    ->update([
                        'pdi_list' => 'Yes'
                    ]);
            }

            //Fetch related data
            $locationName = Location::where('id', $request->location_id)->value('location_name');
            //$company = Company::firstOrFail();
            $company = Customer::where('id', 2)->first();
            $customer = Customer::where('id', 1)->first();

            $items = $request->input('selectstockout'); // Array like ["3,58.78,91.6", "4,59.00,92.0"]

            if (!empty($items)) {
                // Get first row purity
                $firstItemParts = explode(',', $items[0]);
                $firstPurity = $firstItemParts[3] ?? null; // fourth value (91.6)
            } else {
                $firstPurity = null;
            }

            $pureWt = round(($request->total_wt * $firstPurity) / 100, 3);

            // Company Stock Effect
            StockEffect::create([
                'vou_no'                      => $request->vou_no,
                'metal_receive_entries_date'  => $request->date,
                'location_name'               => $locationName,
                'ledger_name'                 => $company->cust_name,
                'ledger_code'                 => $company->id,
                'ledger_type'                 => 'Vendor',
                'metal_category'              => 'Metal',
                'metal_name'                  => 'GOLD',
                'net_wt'                      => '-' . $request->total_wt,
                'purity'                      => $firstPurity,
                'pure_wt'                     => '-' . $pureWt,
            ]);


            StockEffect::create([
                'vou_no'                      => $request->vou_no,
                'metal_receive_entries_date'  => $request->date,
                'location_name'               => $locationName,
                'ledger_name'                 => $customer->cust_name,
                'ledger_code'                 => $customer->id,
                'ledger_type'                 => 'Customer',
                'metal_category'              => 'Metal',
                'metal_name'                  => 'GOLD',
                'net_wt'                      => '-' . $request->total_wt,
                'purity'                      => $firstPurity,
                'pure_wt'                     => '-' . $pureWt,
            ]);

            // Update last voucher number
            $voucherType->update(['lastno' => $nextNo]);

            // ✅ Commit all changes
            DB::commit();

            return redirect()->route('stockoutpdilists.index')
                ->withSuccess('Finished Product PDI saved successfully.');
                
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->withInput()->withErrors(['error' => 'Failed to save PDI. ' . $e->getMessage()]);
        }
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
}