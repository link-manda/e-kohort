<?php

namespace App\Exports;

use App\Models\GeneralVisit;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class GeneralRegisterExport implements FromView, ShouldAutoSize, WithStyles, WithTitle
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        $query = GeneralVisit::with('patient')->orderBy('visit_date', 'asc');

        if ($this->startDate) {
            $query->whereDate('visit_date', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('visit_date', '<=', $this->endDate);
        }

        return view('exports.general-register', [
            'visits' => $query->get(),
            'period' => $this->startDate && $this->endDate
                ? "{$this->startDate} sd {$this->endDate}"  // Changed s/d to sd (no slash)
                : "Semua Data",
        ]);
    }

    public function title(): string
    {
        // PhpSpreadsheet max 31 characters
        $title = 'Poli Umum';

        if ($this->startDate && $this->endDate) {
            $start = \Carbon\Carbon::parse($this->startDate);
            $end = \Carbon\Carbon::parse($this->endDate);

            // Format: MMM (Jan, Feb, etc.)
            // We use standard letters to be safe, but keep locale if needed.
            // Strict truncation at the end will save us.

            // Same month: "Poli Feb 2026"
            if ($start->format('Y-m') === $end->format('Y-m')) {
                $title = 'Poli ' . $start->locale('id')->isoFormat('MMM YYYY');
            }
            // Same year: "Poli Jan-Feb 2026"
            elseif ($start->year === $end->year) {
                $title = sprintf(
                    'Poli %s-%s %s',
                    $start->locale('id')->isoFormat('MMM'),
                    $end->locale('id')->isoFormat('MMM'),
                    $end->format('Y')
                );
            }
            // Different years: "Poli Des 25-Jan 26"
            else {
                $title = sprintf(
                    'Poli %s %s-%s %s',
                    $start->locale('id')->isoFormat('MMM'),
                    $start->format('y'),
                    $end->locale('id')->isoFormat('MMM'),
                    $end->format('y')
                );
            }
        }

        // Final safety bracket: max 31 chars
        return mb_substr($title, 0, 31);
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        return [];
    }
}
