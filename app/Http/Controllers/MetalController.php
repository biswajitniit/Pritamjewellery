<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Metal;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;

class MetalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $metals = Metal::simplePaginate(25);
        return view('metals.list', compact('metals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('metals.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'metal_name'        => 'required',
                'metal_category'    => 'required',
                'description'       => 'required'
            ],
            [
                'metal_name.required' => 'Metal Name is Required', // custom message
                'metal_category.required' => 'Metal Category is Required', // custom message
                'description.required' => 'Your Description is Required', // custom message
            ]
        );

        Metal::create([
            'metal_name'           => strip_tags($request->metal_name),
            'metal_category'       => strip_tags($request->metal_category),
            'metal_hsn'            => strip_tags($request->metal_hsn),
            'metal_sac'            => strip_tags($request->metal_sac),
            'description'          => strip_tags($request->description),
            'is_active'            => strip_tags($request->is_active),
            'created_by'           => Auth::user()->name
        ]);

        return redirect()->route('metals.index')->withSuccess('Metal record created successfully.');
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
        $metals = Metal::findOrFail($id);
        return view('metals.edit', compact('metals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validatedData = $request->validate(
            [
                'metal_name'        => 'required',
                'metal_category'    => 'required',
                'description'       => 'required'
            ],
            [
                'metal_name.required' => 'Metal Name is Required', // custom message
                'metal_category.required' => 'Metal Category is Required', // custom message
                'description.required' => 'Your Description is Required', // custom message
            ]
        );

        try {
            $metal                     = Metal::find($id);
            $metal->metal_name         = strip_tags($request->input('metal_name'));
            $metal->metal_category     = strip_tags($request->input('metal_category'));
            $metal->metal_hsn          = strip_tags($request->input('metal_hsn'));
            $metal->metal_sac          = strip_tags($request->input('metal_sac'));
            $metal->description        = strip_tags($request->input('description'));
            $metal->is_active          = strip_tags($request->input('is_active'));
            $metal->updated_by         = Auth::user()->name;
            $metal->update();
            return redirect()->route('metals.index')->withSuccess('Metal record updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Metal::where('metal_id', $id)->firstorfail()->delete();
        return redirect('/metals')->with('success', 'Metal records deleted successfully.');
    }
}
