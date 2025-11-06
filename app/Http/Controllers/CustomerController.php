<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::simplePaginate(25);
        return view('customers.list', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'party_type'  => 'required',
                'cid'         => 'required',
                'cust_name'   => 'required',
                'address'     => 'required',
                'city'        => 'required',
                'state'       => 'required',
                'cont_person' => 'required',
                'statecode'   => 'required'
            ],
            [
                'party_type.required'  => 'Party Type is Required',
                'cid.required'         => 'CID is Required',
                'cust_name.required'   => 'Name is Required',
                'address.required'     => 'Address is Required',
                'city.required'        => 'City is Required',
                'state.required'       => 'State is Required',
                'cont_person.required' => 'Contact Person is Required',
                'statecode.required'   => 'State Code is Required',
            ]
        );

        try {
            Customer::create([
                'party_type'    => strip_tags($request->party_type),
                'cid'           => strip_tags($request->cid),
                'cust_code'     => strip_tags($request->cust_code),
                'cust_name'     => strip_tags($request->cust_name),
                'address'       => strip_tags($request->address),
                'city'          => strip_tags($request->city),
                'state'         => strip_tags($request->state),
                'phone'         => strip_tags($request->phone),
                'mobile'        => strip_tags($request->mobile),
                'cont_person'   => strip_tags($request->cont_person),
                'gstin'         => strip_tags($request->gstin),
                'statecode'     => strip_tags($request->statecode),
                'is_validation' => strip_tags($request->is_validation ?? 'Yes'),
                'is_active'     => strip_tags($request->is_active ?? 'Yes'),
                'created_by'    => Auth::user()->name
            ]);

            return redirect()->route('customers.index')
                ->with('success', 'Customer record created successfully.');
        } catch (\Exception $e) {
            \Log::error('Customer store error: ' . $e->getMessage());

            return back()->withInput()
                ->withErrors(['error' => 'Failed to create customer. Please try again.']);
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
        $customers = Customer::findOrFail($id);
        return view('customers.edit', compact('customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation
        $validatedData = $request->validate(
            [
                'party_type'  => 'required',
                'cid'         => 'required',
                'cust_name'   => 'required',
                'address'     => 'required',
                'city'        => 'required',
                'state'       => 'required',
                'cont_person' => 'required',
                'statecode'   => 'required'
            ],
            [
                'party_type.required'  => 'Party Type is Required',
                'cid.required'         => 'CID is Required',
                'cust_name.required'   => 'Name is Required',
                'address.required'     => 'Address is Required',
                'city.required'        => 'City is Required',
                'state.required'       => 'State is Required',
                'cont_person.required' => 'Contact Person is Required',
                'statecode.required'   => 'State Code is Required',
            ]
        );

        try {
            $customer = Customer::findOrFail($id); // throws 404 if not found

            $customer->party_type    = $request->party_type;
            $customer->cid           = $request->cid;
            $customer->cust_code     = $request->cust_code;
            $customer->cust_name     = $request->cust_name;
            $customer->address       = $request->address;
            $customer->city          = $request->city;
            $customer->state         = $request->state;
            $customer->phone         = $request->phone;
            $customer->mobile        = $request->mobile;
            $customer->cont_person   = $request->cont_person;
            $customer->gstin         = $request->gstin;
            $customer->statecode     = $request->statecode;
            $customer->is_validation = $request->is_validation ?? 'Yes';
            $customer->is_active     = $request->is_active ?? 'Yes';
            $customer->updated_by    = Auth::user()->name;

            $customer->save(); // or update()

            return redirect()->route('customers.index')
                ->with('success', 'Customer record updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Customer update error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update customer. Please try again.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Customer::where('id', $id)->firstorfail()->delete();
        return redirect('/customers')->with('success', 'Stone records deleted successfully.');
    }
}
