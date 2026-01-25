<?php

use App\Models\Child;
use App\Services\GrowthCalculatorService;
use Carbon\Carbon;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Test GrowthCalculatorService - Kasus Stunting ===\n\n";

// Create sample child with malnutrition (6 months old boy, underweight and stunted)
$child = new Child([
    'gender' => 'L',
    'dob' => Carbon::now()->subMonths(6),
]);

$service = new GrowthCalculatorService();

// Test calculation with stunting case
$recordDate = Carbon::now();
$weight = 5.5; // kg (underweight)
$height = 60.0; // cm (stunted)
$measurementMethod = 'Terlentang';

echo "Input Data (Kasus Gizi Buruk):\n";
echo "- Jenis Kelamin: " . ($child->gender == 'L' ? 'Laki-laki' : 'Perempuan') . "\n";
echo "- Umur: 6 bulan\n";
echo "- Berat Badan: {$weight} kg (rendah)\n";
echo "- Tinggi Badan: {$height} cm (pendek)\n";
echo "- Metode: {$measurementMethod}\n\n";

try {
    $result = $service->calculateAllIndicators($child, $recordDate, $weight, $height, $measurementMethod);

    echo "Hasil Perhitungan:\n";
    echo "- Umur: {$result['age_in_months']} bulan\n";
    echo "- Tinggi Terkoreksi: {$result['corrected_height']} cm\n\n";

    echo "Indikator Gizi:\n";
    echo "1. BB/U: " . number_format($result['bb_u']['zscore'], 2) . " SD → {$result['bb_u']['status']}\n";
    echo "2. TB/U: " . number_format($result['tb_u']['zscore'], 2) . " SD → {$result['tb_u']['status']}\n";
    echo "3. BB/TB: " . number_format($result['bb_tb']['zscore'], 2) . " SD → {$result['bb_tb']['status']}\n\n";

    echo "Alert Flags:\n";
    echo "- Stunting (TB/U < -2 SD): " . ($result['has_stunting'] ? "❌ YA" : "✅ TIDAK") . "\n";
    echo "- Wasting (BB/TB < -2 SD): " . ($result['has_wasting'] ? "❌ YA" : "✅ TIDAK") . "\n";
    echo "- Underweight (BB/U < -2 SD): " . ($result['has_underweight'] ? "❌ YA" : "✅ TIDAK") . "\n\n";

    if ($result['has_stunting'] || $result['has_wasting'] || $result['has_underweight']) {
        echo "⚠️ PERINGATAN: Anak memerlukan intervensi gizi segera!\n";
    } else {
        echo "✅ Status gizi baik!\n";
    }

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
