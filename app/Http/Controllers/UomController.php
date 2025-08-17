<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Uom;
use Exception;
use Illuminate\Support\Facades\Auth;

class UomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $uoms = Uom::simplePaginate(25);
        return view('uoms.list', compact('uoms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('uoms.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'uomid'             => 'required',
                'description'       => 'required'
            ],
            [
                'uomid.required' => 'Uomid is Required', // custom message
                'description.required' => 'Your Description is Required', // custom message
            ]
        );

        Uom::create([
            'uomid'                => strip_tags($request->uomid),
            'description'          => strip_tags($request->description),
            'is_active'            => strip_tags($request->is_active),
            'created_by'           => Auth::user()->name
        ]);

        return redirect()->route('uoms.index')->withSuccess('Uom record created successfully.');
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
        $uoms = Uom::findOrFail($id);
        return view('uoms.edit', compact('uoms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {


        $validatedData = $request->validate(
            [
                'uomid'             => 'required',
                'description'       => 'required'
            ],
            [
                'uomid.required' => 'Uomid is Required', // custom message
                'description.required' => 'Your Description is Required', // custom message
            ]
        );
        try {
            $uom                     = Uom::find($id);
            $uom->uomid              = strip_tags($request->input('uomid'));
            $uom->description        = strip_tags($request->input('description'));
            $uom->is_active          = strip_tags($request->input('is_active'));
            $uom->updated_by         = Auth::user()->name;
            $uom->update();

            return redirect()->route('uoms.index')->withSuccess('Uom record updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Uom::where('id', $id)->firstorfail()->delete();
        return redirect('/pcodes')->with('success', 'Pcode records deleted successfully.');
    }
}
