<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Size;
use App\Models\Pcode;
use Illuminate\Support\Facades\Auth;
use Exception;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sizes = Size::simplePaginate(25);
        return view('sizes.list', compact('sizes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pcodes = Pcode::where('is_active', 'Yes')->orderBy('code')->get();
        return view('sizes.add', compact('pcodes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'pcode_id'          => 'required',
                'schar'             => 'required',
                'item_name'         => 'required',
                'ssize'             => 'required'
            ],
            [
                'pcode_id.required' => 'Pcode is Required', // custom message
                'schar.required' => 'schar is Required', // custom message
                'item_name.required' => 'Item Name is Required', // custom message
                'ssize.required' => 'Ssize is Required', // custom message
            ]
        );

        Size::create([
            'pcode_id'             => strip_tags($request->pcode_id),
            'schar'                => strip_tags($request->schar),
            'item_name'            => strip_tags($request->item_name),
            'ssize'                => strip_tags($request->ssize),
            'is_active'            => strip_tags($request->is_active),
            'created_by'           => Auth::user()->name
        ]);

        return redirect()->route('sizes.index')->withSuccess('Size record created successfully.');
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
        $pcodes = Pcode::where('is_active', 'Yes')->orderBy('code')->get();
        $sizes = Size::findOrFail($id);
        return view('sizes.edit', compact('pcodes', 'sizes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validatedData = $request->validate(
            [
                'pcode_id'          => 'required',
                'schar'             => 'required',
                'item_name'         => 'required',
                'ssize'             => 'required'
            ],
            [
                'pcode_id.required'      => 'Pcode is Required', // custom message
                'schar.required'         => 'schar is Required', // custom message
                'item_name.required'     => 'Item Name is Required', // custom message
                'ssize.required'         => 'Ssize is Required', // custom message
            ]
        );

        try {
            $size                     = Size::find($id);
            $size->pcode_id           = strip_tags($request->pcode_id);
            $size->schar              = strip_tags($request->schar);
            $size->item_name          = strip_tags($request->item_name);
            $size->ssize              = strip_tags($request->ssize);
            $size->is_active          = strip_tags($request->input('is_active'));
            $size->updated_by         = Auth::user()->name;
            $size->update();
            return redirect()->route('sizes.index')->withSuccess('Size record updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Size::where('id', $id)->firstorfail()->delete();
        return redirect('/sizes')->with('success', 'Size records deleted successfully.');
    }
}
