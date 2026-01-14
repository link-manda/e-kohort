<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Patient;

try {
    $patient = Patient::find(64);
    if (!$patient) {
        echo "❌ Patient not found\n";
        exit;
    }

    echo "🔍 Patient Analysis:\n";
    echo "Patient ID: {$patient->id}\n";
    echo "Patient Name: {$patient->name}\n";

    echo "\n🔍 Active Pregnancy:\n";
    $activePregnancy = $patient->activePregnancy;
    if ($activePregnancy) {
        echo "ID: {$activePregnancy->id}\n";
        echo "Status: '{$activePregnancy->status}'\n";
        echo "Delivery Date: " . ($activePregnancy->delivery_date ? $activePregnancy->delivery_date->format('Y-m-d H:i') : 'null') . "\n";
    } else {
        echo "No active pregnancy (null)\n";
    }

    echo "\n🔍 All Pregnancies:\n";
    foreach ($patient->pregnancies as $pregnancy) {
        echo "- ID: {$pregnancy->id}, Status: '{$pregnancy->status}', Delivery: " .
             ($pregnancy->delivery_date ? $pregnancy->delivery_date->format('Y-m-d H:i') : 'null') . "\n";
    }

    echo "\n🔍 View Logic Simulation:\n";

    // Simulate active pregnancy section
    if ($activePregnancy) {
        echo "✅ Active Pregnancy Section: VISIBLE\n";
        echo "   Status Badge: '{$activePregnancy->status}'\n";

        // Simulate buttons
        if (in_array($activePregnancy->status, ['Aktif'])) {
            echo "   ✅ Button 'Catat Persalinan': VISIBLE\n";
        } else {
            echo "   ❌ Button 'Catat Persalinan': HIDDEN\n";
        }

        if ($activePregnancy->status === 'Lahir' && $activePregnancy->delivery_date) {
            echo "   ✅ Button 'Kunjungan Nifas': VISIBLE\n";
        } else {
            echo "   ❌ Button 'Kunjungan Nifas': HIDDEN\n";
        }
    } else {
        echo "❌ Active Pregnancy Section: HIDDEN (activePregnancy is null)\n";
        echo "   This explains empty section!\n";
    }

    // Simulate ANC visit button
    if ($activePregnancy && $activePregnancy->status === 'Aktif') {
        echo "   ✅ Button 'Tambah Kunjungan' (ANC): VISIBLE\n";
    } else {
        echo "   ❌ Button 'Tambah Kunjungan' (ANC): HIDDEN\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}