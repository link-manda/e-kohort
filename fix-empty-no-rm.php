<?php

/**
 * Fix Empty no_rm for Existing Patients
 *
 * This script will update all patients with empty no_rm
 * and assign them auto-generated No. RM
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Patient;

echo "=== Fix Empty No. RM Script ===\n\n";

// Find patients with empty no_rm
$patientsWithEmptyRM = Patient::whereNull('no_rm')
    ->orWhere('no_rm', '')
    ->get();

echo "Found {$patientsWithEmptyRM->count()} patients with empty No. RM\n\n";

if ($patientsWithEmptyRM->isEmpty()) {
    echo "✅ No patients to update!\n";
    exit(0);
}

$year = date('Y');
$prefix = "RM-{$year}-";

// Get last patient number for this year
$lastPatient = Patient::where('no_rm', 'like', "{$prefix}%")
    ->orderBy('no_rm', 'desc')
    ->first();

if ($lastPatient) {
    $lastNumber = (int) substr($lastPatient->no_rm, -4);
    $nextNumber = $lastNumber + 1;
} else {
    $nextNumber = 1;
}

echo "Starting from: {$prefix}" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT) . "\n\n";

// Update each patient
foreach ($patientsWithEmptyRM as $patient) {
    $newNoRM = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

    $patient->update(['no_rm' => $newNoRM]);

    echo "✅ Updated Patient ID {$patient->id} ({$patient->name}): {$newNoRM}\n";

    $nextNumber++;
}

echo "\n=== Update Complete! ===\n";
echo "Total updated: {$patientsWithEmptyRM->count()} patients\n";
