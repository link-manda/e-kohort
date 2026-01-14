<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Patient;
use App\Models\Pregnancy;
use App\Models\PostnatalVisit;

try {
    // Find test patient
    $patient = Patient::where('nik', '9999999999999999')->first();
    if (!$patient) {
        echo "❌ Test patient not found\n";
        exit;
    }

    // Delete existing postnatal visits
    $postnatalVisits = PostnatalVisit::whereHas('pregnancy', function($q) use ($patient) {
        $q->where('patient_id', $patient->id);
    })->delete();

    // Reset pregnancy to active state
    $pregnancy = $patient->pregnancies()->first();
    if ($pregnancy) {
        $pregnancy->update([
            'status' => 'Aktif',
            'delivery_date' => null,
            'delivery_method' => null,
            'birth_attendant' => null,
            'place_of_birth' => null,
            'outcome' => null,
            'baby_gender' => null,
            'complications' => null,
        ]);

        echo "✅ Test data reset successfully!\n";
        echo "   Patient ID: {$patient->id} - {$patient->name}\n";
        echo "   Pregnancy ID: {$pregnancy->id} - Status: {$pregnancy->status}\n";
        echo "   Deleted postnatal visits: {$postnatalVisits}\n";
        echo "\n📋 Test URLs:\n";
        echo "   Patient Detail: http://127.0.0.1:8000/patients/{$patient->id}\n";
        echo "   Delivery Form: http://127.0.0.1:8000/pregnancies/{$pregnancy->id}/delivery\n";
    } else {
        echo "❌ Pregnancy not found\n";
    }

} catch (Exception $e) {
    echo "❌ Error resetting test data: " . $e->getMessage() . "\n";
}