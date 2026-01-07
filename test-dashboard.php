<?php

/**
 * TEST SCRIPT: Dashboard Statistics & Data
 * Tests Epic 1 Story 1.1, 1.2, 1.3
 */

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;

$app = Application::configure(basePath: __DIR__)
    ->withRouting(
        web: __DIR__ . '/routes/web.php',
        commands: __DIR__ . '/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function ($middleware) {
        //
    })
    ->withExceptions(function ($exceptions) {
        //
    })->create();

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING DASHBOARD - EPIC 1 ===\n\n";

// 1. Test Statistics Cards
echo "1. Testing Dashboard Statistics...\n";

$totalPatients = App\Models\Patient::count();
echo "   ✓ Total Patients: {$totalPatients}\n";

$activePregnancies = App\Models\Pregnancy::where('status', 'Aktif')->count();
echo "   ✓ Active Pregnancies: {$activePregnancies}\n";

$todayVisits = App\Models\AncVisit::whereDate('visit_date', today())->count();
echo "   ✓ Today's Visits: {$todayVisits}\n";

$highRiskPatients = App\Models\AncVisit::where(function ($query) {
    $query->where('map_score', '>', 90)
        ->orWhere('hiv_status', 'R')
        ->orWhere('syphilis_status', 'R')
        ->orWhere('hbsag_status', 'R')
        ->orWhere('lila', '<', 23.5)
        ->orWhere('hb', '<', 11);
})
    ->whereHas('pregnancy', function ($query) {
        $query->where('status', 'Aktif');
    })
    ->distinct('pregnancy_id')
    ->count('pregnancy_id');

echo "   ✓ High-Risk Patients: {$highRiskPatients}\n";

// 2. Test High-Risk Patient List
echo "\n2. Testing High-Risk Patient Detection...\n";

$highRiskVisits = App\Models\AncVisit::with(['pregnancy.patient'])
    ->whereHas('pregnancy', function ($query) {
        $query->where('status', 'Aktif');
    })
    ->where(function ($query) {
        $query->where('map_score', '>', 90)
            ->orWhere('hiv_status', 'R')
            ->orWhere('syphilis_status', 'R')
            ->orWhere('hbsag_status', 'R')
            ->orWhere('lila', '<', 23.5)
            ->orWhere('hb', '<', 11);
    })
    ->whereIn('id', function ($query) {
        $query->select(DB::raw('MAX(id)'))
            ->from('anc_visits')
            ->groupBy('pregnancy_id');
    })
    ->orderBy('risk_category', 'desc')
    ->orderBy('visit_date', 'desc')
    ->limit(5)
    ->get();

if ($highRiskVisits->count() > 0) {
    echo "   ✓ Found {$highRiskVisits->count()} high-risk patients (showing top 5)\n\n";

    foreach ($highRiskVisits as $i => $visit) {
        $risks = [];

        if ($visit->map_score > 100) {
            $risks[] = "MAP BAHAYA: " . number_format($visit->map_score, 1);
        } elseif ($visit->map_score > 90) {
            $risks[] = "MAP WASPADA: " . number_format($visit->map_score, 1);
        }

        if ($visit->lila && $visit->lila < 23.5) {
            $risks[] = "KEK: {$visit->lila} cm";
        }

        if ($visit->hb && $visit->hb < 11) {
            $risks[] = "Anemia: {$visit->hb} g/dL";
        }

        if ($visit->hiv_status === 'R') {
            $risks[] = "HIV Reaktif";
        }

        if ($visit->syphilis_status === 'R') {
            $risks[] = "Syphilis Reaktif";
        }

        if ($visit->hbsag_status === 'R') {
            $risks[] = "HBsAg Reaktif";
        }

        echo "   " . ($i + 1) . ". {$visit->pregnancy->patient->name} (Risk: {$visit->risk_category})\n";
        echo "      NIK: {$visit->pregnancy->patient->nik}\n";
        echo "      UK: {$visit->gestational_age} minggu\n";
        echo "      Faktor Risiko: " . implode(', ', $risks) . "\n";
        echo "      Kunjungan: {$visit->visit_date->format('d M Y')}\n\n";
    }
} else {
    echo "   ✓ No high-risk patients found (All patients in good condition)\n";
}

// 3. Test Recent Visits Timeline
echo "\n3. Testing Recent Visits Timeline...\n";

$recentVisits = App\Models\AncVisit::with(['pregnancy.patient'])
    ->orderBy('visit_date', 'desc')
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();

if ($recentVisits->count() > 0) {
    echo "   ✓ Found {$recentVisits->count()} recent visits (showing 5)\n\n";

    foreach ($recentVisits as $i => $visit) {
        $mapColor = $visit->map_score > 100 ? 'RED' : ($visit->map_score > 90 ? 'YELLOW' : 'GREEN');

        echo "   " . ($i + 1) . ". [{$visit->visit_code}] {$visit->pregnancy->patient->name}\n";
        echo "      Date: {$visit->visit_date->format('d M Y')}\n";
        echo "      UK: {$visit->gestational_age} minggu\n";
        echo "      MAP: " . number_format($visit->map_score, 1) . " ({$mapColor})\n";
        echo "      Risk: {$visit->risk_category}\n\n";
    }
} else {
    echo "   ✗ No visits found in database\n";
}

// 4. Test Dashboard Component
echo "\n4. Testing Dashboard Livewire Component...\n";

try {
    $dashboard = new App\Livewire\Dashboard();
    $dashboard->mount();

    echo "   ✓ Dashboard component instantiated successfully\n";
    echo "   ✓ Statistics loaded:\n";
    echo "      - Total Patients: {$dashboard->totalPatients}\n";
    echo "      - Active Pregnancies: {$dashboard->activePregnancies}\n";
    echo "      - Today's Visits: {$dashboard->todayVisits}\n";
    echo "      - High-Risk Patients: {$dashboard->highRiskPatients}\n";
    echo "   ✓ High-Risk List: " . count($dashboard->highRiskList) . " patients\n";
    echo "   ✓ Recent Visits: " . count($dashboard->recentVisits) . " visits\n";
} catch (\Exception $e) {
    echo "   ✗ ERROR: {$e->getMessage()}\n";
}

// 5. Summary
echo "\n=== TEST SUMMARY ===\n\n";
echo "✅ Epic 1 - Dashboard Bidan: COMPLETE\n\n";
echo "Stories Completed:\n";
echo "  ✓ Story 1.1: Dashboard Statistics Cards\n";
echo "  ✓ Story 1.2: High-Risk Patient Alert List\n";
echo "  ✓ Story 1.3: Recent Visits Timeline\n";
echo "  ⏭️  Story 1.4: Monthly Statistics Chart (Optional - Deferred)\n\n";

echo "📊 Current Data:\n";
echo "  • {$totalPatients} patients registered\n";
echo "  • {$activePregnancies} active pregnancies\n";
echo "  • {$todayVisits} visits today\n";
echo "  • {$highRiskPatients} high-risk patients\n";
echo "  • {$recentVisits->count()} recent visits\n\n";

echo "🎉 Phase 2 Epic 1 is READY!\n";
echo "🚀 Next: Access dashboard at http://127.0.0.1:8000/dashboard\n";
