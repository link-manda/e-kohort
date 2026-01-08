<?php

namespace App\Exports;

use App\Models\Patient;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PatientExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        // Query patients with relationships
        $query = Patient::query()
            ->with(['pregnancies' => function ($q) {
                $q->latest('created_at');
            }])
            ->orderBy('created_at', 'desc');

        // Filter by date range (registration date)
        if (!empty($this->filters['date_from'])) {
            $query->where('created_at', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->where('created_at', '<=', $this->filters['date_to']);
        }

        // Filter by pregnancy status
        if (!empty($this->filters['pregnancy_status'])) {
            $query->whereHas('pregnancies', function ($q) {
                $q->where('status', $this->filters['pregnancy_status']);
            });
        }

        // Filter by has active pregnancy
        if (!empty($this->filters['has_active_pregnancy'])) {
            if ($this->filters['has_active_pregnancy'] === 'yes') {
                $query->whereHas('pregnancies', function ($q) {
                    $q->where('status', 'aktif');
                });
            } elseif ($this->filters['has_active_pregnancy'] === 'no') {
                $query->whereDoesntHave('pregnancies', function ($q) {
                    $q->where('status', 'aktif');
                });
            }
        }

        $patients = $query->get();

        return view('exports.patient_master', [
            'patients' => $patients
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Get last row
        $lastRow = $sheet->getHighestRow();

        // Header row - Blue background
        $sheet->getStyle('A1:U1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 10,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
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

        // Data rows - Borders only
        $sheet->getStyle('A2:U' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(35);

        // Freeze header row
        $sheet->freezePane('A2');

        return [];
    }
}
