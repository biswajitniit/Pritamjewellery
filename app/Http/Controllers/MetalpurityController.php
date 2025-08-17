<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Metal;
use App\Models\Metalpurity;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;

class MetalpurityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $metalpurities = Metalpurity::with('metal')->simplePaginate(25);
        return view('metalpurities.list', compact('metalpurities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $metals = Metal::where('is_active', 'Yes')->orderBy('metal_name')->get();
        return view('metalpurities.add', compact('metals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'metal_id'        => 'required',
                'purity'          => 'required'
            ],
            [
                'metal_id.required' => 'Metal Name is Required', // custom message
                'purity.required' => 'Purity is Required', // custom message
            ]
        );

        Metalpurity::create([
            'metal_id'             => strip_tags($request->metal_id),
            'purity'               => strip_tags($request->purity),
            'is_active'            => strip_tags($request->is_active),
            'created_by'           => Auth::user()->name
        ]);

        return redirect()->route('metalpurities.index')->withSuccess('Metal purities record created successfully.');
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
        $metals = Metal::where('is_active', 'Yes')->orderBy('metal_name')->get();
        $metalpurities = Metalpurity::findOrFail($id);
        return view('metalpurities.edit', compact('metals', 'metalpurities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate(
            [
                'metal_id'        => 'required',
                'purity'          => 'required'
            ],
            [
                'metal_id.required' => 'Metal Name is Required', // custom message
                'purity.required' => 'Purity is Required', // custom message
            ]
        );

        try {
            $metalpurities                     = Metalpurity::find($id);
            $metalpurities->metal_id           = strip_tags($request->input('metal_id'));
            $metalpurities->purity             = strip_tags($request->input('purity'));
            $metalpurities->is_active          = strip_tags($request->input('is_active'));
            $metalpurities->updated_by         = Auth::user()->name;
            $metalpurities->update();
            return redirect()->route('metalpurities.index')->withSuccess('Metal purities record updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Metalpurity::where('purity_id', $id)->firstorfail()->delete();
        return redirect('/metalpurities')->with('success', 'Metal purities records deleted successfully.');
    }
}
