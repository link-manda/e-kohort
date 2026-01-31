<?php

namespace App\Exports;

use App\Models\KbVisit;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class KbDailyReportExport implements FromView, WithTitle, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $visitType;
    protected $paymentType;
    protected $kbMethodId;

    public function __construct($startDate, $endDate, $visitType = null, $paymentType = null, $kbMethodId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->visitType = $visitType;
        $this->paymentType = $paymentType;
        $this->kbMethodId = $kbMethodId;
    }

    public function view(): View
    {
        $query = KbVisit::with(['patient', 'kbMethod'])
            ->whereBetween('visit_date', [$this->startDate, $this->endDate]);

        if ($this->visitType) {
            $query->where('visit_type', $this->visitType);
        }

        if ($this->paymentType) {
            $query->where('payment_type', $this->paymentType);
        }

        if ($this->kbMethodId) {
            $query->where('kb_method_id', $this->kbMethodId);
        }

        $visits = $query->orderBy('visit_date')->get();

        return view('exports.kb-daily-report', [
            'visits' => $visits,
            'startDate' => Carbon::parse($this->startDate)->locale('id')->isoFormat('D MMMM YYYY'),
            'endDate' => Carbon::parse($this->endDate)->locale('id')->isoFormat('D MMMM YYYY'),
            'visitType' => $this->visitType,
            'paymentType' => $this->paymentType,
        ]);
    }

    public function title(): string
    {
        return 'Laporan KB';
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
        // Assuming 3 header rows like the other report (Title, Parent Headers, Child Headers)
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

        // Center align specific columns (No, Date, IDs, etc.)
        // Adjust these based on the actual columns in the blade view
        // No(A), Tanggal(B), RM(C), KK(D), NIK(F), Umur(H), Suami NIK(K),
        // Jenis(L), Metode(M), Merek(N), Bayar(O), BB(P), TD(Q), TglKembali(R)
        $centerColumns = ['A', 'B', 'C', 'D', 'F', 'H', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R'];
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