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

        if (count($this->semesterData['data']) > 0) {
            $sheets[] = new SemesterSheet($this->semesterData);
        }

        if (count($this->signifikanData['data']) > 0) {
            $sheets[] = new SignifikanSheet($this->signifikanData);
        }

        return $sheets;
    }
}

class SemesterSheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    protected array $data;
    protected array $headers;

    public function __construct(array $semesterData)
    {
        $this->data = $semesterData['data'];
        $this->headers = $semesterData['headers'];
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function title(): string
    {
        return 'Laporan Semester';
    }

    public function styles($sheet)
    {
        // Style header row
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFont()->setSize(11);
        $sheet->getStyle('1')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('1')->getFill()->getStartColor()->setRGB('dc2626');
        $sheet->getStyle('1')->getFont()->getColor()->setRGB('ffffff');
        $sheet->getStyle('1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Style all rows with wrap text and vertical alignment
        for ($row = 1; $row <= count($this->data) + 1; $row++) {
            $sheet->getStyle($row)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
            $sheet->getStyle($row)->getAlignment()->setWrapText(true);
        }

        return $sheet;
    }
}

class SignifikanSheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    protected array $data;
    protected array $headers;

    public function __construct(array $signifikanData)
    {
        $this->data = $signifikanData['data'];
        $this->headers = $signifikanData['headers'];
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function title(): string
    {
        return 'Laporan Signifikan';
    }

    public function styles($sheet)
    {
        // Style header row
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFont()->setSize(11);
        $sheet->getStyle('1')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('1')->getFill()->getStartColor()->setRGB('dc2626');
        $sheet->getStyle('1')->getFont()->getColor()->setRGB('ffffff');
        $sheet->getStyle('1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Style all rows with wrap text and vertical alignment
        for ($row = 1; $row <= count($this->data) + 1; $row++) {
            $sheet->getStyle($row)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
            $sheet->getStyle($row)->getAlignment()->setWrapText(true);
        }

        return $sheet;
    }
}

