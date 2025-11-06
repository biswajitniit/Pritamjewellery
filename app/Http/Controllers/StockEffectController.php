<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockEffect;
use Illuminate\Support\Facades\DB;
use App\Exports\StockEffectReportExport;
use Maatwebsite\Excel\Facades\Excel;

class StockEffectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $ledgerType = $request->query('ledger_type');
        $fromDate   = $request->query('from_date');
        $toDate     = $request->query('to_date');
        $export     = $request->query('export');

        // 🔹 If export button clicked, download Excel
        if ($export) {
            $fileName = "StockEffect_{$ledgerType}_{$fromDate}_to_{$toDate}.xlsx";
            return Excel::download(
                new StockEffectReportExport($ledgerType, $fromDate, $toDate),
                $fileName
            );
        }

        // 🔹 Otherwise show filtered data in table
        $report = collect();
        if ($fromDate && $toDate) {
            $query = DB::table('stock_effects');

            if ($ledgerType) {
                $query->where('ledger_type', $ledgerType);
            }

            $report = $query
                ->whereBetween('metal_receive_entries_date', [$fromDate, $toDate])
                ->select(
                    'vou_no',
                    'metal_receive_entries_date',
                    'location_name',
                    'ledger_name',
                    'ledger_code',
                    'ledger_type',
                    'metal_category',
                    'metal_name',
                    'net_wt',
                    'purity',
                    'pure_wt'
                )
                ->get();
        }

        return view('stockeffect.list', compact('report', 'ledgerType', 'fromDate', 'toDate'));
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