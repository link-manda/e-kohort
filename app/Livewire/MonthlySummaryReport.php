<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Patient;
use App\Models\AncVisit;
use App\Models\Pregnancy;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class MonthlySummaryReport extends Component
{
    public $month;
    public $year;
    public $data = [];

    public function mount()
    {
        $this->month = now()->month;
        $this->year = now()->year;
        $this->generateReport();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['month', 'year'])) {
            $this->generateReport();
        }
    }

    public function generateReport()
    {
        $startDate = Carbon::create($this->year, $this->month, 1)->startOfMonth();
        $endDate = Carbon::create($this->year, $this->month, 1)->endOfMonth();

        // Calculate high risk using map_score column
        $highRiskQuery = AncVisit::whereBetween('visit_date', [$startDate, $endDate])
            ->whereNotNull('map_score')
            ->select('pregnancy_id', 'map_score')
            ->get();

        $highRiskCount = $highRiskQuery->where('map_score', '>', 90)->pluck('pregnancy_id')->unique()->count();
        $extremeRiskCount = $highRiskQuery->where('map_score', '>', 100)->pluck('pregnancy_id')->unique()->count();

        $this->data = [
            // 1. Total Pasien Baru Terdaftar
            'new_patients' => Patient::whereBetween('created_at', [$startDate, $endDate])->count(),

            // 2. Total Kehamilan Baru Terdaftar
            'new_pregnancies' => Pregnancy::whereBetween('created_at', [$startDate, $endDate])->count(),

            // 3. Total Kunjungan ANC per Kode
            'visits_by_code' => [
                'K1' => AncVisit::where('visit_code', 'K1')
                    ->whereBetween('visit_date', [$startDate, $endDate])
                    ->count(),
                'K2' => AncVisit::where('visit_code', 'K2')
                    ->whereBetween('visit_date', [$startDate, $endDate])
                    ->count(),
                'K3' => AncVisit::where('visit_code', 'K3')
                    ->whereBetween('visit_date', [$startDate, $endDate])
                    ->count(),
                'K4' => AncVisit::where('visit_code', 'K4')
                    ->whereBetween('visit_date', [$startDate, $endDate])
                    ->count(),
                'K5' => AncVisit::where('visit_code', 'K5')
                    ->whereBetween('visit_date', [$startDate, $endDate])
                    ->count(),
                'K6' => AncVisit::where('visit_code', 'K6')
                    ->whereBetween('visit_date', [$startDate, $endDate])
                    ->count(),
                'K7' => AncVisit::where('visit_code', 'K7')
                    ->whereBetween('visit_date', [$startDate, $endDate])
                    ->count(),
                'K8' => AncVisit::where('visit_code', 'K8')
                    ->whereBetween('visit_date', [$startDate, $endDate])
                    ->count(),
            ],

            // 4. Total Kunjungan ANC
            'total_visits' => AncVisit::whereBetween('visit_date', [$startDate, $endDate])->count(),

            // 5. Pasien Resiko Tinggi (MAP > 90) - Calculated from BP
            'high_risk_count' => $highRiskCount,

            // 6. Pasien dengan MAP Ekstrem (MAP > 100) - Calculated from BP
            'extreme_risk_count' => $extremeRiskCount,

            // 7. Pasien KEK (LILA < 23.5)
            'kek_count' => AncVisit::whereBetween('visit_date', [$startDate, $endDate])
                ->where('lila', '<', 23.5)
                ->whereNotNull('lila')
                ->distinct('pregnancy_id')
                ->count('pregnancy_id'),

            // 8. Pasien dengan Anemia (Hb < 11)
            'anemia_count' => AncVisit::whereBetween('visit_date', [$startDate, $endDate])
                ->where('hb', '<', 11)
                ->whereNotNull('hb')
                ->distinct('pregnancy_id')
                ->count('pregnancy_id'),

            // 9. Triple Eliminasi Screening Coverage
            'triple_eliminasi' => [
                'hiv_tested' => AncVisit::whereBetween('visit_date', [$startDate, $endDate])
                    ->whereNotNull('hiv_status')
                    ->distinct('pregnancy_id')
                    ->count('pregnancy_id'),
                'hiv_reactive' => AncVisit::whereBetween('visit_date', [$startDate, $endDate])
                    ->where('hiv_status', 'Reaktif')
                    ->distinct('pregnancy_id')
                    ->count('pregnancy_id'),
                'syphilis_tested' => AncVisit::whereBetween('visit_date', [$startDate, $endDate])
                    ->whereNotNull('syphilis_status')
                    ->distinct('pregnancy_id')
                    ->count('pregnancy_id'),
                'syphilis_reactive' => AncVisit::whereBetween('visit_date', [$startDate, $endDate])
                    ->where('syphilis_status', 'Reaktif')
                    ->distinct('pregnancy_id')
                    ->count('pregnancy_id'),
                'hbsag_tested' => AncVisit::whereBetween('visit_date', [$startDate, $endDate])
                    ->whereNotNull('hbsag_status')
                    ->distinct('pregnancy_id')
                    ->count('pregnancy_id'),
                'hbsag_reactive' => AncVisit::whereBetween('visit_date', [$startDate, $endDate])
                    ->where('hbsag_status', 'Reaktif')
                    ->distinct('pregnancy_id')
                    ->count('pregnancy_id'),
            ],

            // 10. ANC 12T Compliance (yang sudah lengkap 12 pemeriksaan)
            'anc_12t_complete' => AncVisit::whereBetween('visit_date', [$startDate, $endDate])
                ->where('anc_12t', true)
                ->distinct('pregnancy_id')
                ->count('pregnancy_id'),

            // 13. Imunisasi TT
            'tt_immunization' => [
                'tt1' => AncVisit::whereBetween('visit_date', [$startDate, $endDate])
                    ->where('tt_immunization', 'TT1')
                    ->count(),
                'tt2' => AncVisit::whereBetween('visit_date', [$startDate, $endDate])
                    ->where('tt_immunization', 'TT2')
                    ->count(),
                'tt3' => AncVisit::whereBetween('visit_date', [$startDate, $endDate])
                    ->where('tt_immunization', 'TT3')
                    ->count(),
                'tt4' => AncVisit::whereBetween('visit_date', [$startDate, $endDate])
                    ->where('tt_immunization', 'TT4')
                    ->count(),
                'tt5' => AncVisit::whereBetween('visit_date', [$startDate, $endDate])
                    ->where('tt_immunization', 'TT5')
                    ->count(),
            ],

            // 14. USG Screening
            'usg_count' => AncVisit::whereBetween('visit_date', [$startDate, $endDate])
                ->where('usg_check', true)
                ->count(),

            // 15. Konseling
            'counseling_count' => AncVisit::whereBetween('visit_date', [$startDate, $endDate])
                ->where('counseling_check', true)
                ->count(),

            // 16. Rujukan
            'referrals' => AncVisit::whereBetween('visit_date', [$startDate, $endDate])
                ->whereNotNull('referral_target')
                ->whereNotIn('referral_target', ['-', ''])
                ->count(),

            // Metadata
            'period' => [
                'month' => $this->month,
                'year' => $this->year,
                'month_name' => Carbon::create($this->year, $this->month, 1)->locale('id')->monthName,
                'start_date' => $startDate->format('d M Y'),
                'end_date' => $endDate->format('d M Y'),
            ],
        ];
    }

    public function exportPdf()
    {
        $data = $this->data;
        $pdf = Pdf::loadView('reports.monthly-summary', compact('data'));

        $filename = 'Laporan_Bulanan_' . $this->year . '_' . str_pad($this->month, 2, '0', STR_PAD_LEFT) . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $filename);
    }

    public function render()
    {
        return view('livewire.monthly-summary-report')->layout('layouts.dashboard');
    }
}
