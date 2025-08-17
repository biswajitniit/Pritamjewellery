<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use Exception;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = Location::simplePaginate(25);
        return view('locations.list', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('locations.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'location_name'          => 'required',
                'location_address'       => 'required'
            ],
            [
                'location_name.required' => 'Location name is Required', // custom message
                'location_address.required' => 'Location address is Required', // custom message
            ]
        );

        Location::create([
            'location_name'        => strip_tags($request->location_name),
            'location_address'     => strip_tags($request->location_address),
            'created_by'           => Auth::user()->name
        ]);

        return redirect()->route('locations.index')->withSuccess('Locations record created successfully.');
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
        $locations = Location::findOrFail($id);
        return view('locations.edit', compact('locations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate(
            [
                'location_name'          => 'required',
                'location_address'       => 'required'
            ],
            [
                'location_name.required' => 'Location name is Required', // custom message
                'location_address.required' => 'Location address is Required', // custom message
            ]
        );

        try {
            $location                     = Location::find($id);
            $location->location_name      = strip_tags($request->input('location_name'));
            $location->location_address   = strip_tags($request->input('location_address'));
            $location->updated_by         = Auth::user()->name;
            $location->update();

            return redirect()->route('locations.index')->withSuccess('Locations record updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Location::where('id', $id)->firstorfail()->delete();
        return redirect('/locations')->with('success', 'Location records deleted successfully.');
    }
}
