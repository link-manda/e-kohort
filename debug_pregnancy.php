<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Patient;
use App\Models\Pregnancy;

try {
    // Get the test patient and pregnancy
    $patient = Patient::where('nik', '9999999999999999')->first();
    if (!$patient) {
        echo "❌ Test patient not found\n";
        exit;
    }

    $pregnancy = $patient->pregnancies()->first();
    if (!$pregnancy) {
        echo "❌ Test pregnancy not found\n";
        exit;
    }

    echo "🔍 Database Check:\n";
    echo "Patient ID: {$patient->id}\n";
    echo "Pregnancy ID: {$pregnancy->id}\n";
    echo "Raw Status from DB: '{$pregnancy->getOriginal('status')}'\n";
    echo "Status Attribute: '{$pregnancy->status}'\n";
    echo "All Pregnancy Attributes:\n";
    print_r($pregnancy->getAttributes());

    echo "\n🔍 Active Pregnancy Check:\n";
    $activePregnancy = $patient->activePregnancy;
    if ($activePregnancy) {
        echo "Active Pregnancy ID: {$activePregnancy->id}\n";
        echo "Active Pregnancy Status: '{$activePregnancy->status}'\n";
    } else {
        echo "No active pregnancy found\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}