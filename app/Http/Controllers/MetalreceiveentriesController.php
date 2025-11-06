<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Customer;
use App\Models\FinancialYear;
use App\Models\Location;
use App\Models\Metal;
use App\Models\Metalpurity;
use App\Models\Metalreceiveentry;
use App\Models\Miscellaneous;
use App\Models\StockEffect;
use App\Models\Stone;
use App\Models\Vouchertype;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MetalreceiveentriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Load all possible relationships
        $metalreceiveentries = Metalreceiveentry::with(['customer', 'metalpurity', 'metal', 'stone', 'miscellaneous', 'financialYear'])
            ->simplePaginate(25);

        // Dynamically assign item_name based on item_type
        foreach ($metalreceiveentries as $entry) {
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

        return view('metalreceiveentries.list', compact('metalreceiveentries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::where('id', 1)->where('is_active', 'Yes')->orderBy('cust_name')->get();
        $locations = Location::get();
        $financialyears = FinancialYear::where('status', 'Active')
            ->orderBy('id', 'desc')
            ->get();
        return view('metalreceiveentries.add', compact('customers', 'locations', 'financialyears'));
    }

    /**
     * Store a newly created resource in storage.
     */
    /* public function store(Request $request)
    {
        try {
            // 🔹 Lock voucher type row to prevent duplicate voucher numbers
            $voucherType = Vouchertype::where('voucher_type', 'gold_receipt_entry')
                ->where('location_id', $request->location_id)
                ->lockForUpdate()
                ->first();

            if (! $voucherType) {
                return back()->withInput()->withErrors(['Voucher type not found for this location.']);
            }

            // 🔹 Generate next voucher number
            $nextNo = (int) $voucherType->lastno + 1;
            $voucherNo = str_pad($nextNo, 3, '0', STR_PAD_LEFT);

            // 🔹 Validate request with correct table columns
            $validatedData = $request->validate([
                'metal_receive_entries_date' => 'required|date',
                'customer_id' => 'required|exists:customers,id',          // adjust if PK is not 'id'
                'metal_id' => 'required|exists:metals,metal_id',
                'purity_id' => 'required|exists:metalpurities,purity_id',
                'weight' => 'required|numeric|min:0',
                'dv_no' => 'required',
                'dv_date' => 'required|date',
                'location_id' => 'required|exists:locations,id', // adjust if PK is not 'location_id'
                'metal_category' => 'required',
            ]);

            DB::beginTransaction();

            $locationName = Location::where('id', $request->location_id)->value('location_name');
            $customerName = Customer::where('id', $request->customer_id)->value('cust_name');
            $metalName = Metal::where('metal_id', $request->metal_id)->value('metal_name');
            $metalPurity = Metalpurity::where('purity_id', $request->purity_id)->value('purity');
            $company = Company::firstOrFail();

            // 🔹 Create metal receive entry
            Metalreceiveentry::create([
                'metalreceiveentries_id' => (string) Str::uuid(),
                'location_id' => $request->location_id,
                'vou_no' => $request->vou_no, // use backend generated voucher
                'metal_receive_entries_date' => $request->metal_receive_entries_date,
                'customer_id' => $request->customer_id,
                'cust_name' => $customerName,
                'cust_address' => null,
                'metal_category' => $request->metal_category,
                'metal_id' => $request->metal_id,
                'purity_id' => $request->purity_id,
                'weight' => $request->weight,
                'balance_qty' => $request->weight,
                'dv_no' => $request->dv_no,
                'dv_date' => $request->dv_date,
                'created_by' => Auth::user()->name,
            ]);

            // 🔹 Stock effect entry for Customer
            StockEffect::create([
                'vou_no' => $request->vou_no,
                'metal_receive_entries_date' => $request->metal_receive_entries_date,
                'location_name' => $locationName,
                'ledger_name' => $customerName,
                'ledger_code' => $request->customer_id,
                'ledger_type' => 'Customer',
                'metal_category' => $request->metal_category,
                'metal_name' => $metalName,
                'net_wt' => $request->weight,
                'purity' => $metalPurity,
                'pure_wt' => round(($request->weight * $metalPurity) / 100, 3),
            ]);

            // 🔹 Stock effect entry for Company (Vendor side)
            StockEffect::create([
                'vou_no' => $request->vou_no,
                'metal_receive_entries_date' => $request->metal_receive_entries_date,
                'location_name' => $locationName,
                'ledger_name' => $company->cust_name, // company name
                'ledger_code' => $company->id,  // company code
                'ledger_type' => 'Vendor',
                'metal_category' => $request->metal_category,
                'metal_name' => $metalName,
                'net_wt' => $request->weight,
                'purity' => $metalPurity,
                'pure_wt' => round(($request->weight * $metalPurity) / 100, 3),
            ]);

            // 🔹 Update voucher last number
            $voucherType->lastno = $nextNo;
            $voucherType->save();

            DB::commit();

            return redirect()->route('metalreceiveentries.index')
                ->withSuccess('Metal receive entry created successfully');

        } catch (\Throwable $e) {

            DB::rollBack();

            // Log full error for debugging
            \Log::error('Metal Receive Entry Store Failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            // 🔹 In local/dev environment, show full error
            if (app()->environment('local')) {
                return back()
                    ->withInput()
                    ->withErrors(['error' => $e->getMessage()]);
            }

            // 🔹 In production, show friendly message
            return back()
                ->withInput()
                ->withErrors(['error' => 'Something went wrong while saving metal receive entry.']);
        }
    }*/

    /**
     * Create both Customer & Company stock effect entries.
     */
    public function createStockEffectEntries(array $data, Customer $company, string $customerName): void
    {
        // Customer Stock Effect
        StockEffect::create(array_merge($data, [
            'ledger_name' => $customerName,
            'ledger_code' => $data['customer_id'],
            'ledger_type' => 'Customer',
        ]));

        // Company Stock Effect
        StockEffect::create(array_merge($data, [
            'ledger_name' => $company->cust_name,
            'ledger_code' => $company->id,
            'ledger_type' => 'Vendor',
        ]));
    }

    /**
     * Store Metal Receive Entry.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Lock voucher row for concurrency safety
            $voucherType = Vouchertype::where('voucher_type', 'gold_receipt_entry')
                ->where('location_id', $request->location_id)
                ->lockForUpdate()
                ->first();

            if (! $voucherType) {
                return back()->withInput()->withErrors(['Voucher type not found for this location.']);
            }

            // 🔹 Generate next voucher number
            $nextNo = (int) $voucherType->lastno + 1;
            $voucherNo = str_pad($nextNo, 3, '0', STR_PAD_LEFT);

            // Validation
            $rules = [
                'location_id' => 'required|exists:locations,id',
                'metal_receive_entries_date' => 'required|date',
                'customer_id' => 'required|exists:customers,id',
                'item_type' => 'required|string|in:Metal,Stone,Miscellaneous',
                'item' => 'required|integer',
                'dv_no' => 'nullable|string|max:50',
                'dv_date' => 'nullable|date',
            ];

            // Conditional validation rules
            if ($request->item_type === 'Metal') {
                // Only require purity and weight for GOLD (item_id = 1)
                if ((int) $request->item_id === 1) {
                    $rules['purity_id'] = 'required|exists:metalpurities,purity_id';
                    $rules['weight'] = 'required|numeric|min:0.01';
                } else {
                    // Other metals only require weight
                    $rules['weight'] = 'required|numeric|min:0.01';
                }
            }

            // For Findings or Miscellaneous
            if (in_array($request->item_type, ['Stone', 'Miscellaneous'])) {
                $rules['weight'] = 'required|numeric|min:0.01';
            }


            $validated = $request->validate($rules);

            //Fetch related data
            $locationName = Location::where('id', $request->location_id)->value('location_name');
            $customerName = Customer::where('id', $request->customer_id)->value('cust_name');
            //$company = Company::firstOrFail();
            $company = Customer::where('id', 2)->first();
            // Base data for StockEffect
            $baseData = [
                'vou_no' => $request->vou_no,
                'metal_receive_entries_date' => $request->metal_receive_entries_date,
                'location_name' => $locationName,
                'metal_category' => $request->item_type,
                'customer_id' => $request->customer_id,
            ];

            // Handle each item type
            switch ($request->item_type) {
                case 'Metal':
                    $metalName = Metal::where('metal_id', $request->item)->value('metal_name');
                    $metalPurity = Metalpurity::where('purity_id', $request->purity_id)->value('purity');
                    $pureWt = round(($request->weight * $metalPurity) / 100, 3);

                    $data = array_merge($baseData, [
                        'metal_name' => $metalName,
                        'net_wt' => $request->weight,
                        'purity' => $metalPurity,
                        'pure_wt' => $pureWt,
                    ]);

                    $this->createStockEffectEntries($data, $company, $customerName);
                    break;

                case 'Stone':
                    $metalName = Stone::where('id', $request->item)->value('description');
                    $data = array_merge($baseData, [
                        'metal_name' => $metalName,
                        'net_wt' => $request->weight,
                        'purity' => null,
                        'pure_wt' => null,
                    ]);

                    $this->createStockEffectEntries($data, $company, $customerName);
                    break;

                case 'Miscellaneous':
                    $metalName = Miscellaneous::where('id', $request->item)->value('product_name');
                    $data = array_merge($baseData, [
                        'metal_name' => $metalName,
                        'net_wt' => $request->weight,
                        'purity' => null,
                        'pure_wt' => null,
                    ]);

                    $this->createStockEffectEntries($data, $company, $customerName);
                    break;
            }

            $financialYearId = getFinancialYearIdByDate($request->metal_receive_entries_date);

            if (! $financialYearId) {
                throw new \Exception('No financial year found for the given date: ' . $request->metal_receive_entries_date);
            }

            // Save Metal Receive Entry
            Metalreceiveentry::create([
                'metalreceiveentries_id'     => (string) Str::uuid(),
                'financial_year_id'          => $financialYearId,
                'location_id'                => $request->location_id,
                'vou_no'                     => $request->vou_no,
                'metal_receive_entries_date' => $request->metal_receive_entries_date,
                'customer_id'                => $request->customer_id,
                'cust_name'                  => $customerName,
                'item_type'                  => $request->item_type,
                'item'                       => $request->item,
                'purity_id'                  => $request->purity_id ?? null,
                'weight'                     => $request->weight ?? null,
                'balance_qty'                => $request->weight ?? 0,
                'dv_no'                      => $request->dv_no ?? null,
                'dv_date'                    => $request->dv_date ?? null,
                'created_by'                 => Auth::user()->name,
            ]);

            // Update last voucher number
            $voucherType->update(['lastno' => $nextNo]);

            DB::commit();

            return redirect()->route('metalreceiveentries.index')
                ->withSuccess('Metal receive entry created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('Metal Receive Entry Store Failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withInput()->withErrors([
                'error' => app()->environment('local')
                    ? $e->getMessage()
                    : 'Something went wrong while saving metal receive entry.',
            ]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $metalreceiveentries = Metalreceiveentry::findOrFail($id);
        $customers = Customer::findOrFail($metalreceiveentries->customer_id);
        $metals = Metal::findOrFail($metalreceiveentries->metal_id);
        $metalpurities = Metalpurity::findOrFail($metalreceiveentries->purity_id);

        // dd($metals);
        return view('metalreceiveentries.view', compact('metalreceiveentries', 'customers', 'metals', 'metalpurities'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customers = Customer::where('is_active', 'Yes')->orderBy('cust_name')->get();
        $metals = Metal::where('is_active', 'Yes')->orderBy('metal_name')->get();
        $locations = Location::get();
        $metalreceiveentries = Metalreceiveentry::findOrFail($id);
        $metalpurity = Metalpurity::where('metal_id', $metalreceiveentries->metal_id)->get();
        $financialyears = FinancialYear::where('status', 'Active')
            ->orderBy('id', 'desc')
            ->get();
        return view('metalreceiveentries.edit', compact('customers', 'metals', 'locations', 'metalreceiveentries', 'metalpurity', 'financialyears'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate(
            [
                'metal_receive_entries_date' => 'required',
                'customer_id' => 'required',
                'metal_id' => 'required',
                'purity_id' => 'required',
                'weight' => 'required',
                'dv_no' => 'required',
                'dv_date' => 'required',
            ],
            [
                'metal_receive_entries_date.required' => 'Date is Required', // custom message
                'customer_id.required' => 'Company ID is Required', // custom message
                'metal_id.required' => 'Metal Name is Required', // custom message
                'purity_id.required' => 'Purity is Required', // custom message
                'weight.required' => 'Weight is Required', // custom message
                'dv_no.required' => 'DV No. is Required', // custom message
                'dv_date.required' => 'DV Date is Required', // custom message
            ]
        );

        try {
            $financialyear = FinancialYear::findOrFail($request->financial_year_id);

            $metalreceiveentry = Metalreceiveentry::find($id);
            $metalreceiveentry->metal_receive_entries_date = strip_tags($request->input('metal_receive_entries_date'));
            $metalreceiveentry->customer_id = strip_tags($request->input('customer_id'));
            $metalreceiveentry->cust_name = strip_tags($request->input('cust_name'));
            $metalreceiveentry->cust_address = strip_tags($request->input('cust_address'));
            $metalreceiveentry->metal_id = strip_tags($request->input('metal_id'));
            $metalreceiveentry->purity_id = strip_tags($request->input('purity_id'));
            $metalreceiveentry->weight = strip_tags($request->input('weight'));
            $metalreceiveentry->balance_qty = strip_tags($request->input('weight')); // balance qty same as weight when weight value entry
            $metalreceiveentry->dv_no = strip_tags($request->input('dv_no'));
            $metalreceiveentry->dv_date = strip_tags($request->input('dv_date'));
            $metalreceiveentry->updated_by = Auth::user()->name;
            $metalreceiveentry->update();

            return redirect()->route('metalreceiveentries.index')->withSuccess('Metal receive entries record updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Metalreceiveentry::where('metal_receive_entries_id', $id)->firstorfail()->delete();

        return redirect('/metalreceiveentries')->with('success', 'Metal receive entries records deleted successfully.');
    }

    public function getcustomerdetails(Request $request)
    {
        $product = Customer::where('id', $request->customerid)->orderBy('cust_name')->first();

        return response()->json([
            'cust_name' => $product->cust_name,
            'address' => $product->address,
        ]);
    }

    public function getmetalpurity(Request $request)
    {
        $metalpurity = Metalpurity::where('metal_id', $request->metal_id)->orderBy('purity')->get();
        $html = '<select name="purity_id" id="purity_id" class="form-select rounded-0">';
        $html .= '<option value="">Choose...</option>';
        foreach ($metalpurity as $metalpuriti) {
            $html .= '<option value="' . $metalpuriti->purity_id . '">' . $metalpuriti->purity . '</option>';
        }
        $html .= '</select>';
        echo $html;
    }

    public function getitemlist(Request $request)
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
}
