<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pattern;
use Exception;
use Illuminate\Support\Facades\Auth;

class PatternController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patterns = Pattern::simplePaginate(25);
        return view('patterns.list', compact('patterns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('patterns.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'pid'          => 'required',
                'pat_desc'     => 'required'
            ],
            [
                'pid.required' => 'Pattern ID is Required', // custom message
                'pat_desc.required' => 'Pattern Description is Required', // custom message
            ]
        );

        Pattern::create([
            'pid'                  => strip_tags($request->pid),
            'pat_desc'             => strip_tags($request->pat_desc),
            'is_active'            => strip_tags($request->is_active),
            'created_by'           => Auth::user()->name
        ]);

        return redirect()->route('patterns.index')->withSuccess('Pattern record created successfully.');
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
        $patterns = Pattern::findOrFail($id);
        return view('patterns.edit', compact('patterns'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validatedData = $request->validate(
            [
                'pid'          => 'required',
                'pat_desc'     => 'required'
            ],
            [
                'pid.required' => 'Pattern ID is Required', // custom message
                'pat_desc.required' => 'Pattern Description is Required', // custom message
            ]
        );

        try {
            $pattern                     = Pattern::find($id);
            $pattern->pid                = strip_tags($request->pid);
            $pattern->pat_desc           = strip_tags($request->pat_desc);
            $pattern->is_active          = strip_tags($request->input('is_active'));
            $pattern->updated_by         = Auth::user()->name;
            $pattern->update();

            return redirect()->route('patterns.index')->withSuccess('Pattern record updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Pattern::where('id', $id)->firstorfail()->delete();
        return redirect('/patterns')->with('success', 'Pattern records deleted successfully.');
    }
}
