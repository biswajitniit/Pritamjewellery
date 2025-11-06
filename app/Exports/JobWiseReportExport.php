<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class JobWiseReportExport implements FromView, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.job_wise_excel', $this->data);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(8);    // S. NO
                $sheet->getColumnDimension('B')->setWidth(15);   // KARIGAR NAME
                $sheet->getColumnDimension('C')->setWidth(12);   // MBDATE
                $sheet->getColumnDimension('D')->setWidth(15);   // JOB NO
                $sheet->getColumnDimension('E')->setWidth(15);   // ITEM CODE
                $sheet->getColumnDimension('F')->setWidth(12);   // GROSS WT
                $sheet->getColumnDimension('G')->setWidth(12);   // NET WT
                $sheet->getColumnDimension('H')->setWidth(20);   // MBILL NO

                // Row 1: Title (Company Name) - BOLD
                $sheet->getRowDimension(1)->setRowHeight(20);
                $sheet->mergeCells('A1:H1');
                $event->sheet->getStyle('A1:H1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // Row 2: Subtitle - BOLD
                $sheet->getRowDimension(2)->setRowHeight(18);
                $sheet->mergeCells('A2:H2');
                $event->sheet->getStyle('A2:H2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // Row 3: Empty
                $sheet->getRowDimension(3)->setRowHeight(12);

                // Row 4: Header - BOLD with gray background
                $sheet->getRowDimension(4)->setRowHeight(25);
                for ($col = 'A'; $col <= 'H'; $col++) {
                    $cellAddress = $col . '4';
                    $sheet->getStyle($cellAddress)->applyFromArray([
                        'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '000000']],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D3D3D3']],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                    ]);
                }

                // Get last row with data
                $lastRow = $sheet->getHighestRow();

                // Data rows (Row 5 onwards) - NO BOLD
                for ($row = 5; $row <= $lastRow; $row++) {
                    for ($col = 'A'; $col <= 'H'; $col++) {
                        $cellAddress = $col . $row;
                        $sheet->getStyle($cellAddress)->getFont()->setBold(false);
                        $sheet->getStyle($cellAddress)->applyFromArray([
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                        ]);
                    }
                }

                // Apply text and number alignment
                // S. NO - Center
                $sheet->getStyle('A5:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // KARIGAR NAME - Left
                $sheet->getStyle('B5:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // MBDATE - Center
                $sheet->getStyle('C5:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // JOB NO - Left
                $sheet->getStyle('D5:D' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // ITEM CODE - Left
                $sheet->getStyle('E5:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // GROSS WT - Right
                $sheet->getStyle('F5:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // NET WT - Right
                $sheet->getStyle('G5:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // MBILL NO - Left
                $sheet->getStyle('H5:H' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            },
        ];
    }
}
