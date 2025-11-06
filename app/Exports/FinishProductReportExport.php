<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class FinishProductReportExport implements FromView, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.finish_product_excel', $this->data);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(8);    // S. NO
                $sheet->getColumnDimension('B')->setWidth(12);   // JOB NO
                $sheet->getColumnDimension('C')->setWidth(15);   // ITEM CODE
                $sheet->getColumnDimension('D')->setWidth(15);   // VOUCHER NO
                $sheet->getColumnDimension('E')->setWidth(12);   // DATE
                $sheet->getColumnDimension('F')->setWidth(15);   // KARIGAR NAME
                $sheet->getColumnDimension('G')->setWidth(12);   // GROSS WT
                $sheet->getColumnDimension('H')->setWidth(12);   // STONE WT
                $sheet->getColumnDimension('I')->setWidth(12);   // NET WT

                // Style title row (Row 1) - Company Name
                $event->sheet->getStyle('A1:I1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->mergeCells('A1:I1');

                // Style subtitle row (Row 2)
                $event->sheet->getStyle('A2:I2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->mergeCells('A2:I2');

                // Set row height for better spacing
                $sheet->getRowDimension(1)->setRowHeight(20);
                $sheet->getRowDimension(2)->setRowHeight(18);
                $sheet->getRowDimension(4)->setRowHeight(25);

                // Style header row (Row 4) - Remove inline styles from template first
                $event->sheet->getStyle('A4:I4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => '000000'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D3D3D3'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                // Explicitly set bold for each header cell to override template styles
                for ($col = 'A'; $col <= 'I'; $col++) {
                    $event->sheet->getStyle($col . '4')->getFont()->setBold(true);
                }

                // Get last row with data
                $lastRow = $sheet->getHighestRow();

                // Apply borders to all data cells
                $event->sheet->getStyle('A5:I' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Text columns - LEFT ALIGN (A, B, C, D, E, F)
                // S. NO - Center
                $event->sheet->getStyle('A5:A' . $lastRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // JOB NO - Left
                $event->sheet->getStyle('B5:B' . $lastRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // ITEM CODE - Left
                $event->sheet->getStyle('C5:C' . $lastRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // VOUCHER NO - Left
                $event->sheet->getStyle('D5:D' . $lastRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // DATE - Center
                $event->sheet->getStyle('E5:E' . $lastRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // KARIGAR NAME - Left
                $event->sheet->getStyle('F5:F' . $lastRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // Numeric columns - RIGHT ALIGN (G, H, I - weights)
                $event->sheet->getStyle('G5:G' . $lastRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $event->sheet->getStyle('H5:H' . $lastRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $event->sheet->getStyle('I5:I' . $lastRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Add padding to numeric cells
                $event->sheet->getStyle('G5:I' . $lastRow)->getAlignment()
                    ->setIndent(1);


            },
        ];
    }
}
