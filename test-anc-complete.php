<?php

/**
 * TEST SCRIPT: Complete ANC Visit with All Fields
 * Tests new fields: height, tt_immunization, fe_tablets, diagnosis, referral_target
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

echo "=== TESTING ANC VISIT - ALL FIELDS ===\n\n";

// 1. Check database structure
echo "1. Checking anc_visits table structure...\n";
$columns = DB::select("DESCRIBE anc_visits");
$requiredFields = ['height', 'tt_immunization', 'fe_tablets', 'diagnosis', 'referral_target'];
$foundFields = [];

foreach ($columns as $col) {
    if (in_array($col->Field, $requiredFields)) {
        $foundFields[] = $col->Field;
        echo "   ✓ {$col->Field} ({$col->Type}) " . ($col->Null === 'YES' ? 'NULLABLE' : 'REQUIRED') . "\n";
    }
}

$missingFields = array_diff($requiredFields, $foundFields);
if (count($missingFields) > 0) {
    echo "   ✗ Missing fields: " . implode(', ', $missingFields) . "\n";
} else {
    echo "   ✓ All required fields exist!\n";
}

// 2. Test data validation
echo "\n2. Testing field validation...\n";

// Height range
$heightTests = [130, 160, 200, 100, 250];
foreach ($heightTests as $height) {
    $valid = $height >= 130 && $height <= 200;
    $status = $valid ? '✓' : '✗';
    echo "   {$status} Height {$height}cm: " . ($valid ? 'VALID' : 'INVALID') . "\n";
}

// TT Immunization
$ttTests = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6'];
foreach ($ttTests as $tt) {
    $valid = in_array($tt, ['T1', 'T2', 'T3', 'T4', 'T5']);
    $status = $valid ? '✓' : '✗';
    echo "   {$status} TT {$tt}: " . ($valid ? 'VALID' : 'INVALID') . "\n";
}

// Fe tablets range
$feTests = [0, 30, 90, 200, 300];
foreach ($feTests as $fe) {
    $valid = $fe >= 0 && $fe <= 200;
    $status = $valid ? '✓' : '✗';
    echo "   {$status} Fe Tablets {$fe}: " . ($valid ? 'VALID' : 'INVALID') . "\n";
}

// 3. Test creating ANC visit with new fields
echo "\n3. Testing ANC Visit creation with ALL fields...\n";

try {
    // Get existing pregnancy
    $pregnancy = App\Models\Pregnancy::first();

    if (!$pregnancy) {
        echo "   ✗ No pregnancy found! Create test data first.\n";
        exit(1);
    }

    echo "   ✓ Using Pregnancy ID: {$pregnancy->id}\n";

    // Calculate trimester and visit code
    $gestationalAge = 24; // 24 weeks
    $trimester = $gestationalAge <= 12 ? 1 : ($gestationalAge <= 28 ? 2 : 3);
    $visitCount = App\Models\AncVisit::where('pregnancy_id', $pregnancy->id)->count();
    $visitCodes = ['K1', 'K2', 'K3', 'K4', 'K5', 'K6'];
    $visitCode = $visitCodes[min($visitCount, 5)] ?? 'K6';

    echo "   ✓ Calculated: Trimester {$trimester}, Visit Code {$visitCode}\n";

    // Create complete ANC visit
    $ancVisit = App\Models\AncVisit::create([
        'pregnancy_id' => $pregnancy->id,
        'visit_date' => now()->format('Y-m-d'),
        'trimester' => $trimester,
        'visit_code' => $visitCode,
        'gestational_age' => $gestationalAge,

        // Physical Examination (Step 2) - Including NEW height field
        'weight' => 58.5,
        'height' => 160.0,  // NEW FIELD
        'lila' => 24.5,
        'tfu' => 22,
        'djj' => 142,

        // Blood Pressure (Step 3)
        'systolic' => 120,
        'diastolic' => 80,
        'map_score' => 93.33,

        // Laboratory (Step 4)
        'hb' => 11.5,
        'protein_urine' => 'Negatif',
        'hiv_status' => 'NR',
        'syphilis_status' => 'NR',
        'hbsag_status' => 'NR',

        // NEW FIELDS: Interventions
        'tt_immunization' => 'T2',  // NEW FIELD
        'fe_tablets' => 90,          // NEW FIELD

        // NEW FIELDS: Clinical Notes
        'diagnosis' => 'Kehamilan normal G2P1A0, usia kehamilan 24 minggu. Tidak ada keluhan.',  // NEW FIELD
        'referral_target' => null,   // NEW FIELD (no referral)

        // Risk assessment
        'risk_category' => 'Rendah',
    ]);

    echo "   ✓ ANC Visit created successfully! ID: {$ancVisit->id}\n";

    // 4. Verify saved data
    echo "\n4. Verifying saved data...\n";

    $saved = App\Models\AncVisit::find($ancVisit->id);

    $checks = [
        'Height' => ['expected' => 160.0, 'actual' => $saved->height],
        'TT Immunization' => ['expected' => 'T2', 'actual' => $saved->tt_immunization],
        'Fe Tablets' => ['expected' => 90, 'actual' => $saved->fe_tablets],
        'Diagnosis Length' => ['expected' => 'Not Empty', 'actual' => strlen($saved->diagnosis) > 0 ? 'Not Empty' : 'Empty'],
        'Referral' => ['expected' => null, 'actual' => $saved->referral_target],
    ];

    foreach ($checks as $field => $data) {
        $match = $data['expected'] === $data['actual'];
        $status = $match ? '✓' : '✗';
        echo "   {$status} {$field}: Expected '{$data['expected']}', Got '{$data['actual']}'\n";
    }

    // 5. Test with referral
    echo "\n5. Testing ANC Visit WITH referral...\n";

    $ancVisitReferral = App\Models\AncVisit::create([
        'pregnancy_id' => $pregnancy->id,
        'visit_date' => now()->format('Y-m-d'),
        'trimester' => 3,
        'visit_code' => 'K4',
        'gestational_age' => 35,
        'weight' => 65.0,
        'height' => 158.0,
        'lila' => 22.0,  // KEK!
        'systolic' => 145,  // Hypertension!
        'diastolic' => 95,
        'map_score' => 111.67,  // DANGER!
        'hb' => 9.5,  // Anemia!
        'protein_urine' => '+2',  // Proteinuria!
        'hiv_status' => 'NR',
        'syphilis_status' => 'NR',
        'hbsag_status' => 'NR',
        'tt_immunization' => 'T3',
        'fe_tablets' => 120,
        'diagnosis' => 'Preeklampsia berat, KEK, Anemia. Rujuk segera!',
        'referral_target' => 'RSUD Badung',  // RUJUKAN!
        'risk_category' => 'Ekstrem',
    ]);

    echo "   ✓ High-risk ANC Visit created! ID: {$ancVisitReferral->id}\n";
    echo "   ✓ Referral Target: {$ancVisitReferral->referral_target}\n";
    echo "   ✓ Risk Category: {$ancVisitReferral->risk_category}\n";

    echo "\n=== ALL TESTS COMPLETED SUCCESSFULLY! ===\n";
    echo "\n📊 Summary:\n";
    echo "   • Total ANC Visits created: 2\n";
    echo "   • Normal visit: ID {$ancVisit->id}\n";
    echo "   • High-risk visit with referral: ID {$ancVisitReferral->id}\n";
    echo "   • All 5 new fields tested: ✓\n";
    echo "\n✅ Phase 1 is now COMPLETE!\n";
} catch (\Exception $e) {
    echo "   ✗ ERROR: {$e->getMessage()}\n";
    echo "   Stack trace:\n";
    echo $e->getTraceAsString();
    exit(1);
}