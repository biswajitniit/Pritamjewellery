<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class QualityCheckReportExport implements FromView, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.quality_check_excel', $this->data);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(8);    // S. NO
                $sheet->getColumnDimension('B')->setWidth(18);   // QC VOUCHER
                $sheet->getColumnDimension('C')->setWidth(12);   // DATE
                $sheet->getColumnDimension('D')->setWidth(18);   // KARIGAR NAME
                $sheet->getColumnDimension('E')->setWidth(12);   // JOB NO
                $sheet->getColumnDimension('F')->setWidth(18);   // ITEM CODE
                $sheet->getColumnDimension('G')->setWidth(14);   // DESIGN
                $sheet->getColumnDimension('H')->setWidth(12);   // SOLDER ITEMS
                $sheet->getColumnDimension('I')->setWidth(12);   // POLISH ITEMS
                $sheet->getColumnDimension('J')->setWidth(12);   // FINISH ITEMS
                $sheet->getColumnDimension('K')->setWidth(12);   // MINA ITEMS
                $sheet->getColumnDimension('L')->setWidth(12);   // OTHER ITEMS
                $sheet->getColumnDimension('M')->setWidth(12);   // REMARK

                // Row 1: Title (Company Name) - BOLD
                $sheet->getRowDimension(1)->setRowHeight(20);
                $sheet->mergeCells('A1:M1');
                $event->sheet->getStyle('A1:M1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // Row 2: Subtitle - BOLD
                $sheet->getRowDimension(2)->setRowHeight(18);
                $sheet->mergeCells('A2:M2');
                $event->sheet->getStyle('A2:M2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // Row 3: Empty
                $sheet->getRowDimension(3)->setRowHeight(12);

                // Row 4: Header - BOLD with gray background
                $sheet->getRowDimension(4)->setRowHeight(25);
                for ($col = 'A'; $col <= 'M'; $col++) {
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
                    for ($col = 'A'; $col <= 'M'; $col++) {
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

                // QC VOUCHER - Left
                $sheet->getStyle('B5:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // DATE - Center
                $sheet->getStyle('C5:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // KARIGAR NAME - Left
                $sheet->getStyle('D5:D' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // JOB NO - Left
                $sheet->getStyle('E5:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // ITEM CODE - Left
                $sheet->getStyle('F5:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // DESIGN - Left
                $sheet->getStyle('G5:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // SOLDER ITEMS - Center
                $sheet->getStyle('H5:H' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // POLISH ITEMS - Center
                $sheet->getStyle('I5:I' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // FINISH ITEMS - Center
                $sheet->getStyle('J5:J' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // MINA ITEMS - Center
                $sheet->getStyle('K5:K' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // OTHER ITEMS - Center
                $sheet->getStyle('L5:L' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // REMARK - Left
                $sheet->getStyle('M5:M' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            },
        ];
    }
}
