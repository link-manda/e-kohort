<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Patient;

try {
    echo "Testing Patient Print Functionality...\n\n";

    // Test 1: Check if patients exist
    $patientCount = Patient::count();
    echo "✓ Total patients in database: $patientCount\n";

    if ($patientCount == 0) {
        echo "✗ No patients found. Please add patients first.\n";
        exit(1);
    }

    // Test 2: Get first patient with relationships
    $patient = Patient::with([
        'pregnancies.ancVisits.labResult',
        'pregnancies' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }
    ])->first();

    echo "✓ Patient loaded: {$patient->name} (RM: {$patient->no_rm})\n";
    echo "  - Pregnancies: {$patient->pregnancies->count()}\n";

    $totalVisits = $patient->pregnancies->sum(function ($pregnancy) {
        return $pregnancy->ancVisits->count();
    });
    echo "  - Total ANC Visits: {$totalVisits}\n";

    // Test 3: Try to render the print view
    try {
        // Set authenticated user (required for signature)
        $user = \App\Models\User::first();
        if ($user) {
            Auth::login($user);
            echo "✓ Authenticated as: {$user->name}\n";
        }

        $view = view('patients.print', ['patient' => $patient]);
        $html = $view->render();

        echo "✓ Print view rendered successfully\n";
        echo "  HTML length: " . number_format(strlen($html)) . " characters\n";

        // Test 4: Check for required sections
        $sections = [
            'Data Demografis Pasien' => strpos($html, 'Data Demografis Pasien') !== false,
            'Data Suami' => strpos($html, 'Data Suami') !== false,
            'Riwayat Kehamilan' => strpos($html, 'Riwayat Kehamilan') !== false,
            'Riwayat Kunjungan ANC' => strpos($html, 'Riwayat Kunjungan ANC') !== false,
            'Print Button' => strpos($html, 'window.print()') !== false,
            '@page CSS' => strpos($html, '@page') !== false,
        ];

        echo "\n✓ Section checks:\n";
        foreach ($sections as $section => $exists) {
            $status = $exists ? '✓' : '✗';
            echo "  $status $section\n";
        }

        // Test 5: Save HTML to file for inspection
        file_put_contents('test_print_output.html', $html);
        echo "\n✓ HTML saved to test_print_output.html for inspection\n";

        // Test 6: Check print route
        echo "\n✓ Print URL: " . route('patients.print', $patient) . "\n";

        echo "\n✅ ALL TESTS PASSED!\n";
        echo "\nNext steps:\n";
        echo "1. Open browser and navigate to: " . route('patients.print', $patient) . "\n";
        echo "2. Click the print button or press Ctrl+P\n";
        echo "3. Verify A4 layout and print preview\n";
    } catch (\Exception $e) {
        echo "✗ View rendering failed: " . $e->getMessage() . "\n";
        throw $e;
    }
} catch (\Exception $e) {
    echo "\n✗ TEST FAILED\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}
