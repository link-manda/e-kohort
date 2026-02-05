<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Patient;
use App\Models\AncVisit;

$patientName = 'Made Dewi Rinata';

$patient = Patient::where('name', 'like', "%{$patientName}%")->first();

if (!$patient) {
    echo "Patient not found: {$patientName}\n";
    exit(1);
}

echo "Found patient: {$patient->name} (ID: {$patient->id})\n";

foreach ($patient->pregnancies as $pregnancy) {
    echo "\nPregnancy ID: {$pregnancy->id} | Status: {$pregnancy->status} | Gravida: {$pregnancy->gravida}\n";
    $ancs = $pregnancy->ancVisits()->orderBy('visit_date')->get();
    if ($ancs->isEmpty()) {
        echo "  No ANC visits\n";
        continue;
    }
    foreach ($ancs as $a) {
        echo "  ANC Visit ID: {$a->id} | Code: {$a->visit_code} | Date: " . ($a->visit_date ? $a->visit_date->format('Y-m-d') : 'NULL') . "\n";
        echo "    gestational_age (db): " . var_export($a->gestational_age, true) . "\n";
        echo "    gestational_age cast type: " . gettype($a->gestational_age) . "\n";
    }
}

echo "\nDone.\n";