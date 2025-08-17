<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Metal;
use App\Models\Metalpurity;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Metalreceiveentry;
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
        // Get voucher type FIRST (before validation to auto-generate vou_no)
        $voucherType = Vouchertype::where('voucher_type', 'gold_receipt_entry')
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

        // Now validate (no need to ask for vou_no from frontend anymore)
        $validatedData = $request->validate([
            'metal_receive_entries_date' => 'required',
            'customer_id'                => 'required',
            'metal_id'                   => 'required',
            'purity_id'                  => 'required',
            'weight'                     => 'required',
            'dv_no'                      => 'required',
            'dv_date'                    => 'required',
            'location_id'                => 'required'
        ]);

        // Create metal receive entry
        Metalreceiveentry::create([
            'metalreceiveentries_id'       => (string) Str::uuid(),
            'location_id'                  => $request->location_id,
            'vou_no'                       => $voucherNoPadded,
            'metal_receive_entries_date'   => strip_tags($request->metal_receive_entries_date),
            'customer_id'                  => strip_tags($request->customer_id),
            'cust_name'                    => strip_tags($request->cust_name),
            'cust_address'                 => null,
            'metal_id'                     => strip_tags($request->metal_id),
            'purity_id'                    => strip_tags($request->purity_id),
            'weight'                       => strip_tags($request->weight),
            'balance_qty'                  => strip_tags($request->weight),
            'dv_no'                        => strip_tags($request->dv_no),
            'dv_date'                      => strip_tags($request->dv_date),
            'created_by'                   => Auth::user()->name
        ]);

        // Update the voucher type's lastno (as INTEGER, not padded)
        $voucherType->lastno = $voucherNo;
        $voucherType->save();

        return redirect()->route('metalreceiveentries.index')
            ->withSuccess('Metal receive entries record created successfully');
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
        $metalreceiveentries = Metalreceiveentry::findOrFail($id);
        $metalpurity = Metalpurity::where('metal_id', $metalreceiveentries->metal_id)->get();
        return view('metalreceiveentries.edit', compact('customers', 'metals', 'metalreceiveentries', 'metalpurity'));
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
