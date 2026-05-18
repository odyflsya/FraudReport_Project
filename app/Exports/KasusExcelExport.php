<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class KasusExcelExport implements WithMultipleSheets
{
    protected array $semesterData;
    protected array $signifikanData;

    public function __construct(array $semesterData, array $signifikanData)
    {
        $this->semesterData = $semesterData;
        $this->signifikanData = $signifikanData;
    }

    public function sheets(): array
    {
        $sheets = [];

        if (!empty($this->semesterData['data'])) {
            $sheets[] = new SemesterSheet($this->semesterData);
        }

        if (!empty($this->signifikanData['data'])) {
            $sheets[] = new SignifikanSheet($this->signifikanData);
        }

        return $sheets;
    }
}

/* =========================================================
| SHEET 01A
========================================================= */

class SemesterSheet implements FromArray, WithEvents, WithTitle
{
    protected array $data;
    protected array $headers;

    public function __construct(array $semesterData)
    {
        $this->data = $semesterData['data'];
        $this->headers = $semesterData['headers'];
    }

    public function title(): string
    {
        return '01A';
    }

    public function array(): array
    {
        return $this->data;
    }

    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();
                $sheet->getDefaultStyle()
                ->getAlignment()
                ->setWrapText(true);

                /*
                |--------------------------------------------------------------------------
                | HEADER MANUAL
                |--------------------------------------------------------------------------
                */

                $column = 'A';

                foreach ($this->headers as $header) {

                    $sheet->mergeCells($column . '2:' . $column . '4');

                    $sheet->setCellValue($column . '2', $header);

                    $column++;
                }

                /*
                |--------------------------------------------------------------------------
                | TOTAL ROW
                |--------------------------------------------------------------------------
                */

                $lastColumn = $sheet->getHighestColumn();
                $lastRow = count($this->data) + 4;

                /*
                |--------------------------------------------------------------------------
                | HEADER STYLE
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle('A2:' . $lastColumn . '4')->applyFromArray([

                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'name' => 'Calibri',
                        'color' => [
                            'rgb' => 'FFFFFF'
                        ]
                    ],

                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],

                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'FF0000'
                        ]
                    ],

                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => [
                                'rgb' => '000000'
                            ]
                        ]
                    ]
                ]);

                /*
                |--------------------------------------------------------------------------
                | DATA STYLE
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle('A5:' . $lastColumn . $lastRow)
                    ->applyFromArray([

                        'font' => [
                            'name' => 'Calibri',
                            'size' => 11,
                        ],

                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_TOP,
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'wrapText' => true,
                        ],

                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => [
                                    'rgb' => '000000'
                                ]
                            ]
                        ]
                    ]);

                /*
                |--------------------------------------------------------------------------
                | AUTO HEIGHT ROW
                |--------------------------------------------------------------------------
                */

                for ($i = 5; $i <= $lastRow; $i++) {

                    $sheet->getRowDimension($i)->setRowHeight(-1);
                }

                /*
                |--------------------------------------------------------------------------
                | HEADER HEIGHT
                |--------------------------------------------------------------------------
                */

                $sheet->getRowDimension(2)->setRowHeight(28);
                $sheet->getRowDimension(3)->setRowHeight(28);
                $sheet->getRowDimension(4)->setRowHeight(45);

                /*
                |--------------------------------------------------------------------------
                | AUTO WIDTH SEMUA KOLOM
                |--------------------------------------------------------------------------
                */

foreach (range('A', $lastColumn) as $columnID) {

    $sheet->getColumnDimension($columnID)
        ->setWidth(25);
}

                /*
                |--------------------------------------------------------------------------
                | KHUSUS KOLOM DESKRIPSI BIAR LEBAR
                |--------------------------------------------------------------------------
                */

                $sheet->getColumnDimension('H')->setWidth(80);
                $sheet->getColumnDimension('AK')->setWidth(40); // Alamat
                $sheet->getColumnDimension('AL')->setWidth(40);

                /*
                |--------------------------------------------------------------------------
                | FREEZE HEADER
                |--------------------------------------------------------------------------
                */

                $sheet->freezePane('A5');
            },
        ];
    }
}

/* =========================================================
| SHEET 01B
========================================================= */

class SignifikanSheet extends SemesterSheet
{
    public function __construct(array $signifikanData)
    {
        parent::__construct($signifikanData);
    }

    public function title(): string
    {
        return '01B';
    }
}