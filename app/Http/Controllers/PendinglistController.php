<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karigar;
use App\Models\Issuetokarigar;
use App\Models\Issuetokarigaritem;
use App\Models\Product;
use App\Models\Qualitycheck;
use App\Models\Qualitycheckitem;
use Illuminate\Support\Facades\DB;
use App\Exports\PendingReportExport;
use Maatwebsite\Excel\Facades\Excel;

class PendinglistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('date'); // e.g. 2025-08-31

        if ($search) {
            $report = DB::select("
                SELECT
                    i.job_no,
                    i.item_code,
                    i.size,
                    i.st_weight,
                    i.delivery_date AS REQ_DATE_K,
                    CASE
                        WHEN COALESCE(q.bal_qty, 0) = 0 THEN i.qty
                        ELSE q.bal_qty
                    END AS bal_qty,
                    k.kid,
                    k.kname,
                    p.lead_time_karigar,
                    p.product_lead_time,
                    (CAST(p.product_lead_time AS UNSIGNED) - CAST(p.lead_time_karigar AS UNSIGNED)) AS lead_time_days,
                    DATE_ADD(
                        i.delivery_date,
                        INTERVAL (CAST(p.product_lead_time AS UNSIGNED) - CAST(p.lead_time_karigar AS UNSIGNED)) DAY
                    ) AS REQ_DATE_C,
                    c.jo_date
                FROM issuetokarigaritems i
                INNER JOIN customerorders c
                    ON i.job_no = c.jo_no
                LEFT JOIN qualitycheckitems q
                    ON i.job_no = q.job_no
                    AND i.item_code = q.item_code
                INNER JOIN karigars k
                    ON i.kid = k.kid
                INNER JOIN products p
                    ON i.item_code = p.item_code
                WHERE i.delivery_date <= ?
                AND i.finish_product_received = 'No'
            ", [$search]);

            // Download only when search date exists
            $fileName = "PendingList_{$search}.xlsx";
            return Excel::download(new PendingReportExport($report), $fileName);
        }

        // If no search, just show the search form
        return view('pendinglist.list');
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
