<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Issuetokarigar;
use App\Models\Issuetokarigaritem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\DayWiseReportExport;
use Maatwebsite\Excel\Facades\Excel;

class DaywisereportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $from_date = Carbon::createFromFormat('d-m-Y', $request->from_date)->format('Y-m-d');
        $to_date   = Carbon::createFromFormat('d-m-Y', $request->to_date)->format('Y-m-d');

        //DB::enableQueryLog();
        //$issuetokarigaritems = Issuetokarigaritem::whereBetween('created_at', [$from_date, $to_date])->get();
        //dd(DB::getQueryLog());

        // return Excel::download(new DayWiseReportExport($from_date, $to_date), 'daywise_report.xlsx');

        return view('daywisereport.list', compact('from_date', 'to_date'));
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
