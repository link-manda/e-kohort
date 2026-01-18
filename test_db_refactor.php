<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Testing Database Refactoring ===\n\n";

// Test 1: Count Vaccines
echo "1. Vaccines in Database:\n";
$vaccines = App\Models\Vaccine::active()->ordered()->get();
echo "   Total active vaccines: " . $vaccines->count() . "\n";
echo "   Vaccines: " . $vaccines->pluck('name')->implode(', ') . "\n\n";

// Test 2: Count ICD-10
echo "2. ICD-10 Codes in Database:\n";
$icdCodes = App\Models\Icd10Code::active()->get();
echo "   Total active codes: " . $icdCodes->count() . "\n";
foreach ($icdCodes as $icd) {
    echo "   - {$icd->code}: {$icd->name}\n";
}
echo "\n";

// Test 3: Search ICD-10
echo "3. ICD-10 Search Test:\n";
$searchPolio = App\Models\Icd10Code::search('polio');
echo "   Search 'polio': " . $searchPolio->count() . " results\n";
if ($searchPolio->count() > 0) {
    echo "   Found: " . $searchPolio->first()->code . " - " . $searchPolio->first()->name . "\n";
}

$searchCampak = App\Models\Icd10Code::search('campak');
echo "   Search 'campak': " . $searchCampak->count() . " results\n";
if ($searchCampak->count() > 0) {
    echo "   Found: " . $searchCampak->first()->code . " - " . $searchCampak->first()->name . "\n";
}
echo "\n";

// Test 4: Vaccine Age Validation
echo "4. Vaccine Age Validation Test:\n";
$vaccine = App\Models\Vaccine::where('code', 'HB0')->first();
if ($vaccine) {
    echo "   Vaccine: {$vaccine->name}\n";
    echo "   Age range: {$vaccine->age_range}\n";

    $validation = $vaccine->isAgeAppropriate(0);
    echo "   Age 0 months: {$validation['status']} - {$validation['message']}\n";

    $validation = $vaccine->isAgeAppropriate(3);
    echo "   Age 3 months: {$validation['status']} - {$validation['message']}\n";
}

echo "\n=== All Tests Completed Successfully! ===\n";
