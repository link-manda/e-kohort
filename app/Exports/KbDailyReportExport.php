<?php

namespace App\Exports;

use App\Models\KbVisit;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class KbDailyReportExport implements FromView, WithTitle, WithEvents
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
            'startDate' => Carbon::parse($this->startDate)->format('d/m/Y'),
            'endDate' => Carbon::parse($this->endDate)->format('d/m/Y'),
            'visitType' => $this->visitType,
            'paymentType' => $this->paymentType,
        ]);
    }

    public function title(): string
    {
        return 'Laporan Harian KB';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Style header
                $event->sheet->getStyle('A1:N1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4472C4'],
                    ],
                    'font' => ['color' => ['rgb' => 'FFFFFF']],
                ]);

                // Auto-size columns
                foreach(range('A','N') as $col) {
                    $event->sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Borders
                $lastRow = $event->sheet->getHighestRow();
                $event->sheet->getStyle('A1:N'.$lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);
            },
        ];
    }
}
