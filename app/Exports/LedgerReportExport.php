<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LedgerReportExport implements FromView, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('transaction-reports.excel_export', $this->data);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set reduced column widths
                $sheet->getColumnDimension('A')->setWidth(8);   // SL NO
                $sheet->getColumnDimension('B')->setWidth(12);  // Voucher No
                $sheet->getColumnDimension('C')->setWidth(12);  // Date
                $sheet->getColumnDimension('D')->setWidth(15);  // Issued Product
                $sheet->getColumnDimension('E')->setWidth(10);  // Purity
                $sheet->getColumnDimension('F')->setWidth(10);  // Wt/pcs
                $sheet->getColumnDimension('G')->setWidth(12);  // Opening Gold
                $sheet->getColumnDimension('H')->setWidth(12);  // Opening Other
                $sheet->getColumnDimension('I')->setWidth(12);  // Issue Gold
                $sheet->getColumnDimension('J')->setWidth(12);  // Issue Others
                $sheet->getColumnDimension('K')->setWidth(12);  // Receive Gold
                $sheet->getColumnDimension('L')->setWidth(12);  // Receive Others
                $sheet->getColumnDimension('M')->setWidth(12);  // Loss Gold
                $sheet->getColumnDimension('N')->setWidth(12);  // Closing Gold
                $sheet->getColumnDimension('O')->setWidth(12);  // Closing Others

                // Style the header rows
                $event->sheet->getStyle('A1:O1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                $event->sheet->getStyle('A2:O2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Style the main table headers (row 4 and 5)
                $event->sheet->getStyle('A4:O5')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F3F3F3'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Apply borders to all data cells
                $lastRow = $sheet->getHighestRow();
                $event->sheet->getStyle('A4:O' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Text fields (right aligned) - Voucher No, Date, Issued Product
                $event->sheet->getStyle('B6:B' . $lastRow)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $event->sheet->getStyle('C6:C' . $lastRow)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $event->sheet->getStyle('D6:D' . $lastRow)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                // Numeric fields (left aligned) - SL NO and all numeric columns
                $event->sheet->getStyle('A6:A' . $lastRow)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getStyle('E6:O' . $lastRow)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                // Merge cells for title rows
                $sheet->mergeCells('A1:O1');
                $sheet->mergeCells('A2:O2');
            },
        ];
    }
}
