<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Issuetokarigar;
use App\Models\Issuetokarigaritem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\DayWiseReportExport;
use App\Models\FinancialYear;
use Maatwebsite\Excel\Facades\Excel;

class DaywisereportController extends Controller
{
    /**
     * Display Day Wise Report page and handle filter/export
     */
    public function index(Request $request)
    {
        // Fetch all financial years for dropdown
        $financialyears = FinancialYear::orderBy('id', 'desc')->get();

        // Default values
        $issuetokarigaritems = collect();
        $from_date = $to_date = null;

        // If user has submitted form (with from/to date)
        if ($request->filled(['from_date', 'to_date'])) {
            try {
                // Automatically parse any valid date format (prevents Carbon errors)
                $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
                $to_date   = Carbon::parse($request->to_date)->format('Y-m-d');
            } catch (\Exception $e) {
                return back()->with('error', 'Invalid date format. Please use DD-MM-YYYY.');
            }

            // Fetch filtered records
            $issuetokarigaritems = Issuetokarigaritem::whereBetween('created_at', [$from_date, $to_date])
                ->orderBy('created_at', 'desc')
                ->get();

            // Handle Excel export
            if ($request->has('export') && $request->export === 'excel') {
                return Excel::download(
                    new DayWiseReportExport($from_date, $to_date),
                    'daywise_report_' . now()->format('d_m_Y') . '.xlsx'
                );
            }
        }

        // Load main page view
        return view('daywisereport.list', compact('financialyears', 'issuetokarigaritems', 'from_date', 'to_date'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
