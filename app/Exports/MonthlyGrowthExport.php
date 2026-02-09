<?php

namespace App\Exports;

use App\Models\ChildGrowthRecord;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class MonthlyGrowthExport implements FromView, WithTitle, ShouldAutoSize, WithStyles
{
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function view(): View
    {
        $records = ChildGrowthRecord::with(['child.patient'])
            ->whereYear('record_date', $this->year)
            ->whereMonth('record_date', $this->month)
            ->orderBy('record_date', 'asc')
            ->get();

        return view('exports.monthly_growth', [
            'records' => $records,
            'month' => $this->month,
            'year' => $this->year,
            'dateFormatted' => Carbon::createFromDate($this->year, $this->month, 1)->locale('id')->isoFormat('MMMM YYYY'),
        ]);
    }

    public function title(): string
    {
        return 'Laporan Gizi ' . Carbon::createFromDate($this->year, $this->month, 1)->locale('id')->isoFormat('MMMM YYYY');
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Borders
        $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Header Styling
        $sheet->getStyle('A1:' . $highestColumn . '3')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        // Data Alignment
        $sheet->getStyle('A4:' . $highestColumn . $highestRow)->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        // Center specific columns (No, Tgl, Umur, JK, BB, TB, Z-Scores)
        // Adjust these columns based on the actual View later
        $centerColumns = ['A', 'B', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];
        foreach ($centerColumns as $col) {
             if ($col <= $highestColumn) {
                $sheet->getStyle($col . '4:' . $col . $highestRow)->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
             }
        }

        return [];
    }
}
