<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Metal;
use App\Models\Metalpurity;
use App\Models\Metalissueentry;
use App\Models\Customer;
use App\Models\Karigar;
use App\Models\Location;
use App\Models\Metalreceiveentry;
use App\Models\Vouchertype;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;


class MetalissueentryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $metalissueentries = Metalissueentry::with('metal', 'karigar', 'metalpurity')->simplePaginate(25);
        return view('metalissueentries.list', compact('metalissueentries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $karigars = Karigar::where('is_active', 'Yes')->orderBy('kid')->get();
        $metals = Metal::where('is_active', 'Yes')->orderBy('metal_name')->get();

        // $lastVoucher = Metalissueentry::orderBy('metal_issue_entries_id', 'desc')->first();

        // if ($lastVoucher) {
        //     $newNumber = $lastVoucher->metal_issue_entries_id + 1;
        //     $newVoucherNo = $newNumber . '/' . date('y') . '-' . date('y', strtotime('+1 year'));
        // } else {
        //     $newVoucherNo = '1/' . date('y') . '-' . date('y', strtotime('+1 year'));
        // }
        $locations = Location::get();

        return view('metalissueentries.add', compact('metals', 'karigars', 'locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Get voucher type FIRST (before validation to auto-generate vou_no)
        $voucherType = Vouchertype::where('voucher_type', 'gold_issue_entry')
            ->where('location_id', $request->location_id)
            ->first();

        if (!$voucherType) {
            return back()->withErrors(['Voucher type not found for this location.']);
        }

        // Generate next voucher number
        $nextNo = (int)$voucherType->lastno + 1;
        $voucherNo = str_pad($nextNo, 3, '0', STR_PAD_LEFT);

        // Final formatted voucher number
        //$voucherNoPadded = $voucherType->prefix . '/' . $voucherNo . '/' . $voucherType->applicable_year;
        $voucherNoPadded = $voucherType->prefix . '/' . $voucherType->lastno . '/' . $voucherType->applicable_year;

        $validatedData = $request->validate(
            [
                'metal_category'                         => 'required',
                'metal_issue_entries_date'               => 'required',
                'karigar_id'                             => 'required',
                'metal_id'                               => 'required',
                'purity_id'                              => 'required',
                'converted_purity'                       => 'required',
                'weight'                                 => 'required',
                'alloy_gm'                               => 'required',
                'netweight_gm'                           => 'required',
            ],
            [
                'metal_category.required'           => 'Category is Required', // custom message
                'metal_issue_entries_date.required' => 'Date is Required', // custom message
                'karigar_id.required'               => 'Kid is Required', // custom message
                'metal_id.required'                 => 'Metal Name is Required', // custom message
                'purity_id.required'                => 'Purity is Required', // custom message
                'converted_purity.required'         => 'Converted Purity is Required', // custom message
                'weight.required'                   => 'Weight is Required', // custom message
                'alloy_gm.required'                 => 'Alloy (gm) is Required', // custom message
                'netweight_gm.required'             => 'NetWeight (gm) is Required', // custom message
            ]
        );

        Metalissueentry::create([
            'metalissueentries_id'         => (string) Str::uuid(),
            'metal_category'               => $request->metal_category,
            'location_id'                  => $request->location_id,
            'voucher_no'                   => $voucherNoPadded,
            'metal_issue_entries_date'     => $request->metal_issue_entries_date,
            'karigar_id'                   => strip_tags($request->karigar_id),
            'karigar_name'                 => strip_tags($request->karigar_name),
            'metal_id'                     => strip_tags($request->metal_id),
            'purity_id'                    => strip_tags($request->purity_id),
            'converted_purity'             => strip_tags($request->converted_purity),
            'weight'                       => strip_tags($request->weight),
            'alloy_gm'                     => strip_tags($request->alloy_gm),
            'netweight_gm'                 => strip_tags($request->netweight_gm),
            'created_by'                   => Auth::user()->name
        ]);

        $entries = Metalreceiveentry::where('metal_id', $request->metal_id)
            ->where('purity_id', $request->purity_id)
            ->where('balance_qty', '>', 0)
            ->get();

        $requestWeight = $request->weight;

        foreach ($entries as $entry) {
            if ($requestWeight <= 0) break;

            $issueQty = min($entry->balance_qty, $requestWeight);

            if (!is_null($entry->issue_qty)) {
                $entry->issue_qty += $issueQty;
            } else {
                $entry->issue_qty = $issueQty;
            }

            $entry->balance_qty -= $issueQty;
            $entry->last_entry_issue_date = now();
            $entry->last_entry_issue_by = Auth::user()->name;
            $entry->update();

            $requestWeight -= $issueQty;
        }


        // Update the voucher type's lastno (as INTEGER, not padded)
        $voucherType->lastno = $voucherNo;
        $voucherType->save();

        return redirect()->route('metalissueentries.index')->withSuccess('Metal issue entries record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $metalissueentries = Metalissueentry::findOrFail($id);
        $karigars = Karigar::findOrFail($metalissueentries->karigar_id);
        $metals   = Metal::findOrFail($metalissueentries->metal_id);
        $metalpurities   = Metalpurity::findOrFail($metalissueentries->purity_id);
        // dd($metals);
        /*
        $data = [
            'kname' => $karigars->kname,
            'address' =>  $karigars->address,
            'statecode' => $karigars->statecode,
            'gstin' => $karigars->gstin,
            'voucher_no' => $metalissueentries->voucher_no,
            'metal_issue_entries_date' => date('d/m/Y', strtotime(@$metalissueentries->metal_issue_entries_date)),
            'metal_hsn' => $metals->metal_hsn,
            'purity' => $metalpurities->purity,
            'weight' => $metalissueentries->weight,
            'netweight_gm' => $metalissueentries->netweight_gm,
            'metal_sac' => $metals->metal_sac

        ]; // any data for the view

        // Load view and pass data
        $pdf = Pdf::loadView('metalissueentries.view', $data);

        // Download PDF
        //return $pdf->download(date('Ymdhis') . 'invoice.pdf');

        // Or to stream it in the browser
        return $pdf->stream(date('Ymdhis') . 'invoice.pdf'); */


        //return view('metalissueentries.view', compact('metalissueentries', 'karigars', 'metals', 'metalpurities'));

        $pdf = Pdf::loadView('metalissueentries.view', compact('metalissueentries', 'karigars', 'metals', 'metalpurities'));
        $pdf->setPaper('A5', 'landscape');
        return $pdf->stream('metal-issue-voucher.pdf'); // opens in browser

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $metalissueentries = Metalissueentry::findOrFail($id);
        $karigars = Karigar::where('is_active', 'Yes')->orderBy('kid')->get();
        $metals = Metal::where('metal_category', $metalissueentries->metal_category)->orderBy('metal_category')->get();
        $metalpurities = Metalpurity::where('metal_id', $metalissueentries->metal_id)->orderBy('purity')->get();

        // $metalpurities = Metalreceiveentry::where('metalreceiveentries.metal_id', $metalissueentries->metal_id)
        //     ->join('metalpurities', 'metalpurities.purity_id', '=', 'metalreceiveentries.purity_id')
        //     ->select('metalpurities.purity_id', 'metalpurities.purity')
        //     ->groupBy('metalreceiveentries.metal_id')
        //     ->orderBy('metalpurities.purity')
        //     ->get();

        return view('metalissueentries.edit', compact('karigars', 'metals', 'metalpurities', 'metalissueentries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate(
            [
                'metal_category'                         => 'required',
                'metal_issue_entries_date'               => 'required',
                'karigar_id'                             => 'required',
                'metal_id'                               => 'required',
                'purity_id'                              => 'required',
                'converted_purity'                       => 'required',
                'weight'                                 => 'required',
                'alloy_gm'                               => 'required',
                'netweight_gm'                           => 'required',
            ],
            [
                'metal_category.required'           => 'Category is Required', // custom message
                'metal_issue_entries_date.required' => 'Date is Required', // custom message
                'karigar_id.required'               => 'Kid is Required', // custom message
                'metal_id.required'                 => 'Metal Name is Required', // custom message
                'purity_id.required'                => 'Purity is Required', // custom message
                'converted_purity.required'         => 'Converted Purity is Required', // custom message
                'weight.required'                   => 'Weight is Required', // custom message
                'alloy_gm.required'                 => 'Alloy (gm) is Required', // custom message
                'netweight_gm.required'             => 'NetWeight (gm) is Required', // custom message
            ]
        );

        try {
            $metalissueentries                            = Metalissueentry::find($id);

            $metalissueentries->metal_category            = strip_tags($request->input('metal_category'));
            $metalissueentries->metal_issue_entries_date  = strip_tags($request->input('metal_issue_entries_date'));
            $metalissueentries->karigar_id                = strip_tags($request->input('karigar_id'));
            $metalissueentries->karigar_name              = strip_tags($request->input('karigar_name'));
            $metalissueentries->metal_id                  = strip_tags($request->input('metal_id'));
            $metalissueentries->purity_id                 = strip_tags($request->input('purity_id'));
            $metalissueentries->converted_purity          = strip_tags($request->input('converted_purity'));
            $metalissueentries->weight                    = strip_tags($request->input('weight'));
            $metalissueentries->alloy_gm                  = strip_tags($request->input('alloy_gm'));
            $metalissueentries->netweight_gm              = strip_tags($request->input('netweight_gm'));

            $metalissueentries->updated_by                = Auth::user()->name;
            $metalissueentries->update();

            return redirect()->route('metalissueentries.index')->withSuccess('Metal issue entries record updated successfully.');
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

    public function getkarigardetails(Request $request)
    {
        $karigar = Karigar::where('id', $request->karigar_id)->first();
        return response()->json([
            "kname" => $karigar->kname,
        ]);
    }


    public function getmetalname(Request $request)
    {
        $metals = Metal::where('metal_category', $request->metal_category)->orderBy('metal_category')->get();
        $html = '<select name="metal_id" id="metal_id" class="form-select rounded-0 @error("metal_id") is-invalid @enderror" onchange="GetMetalPurityMetalIssue(this.value)">';
        $html .= '<option value="">Choose...</option>';
        foreach ($metals as $metal) {
            $html .= '<option value="' . $metal->metal_id . '">' . $metal->metal_name . '</option>';
        }
        $html .= '</select>';
        echo $html;
    }

    public function getmetalpuritymetalissue(Request $request)
    {
        $metalpurity = Metalpurity::where('metal_id', $request->metal_id)->orderBy('purity')->get();
        $html = '<select  name="purity_id" id="purity_id" class="form-select rounded-0 @error("metal_id") is-invalid @enderror" onchange="GetMetalPurityInfo(this.value)">';
        $html .= '<option value="">Choose...</option>';
        foreach ($metalpurity as $metalpuriti) {
            $html .= '<option value="' . $metalpuriti->purity_id . '">' . $metalpuriti->purity . '</option>';
        }
        $html .= '</select>';
        echo $html;
    }

    public function getmetalpurityinfo(Request $request)
    {
        $metalpurity = Metalpurity::where('purity_id', $request->purity_id)->first();
        return response()->json([
            "metal_id" => $metalpurity->metal_id,
            "purity" => $metalpurity->purity,
        ]);
    }

    public function getmetalpuritydistinct(Request $request)
    {
        $metalpurity = Metalreceiveentry::where('metalreceiveentries.metal_id', $request->metal_id)
            ->join('metalpurities', function ($join) {
                $join->on('metalpurities.purity_id', '=', 'metalreceiveentries.purity_id')
                    ->on('metalpurities.metal_id', '=', 'metalreceiveentries.metal_id');
            })
            ->select('metalpurities.purity_id', 'metalpurities.purity')
            ->where('metalreceiveentries.balance_qty', '>', 0)
            ->distinct()
            ->orderBy('metalpurities.purity')
            ->get();

        $html = '<select name="purity_id" id="purity_id" class="form-select rounded-0 @error("purity_id") is-invalid @enderror">';
        $html .= '<option value="">Choose...</option>';
        foreach ($metalpurity as $metalpuriti) {
            $html .= '<option value="' . $metalpuriti->purity_id . '">' . $metalpuriti->purity . '</option>';
        }
        $html .= '</select>';
        echo $html;
    }

    public function gettotalmetalreceiveweight(Request $request)
    {
        $totalWeight = Metalreceiveentry::where('metal_id', $request->metal_id)
            ->where('purity_id', $request->purity_id)
            ->where('balance_qty', '>', 0)
            ->sum('balance_qty');

        return response()->json(['total_weight' => $totalWeight]);
    }
}
