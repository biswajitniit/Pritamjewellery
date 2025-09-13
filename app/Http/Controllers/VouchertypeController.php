<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vouchertype;
use App\Models\Location;
use Exception;
use Illuminate\Support\Facades\Auth;

class VouchertypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('applicable_year');

        $vouchertypes = Vouchertype::with('location')
            ->when($search, function ($query, $search) {
                $query->where('applicable_year', 'like', "%{$search}%");
            })
            ->paginate(100);

        return view('vouchertypes.list', compact('vouchertypes', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $locations = Location::get();
        return view('vouchertypes.add', compact('locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'applicable_year'        => 'required',
                'voucher_type'           => 'required',
                'location_id'            => 'required',
                'applicable_date'        => 'required',
                'startno'                => 'required',
                'prefix'                 => 'required',
                'suffix'                 => 'required',
                'status'                 => 'required',
            ],
            [
                'applicable_year.required' => 'Applicable Year is Required', // custom message
                'voucher_type.required' => 'Voucher Type is Required',
                'location_id.required' => 'Location is Required', // custom message
                'applicable_date.required' => 'Applicable Date is Required', // custom message
                'startno.required' => 'Start no is Required', // custom message
                'prefix.required'    => 'Prefix is Required', // custom message
                'suffix.required' => 'Suffix is Required', // custom message
                'status.required' => 'Status Code is Required', // custom message
            ]
        );


        Vouchertype::create([
            'applicable_year'        => strip_tags($request->applicable_year),
            'voucher_type'           => strip_tags($request->voucher_type),
            'location_id'            => strip_tags($request->location_id),
            'applicable_date'        => strip_tags($request->applicable_date),
            'startno'                => strip_tags($request->startno),
            'prefix'                 => strip_tags($request->prefix),
            'suffix'                 => strip_tags($request->suffix),
            'status'                 => strip_tags($request->status),
            'lastno'                 => strip_tags($request->startno),
            'created_by'             => Auth::user()->name
        ]);


        return redirect()->route('vouchertypes.index')->withSuccess('Voucher type record created successfully.');
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
        $vouchertypes = Vouchertype::findOrFail($id);
        $locations = Location::get();

        return view('vouchertypes.edit', compact('vouchertypes', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate(
            [
                'applicable_year'        => 'required',
                'voucher_type'           => 'required',
                'location_id'            => 'required',
                'applicable_date'        => 'required',
                'startno'                => 'required',
                'prefix'                 => 'required',
                'suffix'                 => 'required',
                'status'                 => 'required',
            ],
            [
                'applicable_year.required' => 'Applicable Year is Required', // custom message
                'voucher_type.required'    => 'Voucher Type is Required',
                'location_id.required'     => 'Location is Required', // custom message
                'applicable_date.required' => 'Applicable Date is Required', // custom message
                'startno.required'         => 'Start no is Required', // custom message
                'prefix.required'          => 'Prefix is Required', // custom message
                'suffix.required'          => 'Suffix is Required', // custom message
                'status.required'          => 'Status Code is Required', // custom message
            ]
        );

        try {

            $vouchertypes                      = Vouchertype::find($id);
            $vouchertypes->applicable_year     = strip_tags($request->input('applicable_year'));
            $vouchertypes->voucher_type        = strip_tags($request->input('voucher_type'));
            $vouchertypes->location_id         = strip_tags($request->input('location_id'));
            $vouchertypes->applicable_date     = strip_tags($request->input('applicable_date'));
            $vouchertypes->startno             = strip_tags($request->input('startno'));
            $vouchertypes->prefix              = strip_tags($request->input('prefix'));
            $vouchertypes->suffix              = strip_tags($request->input('suffix'));
            $vouchertypes->status              = strip_tags($request->input('status'));
            $vouchertypes->lastno              = strip_tags($request->input('startno'));
            $vouchertypes->update();

            return redirect()->route('vouchertypes.index')->withSuccess('Voucher type record updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Vouchertype::where('id', $id)->firstorfail()->delete();
        return redirect('/vouchertypes')->with('success', 'Voucher type records deleted successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    /*public function getlocationwisevoucherno(Request $request)
    {
        $vouchertypes = Vouchertype::where('voucher_type', $request->voucher_type)
            ->where('location_id', $request->location_id)
            ->where('status', 'Active')
            ->first();

        if (!$vouchertypes) {
            return response()->json(['voucher_no' => null]); // ✅ Proper JSON
        }

        return response()->json([
            'voucher_no' => $vouchertypes->prefix . '/' . $vouchertypes->lastno . '/' . $vouchertypes->suffix . '/' . $vouchertypes->applicable_year
        ]);
    }*/

    public function getlocationwisevoucherno(Request $request)
    {
        $vouchertypes = Vouchertype::where('voucher_type', $request->voucher_type)
            ->where('location_id', $request->location_id)
            ->where('status', 'Active')
            ->first();

        if (!$vouchertypes) {
            return response()->json(['voucher_no' => null]);
        }

        // Determine the next number
        $nextNo = (int) $vouchertypes->lastno + 1;

        // Pad the number according to startno length (001, 0001, etc.)
        $length = strlen($vouchertypes->startno);
        $nextNoFormatted = str_pad($nextNo, $length, '0', STR_PAD_LEFT);

        // Build final voucher no: PREFIX/NUMBER/SUFFIX/YEAR
        $voucherNo = $vouchertypes->prefix . '/' . $nextNoFormatted . '/' . $vouchertypes->suffix . '/' . $vouchertypes->applicable_year;

        return response()->json([
            'voucher_no' => $voucherNo
        ]);
    }
}
