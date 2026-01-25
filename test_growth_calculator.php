<?php

use App\Models\Child;
use App\Services\GrowthCalculatorService;
use Carbon\Carbon;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Test GrowthCalculatorService ===\n\n";

// Create sample child (6 months old boy)
$child = new Child([
    'gender' => 'L',
    'dob' => Carbon::now()->subMonths(6),
]);

$service = new GrowthCalculatorService();

// Test calculation
$recordDate = Carbon::now();
$weight = 7.5; // kg
$height = 67.0; // cm
$measurementMethod = 'Terlentang';

echo "Input Data:\n";
echo "- Jenis Kelamin: " . ($child->gender == 'L' ? 'Laki-laki' : 'Perempuan') . "\n";
echo "- Tanggal Lahir: " . $child->dob->format('Y-m-d') . "\n";
echo "- Tanggal Ukur: " . $recordDate->format('Y-m-d') . "\n";
echo "- Berat Badan: {$weight} kg\n";
echo "- Tinggi Badan: {$height} cm\n";
echo "- Metode: {$measurementMethod}\n\n";

try {
    $result = $service->calculateAllIndicators($child, $recordDate, $weight, $height, $measurementMethod);

    echo "Hasil Perhitungan:\n";
    echo "- Umur: {$result['age_in_months']} bulan\n";
    echo "- Tinggi Terkoreksi: {$result['corrected_height']} cm\n";
    echo "- Koreksi Diterapkan: " . ($result['height_correction_applied'] ? 'Ya' : 'Tidak') . "\n\n";

    echo "Indikator Gizi:\n";
    echo "1. BB/U (Berat Badan menurut Umur):\n";
    echo "   Z-Score: " . number_format($result['bb_u']['zscore'], 2) . "\n";
    echo "   Status: {$result['bb_u']['status']}\n\n";

    echo "2. TB/U (Tinggi Badan menurut Umur):\n";
    echo "   Z-Score: " . number_format($result['tb_u']['zscore'], 2) . "\n";
    echo "   Status: {$result['tb_u']['status']}\n\n";

    echo "3. BB/TB (Berat Badan menurut Tinggi Badan):\n";
    echo "   Z-Score: " . number_format($result['bb_tb']['zscore'], 2) . "\n";
    echo "   Status: {$result['bb_tb']['status']}\n\n";

    echo "✅ Test berhasil!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
