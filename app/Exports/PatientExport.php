<?php

namespace App\Exports;

use App\Models\Patient;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class PatientExport implements FromView, ShouldAutoSize, WithStyles, WithTitle
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

        $period = 'SEMUA DATA';
        if (!empty($this->filters['date_from']) && !empty($this->filters['date_to'])) {
            $period = Carbon::parse($this->filters['date_from'])->format('d/m/Y') . ' - ' . Carbon::parse($this->filters['date_to'])->format('d/m/Y');
        }

        return view('exports.patient_master', [
            'patients' => $patients,
            'period' => $period,
        ]);
    }

    public function title(): string
    {
        return 'Data Pasien';
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
        // No(A), NoRM(B), NIK(C), NoKK(D), NoBPJS(E), TglLahir(H), Umur(I), GolDar(L), SuamiNIK(P), SuamiGolda(S), TglReg(U)
        $centerColumns = ['A', 'B', 'C', 'D', 'E', 'H', 'I', 'L', 'P', 'S', 'U'];
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
