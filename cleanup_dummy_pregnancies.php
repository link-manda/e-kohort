<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Pregnancy;

echo "=== CLEANUP DUMMY PREGNANCY DATA ===" . PHP_EOL . PHP_EOL;

// Find all pregnancies with delivery_date but NO deliveryRecord (dummy data)
$dummyPregnancies = Pregnancy::query()
    ->where('status', 'Lahir')
    ->whereNotNull('delivery_date')
    ->whereDoesntHave('deliveryRecord')
    ->where('is_external', false)
    ->get();

if ($dummyPregnancies->isEmpty()) {
    echo "✅ No dummy pregnancies found. All clean!" . PHP_EOL;
    exit(0);
}

echo "🔍 Found {$dummyPregnancies->count()} dummy pregnancy records:" . PHP_EOL;
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL . PHP_EOL;

foreach ($dummyPregnancies as $pregnancy) {
    echo "ID: {$pregnancy->id} | Patient: {$pregnancy->patient->name}" . PHP_EOL;
    echo "   Delivery Date: {$pregnancy->delivery_date}" . PHP_EOL;
    echo "   Has DeliveryRecord: NO ❌" . PHP_EOL;
    echo "   Notes: " . ($pregnancy->notes ?? '-') . PHP_EOL;
    echo PHP_EOL;
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL;
echo "🔧 FIXING: Setting delivery_date to NULL for dummy records..." . PHP_EOL;
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL . PHP_EOL;

$updated = 0;
foreach ($dummyPregnancies as $pregnancy) {
    // Reset delivery_date to NULL so modal will trigger
    $pregnancy->update([
        'delivery_date' => null,
        'delivery_method' => null,
        'place_of_birth' => null,
        'birth_attendant' => null,
        'baby_gender' => null,
        'outcome' => null,
    ]);

    echo "✅ Fixed Pregnancy ID: {$pregnancy->id} ({$pregnancy->patient->name})" . PHP_EOL;
    $updated++;
}

echo PHP_EOL;
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL;
echo "✅ Cleanup complete! {$updated} pregnancy records reset." . PHP_EOL;
echo PHP_EOL;
echo "📝 Next steps:" . PHP_EOL;
echo "   1. Access patient detail page" . PHP_EOL;
echo "   2. Click 'Poli Nifas' button" . PHP_EOL;
echo "   3. Modal 'Konfirmasi Riwayat Persalinan' should now appear" . PHP_EOL;
echo "   4. Fill in REAL delivery data from patient" . PHP_EOL;
