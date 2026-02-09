<?php

namespace App\Exports;

use App\Models\Child;
use App\Models\ChildGrowthRecord;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class IndividualGrowthExport implements FromView, WithTitle, ShouldAutoSize, WithStyles
{
    protected $childId;

    public function __construct($childId)
    {
        $this->childId = $childId;
    }

    public function view(): View
    {
        $child = Child::findOrFail($this->childId);
        $records = ChildGrowthRecord::where('child_id', $this->childId)
            ->orderBy('record_date', 'asc')
            ->get();

        return view('exports.individual_growth', [
            'child' => $child,
            'records' => $records,
        ]);
    }

    public function title(): string
    {
        return 'Riwayat Pertumbuhan';
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
        $sheet->getStyle('A1:' . $highestColumn . '5')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        return [];
    }
}
