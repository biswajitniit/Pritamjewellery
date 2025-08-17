<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\Karigar;

class KarigarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karigars = Karigar::simplePaginate(25);
        return view('karigars.list', compact('karigars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('karigars.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'kid'                  => 'required',
                'kname'                => 'required',
                'kfather'              => 'required',
                'address'              => 'required',
                'mobile'               => 'required'
            ],
            [
                'kid.required'     => 'KID Name is Required', // custom message
                'kname.required'   => 'Karigar Name is Required', // custom message
                'kfather.required' => 'Father Name is Required', // custom message
                'address.required' => 'Address is Required', // custom message
                'mobile.required'  => 'Mobile is Required', // custom message
            ]
        );

        Karigar::create([
            'kid'                  => strip_tags($request->kid),
            'kname'                => strip_tags($request->kname),
            'kfather'              => strip_tags($request->kfather),
            'address'              => strip_tags($request->address),
            'phone'                => strip_tags($request->phone),
            'mobile'               => strip_tags($request->mobile),
            'pan'                  => strip_tags($request->pan),
            'introducer'           => strip_tags($request->introducer),
            'remark'               => strip_tags($request->remark),
            'gstin'                => strip_tags($request->gstin),
            'statecode'            => strip_tags($request->statecode),
            'is_active'            => strip_tags($request->is_active),
            'created_by'           => Auth::user()->name
        ]);

        return redirect()->route('karigars.index')->withSuccess('Karigar record created successfully.');
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
        $karigars = Karigar::findOrFail($id);
        return view('karigars.edit', compact('karigars'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate(
            [
                'kid'                  => 'required',
                'kname'                => 'required',
                'kfather'              => 'required',
                'address'              => 'required',
                'mobile'               => 'required'
            ],
            [
                'kid.required'     => 'KID Name is Required', // custom message
                'kname.required'   => 'Karigar Name is Required', // custom message
                'kfather.required' => 'Father Name is Required', // custom message
                'address.required' => 'Address is Required', // custom message
                'mobile.required'  => 'Mobile is Required', // custom message
            ]
        );

        try {

            $karigar                     = Karigar::find($id);
            $karigar->kid                = strip_tags($request->kid);
            $karigar->kname              = strip_tags($request->kname);
            $karigar->kfather            = strip_tags($request->kfather);
            $karigar->address            = strip_tags($request->address);
            $karigar->phone              = strip_tags($request->phone);
            $karigar->mobile             = strip_tags($request->mobile);
            $karigar->pan                = strip_tags($request->pan);
            $karigar->introducer         = strip_tags($request->introducer);
            $karigar->remark             = strip_tags($request->remark);
            $karigar->gstin              = strip_tags($request->gstin);
            $karigar->statecode          = strip_tags($request->statecode);
            $karigar->is_active          = strip_tags($request->input('is_active'));
            $karigar->updated_by         = Auth::user()->name;
            $karigar->update();

            return redirect()->route('karigars.index')->withSuccess('Karigar record updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Karigar::where('id', $id)->firstorfail()->delete();
        return redirect('/karigars')->with('success', 'Karigar records deleted successfully.');
    }
}
