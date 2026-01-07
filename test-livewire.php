<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING E-KOHORT LIVEWIRE COMPONENTS ===" . PHP_EOL;
echo PHP_EOL;

// Test Patient Model
echo "1. Testing Patient Model..." . PHP_EOL;
$patientCount = App\Models\Patient::count();
echo "   Total Patients: " . $patientCount . PHP_EOL;

if ($patientCount > 0) {
    $patient = App\Models\Patient::first();
    echo "   Sample Patient: " . $patient->name . " (ID: " . $patient->id . ")" . PHP_EOL;

    // Test Pregnancy Relationship
    echo PHP_EOL;
    echo "2. Testing Pregnancy Relationship..." . PHP_EOL;
    $activePregnancy = $patient->activePregnancy;
    if ($activePregnancy) {
        echo "   Active Pregnancy Found!" . PHP_EOL;
        echo "   Pregnancy ID: " . $activePregnancy->id . PHP_EOL;
        echo "   Gravida: " . $activePregnancy->gravida . PHP_EOL;
        echo "   Gestational Age: " . $activePregnancy->gestational_age . " weeks" . PHP_EOL;
    } else {
        echo "   No active pregnancy for this patient" . PHP_EOL;
    }
}

// Test Pregnancy Model
echo PHP_EOL;
echo "3. Testing Pregnancy Model..." . PHP_EOL;
$pregnancyCount = App\Models\Pregnancy::count();
echo "   Total Pregnancies: " . $pregnancyCount . PHP_EOL;

// Test ANC Visit Model
echo PHP_EOL;
echo "4. Testing ANC Visit Model..." . PHP_EOL;
$ancVisitCount = App\Models\AncVisit::count();
echo "   Total ANC Visits: " . $ancVisitCount . PHP_EOL;

// Test Livewire Component Classes
echo PHP_EOL;
echo "5. Testing Livewire Component Classes..." . PHP_EOL;

try {
    $pregnancyReg = new App\Livewire\PregnancyRegistration();
    echo "   ✓ PregnancyRegistration class exists" . PHP_EOL;
} catch (Exception $e) {
    echo "   ✗ PregnancyRegistration error: " . $e->getMessage() . PHP_EOL;
}

try {
    $ancWizard = new App\Livewire\AncVisitWizard();
    echo "   ✓ AncVisitWizard class exists" . PHP_EOL;
} catch (Exception $e) {
    echo "   ✗ AncVisitWizard error: " . $e->getMessage() . PHP_EOL;
}

// Test Routes
echo PHP_EOL;
echo "6. Testing Routes..." . PHP_EOL;
$routes = app('router')->getRoutes();
$targetRoutes = ['pregnancies.create', 'anc-visits.create'];

foreach ($targetRoutes as $routeName) {
    $route = $routes->getByName($routeName);
    if ($route) {
        echo "   ✓ Route '{$routeName}': " . $route->uri() . PHP_EOL;
    } else {
        echo "   ✗ Route '{$routeName}' not found" . PHP_EOL;
    }
}

echo PHP_EOL;
echo "=== ALL TESTS COMPLETED ===" . PHP_EOL;
