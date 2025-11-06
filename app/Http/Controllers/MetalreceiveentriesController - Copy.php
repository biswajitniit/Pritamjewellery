<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Metal;
use App\Models\Metalpurity;
use App\Models\Customer;
use App\Models\Company;
use App\Models\Location;
use App\Models\Metalreceiveentry;
use App\Models\StockEffect;
use App\Models\Vouchertype;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MetalreceiveentriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $metalreceiveentries = Metalreceiveentry::with('metal', 'customer', 'metalpurity')->simplePaginate(25);
        return view('metalreceiveentries.list', compact('metalreceiveentries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::where('id', 1)->where('is_active', 'Yes')->orderBy('cust_name')->get();
        $metals = Metal::where('is_active', 'Yes')->orderBy('metal_name')->get();
        $locations = Location::get();

        return view('metalreceiveentries.add', compact('metals', 'customers', 'locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // 🔹 Lock voucher type row to prevent duplicate voucher numbers
            $voucherType = Vouchertype::where('voucher_type', 'gold_receipt_entry')
                ->where('location_id', $request->location_id)
                ->lockForUpdate()
                ->first();

            if (!$voucherType) {
                return back()->withInput()->withErrors(['Voucher type not found for this location.']);
            }

            // 🔹 Generate next voucher number
            $nextNo = (int) $voucherType->lastno + 1;
            $voucherNo = str_pad($nextNo, 3, '0', STR_PAD_LEFT);

            // 🔹 Validate request with correct table columns
            $validatedData = $request->validate([
                'metal_receive_entries_date' => 'required|date',
                'customer_id'                => 'required|exists:customers,id',          // adjust if PK is not 'id'
                'metal_id'                   => 'required|exists:metals,metal_id',
                'purity_id'                  => 'required|exists:metalpurities,purity_id',
                'weight'                     => 'required|numeric|min:0',
                'dv_no'                      => 'required',
                'dv_date'                    => 'required|date',
                'location_id'                => 'required|exists:locations,id', // adjust if PK is not 'location_id'
                'metal_category'             => 'required',
            ]);

            DB::beginTransaction();

            $locationName = Location::where('id', $request->location_id)->value('location_name');
            $customerName = Customer::where('id', $request->customer_id)->value('cust_name');
            $metalName    = Metal::where('metal_id', $request->metal_id)->value('metal_name');
            $metalPurity  = Metalpurity::where('purity_id', $request->purity_id)->value('purity');
            $company      = Company::firstOrFail();

            // 🔹 Create metal receive entry
            Metalreceiveentry::create([
                'metalreceiveentries_id'     => (string) Str::uuid(),
                'location_id'                => $request->location_id,
                'vou_no'                     => $request->vou_no, // use backend generated voucher
                'metal_receive_entries_date' => $request->metal_receive_entries_date,
                'customer_id'                => $request->customer_id,
                'cust_name'                  => $customerName,
                'cust_address'               => null,
                'metal_category'             => $request->metal_category,
                'metal_id'                   => $request->metal_id,
                'purity_id'                  => $request->purity_id,
                'weight'                     => $request->weight,
                'balance_qty'                => $request->weight,
                'dv_no'                      => $request->dv_no,
                'dv_date'                    => $request->dv_date,
                'created_by'                 => Auth::user()->name,
            ]);

            // 🔹 Stock effect entry for Customer
            StockEffect::create([
                'vou_no'                     => $request->vou_no,
                'metal_receive_entries_date' => $request->metal_receive_entries_date,
                'location_name'              => $locationName,
                'ledger_name'                => $customerName,
                'ledger_code'                => $request->customer_id,
                'ledger_type'                => 'Customer',
                'metal_category'             => $request->metal_category,
                'metal_name'                 => $metalName,
                'net_wt'                     => $request->weight,
                'purity'                     => $metalPurity,
                'pure_wt'                    => round(($request->weight * $metalPurity) / 100, 3),
            ]);

            // 🔹 Stock effect entry for Company (Vendor side)
            StockEffect::create([
                'vou_no'                     => $request->vou_no,
                'metal_receive_entries_date' => $request->metal_receive_entries_date,
                'location_name'              => $locationName,
                'ledger_name'                => $company->cust_name, // company name
                'ledger_code'                => $company->id,  // company code
                'ledger_type'                => 'Vendor',
                'metal_category'             => $request->metal_category,
                'metal_name'                 => $metalName,
                'net_wt'                     => $request->weight,
                'purity'                     => $metalPurity,
                'pure_wt'                    => round(($request->weight * $metalPurity) / 100, 3),
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
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
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
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $metalreceiveentries   = Metalreceiveentry::findOrFail($id);
        $customers   = Customer::findOrFail($metalreceiveentries->customer_id);
        $metals   = Metal::findOrFail($metalreceiveentries->metal_id);
        $metalpurities   = Metalpurity::findOrFail($metalreceiveentries->purity_id);
        //dd($metals);
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
        return view('metalreceiveentries.edit', compact('customers', 'metals', 'locations', 'metalreceiveentries', 'metalpurity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate(
            [
                'metal_receive_entries_date'            => 'required',
                'customer_id'                           => 'required',
                'metal_id'                              => 'required',
                'purity_id'                             => 'required',
                'weight'                                => 'required',
                'dv_no'                                 => 'required',
                'dv_date'                               => 'required',
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
            $metalreceiveentry                     = Metalreceiveentry::find($id);
            $metalreceiveentry->metal_receive_entries_date   = strip_tags($request->input('metal_receive_entries_date'));
            $metalreceiveentry->customer_id                  = strip_tags($request->input('customer_id'));
            $metalreceiveentry->cust_name                    = strip_tags($request->input('cust_name'));
            $metalreceiveentry->cust_address                 = strip_tags($request->input('cust_address'));
            $metalreceiveentry->metal_id                     = strip_tags($request->input('metal_id'));
            $metalreceiveentry->purity_id                    = strip_tags($request->input('purity_id'));
            $metalreceiveentry->weight                       = strip_tags($request->input('weight'));
            $metalreceiveentry->balance_qty                  = strip_tags($request->input('weight')); // balance qty same as weight when weight value entry
            $metalreceiveentry->dv_no                        = strip_tags($request->input('dv_no'));
            $metalreceiveentry->dv_date                      = strip_tags($request->input('dv_date'));
            $metalreceiveentry->updated_by                   = Auth::user()->name;
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
            "cust_name" => $product->cust_name,
            "address"   => $product->address,
        ]);
    }

    public function getmetalpurity(Request $request)
    {
        $metalpurity = Metalpurity::where('metal_id', $request->metal_id)->orderBy('purity')->get();
        $html = '<select name="purity_id" id="purity_id" class="form-select rounded-0 @error("purity_id") is-invalid @enderror">';
        $html .= '<option value="">Choose...</option>';
        foreach ($metalpurity as $metalpuriti) {
            $html .= '<option value="' . $metalpuriti->purity_id . '">' . $metalpuriti->purity . '</option>';
        }
        $html .= '</select>';
        echo $html;
    }
}
