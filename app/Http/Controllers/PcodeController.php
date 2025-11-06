<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pcode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

class PcodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pcodes = Pcode::simplePaginate(25);
        return view('pcodes.list', compact('pcodes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pcodes.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'code'              => 'required|',
                'description'       => 'required'
            ],
            [
                'code.required' => 'Code is Required', // custom message
                'description.required' => 'Your Description is Required', // custom message
            ]
        );

        Pcode::create([
            'code'                 => strip_tags($request->code),
            'description'          => strip_tags($request->description),
            'is_active'            => strip_tags($request->is_active),
            'created_by'           => Auth::user()->name
        ]);

        return redirect()->route('pcodes.index')->withSuccess('Pcode record created successfully.');
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
        $pcodes = Pcode::findOrFail($id);
        return view('pcodes.edit', compact('pcodes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate(
            [
                'code'              => 'required',
                'description'       => 'required'
            ],
            [
                'code.required' => 'Code is Required', // custom message
                'description.required' => 'Your Description is Required', // custom message
            ]
        );

        try {
            $pcode                     = Pcode::find($id);
            $pcode->code               = strip_tags($request->input('code'));
            $pcode->description        = strip_tags($request->input('description'));
            $pcode->is_active          = strip_tags($request->input('is_active'));
            $pcode->updated_by         = Auth::user()->name;
            $pcode->update();

            return redirect()->route('pcodes.index')->withSuccess('Pcode record updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Pcode::where('id', $id)->firstorfail()->delete();
        return redirect('/pcodes')->with('success', 'Pcode records deleted successfully.');
    }
}
