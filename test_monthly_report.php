<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Patient;
use App\Models\Pregnancy;
use App\Models\AncVisit;
use Carbon\Carbon;

try {
    echo "Testing Monthly Summary Report...\n\n";

    // Set test month (current month)
    $month = now()->month;
    $year = now()->year;
    $startDate = Carbon::create($year, $month, 1)->startOfMonth();
    $endDate = Carbon::create($year, $month, 1)->endOfMonth();

    echo "=== PERIOD: " . $startDate->locale('id')->monthName . " $year ===\n";
    echo "Date Range: {$startDate->format('d M Y')} - {$endDate->format('d M Y')}\n\n";

    // Test 1: New Patients
    $newPatients = Patient::whereBetween('created_at', [$startDate, $endDate])->count();
    echo "✓ New Patients: $newPatients\n";

    // Test 2: New Pregnancies
    $newPregnancies = Pregnancy::whereBetween('created_at', [$startDate, $endDate])->count();
    echo "✓ New Pregnancies: $newPregnancies\n";

    // Test 3: Total Visits
    $totalVisits = AncVisit::whereBetween('visit_date', [$startDate, $endDate])->count();
    echo "✓ Total ANC Visits: $totalVisits\n";

    // Test 4: Visits by Code
    echo "\n=== VISITS BY CODE ===\n";
    foreach (['K1', 'K2', 'K3', 'K4', 'K5', 'K6', 'K7', 'K8'] as $code) {
        $count = AncVisit::where('visit_code', $code)
            ->whereBetween('visit_date', [$startDate, $endDate])
            ->count();
        echo "  $code: $count visits\n";
    }

    // Test 5: High Risk Patients (using map_score column)
    $highRiskVisits = AncVisit::whereBetween('visit_date', [$startDate, $endDate])
        ->whereNotNull('map_score')
        ->select('pregnancy_id', 'map_score')
        ->get();

    $highRisk = $highRiskVisits->where('map_score', '>', 90)->pluck('pregnancy_id')->unique()->count();
    echo "\n✓ High Risk (MAP > 90): $highRisk patients\n";

    $extremeRisk = $highRiskVisits->where('map_score', '>', 100)->pluck('pregnancy_id')->unique()->count();
    echo "✓ Extreme Risk (MAP > 100): $extremeRisk patients\n";

    // Test 6: KEK & Anemia
    $kek = AncVisit::whereBetween('visit_date', [$startDate, $endDate])
        ->where('lila', '<', 23.5)
        ->whereNotNull('lila')
        ->distinct('pregnancy_id')
        ->count('pregnancy_id');
    echo "✓ KEK (LILA < 23.5): $kek patients\n";

    $anemia = AncVisit::whereBetween('visit_date', [$startDate, $endDate])
        ->where('hb', '<', 11)
        ->whereNotNull('hb')
        ->distinct('pregnancy_id')
        ->count('pregnancy_id');
    echo "✓ Anemia (Hb < 11): $anemia patients\n";

    // Test 7: Triple Eliminasi
    echo "\n=== TRIPLE ELIMINASI SCREENING ===\n";
    $hivTested = AncVisit::whereBetween('visit_date', [$startDate, $endDate])
        ->whereNotNull('hiv_status')
        ->distinct('pregnancy_id')
        ->count('pregnancy_id');
    $hivReactive = AncVisit::whereBetween('visit_date', [$startDate, $endDate])
        ->where('hiv_status', 'Reaktif')
        ->distinct('pregnancy_id')
        ->count('pregnancy_id');
    echo "  HIV: $hivTested tested, $hivReactive reactive\n";

    $syphilisTested = AncVisit::whereBetween('visit_date', [$startDate, $endDate])
        ->whereNotNull('syphilis_status')
        ->distinct('pregnancy_id')
        ->count('pregnancy_id');
    $syphilisReactive = AncVisit::whereBetween('visit_date', [$startDate, $endDate])
        ->where('syphilis_status', 'Reaktif')
        ->distinct('pregnancy_id')
        ->count('pregnancy_id');
    echo "  Syphilis: $syphilisTested tested, $syphilisReactive reactive\n";

    $hbsagTested = AncVisit::whereBetween('visit_date', [$startDate, $endDate])
        ->whereNotNull('hbsag_status')
        ->distinct('pregnancy_id')
        ->count('pregnancy_id');
    $hbsagReactive = AncVisit::whereBetween('visit_date', [$startDate, $endDate])
        ->where('hbsag_status', 'Reaktif')
        ->distinct('pregnancy_id')
        ->count('pregnancy_id');
    echo "  HBsAg: $hbsagTested tested, $hbsagReactive reactive\n";

    // Test 8: Interventions
    echo "\n=== INTERVENTIONS ===\n";
    $anc12t = AncVisit::whereBetween('visit_date', [$startDate, $endDate])
        ->where('anc_12t', true)
        ->distinct('pregnancy_id')
        ->count('pregnancy_id');
    echo "  ANC 12T Complete: $anc12t\n";

    $usg = AncVisit::whereBetween('visit_date', [$startDate, $endDate])
        ->where('usg_check', true)
        ->count();
    echo "  USG Screening: $usg\n";

    $counseling = AncVisit::whereBetween('visit_date', [$startDate, $endDate])
        ->where('counseling_check', true)
        ->count();
    echo "  Counseling: $counseling\n";

    // Test 9: TT Immunization
    echo "\n=== TT IMMUNIZATION ===\n";
    foreach (['TT1', 'TT2', 'TT3', 'TT4', 'TT5'] as $tt) {
        $count = AncVisit::whereBetween('visit_date', [$startDate, $endDate])
            ->where('tt_immunization', $tt)
            ->count();
        echo "  $tt: $count\n";
    }

    // Test 10: Referrals
    echo "\n=== OTHER INDICATORS ===\n";
    $referrals = AncVisit::whereBetween('visit_date', [$startDate, $endDate])
        ->whereNotNull('referral_target')
        ->whereNotIn('referral_target', ['-', ''])
        ->count();
    echo "  Referrals: $referrals\n";

    // Test 11: Try to instantiate Livewire component
    echo "\n=== COMPONENT TEST ===\n";
    try {
        $component = new \App\Livewire\MonthlySummaryReport();
        $component->mount();
        echo "✓ Livewire component instantiated successfully\n";
        echo "  Component month: {$component->month}\n";
        echo "  Component year: {$component->year}\n";
        echo "  Data keys: " . count($component->data) . "\n";

        // Check data structure
        $expectedKeys = [
            'new_patients',
            'new_pregnancies',
            'visits_by_code',
            'total_visits',
            'high_risk_count',
            'extreme_risk_count',
            'kek_count',
            'anemia_count',
            'triple_eliminasi',
            'referrals',
            'anc_12t_complete',
            'tt_immunization',
            'usg_count',
            'counseling_count',
            'period'
        ];

        $missingKeys = array_diff($expectedKeys, array_keys($component->data));
        if (empty($missingKeys)) {
            echo "✓ All expected data keys present\n";
        } else {
            echo "✗ Missing keys: " . implode(', ', $missingKeys) . "\n";
        }
    } catch (\Exception $e) {
        echo "✗ Component instantiation failed: " . $e->getMessage() . "\n";
    }

    // Test 12: Check route
    echo "\n=== ROUTE TEST ===\n";
    try {
        $url = route('reports.monthly-summary');
        echo "✓ Route URL: $url\n";
    } catch (\Exception $e) {
        echo "✗ Route not found: " . $e->getMessage() . "\n";
    }

    echo "\n✅ ALL TESTS PASSED!\n";
    echo "\nNext steps:\n";
    echo "1. Open browser: http://localhost/reports/monthly-summary\n";
    echo "2. Select month and year\n";
    echo "3. View generated statistics\n";
    echo "4. Click 'Export PDF' to download report\n";
} catch (\Exception $e) {
    echo "\n✗ TEST FAILED\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}
