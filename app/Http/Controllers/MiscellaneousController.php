<?php

namespace App\Http\Controllers;

use App\Models\Miscellaneous;
use App\Models\Uom;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MiscellaneousController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $miscellaneouses = Miscellaneous::simplePaginate(25);
        return view('miscellaneouses.list', compact('miscellaneouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $uoms = Uom::where('is_active', 'Yes')->orderBy('uomid')->get();
        return view('miscellaneouses.add', [
            // 'uoms' => $uoms,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'product_code'  => 'required',
                'product_name'  => 'required',
                'uom'           => 'required',
                // 'size'          => 'required',
            ],
            [
                'product_code.required' => 'Product Code is Required',
                'product_name.required' => 'Product Name Required',
                'uom.required' => 'UOM is Required',
                // 'size.required' => 'Size is Required',
            ]
        );

        Miscellaneous::create([
            'product_code'  => strip_tags($request->product_code),
            'product_name'  => strip_tags($request->product_name),
            'uom'           => strip_tags($request->uom),
            'size' => null, // strip_tags($request->size),
            'is_active'     => strip_tags($request->is_active),
            'created_by'    => Auth::user()->name,
        ]);

        return redirect()->route('miscellaneouses.index')->withSuccess('New Miscellaneous added successfully.');
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
        // $uoms = Uom::where('is_active', 'Yes')->orderBy('uomid')->get();
        $miscellaneous = Miscellaneous::findOrFail($id);
        return view('miscellaneouses.edit', [
            'miscellaneous' => $miscellaneous,
            // 'uoms' => $uoms,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate(
            [
                'product_code'  => 'required',
                'product_name'  => 'required',
                'uom'           => 'required',
                // 'size'          => 'required',
            ],
            [
                'product_code.required' => 'Product Code is Required',
                'product_name.required' => 'Product Name Required',
                'uom.required' => 'UOM is Required',
                // 'size.required' => 'Size is Required',
            ]
        );

        try {
            Miscellaneous::whereRaw('id = ?', [$id])->update([
                'product_code' => strip_tags($request->product_code),
                'product_name' => strip_tags($request->product_name),
                'uom' => strip_tags($request->uom),
                'size' => null, // strip_tags($request->size),
                'is_active' => strip_tags($request->input('is_active')),
                'updated_by' => Auth::user()->name,
            ]);

            return redirect()->route('miscellaneouses.index')->withSuccess('Miscellaneous record updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $miscellaneous = Miscellaneous::whereRaw('id = ?', [$id])->firstOrFail();
        if ($miscellaneous) {
            $miscellaneous->delete();
            return redirect('/miscellaneouses')->with('success', 'Miscellaneous record deleted successfully.');
        }

        return redirect('/miscellaneouses')->with('success', 'Invalid Miscellaneous ID.');
    }
}
