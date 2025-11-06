<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Itemdescriptionheader;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\Auth;

class ItemdescriptionheaderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $itemdescriptionheaders = Itemdescriptionheader::simplePaginate(25);
        return view('itemdescriptionheaders.list', compact('itemdescriptionheaders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::where('is_active', 'Yes')->get();
        return view('itemdescriptionheaders.add', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'company_id'             => 'required',
                'number_of_digits'       => 'required',
                'value'                  => 'required',
                'description'            => 'required',
            ],
            [
                'company_id.required'        => 'Company is Required', // custom message
                'number_of_digits.required' => 'Number Of Digits is Required', // custom message
                'value.required'            => 'Value is Required', // custom message
                'description.required'      => 'Description is Required', // custom message
            ]
        );

        Itemdescriptionheader::create([
            'company_id'                => strip_tags($request->company_id),
            'number_of_digits'          => strip_tags($request->number_of_digits),
            'value'                     => strip_tags($request->value),
            'description'               => strip_tags($request->description),
            'is_active'                 => strip_tags($request->is_active),
            'created_by'                => Auth::user()->name
        ]);

        return redirect()->route('itemdescriptionheaders.index')->withSuccess('Item description headers record created successfully.');
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
        $itemdescriptionheaders = Itemdescriptionheader::findOrFail($id);
        $customers = Customer::where('is_active', 'Yes')->get();
        return view('itemdescriptionheaders.edit', compact('itemdescriptionheaders', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate(
            [
                'company_id'             => 'required',
                'number_of_digits'       => 'required',
                'value'                  => 'required',
                'description'            => 'required',
            ],
            [
                'company_id.required'        => 'Company is Required', // custom message
                'number_of_digits.required' => 'Number Of Digits is Required', // custom message
                'value.required'            => 'Value is Required', // custom message
                'description.required'      => 'Description is Required', // custom message
            ]
        );
        try {
            $itemdescriptionheader                     = Itemdescriptionheader::find($id);
            $itemdescriptionheader->company_id         = strip_tags($request->input('company_id'));
            $itemdescriptionheader->number_of_digits   = strip_tags($request->input('number_of_digits'));
            $itemdescriptionheader->value              = strip_tags($request->input('value'));
            $itemdescriptionheader->description        = strip_tags($request->input('description'));
            $itemdescriptionheader->is_active          = strip_tags($request->input('is_active'));
            $itemdescriptionheader->updated_by         = Auth::user()->name;
            $itemdescriptionheader->update();

            return redirect()->route('itemdescriptionheaders.index')->withSuccess('Item description headers record updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Itemdescriptionheader::where('id', $id)->firstorfail()->delete();
        return redirect('/itemdescriptionheaders')->with('success', 'Item description headers records deleted successfully.');
    }
}
