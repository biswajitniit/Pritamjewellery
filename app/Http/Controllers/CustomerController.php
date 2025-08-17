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
                'cid'                => 'required',
                'cust_name'          => 'required',
                'address'            => 'required',
                'city'               => 'required',
                'state'              => 'required',
                'cont_person'        => 'required',
                'statecode'          => 'required'
            ],
            [
                'cid.required' => 'CID is Required', // custom message
                'cust_name.required' => 'Name is Required', // custom message
                'address.required' => 'Address is Required', // custom message
                'city.required' => 'City is Required', // custom message
                'state.required' => 'State is Required', // custom message
                'cont_person.required' => 'Contact Person is Required', // custom message
                'statecode.required' => 'State Code is Required', // custom message
            ]
        );

        Customer::create([
            'cid'                  => strip_tags($request->cid),
            'cust_code'            => strip_tags($request->cust_code),
            'cust_name'            => strip_tags($request->cust_name),
            'address'              => strip_tags($request->address),
            'city'                 => strip_tags($request->city),
            'state'                => strip_tags($request->state),
            'phone'                => strip_tags($request->phone),
            'mobile'               => strip_tags($request->mobile),
            'cont_person'          => strip_tags($request->cont_person),
            'gstin'                => strip_tags($request->gstin),
            'statecode'            => strip_tags($request->statecode),
            'is_active'            => strip_tags($request->is_active),
            'created_by'           => Auth::user()->name
        ]);

        return redirect()->route('customers.index')->withSuccess('Customer record created successfully.');
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
        $validatedData = $request->validate(
            [
                'cid'                => 'required',
                'cust_name'          => 'required',
                'address'            => 'required',
                'city'               => 'required',
                'state'              => 'required',
                'cont_person'        => 'required',
                'statecode'          => 'required'
            ],
            [
                'cid.required' => 'CID is Required', // custom message
                'cust_name.required' => 'Name is Required', // custom message
                'address.required' => 'Address is Required', // custom message
                'city.required' => 'City is Required', // custom message
                'state.required' => 'State is Required', // custom message
                'cont_person.required' => 'Contact Person is Required', // custom message
                'statecode.required' => 'State Code is Required', // custom message
            ]
        );

        try {
            $customer                     = Customer::find($id);
            $customer->cid                = strip_tags($request->cid);
            $customer->cust_code          = strip_tags($request->cust_code);
            $customer->cust_name          = strip_tags($request->cust_name);
            $customer->address            = strip_tags($request->address);
            $customer->city               = strip_tags($request->city);
            $customer->state              = strip_tags($request->state);
            $customer->phone              = strip_tags($request->phone);
            $customer->mobile             = strip_tags($request->mobile);
            $customer->cont_person        = strip_tags($request->cont_person);
            $customer->gstin              = strip_tags($request->gstin);
            $customer->statecode          = strip_tags($request->statecode);
            $customer->is_active          = strip_tags($request->input('is_active'));
            $customer->updated_by         = Auth::user()->name;
            $customer->update();

            return redirect()->route('customers.index')->withSuccess('Customer record updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
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
