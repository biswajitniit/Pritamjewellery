<?php

namespace App\Http\Controllers;

use App\Models\Finishedproductpdi;
use App\Models\Location;
use App\Models\RejectionReason;
use App\Models\RejectionRecdFromCustomer;
use Illuminate\Http\Request;


class RejectionRecdFromCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rejectionrecdfromcustomers = RejectionRecdFromCustomer::with('location')->paginate(25);
        return view('rejectionrecdfromcustomers.list', compact('rejectionrecdfromcustomers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $locations = Location::orderBy('location_name')->get();
        $reasons = RejectionReason::orderBy('id', 'ASC')->get();

        // get unique job numbers where rejection is still pending
        $finishedproductpdis = Finishedproductpdi::where('rejection_from_customer', 'Pending')
            ->select('job_no')
            ->distinct()
            ->get();
        return view('rejectionrecdfromcustomers.add', compact('locations', 'reasons', 'finishedproductpdis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(RejectionRecdFromCustomer $rejectionRecdFromCustomer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RejectionRecdFromCustomer $rejectionRecdFromCustomer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RejectionRecdFromCustomer $rejectionRecdFromCustomer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RejectionRecdFromCustomer $rejectionRecdFromCustomer)
    {
        //
    }

    public function getkidjobnowise(Request $request)
    {
        // get unique job numbers where rejection is still pending
        $finishedproductpdis = Finishedproductpdi::where('rejection_from_customer', 'Pending')
            ->where('job_no', $request->job_no)
            ->select('kid')
            ->distinct()
            ->get();

        $html = '<select name="kid" class="form-select rounded-0 @error("kid") is-invalid @enderror" id="jobno_kid" onchange="GetItemCodeKIDJobNoWise(this.value)" required>';
        $html .= '<option value="">Choose...</option>';
        foreach ($finishedproductpdis as $finishedproductpdi) {
            $html .= '<option value="' . $finishedproductpdi->kid . '" >' . $finishedproductpdi->kid . '</option>';
        }
        $html .= '</select>';

        echo $html;
    }
    public function getitemcodekidjobnowise(Request $request)
    {
        // get unique job numbers where rejection is still pending
        $finishedproductpdis = Finishedproductpdi::where('rejection_from_customer', 'Pending')
            ->where('job_no', $request->job_no)
            ->where('kid', $request->kid)
            ->select('item_code')
            ->distinct()
            ->get();

        $html = '<select name="item_code" 
                                        class="form-select rounded-0 @error("item_code") is-invalid @enderror"
                                        id="item_code"
                                        onchange="GetItemCodeKIDJobNoWiseQtyGrosswtNetwt(this.value)"
                                        required>';
        $html .= '<option value="">Choose...</option>';
        foreach ($finishedproductpdis as $finishedproductpdi) {
            $html .= '<option value="' . $finishedproductpdi->item_code . '" >' . $finishedproductpdi->item_code . '</option>';
        }
        $html .= '</select>';

        echo $html;
    }

    public function getitemcodekidjobnowiseqtygrosswtnetwt(Request $request)
    {
        $record = Finishedproductpdi::where('rejection_from_customer', 'Pending')
            ->where('job_no', $request->job_no)
            ->where('kid', $request->kid)
            ->where('item_code', $request->item_code)
            ->first();

        if (!$record) {
            return response()->json([
                'qty' => '',
                'gross_wt' => '',
                'net_wt' => ''
            ]);
        }

        return response()->json([
            'qty'      => $record->qty,
            'gross_wt' => $record->gross_wt,
            'net_wt'   => $record->net_wt
        ]);
    }
}