<?php

// namespace App\Exports;

// use Illuminate\Contracts\View\View;
// use Maatwebsite\Excel\Concerns\FromView;

// class StockEffectReportExport implements FromView
// {
//     protected $report;

//     public function __construct($report)
//     {
//         $this->report = $report;
//     }

//     public function view(): View
//     {
//         return view('stockeffect.export', [
//             'report' => $this->report
//         ]);
//     }
// }



namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class StockEffectReportExport implements FromView
{
    protected $ledgerType;
    protected $fromDate;
    protected $toDate;

    public function __construct($ledgerType = null, $fromDate = null, $toDate = null)
    {
        $this->ledgerType = $ledgerType;
        $this->fromDate   = $fromDate;
        $this->toDate     = $toDate;
    }

    public function view(): View
    {
        $query = DB::table('stock_effects');

        // Apply filters if available
        if ($this->ledgerType) {
            $query->where('ledger_type', $this->ledgerType);
        }

        if ($this->fromDate && $this->toDate) {
            $query->whereBetween('metal_receive_entries_date', [$this->fromDate, $this->toDate]);
        }

        $report = $query->select(
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
        )->get();

        return view('stockeffect.export', [
            'report' => $report
        ]);
    }
}
