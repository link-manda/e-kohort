<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Testing Immunization Module Features ===\n\n";

// Test 1: RM Generator
echo "1. RM Number Generator:\n";
echo "   Next RM: " . App\Models\Child::getNextRmNumber() . "\n\n";

// Test 2: Age Calculator
echo "2. Age Calculator:\n";
$testChild = new App\Models\Child();
$testChild->dob = \Carbon\Carbon::parse('2025-09-01');
echo "   Child born on 2025-09-01\n";
echo "   Detailed age: " . $testChild->getDetailedAge() . "\n";
echo "   Age in months: " . $testChild->age_in_months . " bulan\n\n";

// Test 3: ICD-10 Config
echo "3. ICD-10 Immunization Codes:\n";
$icdCodes = config('icd10_immunization');
echo "   Total codes: " . count($icdCodes) . "\n";
foreach ($icdCodes as $code => $data) {
    echo "   - {$data['code']}: {$data['name']}\n";
}
echo "\n";

// Test 4: Vaccines Config (if exists)
echo "4. Vaccine Types:\n";
$vaccines = config('vaccines');
if ($vaccines) {
    echo "   Total vaccines: " . count($vaccines) . "\n";
    $vaccineList = array_column($vaccines, 'name');
    echo "   " . implode(', ', $vaccineList) . "\n";
} else {
    echo "   Config not found\n";
}

echo "\n=== All Tests Completed Successfully! ===\n";
