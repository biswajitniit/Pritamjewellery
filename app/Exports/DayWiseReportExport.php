<?php

namespace App\Exports;

use App\Models\Issuetokarigaritem;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class DayWiseReportExport implements FromView, WithTitle, WithStyles
{
    protected $from_date;
    protected $to_date;

    public function __construct($from_date, $to_date)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

    /**
     * Generate data and send to Blade view
     */
    public function view(): View
    {
        // $data = Issuetokarigaritem::whereBetween('created_at', [$this->from_date, $this->to_date])
        //     ->orderBy('created_at', 'asc')
        //     ->get();

        // return view('daywisereport.daywise_report_export', [
        //     'data' => $data,
        //     'from_date' => Carbon::parse($this->from_date)->format('d-m-Y'),
        //     'to_date' => Carbon::parse($this->to_date)->format('d-m-Y'),
        // ]);

        $receipts = \App\Models\MetalReceiveEntry::whereBetween('metal_receive_entries_date', [$this->from_date, $this->to_date])
            ->orderBy('metal_receive_entries_date', 'asc')
            ->get();

        $issues = \App\Models\MetalIssueEntry::whereBetween('metal_issue_entries_date', [$this->from_date, $this->to_date])
            ->orderBy('metal_issue_entries_date', 'asc')
            ->get();

        return view('daywisereport.daywise_report_export', [
            'receipts' => $receipts,
            'issues'   => $issues,
            'from_date' => \Carbon\Carbon::parse($this->from_date)->format('d/m/Y'),
            'to_date'   => \Carbon\Carbon::parse($this->to_date)->format('d/m/Y'),
            'vendor_name' => 'Bhavya Laxmi Jewellers',
            'vendor_code' => '1000684',
        ]);
    }

    public function title(): string
    {
        return 'Day Wise Report';
    }

    public function styles(Worksheet $sheet)
    {
        // Optional styling for header rows
        return [
            1 => ['font' => ['bold' => true, 'size' => 13]],
            2 => ['font' => ['bold' => true]],
        ];
    }
}