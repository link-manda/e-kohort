<?php

namespace App\Exports;

use App\Models\ChildVisit;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class MonthlyImmunizationExport implements FromView, WithTitle, ShouldAutoSize, WithStyles
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
        $visits = ChildVisit::with(['child.patient', 'immunizationActions.vaccine'])
            ->whereYear('visit_date', $this->year)
            ->whereMonth('visit_date', $this->month)
            ->orderBy('visit_date', 'asc')
            ->get();

        return view('exports.monthly_immunization', [
            'visits' => $visits,
            'month' => $this->month,
            'year' => $this->year,
            'dateFormatted' => Carbon::createFromDate($this->year, $this->month, 1)->locale('id')->isoFormat('MMMM YYYY'),
        ]);
    }

    public function title(): string
    {
        return 'Reg. Imunisasi ' . Carbon::createFromDate($this->year, $this->month, 1)->locale('id')->isoFormat('MMMM YYYY');
    }

    public function styles(Worksheet $sheet)
    {
        // Get highest row and column
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Apply borders to all cells
        $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Header rows (1-3) - Bold and centered
        // Assuming 3 header rows (Title, Parent Headers, Child Headers)
        $sheet->getStyle('A1:' . $highestColumn . '3')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        // All data cells - wrap text and vertical center
        $sheet->getStyle('A4:' . $highestColumn . $highestRow)->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        // Center align specific columns
        // No(A), Tanggal(B), NIK(D), TTL(E), Umur(F), OrtuNIK(H), BB(J), TB(K), LK(L), VitA(O), Obat(P)
        // Check actual columns in view to be sure
        $centerColumns = ['A', 'B', 'D', 'E', 'F', 'H', 'J', 'K', 'L', 'O', 'P'];
        foreach ($centerColumns as $col) {
            if ($col <= $highestColumn) {
                $sheet->getStyle($col . '4:' . $col . $highestRow)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
            }
        }

        // Set row heights
        $sheet->getRowDimension(1)->setRowHeight(30); // Title
        $sheet->getRowDimension(2)->setRowHeight(25); // Parent Headers
        $sheet->getRowDimension(3)->setRowHeight(20); // Child Headers

        return [];
    }
}
