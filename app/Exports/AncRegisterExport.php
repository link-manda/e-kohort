<?php

namespace App\Exports;

use App\Models\AncVisit;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class AncRegisterExport implements FromView, WithTitle, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = AncVisit::query()
            ->with(['pregnancy.patient'])
            ->whereHas('pregnancy.patient');

        if (!empty($this->filters['date_from'])) {
            $query->where('visit_date', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->where('visit_date', '<=', $this->filters['date_to']);
        }

        $visits = $query->orderBy('visit_date', 'asc')->get();

        $period = 'SEMUA';
        if (!empty($this->filters['date_from']) && !empty($this->filters['date_to'])) {
            $period = Carbon::parse($this->filters['date_from'])->format('d/m/Y') . ' - ' . Carbon::parse($this->filters['date_to'])->format('d/m/Y');
        }

        return view('exports.anc_register', [
            'visits' => $visits,
            'period' => $period,
        ]);
    }

    public function title(): string
    {
        return 'Reg. ANC';
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
        // This list covers standard "short" data columns (No, Date, IDs, Checks, etc.)
        // Adjust column letters based on the final view structure (approx 50 cols)
        $centerColumns = [
            'A', 'B', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
            'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE',
            'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO',
            'AP', 'AQ', 'AR', 'AS', 'AT', 'AU'
        ];

        foreach ($centerColumns as $col) {
            if ($col <= $highestColumn) { // Check if column exists
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
        $sheet->getRowDimension(3)->setRowHeight(25); // Child Headers

        return [];
    }
}