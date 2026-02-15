<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\Child;
use App\Models\AncVisit;
use App\Models\Pregnancy;
use App\Models\DeliveryRecord;
use App\Models\PostnatalVisit;
use App\Models\KbVisit;
use App\Models\GeneralVisit;
use App\Models\ChildVisit;
use App\Models\ChildGrowthRecord;
use App\Models\ImmunizationAction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MonthlyReportService
{
    public function getReportData(int $year, int $month): array
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        // Calculate high risk using map_score column
        $highRiskQuery = AncVisit::whereBetween('visit_date', [$startDate, $endDate])
            ->whereNotNull('map_score')
            ->select('pregnancy_id', 'map_score')
            ->get();

        $highRiskCount = $highRiskQuery->where('map_score', '>', 90)->pluck('pregnancy_id')->unique()->count();
        $extremeRiskCount = $highRiskQuery->where('map_score', '>', 100)->pluck('pregnancy_id')->unique()->count();

        return [
            // 1. Patient Demographics (Enhanced)
            'patient_demographics' => [
                'new_patients_total' => Patient::whereBetween('created_at', [$startDate, $endDate])->count(),
                'new_patients_by_category' => [
                    'umum' => Patient::whereBetween('created_at', [$startDate, $endDate])->where('category', 'Umum')->count(),
                    'bumil' => Patient::whereBetween('created_at', [$startDate, $endDate])->where('category', 'Bumil')->count(),
                    'lansia' => Patient::whereBetween('created_at', [$startDate, $endDate])->where('category', 'Lansia')->count(),
                ],
                'new_children' => Child::whereBetween('created_at', [$startDate, $endDate])->count(),
            ],

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

            // 5. Pasien Resiko Tinggi (MAP > 90)
            'high_risk_count' => $highRiskCount,

            // 6. Pasien dengan MAP Ekstrem (MAP > 100)
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

            // 10. ANC 12T Compliance
            'anc_12t_complete' => AncVisit::whereBetween('visit_date', [$startDate, $endDate])
                ->where('anc_12t', true)
                ->distinct('pregnancy_id')
                ->count('pregnancy_id'),

            // 11. Imunisasi TT
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

            // 12. USG Screening
            'usg_count' => AncVisit::whereBetween('visit_date', [$startDate, $endDate])
                ->where('usg_check', true)
                ->count(),

            // 13. Konseling
            'counseling_count' => AncVisit::whereBetween('visit_date', [$startDate, $endDate])
                ->where('counseling_check', true)
                ->count(),

            // 14. Rujukan
            'referrals' => AncVisit::whereBetween('visit_date', [$startDate, $endDate])
                ->whereNotNull('referral_target')
                ->whereNotIn('referral_target', ['-', ''])
                ->count(),

            // === NEW MODULES ===

            // 15. Persalinan (Delivery Records)
            'delivery' => [
                'total_records' => DeliveryRecord::whereBetween('delivery_date_time', [$startDate, $endDate])->count(),
                'normal' => DeliveryRecord::whereBetween('delivery_date_time', [$startDate, $endDate])
                    ->where('delivery_method', 'Normal')->count(),
                'sc' => DeliveryRecord::whereBetween('delivery_date_time', [$startDate, $endDate])
                    ->where('delivery_method', 'SC')->count(),
                'with_complications' => DeliveryRecord::whereBetween('delivery_date_time', [$startDate, $endDate])
                    ->where('complications', true)->count(),
                'live_births' => DeliveryRecord::whereBetween('delivery_date_time', [$startDate, $endDate])
                    ->where('condition', 'Hidup')->count(),
                'stillbirths' => DeliveryRecord::whereBetween('delivery_date_time', [$startDate, $endDate])
                    ->where('condition', 'Mati')->count(),
            ],

            // 16. Postnatal Care (Kunjungan Nifas)
            'postnatal' => [
                'total_visits' => PostnatalVisit::whereBetween('visit_date', [$startDate, $endDate])->count(),
                'kf1' => PostnatalVisit::whereBetween('visit_date', [$startDate, $endDate])
                    ->where('visit_code', 'KF1')->count(),
                'kf2' => PostnatalVisit::whereBetween('visit_date', [$startDate, $endDate])
                    ->where('visit_code', 'KF2')->count(),
                'kf3' => PostnatalVisit::whereBetween('visit_date', [$startDate, $endDate])
                    ->where('visit_code', 'KF3')->count(),
                'with_complications' => PostnatalVisit::whereBetween('visit_date', [$startDate, $endDate])
                    ->where('complication_check', true)->count(),
            ],

            // 17. KB (Family Planning)
            'kb' => [
                'total_visits' => KbVisit::whereBetween('visit_date', [$startDate, $endDate])->count(),
                'new_acceptors' => KbVisit::whereBetween('visit_date', [$startDate, $endDate])
                    ->where('visit_type', 'Baru')->count(),
                'by_method' => KbVisit::whereBetween('visit_date', [$startDate, $endDate])
                    ->join('kb_methods', 'kb_visits.kb_method_id', '=', 'kb_methods.id')
                    ->select('kb_methods.name', DB::raw('count(*) as count'))
                    ->groupBy('kb_methods.name')
                    ->pluck('count', 'name')
                    ->toArray(),
            ],

            // 18. Child Immunization
            'immunization' => [
                'total_visits' => ChildVisit::whereBetween('visit_date', [$startDate, $endDate])->count(),
                'total_actions' => ImmunizationAction::whereHas('childVisit', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('visit_date', [$startDate, $endDate]);
                })->count(),
                'by_vaccine' => ImmunizationAction::whereHas('childVisit', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('visit_date', [$startDate, $endDate]);
                })
                    ->select('vaccine_type', DB::raw('count(*) as count'))
                    ->groupBy('vaccine_type')
                    ->pluck('count', 'vaccine_type')
                    ->toArray(),
            ],

            // 19. Child Growth & Nutrition
            'child_growth' => [
                'total_measurements' => ChildGrowthRecord::whereBetween('record_date', [$startDate, $endDate])->count(),
                'vitamin_a' => ChildGrowthRecord::whereBetween('record_date', [$startDate, $endDate])
                    ->whereNotNull('vitamin_a')->count(),
                'nutrition_summary' => ChildGrowthRecord::whereBetween('record_date', [$startDate, $endDate])
                    ->selectRaw('
                        SUM(CASE WHEN zscore_bb_tb > -2 AND zscore_bb_tb < 2 THEN 1 ELSE 0 END) as normal,
                        SUM(CASE WHEN zscore_bb_tb <= -2 THEN 1 ELSE 0 END) as wasting,
                        SUM(CASE WHEN zscore_tb_u <= -2 THEN 1 ELSE 0 END) as stunting
                    ')
                    ->first(),
            ],

            // 20. General Visits (Poli Umum)
            'general_visits' => [
                'total' => GeneralVisit::whereBetween('visit_date', [$startDate, $endDate])->count(),
                'adult_patients' => GeneralVisit::whereBetween('visit_date', [$startDate, $endDate])
                    ->whereNotNull('patient_id')->count(),
                'child_patients' => GeneralVisit::whereBetween('visit_date', [$startDate, $endDate])
                    ->whereNotNull('child_id')->count(),
                'top_diagnoses' => GeneralVisit::whereBetween('visit_date', [$startDate, $endDate])
                    ->whereNotNull('icd10_code')
                    ->whereNotNull('diagnosis')
                    ->select('icd10_code', 'diagnosis', DB::raw('count(*) as count'))
                    ->groupBy('icd10_code', 'diagnosis')
                    ->orderByDesc('count')
                    ->limit(10)
                    ->get()
                    ->map(function($item) {
                        return [
                            'code' => $item->icd10_code,
                            'description' => $item->diagnosis,
                            'count' => $item->count,
                        ];
                    })
                    ->toArray(),
                'with_prescription' => GeneralVisit::whereBetween('visit_date', [$startDate, $endDate])
                    ->whereHas('prescriptions')->count(),
            ],

            // Metadata
            'period' => [
                'month' => $month,
                'year' => $year,
                'month_name' => Carbon::create($year, $month, 1)->locale('id')->monthName,
                'start_date' => $startDate->format('d M Y'),
                'end_date' => $endDate->format('d M Y'),
            ],
        ];
    }
}
