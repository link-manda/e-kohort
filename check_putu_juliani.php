<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Patient;
use App\Models\Pregnancy;
use App\Models\DeliveryRecord;

echo "=== CHECKING PUTU JULIANI PREGNANCY DATA ===" . PHP_EOL . PHP_EOL;

// Find Putu Juliani patient
$patient = Patient::where('name', 'like', '%Putu Juliani%')->first();

if (!$patient) {
    echo "❌ Patient 'Putu Juliani' not found" . PHP_EOL;
    exit(1);
}

echo "✅ Found Patient: {$patient->name} (No.RM: {$patient->no_rm})" . PHP_EOL;
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL . PHP_EOL;

// Get pregnancies with status Lahir
$pregnancies = $patient->pregnancies()->where('status', 'Lahir')->get();

if ($pregnancies->isEmpty()) {
    echo "⚠️  No pregnancy with status 'Lahir' found" . PHP_EOL;
    exit(0);
}

foreach ($pregnancies as $pregnancy) {
    echo "📋 Pregnancy ID: {$pregnancy->id}" . PHP_EOL;
    echo "   Status: {$pregnancy->status}" . PHP_EOL;
    echo "   Gravida: {$pregnancy->gravida}" . PHP_EOL;
    echo "   HPHT: " . ($pregnancy->hpht ? $pregnancy->hpht->format('Y-m-d') : 'NULL') . PHP_EOL;
    echo "   HPL: " . ($pregnancy->hpl ? $pregnancy->hpl->format('Y-m-d') : 'NULL') . PHP_EOL;
    echo "   Delivery Date: " . ($pregnancy->delivery_date ? $pregnancy->delivery_date->format('Y-m-d H:i') : 'NULL') . PHP_EOL;
    echo "   is_external: " . ($pregnancy->is_external ? 'TRUE' : 'FALSE') . PHP_EOL;

    // Check if has delivery record
    $deliveryRecord = $pregnancy->deliveryRecord;
    echo "   Has DeliveryRecord: " . ($deliveryRecord ? 'YES ✅' : 'NO ❌') . PHP_EOL;

    if ($deliveryRecord) {
        echo "      → Delivery Method: {$deliveryRecord->delivery_method}" . PHP_EOL;
        echo "      → Birth Attendant: {$deliveryRecord->birth_attendant}" . PHP_EOL;
        echo "      → Birth Weight: {$deliveryRecord->birth_weight}g" . PHP_EOL;
    }

    // Check delivery_date vs today
    if ($pregnancy->delivery_date) {
        $daysSince = now()->diffInDays($pregnancy->delivery_date);
        echo "   Days since delivery: {$daysSince} days" . PHP_EOL;
    }

    echo "   Notes: " . ($pregnancy->notes ?? '-') . PHP_EOL;
    echo PHP_EOL;
    echo "🔗 URL: http://localhost/e-kohort_klinik/public/pregnancies/{$pregnancy->id}/postnatal" . PHP_EOL;
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL . PHP_EOL;
}

echo PHP_EOL;
echo "🔍 DIAGNOSIS:" . PHP_EOL;
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL;
echo "1. If delivery_date EXISTS but no DeliveryRecord → DUMMY DATA (from old proceedToNifas)" . PHP_EOL;
echo "2. If is_external = FALSE but delivery_date exists → INCOMPLETE DATA" . PHP_EOL;
echo "3. Should trigger modal if: delivery_date NULL OR (no DeliveryRecord AND is_external=FALSE)" . PHP_EOL;
echo PHP_EOL;

echo "💡 RECOMMENDED FIX:" . PHP_EOL;
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . PHP_EOL;
echo "Option 1: Delete dummy pregnancy and recreate with delivery_date=NULL" . PHP_EOL;
echo "Option 2: Update mount() logic to check deliveryRecord existence too" . PHP_EOL;
echo "Option 3: Add 'Edit External Birth Data' button in postnatal form" . PHP_EOL;
