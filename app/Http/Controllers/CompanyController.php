<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::simplePaginate(25);
        return view('companies.list', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('companies.add');
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

        Company::create([
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
            'is_validation'        => strip_tags($request->is_validation),
            'is_active'            => strip_tags($request->is_active),
            'created_by'           => Auth::user()->name
        ]);

        return redirect()->route('companies.index')->withSuccess('Company record created successfully.');
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
        $companies = Company::findOrFail($id);
        return view('companies.edit', compact('companies'));
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
            $company                     = Company::find($id);
            $company->cid                = strip_tags($request->cid);
            $company->cust_code          = strip_tags($request->cust_code);
            $company->cust_name          = strip_tags($request->cust_name);
            $company->address            = strip_tags($request->address);
            $company->city               = strip_tags($request->city);
            $company->state              = strip_tags($request->state);
            $company->phone              = strip_tags($request->phone);
            $company->mobile             = strip_tags($request->mobile);
            $company->cont_person        = strip_tags($request->cont_person);
            $company->gstin              = strip_tags($request->gstin);
            $company->statecode          = strip_tags($request->statecode);
            $company->is_validation      = strip_tags($request->is_validation);
            $company->is_active          = strip_tags($request->input('is_active'));
            $company->updated_by         = Auth::user()->name;
            $company->update();

            return redirect()->route('companies.index')->withSuccess('Company record updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Company::where('id', $id)->firstorfail()->delete();
        return redirect('/customers')->with('success', 'Stone records deleted successfully.');
    }
}
