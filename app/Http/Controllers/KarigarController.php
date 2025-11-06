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
        try {
            // Validate input with stricter rules
            $validatedData = $request->validate(
                [
                    'kid'       => 'required|string|max:50|unique:karigars,kid',
                    'kname'     => 'required|string|max:255',
                    'kfather'   => 'required|string|max:255',
                    'address'   => 'required|string',
                    'mobile'    => 'required|digits:10',
                    'phone'     => 'nullable|digits_between:6,15',
                    'pan'       => 'nullable|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/i', // e.g. ABCDE1234F
                    'gstin'     => 'nullable|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i',
                    'remark'    => 'nullable|string|max:500',
                    'introducer' => 'nullable|string|max:255',
                    'statecode' => 'nullable|string|max:10',
                    'karigar_loss' => 'nullable|between:0,99.99',
                    'is_active' => 'nullable|in:Yes,No',
                ],
                [
                    'kid.required'     => 'KID is required.',
                    'kid.unique'       => 'This KID already exists.',
                    'kname.required'   => 'Karigar name is required.',
                    'kfather.required' => 'Father name is required.',
                    'address.required' => 'Address is required.',
                    'mobile.required'  => 'Mobile number is required.',
                    'mobile.digits'    => 'Mobile number must be exactly 10 digits.',
                    'pan.regex'        => 'PAN format is invalid.',
                    'gstin.regex'      => 'GSTIN format is invalid.',
                ]
            );

            // Create record directly with validated data
            Karigar::create([
                'kid'          => $validatedData['kid'],
                'kname'        => $validatedData['kname'],
                'kfather'      => $validatedData['kfather'],
                'address'      => $validatedData['address'],
                'phone'        => $validatedData['phone'] ?? null,
                'mobile'       => $validatedData['mobile'],
                'pan'          => $validatedData['pan'] ?? null,
                'introducer'   => $validatedData['introducer'] ?? null,
                'karigar_loss' => $validatedData['karigar_loss'] ?? null,
                'remark'       => $validatedData['remark'] ?? null,
                'gstin'        => $validatedData['gstin'] ?? null,
                'statecode'    => $validatedData['statecode'] ?? null,
                'is_active'    => $validatedData['is_active'] ?? 'Yes',
                'created_by'   => Auth::user()->name ?? 'system',
            ]);

            return redirect()
                ->route('karigars.index')
                ->with('success', 'Karigar record created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Error creating Karigar: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Something went wrong while creating Karigar. Please try again.')
                ->withInput();
        }
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
        try {
            // Validate input
            $validatedData = $request->validate(
                [
                    'kid'       => 'required|string|max:50|unique:karigars,kid,' . $id,
                    'kname'     => 'required|string|max:255',
                    'kfather'   => 'required|string|max:255',
                    'address'   => 'required|string',
                    'mobile'    => 'required|digits:10',
                    'phone'     => 'nullable|digits_between:6,15',
                    'pan'       => 'nullable|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/i',
                    'gstin'     => 'nullable|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i',
                    'remark'    => 'nullable|string|max:500',
                    'introducer' => 'nullable|string|max:255',
                    'statecode' => 'nullable|string|max:10',
                    'karigar_loss' => 'nullable|between:0,99.99',
                    'is_active' => 'nullable|in:Yes,No',
                ],
                [
                    'kid.required'     => 'KID is required.',
                    'kid.unique'       => 'This KID already exists.',
                    'kname.required'   => 'Karigar name is required.',
                    'kfather.required' => 'Father name is required.',
                    'address.required' => 'Address is required.',
                    'mobile.required'  => 'Mobile number is required.',
                    'mobile.digits'    => 'Mobile number must be exactly 10 digits.',
                    'pan.regex'        => 'PAN format is invalid.',
                    'gstin.regex'      => 'GSTIN format is invalid.',
                ]
            );

            // Find Karigar
            $karigar = Karigar::findOrFail($id);

            // Update with validated data
            $karigar->update([
                'kid'          => $validatedData['kid'],
                'kname'        => $validatedData['kname'],
                'kfather'      => $validatedData['kfather'],
                'address'      => $validatedData['address'],
                'phone'        => $validatedData['phone'] ?? null,
                'mobile'       => $validatedData['mobile'],
                'pan'          => $validatedData['pan'] ?? null,
                'introducer'   => $validatedData['introducer'] ?? null,
                'karigar_loss' => $validatedData['karigar_loss'] ?? null,
                'remark'       => $validatedData['remark'] ?? null,
                'gstin'        => $validatedData['gstin'] ?? null,
                'statecode'    => $validatedData['statecode'] ?? null,
                'is_active'    => $validatedData['is_active'] ?? 'Yes',
                'updated_by'   => Auth::user()->name ?? 'system',
            ]);

            return redirect()
                ->route('karigars.index')
                ->with('success', 'Karigar record updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Error updating Karigar: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Something went wrong while updating Karigar. Please try again.')
                ->withInput();
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
