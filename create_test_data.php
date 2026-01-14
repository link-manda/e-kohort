<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Patient;
use App\Models\Pregnancy;
use Carbon\Carbon;

try {
    // Create test patient
    $patient = Patient::create([
        'name' => 'Test Patient - Persalinan',
        'nik' => '9999999999999999', // Unique test NIK
        'phone' => '081234567890',
        'address' => 'Jl. Test No. 123',
        'dob' => '1990-01-01',
        'pob' => 'Test City',
        'education' => 'SMA',
        'occupation' => 'IRT',
        'religion' => 'Islam',
        'marital_status' => 'Kawin',
        'blood_type' => 'O',
        'rt' => '001',
        'rw' => '001',
        'village' => 'Test Village',
        'district' => 'Test District',
        'city' => 'Test City',
        'province' => 'Test Province',
    ]);

    // Create pregnancy for testing
    $pregnancy = Pregnancy::create([
        'patient_id' => $patient->id,
        'gravida' => 'G1P0A0',
        'hpht' => Carbon::now()->subWeeks(20), // 20 weeks pregnant
        'hpl' => Carbon::now()->addWeeks(20),
        'pregnancy_gap' => 2,
        'weight_before' => 55.0,
        'height' => 160,
        'status' => 'Aktif', // Still pregnant for delivery testing
        'risk_score_initial' => 0,
    ]);

    echo "✅ Test data created successfully!\n";
    echo "   Patient ID: {$patient->id} - {$patient->name}\n";
    echo "   Pregnancy ID: {$pregnancy->id} - Status: {$pregnancy->status}\n";
    echo "\n📋 Test URLs:\n";
    echo "   Patient Detail: http://127.0.0.1:8000/patients/{$patient->id}\n";
    echo "   Delivery Form: http://127.0.0.1:8000/pregnancies/{$pregnancy->id}/delivery\n";

} catch (Exception $e) {
    echo "❌ Error creating test data: " . $e->getMessage() . "\n";
}