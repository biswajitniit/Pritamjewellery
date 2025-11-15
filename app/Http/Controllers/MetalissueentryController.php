<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Metal;
use App\Models\Metalpurity;
use App\Models\Metalissueentry;
use App\Models\Customer;
use App\Models\Company;
use App\Models\Karigar;
use App\Models\Location;
use App\Models\Metalreceiveentry;
use App\Models\StockEffect;
use App\Models\Vouchertype;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Stone;
use App\Models\Miscellaneous;

class MetalissueentryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $metalissueentries = Metalissueentry::with(['karigar', 'metalpurity', 'metal', 'stone', 'miscellaneous'])
            ->simplePaginate(25);

        foreach ($metalissueentries as $entry) {
            switch ($entry->item_type) {
                case 'Metal':
                    $entry->item_name = $entry->metal?->metal_name;
                    break;
                case 'Stone':
                    $entry->item_name = $entry->stone?->description;
                    break;
                case 'Miscellaneous':
                    $entry->item_name = $entry->miscellaneous?->product_name;
                    break;
                default:
                    $entry->item_name = '-';
            }
        }

        return view('metalissueentries.list', compact('metalissueentries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $karigars = Karigar::where('is_active', 'Yes')->orderBy('kid')->get();
        $metals = Metal::where('is_active', 'Yes')->orderBy('metal_name')->get();
        $locations = Location::get();
        // $customers = Customer::where('id', 1)->where('is_active', 'Yes')->orderBy('cust_name')->get();
        $customers = Customer::where('is_active', 'Yes')->orderBy('cust_name')->get();
        // Active metal purities, ordered by purity name
        $metalpurities = Metalpurity::select('purity_id', 'purity')
            ->where('is_active', 'Yes')
            ->orderBy('purity')
            ->get();
        return view('metalissueentries.add', compact('metals', 'karigars', 'locations', 'customers', 'metalpurities'));
    }

    /**
     * Create both Customer & Company stock effect entries.
     */
    public function createStockEffectEntries(array $data, Customer $company, string $karigarName): void
    {
        // Customer Stock Effect
        StockEffect::create(array_merge($data, [
            'ledger_name' => $data['karigar_name'],
            'ledger_code' => $data['karigar_id'],
            'ledger_type' => 'Karigar',
            'net_wt'      => $data['net_wt'],
        ]));

        // Company Stock Effect
        StockEffect::create(array_merge($data, [
            'ledger_name' => $company->cust_name,
            'ledger_code' => $company->id,
            'ledger_type' => 'Vendor',
            'net_wt'      => '-' . $data['net_wt'],
        ]));
    }
    /**
     * Store Metal Issue Entry.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            // Validation
            $rules = [
                'location_id'              => 'required|exists:locations,id',
                'customer_id'              => 'required|exists:customers,id',
                'voucher_no'               => 'required|string|max:255',
                'metal_issue_entries_date' => 'required|date',
                'karigar_id'               => 'required|exists:karigars,id',
                'item_type'                => 'required|string|in:Metal,Stone,Miscellaneous',
                'item'                     => 'required|integer',
            ];

            // Conditional validation rules
            if ($request->item_type === 'Metal') {
                // Only require purity and weight for GOLD (item_id = 1)
                if ((int) $request->item_id === 1) {
                    $rules['purity_id']        = 'required|exists:metalpurities,purity_id';
                    $rules['converted_purity'] = 'required';
                    $rules['weight']           = 'required|numeric|min:0.01';
                    $rules['alloy_gm']         = 'required|numeric|min:0.01';
                    $rules['netweight_gm']     = 'required|numeric|min:0.01';
                } else {
                    // Other metals only require weight
                    $rules['weight'] = 'required|numeric|min:0.01';
                }
            }

            // For Findings or Miscellaneous
            if (in_array($request->item_type, ['Stone', 'Miscellaneous'])) {
                // Other metals (optional purity)
                $rules['purity_id']        = 'nullable|exists:metalpurities,purity_id';
                $rules['weight'] = 'required|numeric|min:0.01';
            }


            $validated = $request->validate($rules);



            // ✅ Get voucher type (locked for concurrency safety)
            $voucherType = Vouchertype::where('voucher_type', 'gold_issue_entry')
                ->where('location_id', $validated['location_id'])
                ->lockForUpdate()
                ->first();

            if (!$voucherType) {
                throw new \Exception('Voucher type not found for this location.');
            }

            // ✅ Generate next voucher number
            $nextNo    = (int) $voucherType->lastno + 1;
            $voucherNo = str_pad($nextNo, 3, '0', STR_PAD_LEFT);



            // ✅ Fetch stock entries safely
            $query = Metalreceiveentry::where('location_id', $validated['location_id'])
                ->where('item', $validated['item'])
                ->where('balance_qty', '>', 0);

            if (!empty($request->purity_id)) {
                $query->where('purity_id', $request->purity_id);
            }

            $entries = $query->orderBy('metal_receive_entries_id')
                ->lockForUpdate()
                ->get();



            $remainingWeight = $validated['weight'];

            foreach ($entries as $entry) {
                if ($remainingWeight <= 0) break;

                $issueQty = min($entry->balance_qty, $remainingWeight);
                $entry->issue_qty = ($entry->issue_qty ?? 0) + $issueQty;
                $entry->balance_qty -= $issueQty;
                $entry->last_entry_issue_date = now();
                $entry->last_entry_issue_by = Auth::user()->name;
                $entry->save();

                $remainingWeight -= $issueQty;
            }

            if ($remainingWeight > 0) {
                throw new \Exception('Insufficient stock available for issue.');
            }

            //Fetch related data
            $locationName = Location::where('id', $validated['location_id'])->value('location_name');
            $customerName = Customer::where('id', $validated['customer_id'])->value('cust_name');
            //$company = Company::firstOrFail();
            $company = Customer::where('id', 2)->first();

            // Base data for StockEffect
            $baseData = [
                'vou_no' => $request->voucher_no,
                'metal_receive_entries_date' => $request->metal_issue_entries_date,
                'location_name'  => $locationName,
                'metal_category' => $request->item_type,
                'customer_id'    => $request->customer_id,
                'net_wt'         => $request->weight
            ];

            // Handle each item type
            switch ($request->item_type) {
                case 'Metal':
                    if ($request->item == 1) {
                        $metalName   = Metal::where('metal_id', $request->item)->value('metal_name');
                        $metalPurity = Metalpurity::where('purity_id', $request->purity_id)->value('purity');
                        $pureWt      = round(($request->weight * $metalPurity) / 100, 3);
                        $data = array_merge($baseData, [
                            'metal_name' => $metalName,
                            'karigar_id' => $validated['karigar_id'],
                            'karigar_name' => $request->karigar_name,
                            'purity'     => $metalPurity,
                            'pure_wt'    => $pureWt,
                        ]);
                    } else {
                        $metalName = Metal::where('metal_id', $validated['item'])->value('metal_name');
                        $data = array_merge($baseData, [
                            'metal_name' => $metalName,
                            'karigar_id' => $validated['karigar_id'],
                            'karigar_name' => $request->karigar_name,
                            'purity'     => null,
                            'pure_wt'    => null,
                        ]);
                    }
                    $this->createStockEffectEntries($data, $company, $customerName);
                    break;
                case 'Stone':
                    $metalName = Stone::where('id', $request->item)->value('description');
                    $data = array_merge($baseData, [
                        'metal_name' => $metalName,
                        'karigar_id' => $request->karigar_id,
                        'karigar_name' => $request->karigar_name,
                        'purity' => null,
                        'pure_wt' => null,
                    ]);

                    $this->createStockEffectEntries($data, $company, $request->karigar_name);
                    break;

                case 'Miscellaneous':
                    $metalName = Miscellaneous::where('id', $request->item)->value('product_name');
                    $data = array_merge($baseData, [
                        'metal_name' => $metalName,
                        'karigar_id' => $request->karigar_id,
                        'purity' => null,
                        'pure_wt' => null,
                    ]);

                    $this->createStockEffectEntries($data, $company, $request->karigar_name);
                    break;
            }

            // ✅ Create new Metal Issue Entry
            Metalissueentry::create([
                'metalissueentries_id'     => (string) Str::uuid(),
                'location_id'              => $request->location_id,
                'customer_id'              => $request->customer_id,
                'voucher_no'               => $request->voucher_no,
                'metal_issue_entries_date' => $request->metal_issue_entries_date,
                'karigar_id'               => $request->karigar_id,
                'karigar_name'             => $request->karigar_name, // optional
                'item_type'                => $request->item_type,
                'item'                     => $request->item,
                'purity_id'                => $request->purity_id  ?? null,
                'converted_purity'         => $request->converted_purity ?? null,
                'weight'                   => $request->weight  ?? null,
                'alloy_gm'                 => $request->alloy_gm ?? null,
                'netweight_gm'             => $request->netweight_gm ?? null,
                'created_by'               => Auth::user()->name,
            ]);


            // Update last voucher number
            $voucherType->update(['lastno' => $nextNo]);

            DB::commit();

            return redirect()
                ->route('metalissueentries.index')
                ->with('success', 'Metal issue entry created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('Metal issue Entry Store Failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withInput()->withErrors([
                'error' => app()->environment('local')
                    ? $e->getMessage()
                    : 'Something went wrong while saving metal issue entry.',
            ]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $metalissueentries = Metalissueentry::findOrFail($id);
        $karigars = Karigar::findOrFail($metalissueentries->karigar_id);
        $metals   = Metal::findOrFail($metalissueentries->metal_id);
        $metalpurities   = Metalpurity::findOrFail($metalissueentries->purity_id);
        // dd($metals);
        /*
        $data = [
            'kname' => $karigars->kname,
            'address' =>  $karigars->address,
            'statecode' => $karigars->statecode,
            'gstin' => $karigars->gstin,
            'voucher_no' => $metalissueentries->voucher_no,
            'metal_issue_entries_date' => date('d/m/Y', strtotime(@$metalissueentries->metal_issue_entries_date)),
            'metal_hsn' => $metals->metal_hsn,
            'purity' => $metalpurities->purity,
            'weight' => $metalissueentries->weight,
            'netweight_gm' => $metalissueentries->netweight_gm,
            'metal_sac' => $metals->metal_sac

        ]; // any data for the view

        // Load view and pass data
        $pdf = Pdf::loadView('metalissueentries.view', $data);

        // Download PDF
        //return $pdf->download(date('Ymdhis') . 'invoice.pdf');

        // Or to stream it in the browser
        return $pdf->stream(date('Ymdhis') . 'invoice.pdf'); */


        //return view('metalissueentries.view', compact('metalissueentries', 'karigars', 'metals', 'metalpurities'));

        $pdf = Pdf::loadView('metalissueentries.view', compact('metalissueentries', 'karigars', 'metals', 'metalpurities'));
        $pdf->setPaper('A5', 'landscape');
        return $pdf->stream('metal-issue-voucher.pdf'); // opens in browser

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $metalissueentries = Metalissueentry::findOrFail($id);
        $karigars = Karigar::where('is_active', 'Yes')->orderBy('kid')->get();
        $metals = Metal::where('metal_category', $metalissueentries->metal_category)->orderBy('metal_category')->get();
        $metalpurities = Metalpurity::where('metal_id', $metalissueentries->metal_id)->orderBy('purity')->get();

        // $metalpurities = Metalreceiveentry::where('metalreceiveentries.metal_id', $metalissueentries->metal_id)
        //     ->join('metalpurities', 'metalpurities.purity_id', '=', 'metalreceiveentries.purity_id')
        //     ->select('metalpurities.purity_id', 'metalpurities.purity')
        //     ->groupBy('metalreceiveentries.metal_id')
        //     ->orderBy('metalpurities.purity')
        //     ->get();

        return view('metalissueentries.edit', compact('karigars', 'metals', 'metalpurities', 'metalissueentries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate(
            [
                'metal_category'                         => 'required',
                'metal_issue_entries_date'               => 'required',
                'karigar_id'                             => 'required',
                'metal_id'                               => 'required',
                'purity_id'                              => 'required',
                'converted_purity'                       => 'required',
                'weight'                                 => 'required',
                'alloy_gm'                               => 'required',
                'netweight_gm'                           => 'required',
            ],
            [
                'metal_category.required'           => 'Category is Required', // custom message
                'metal_issue_entries_date.required' => 'Date is Required', // custom message
                'karigar_id.required'               => 'Kid is Required', // custom message
                'metal_id.required'                 => 'Metal Name is Required', // custom message
                'purity_id.required'                => 'Purity is Required', // custom message
                'converted_purity.required'         => 'Converted Purity is Required', // custom message
                'weight.required'                   => 'Weight is Required', // custom message
                'alloy_gm.required'                 => 'Alloy (gm) is Required', // custom message
                'netweight_gm.required'             => 'NetWeight (gm) is Required', // custom message
            ]
        );

        try {
            $metalissueentries                            = Metalissueentry::find($id);

            $metalissueentries->metal_category            = strip_tags($request->input('metal_category'));
            $metalissueentries->metal_issue_entries_date  = strip_tags($request->input('metal_issue_entries_date'));
            $metalissueentries->karigar_id                = strip_tags($request->input('karigar_id'));
            $metalissueentries->karigar_name              = strip_tags($request->input('karigar_name'));
            $metalissueentries->metal_id                  = strip_tags($request->input('metal_id'));
            $metalissueentries->purity_id                 = strip_tags($request->input('purity_id'));
            $metalissueentries->converted_purity          = strip_tags($request->input('converted_purity'));
            $metalissueentries->weight                    = strip_tags($request->input('weight'));
            $metalissueentries->alloy_gm                  = strip_tags($request->input('alloy_gm'));
            $metalissueentries->netweight_gm              = strip_tags($request->input('netweight_gm'));

            $metalissueentries->updated_by                = Auth::user()->name;
            $metalissueentries->update();

            return redirect()->route('metalissueentries.index')->withSuccess('Metal issue entries record updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Metalpurity::where('purity_id', $id)->firstorfail()->delete();
        return redirect('/metalpurities')->with('success', 'Metal purities records deleted successfully.');
    }

    public function getkarigardetails(Request $request)
    {
        $karigar = Karigar::where('id', $request->karigar_id)->first();
        return response()->json([
            "kname" => $karigar->kname,
        ]);
    }


    public function getmetalname(Request $request)
    {
        $metals = Metal::where('metal_category', $request->metal_category)->orderBy('metal_category')->get();
        $html = '<select name="metal_id" id="metal_id" class="form-select rounded-0 @error("metal_id") is-invalid @enderror" onchange="GetMetalPurityMetalIssue(this.value)">';
        $html .= '<option value="">Choose...</option>';
        foreach ($metals as $metal) {
            $html .= '<option value="' . $metal->metal_id . '">' . $metal->metal_name . '</option>';
        }
        $html .= '</select>';
        echo $html;
    }

    public function getmetalpuritymetalissue(Request $request)
    {
        $metalpurity = Metalpurity::where('metal_id', $request->metal_id)->orderBy('purity')->get();
        $html = '<select  name="purity_id" id="purity_id" class="form-select rounded-0 @error("metal_id") is-invalid @enderror" onchange="GetMetalPurityInfo(this.value)">';
        $html .= '<option value="">Choose...</option>';
        foreach ($metalpurity as $metalpuriti) {
            $html .= '<option value="' . $metalpuriti->purity_id . '">' . $metalpuriti->purity . '</option>';
        }
        $html .= '</select>';
        echo $html;
    }

    public function getmetalpurityinfo(Request $request)
    {
        $metalpurity = Metalpurity::where('purity_id', $request->purity_id)->first();
        return response()->json([
            "metal_id" => $metalpurity->metal_id,
            "purity" => $metalpurity->purity,
        ]);
    }

    public function getmetalpuritydistinct(Request $request)
    {
        $metalpurity = Metalreceiveentry::where('metalreceiveentries.metal_id', $request->metal_id)
            ->join('metalpurities', function ($join) {
                $join->on('metalpurities.purity_id', '=', 'metalreceiveentries.purity_id')
                    ->on('metalpurities.metal_id', '=', 'metalreceiveentries.metal_id');
            })
            ->select('metalpurities.purity_id', 'metalpurities.purity')
            ->where('metalreceiveentries.balance_qty', '>', 0)
            ->distinct()
            ->orderBy('metalpurities.purity')
            ->get();

        $html = '<select name="purity_id" id="purity_id" class="form-select rounded-0 @error("purity_id") is-invalid @enderror">';
        $html .= '<option value="">Choose...</option>';
        foreach ($metalpurity as $metalpuriti) {
            $html .= '<option value="' . $metalpuriti->purity_id . '">' . $metalpuriti->purity . '</option>';
        }
        $html .= '</select>';
        echo $html;
    }

    public function gettotalmetalreceiveweight(Request $request)
    {
        $totalWeight = Metalreceiveentry::where('metal_id', $request->metal_id)
            ->where('purity_id', $request->purity_id)
            ->where('balance_qty', '>', 0)
            ->sum('balance_qty');

        return response()->json(['total_weight' => $totalWeight]);
    }

    public function getitemlistissuetokarigar(Request $request)
    {
        $itemname = $request->itemname;
        $options = collect(); // default empty

        switch ($itemname) {
            case 'Metal':
                $options = Metal::select('metal_id as id', 'metal_name as name')
                    ->where('is_active', 'Yes')
                    ->get();
                break;

            case 'Stone':
                $options = Stone::select('id', 'description as name')
                    ->where('is_active', 'Yes')
                    ->get();
                break;

            case 'Miscellaneous':
                $options = Miscellaneous::select('id', 'product_name as name')
                    ->where('is_active', 'Yes')
                    ->get();
                break;
        }

        // Generate the dropdown
        $html = '<select name="item" id="itemlist" class="form-select rounded-0" onchange="GetHtml(this.value)">';
        $html .= '<option value="">Choose...</option>';

        foreach ($options as $opt) {
            $html .= '<option value="' . e($opt->id) . '" data-name = "' . e($opt->name) . '">' . e($opt->name) . '</option>';
        }

        $html .= '</select>';

        return response($html);
    }

    public function getmetalpuritygroup(Request $request)
    {
        $metalpurity = Metalreceiveentry::join('metalpurities', 'metalpurities.purity_id', '=', 'metalreceiveentries.purity_id')
            ->where('metalreceiveentries.item', $request->item)
            ->where('metalreceiveentries.balance_qty', '>', 0)
            ->select('metalpurities.purity_id', 'metalpurities.purity')
            ->groupBy('metalpurities.purity_id', 'metalpurities.purity')
            ->orderBy('metalpurities.purity')
            ->get();

        $html = '<select name="purity_id" id="purity_id" class="form-select rounded-0 @error("purity_id") is-invalid @enderror">';
        $html .= '<option value="">Choose...</option>';
        foreach ($metalpurity as $metalpuriti) {
            $html .= '<option value="' . $metalpuriti->purity_id . '">' . $metalpuriti->purity . '</option>';
        }
        $html .= '</select>';

        echo $html;
    }

    public function getmetalpuritylist(Request $request)
    {
        $metalpurity = Metalpurity::select('purity_id', 'purity')
            ->where('is_active', 'Yes')
            ->orderBy('purity')
            ->get();

        $html = '<select name="converted_purity" id="converted_purity" class="form-select rounded-0" required>';
        $html .= '<option value="">Choose...</option>';
        foreach ($metalpurity as $metalpuriti) {
            $html .= '<option value="' . $metalpuriti->purity . '">' . $metalpuriti->purity . '</option>';
        }
        $html .= '</select>';

        echo $html;
    }
    public function checkStock(Request $request)
    {
        $request->validate([
            'location_id' => 'required|integer',
            'item_id'     => 'required|integer',
            'purity_id'   => 'nullable|integer',
        ]);

        $available = Metalreceiveentry::where('location_id', $request->location_id)
            ->where('item', $request->item_id)
            ->when($request->purity_id, fn($q) => $q->where('purity_id', $request->purity_id))
            ->sum('balance_qty');

        return response()->json([
            'available_stock' => round($available, 3),
        ]);
    }
}
