<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendors = Vendor::simplePaginate(25);
        return view('vendors.list', compact('vendors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vendors.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'vid'            => 'required',
                'name'           => 'required',
                'address'        => 'required',
                'city'           => 'required',
                'state'          => 'required',
                'contact_person' => 'required',
                'statecode'      => 'required'
            ],
            [
                'vid.required'               => 'VID is Required', // custom message
                'name.required'              => 'Name is Required', // custom message
                'address.required'           => 'Address is Required', // custom message
                'city.required'              => 'City is Required', // custom message
                'state.required'             => 'State is Required', // custom message
                'contact_person.required'    => 'Contact Person is Required', // custom message
                'statecode.required'         => 'State Code is Required', // custom message
            ]
        );

        Vendor::create([
            'vid'            => strip_tags($request->vid),
            'vendor_code'    => strip_tags($request->vendor_code),
            'name'           => strip_tags($request->name),
            'address'        => strip_tags($request->address),
            'city'           => strip_tags($request->city),
            'state'          => strip_tags($request->state),
            'phone'          => strip_tags($request->phone),
            'mobile'         => strip_tags($request->mobile),
            'contact_person' => strip_tags($request->contact_person),
            'gstin'          => strip_tags($request->gstin),
            'statecode'      => strip_tags($request->statecode),
            'is_active'      => strip_tags($request->is_active),
            'created_by'     => Auth::user()->name
        ]);

        return redirect()->route('vendors.index')->withSuccess('Customer record created successfully.');
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
        $vendors = Vendor::findOrFail($id);
        return view('vendors.edit', compact('vendors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate(
            [
                'vid'         => 'required',
                'name'        => 'required',
                'address'     => 'required',
                'city'        => 'required',
                'state'       => 'required',
                'contact_person' => 'required',
                'statecode'   => 'required'
            ],
            [
                'vid.required'          => 'VID is Required', // custom message
                'name.required'         => 'Name is Required', // custom message
                'address.required'      => 'Address is Required', // custom message
                'city.required'         => 'City is Required', // custom message
                'state.required'        => 'State is Required', // custom message
                'contact_person.required'  => 'Contact Person is Required', // custom message
                'statecode.required'    => 'State Code is Required', // custom message
            ]
        );

        try {
            Vendor::whereRaw('id = ?', [$id])->update([
                'vid'            => strip_tags($request->vid),
                'vendor_code'    => strip_tags($request->vendor_code),
                'name'           => strip_tags($request->name),
                'address'        => strip_tags($request->address),
                'city'           => strip_tags($request->city),
                'state'          => strip_tags($request->state),
                'phone'          => strip_tags($request->phone),
                'mobile'         => strip_tags($request->mobile),
                'contact_person' => strip_tags($request->contact_person),
                'gstin'          => strip_tags($request->gstin),
                'statecode'      => strip_tags($request->statecode),
                'is_active'      => strip_tags($request->input('is_active')),
                'updated_by'     => Auth::user()->name,
            ]);

            return redirect()->route('vendors.index')->withSuccess('Vendor record updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vendor = Vendor::whereRaw('id = ?', [$id])->firstOrFail();
        if ($vendor) {
            $vendor->delete();
            return redirect('/vendors')->with('success', 'Vendor record deleted successfully.');
        }
        return redirect('/vendors')->with('success', 'Invalid Vendor ID.');
    }
}
