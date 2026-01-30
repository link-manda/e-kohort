<?php

namespace App\Exports;

use App\Models\DeliveryRecord;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class DeliveryRegisterExport implements FromView, ShouldAutoSize, WithStyles, WithTitle
{
    protected $month;
    protected $year;

    public function __construct($month = null, $year = null)
    {
        $this->month = $month ?? now()->month;
        $this->year = $year ?? now()->year;
    }

    public function title(): string
    {
        return 'Reg. Persalinan ' . \Carbon\Carbon::createFromDate($this->year, $this->month, 1)->locale('id')->isoFormat('MMMM YYYY');
    }

    public function view(): View
    {
        $deliveries = DeliveryRecord::with([
            'pregnancy.patient'
        ])
            ->whereYear('delivery_date_time', $this->year)
            ->whereMonth('delivery_date_time', $this->month)
            ->orderBy('delivery_date_time', 'asc')
            ->get();

        return view('exports.delivery_register', [
            'deliveries' => $deliveries,
            'month' => $this->month,
            'year' => $this->year
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Get highest row
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

        // Center align for specific columns (NO, NIK, Umur, etc.)
        $centerColumns = ['A', 'B', 'D', 'F', 'H', 'J', 'L', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V'];
        foreach ($centerColumns as $col) {
            $sheet->getStyle($col . '4:' . $col . $highestRow)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);
        }

        // Set row heights
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(25);
        $sheet->getRowDimension(3)->setRowHeight(20);

        return [];
    }
}