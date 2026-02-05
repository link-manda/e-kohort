<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Patient;
use App\Models\Pregnancy;

echo "=== TESTING EXTERNAL BIRTH COMPLETE FLOW ===" . PHP_EOL . PHP_EOL;

// Scenario 1: Patient with pregnancy status='Lahir' but delivery_date=NULL (External Birth)
echo "📋 SCENARIO 1: External Birth (delivery_date = NULL)" . PHP_EOL;
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL;

$externalPregnancy = Pregnancy::where('status', 'Lahir')
    ->whereNull('delivery_date')
    ->first();

if ($externalPregnancy) {
    echo "✅ Found pregnancy with external birth scenario" . PHP_EOL;
    echo "   Pregnancy ID: {$externalPregnancy->id}" . PHP_EOL;
    echo "   Patient: {$externalPregnancy->patient->name} (No.RM: {$externalPregnancy->patient->no_rm})" . PHP_EOL;
    echo "   Status: {$externalPregnancy->status}" . PHP_EOL;
    echo "   Delivery Date: " . ($externalPregnancy->delivery_date ? 'EXISTS' : 'NULL ← Should trigger modal') . PHP_EOL;
    echo PHP_EOL;
    echo "🔗 Front Desk URL: http://localhost/e-kohort_klinik/public/registration-desk" . PHP_EOL;
    echo "   → Search patient: {$externalPregnancy->patient->name}" . PHP_EOL;
    echo "   → Click 'Poli Nifas' button" . PHP_EOL;
    echo "   → Should redirect to: /pregnancies/{$externalPregnancy->id}/postnatal" . PHP_EOL;
    echo "   → Modal should appear: 'Konfirmasi Riwayat Persalinan'" . PHP_EOL;
} else {
    echo "⚠️  No external birth pregnancy found. Creating one..." . PHP_EOL;

    $patient = Patient::first();
    if (!$patient) {
        echo "❌ No patient found. Run seeder first." . PHP_EOL;
        exit(1);
    }

    $pregnancy = Pregnancy::create([
        'patient_id' => $patient->id,
        'gravida' => 1,
        'hpht' => now()->subDays(280),
        'hpl' => now(),
        'status' => 'Lahir',
        'delivery_date' => null, // ← NULL triggers modal
        'is_external' => false,
    ]);

    echo "✅ Created test pregnancy" . PHP_EOL;
    echo "   Pregnancy ID: {$pregnancy->id}" . PHP_EOL;
    echo "   Patient: {$patient->name} (No.RM: {$patient->no_rm})" . PHP_EOL;
    echo PHP_EOL;
    echo "🔗 Direct Test URL: http://localhost/e-kohort_klinik/public/pregnancies/{$pregnancy->id}/postnatal" . PHP_EOL;
}

echo PHP_EOL;
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL;
echo PHP_EOL;

// Scenario 2: Patient with normal delivery (delivery_date exists)
echo "📋 SCENARIO 2: Normal Delivery (delivery_date exists)" . PHP_EOL;
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL;

$normalPregnancy = Pregnancy::where('status', 'Lahir')
    ->whereNotNull('delivery_date')
    ->first();

if ($normalPregnancy) {
    echo "✅ Found normal delivery pregnancy" . PHP_EOL;
    echo "   Pregnancy ID: {$normalPregnancy->id}" . PHP_EOL;
    echo "   Patient: {$normalPregnancy->patient->name}" . PHP_EOL;
    echo "   Delivery Date: {$normalPregnancy->delivery_date->format('Y-m-d')}" . PHP_EOL;
    echo PHP_EOL;
    echo "🔗 Test URL: http://localhost/e-kohort_klinik/public/pregnancies/{$normalPregnancy->id}/postnatal" . PHP_EOL;
    echo "   → Modal should NOT appear" . PHP_EOL;
    echo "   → Should show postnatal form directly" . PHP_EOL;
} else {
    echo "⚠️  No normal delivery pregnancy found (OK for testing external birth focus)" . PHP_EOL;
}

echo PHP_EOL;
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL;
echo PHP_EOL;

echo "📝 EXPECTED BEHAVIOR:" . PHP_EOL;
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL;
echo "1. ✅ Modal muncul dengan dark overlay (bg-gray-900 bg-opacity-75)" . PHP_EOL;
echo "2. ✅ Header: 'Konfirmasi Riwayat Persalinan'" . PHP_EOL;
echo "3. ✅ Warning box kuning: 'Pasien belum tercatat melahirkan...'" . PHP_EOL;
echo "4. ✅ Form fields:" . PHP_EOL;
echo "   - Tanggal & Jam Lahir (datetime-local, required)" . PHP_EOL;
echo "   - Jenis Kelamin Bayi (radio L/P, required)" . PHP_EOL;
echo "   - Berat Bayi (number 500-6000g, required)" . PHP_EOL;
echo "   - Tempat Bersalin (text, optional)" . PHP_EOL;
echo "5. ✅ Buttons: 'Batal' (gray) & 'Simpan & Lanjut Nifas' (blue)" . PHP_EOL;
echo "6. ✅ After save:" . PHP_EOL;
echo "   - Pregnancy updated with delivery data" . PHP_EOL;
echo "   - Child record auto-created (ANAK-YYYY-####)" . PHP_EOL;
echo "   - Modal closes" . PHP_EOL;
echo "   - Postnatal form appears" . PHP_EOL;
echo PHP_EOL;

echo "🚨 CRITICAL CHECKS:" . PHP_EOL;
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL;
echo "1. ❌ Modal di PatientQueueEntry TIDAK BOLEH muncul" . PHP_EOL;
echo "2. ❌ Method proceedToNifas() TIDAK BOLEH dipanggil" . PHP_EOL;
echo "3. ❌ Tidak boleh ada data dummy/estimated di database" . PHP_EOL;
echo "4. ✅ Hanya modal di PostnatalEntry yang muncul" . PHP_EOL;
echo "5. ✅ Data yang disimpan harus data REAL dari user input" . PHP_EOL;
