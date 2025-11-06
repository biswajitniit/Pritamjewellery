<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stone;
use Exception;
use Illuminate\Support\Facades\Auth;

class StoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stones = Stone::simplePaginate(25);
        return view('stones.list', compact('stones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stones.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'additional_charge_id'             => 'required',
                'category'                         => 'required',
                'description'                      => 'required',
                'uom'                              => 'required'
            ],
            [
                'additional_charge_id.required' => 'Additional charge id is Required', // custom message
                'category.required' => 'Category is Required', // custom message
                'description.required' => 'Description is Required', // custom message
                'uom.required' => 'UOM is Required', // custom message
            ]
        );

        Stone::create([
            'additional_charge_id'            => strip_tags($request->additional_charge_id),
            'category'                        => strip_tags($request->category),
            'description'                     => strip_tags($request->description),
            'uom'                             => strip_tags($request->uom),
            'is_active'                       => strip_tags($request->is_active),
            'created_by'                      => Auth::user()->name
        ]);

        return redirect()->route('stones.index')->withSuccess('Stone record created successfully.');
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
        $stones = Stone::findOrFail($id);
        return view('stones.edit', compact('stones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate(
            [
                'additional_charge_id'             => 'required',
                'category'                         => 'required',
                'description'                      => 'required',
                'uom'                              => 'required'
            ],
            [
                'additional_charge_id.required' => 'Additional charge id is Required', // custom message
                'category.required' => 'Category is Required', // custom message
                'description.required' => 'Description is Required', // custom message
                'uom.required' => 'UOM is Required', // custom message
            ]
        );

        try {
            $stone                        = Stone::find($id);
            $stone->additional_charge_id  = strip_tags($request->additional_charge_id);
            $stone->category              = strip_tags($request->category);
            $stone->description           = strip_tags($request->description);
            $stone->uom                   = strip_tags($request->uom);
            $stone->is_active             = strip_tags($request->input('is_active'));
            $stone->updated_by            = Auth::user()->name;
            $stone->update();

            return redirect()->route('stones.index')->withSuccess('Stone record updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Stone::where('id', $id)->firstorfail()->delete();
        return redirect('/stones')->with('success', 'Stone records deleted successfully.');
    }
}
