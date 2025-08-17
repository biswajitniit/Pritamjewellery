<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tollerence;
use Exception;
use Illuminate\Support\Facades\Auth;

class TollerenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tollerences = Tollerence::simplePaginate(25);
        return view('tollerences.list', compact('tollerences'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tollerences.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'weight_min'             => 'required',
                'weight_max'             => 'required',
                'tolerance_plus'         => 'required',
                'tolerance_minus'        => 'required'
            ],
            [
                'weight_min.required'       => 'Weight Min is Required', // custom message
                'weight_max.required'       => 'Weight Max is Required', // custom message
                'tolerance_plus.required'   => 'Tolerance (+) is Required', // custom message
                'tolerance_minus.required'  => 'Tolerance (-) is Required', // custom message
            ]
        );

        Tollerence::create([
            'weight_min'           => strip_tags($request->weight_min),
            'weight_max'           => strip_tags($request->weight_max),
            'tolerance_plus'       => strip_tags($request->tolerance_plus),
            'tolerance_minus'      => strip_tags($request->tolerance_minus),
            'created_by'           => Auth::user()->name
        ]);

        return redirect()->route('tollerences.index')->withSuccess('Tollerences record created successfully.');
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
        $tollerences = Tollerence::findOrFail($id);
        return view('tollerences.edit', compact('tollerences'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate(
            [
                'weight_min'             => 'required',
                'weight_max'             => 'required',
                'tolerance_plus'         => 'required',
                'tolerance_minus'        => 'required'
            ],
            [
                'weight_min.required'       => 'Weight Min is Required', // custom message
                'weight_max.required'       => 'Weight Max is Required', // custom message
                'tolerance_plus.required'   => 'Tolerance (+) is Required', // custom message
                'tolerance_minus.required'  => 'Tolerance (-) is Required', // custom message
            ]
        );
        try {
            $tollerence                     = Tollerence::find($id);
            $tollerence->weight_min         = strip_tags($request->input('weight_min'));
            $tollerence->weight_max         = strip_tags($request->input('weight_max'));
            $tollerence->tolerance_plus     = strip_tags($request->input('tolerance_plus'));
            $tollerence->tolerance_minus    = strip_tags($request->input('tolerance_minus'));
            $tollerence->updated_by         = Auth::user()->name;
            $tollerence->update();

            return redirect()->route('tollerences.index')->withSuccess('Tollerences record updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Tollerence::where('id', $id)->firstorfail()->delete();
        return redirect('/tollerences')->with('success', 'Tollerences records deleted successfully.');
    }
}
