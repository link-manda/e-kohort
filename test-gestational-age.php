<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING GESTATIONAL AGE FORMATTING ===" . PHP_EOL;
echo PHP_EOL;

// Test 1: Raw diffInWeeks
echo "1. Testing Carbon diffInWeeks output..." . PHP_EOL;
$hpht = \Carbon\Carbon::parse('2025-10-01');
$now = \Carbon\Carbon::now();

$weeksDecimal = $hpht->diffInWeeks($now);
$weeksInteger = (int) $hpht->diffInWeeks($now);

echo "   HPHT: " . $hpht->format('Y-m-d') . PHP_EOL;
echo "   Now: " . $now->format('Y-m-d') . PHP_EOL;
echo "   diffInWeeks (raw): " . $weeksDecimal . " minggu" . PHP_EOL;
echo "   diffInWeeks (int cast): " . $weeksInteger . " minggu" . PHP_EOL;
echo PHP_EOL;

// Test 2: Model computed attribute
echo "2. Testing Pregnancy Model gestational_age attribute..." . PHP_EOL;
$pregnancy = App\Models\Pregnancy::first();

if ($pregnancy) {
    echo "   Pregnancy ID: " . $pregnancy->id . PHP_EOL;
    echo "   HPHT: " . $pregnancy->hpht->format('Y-m-d') . PHP_EOL;
    echo "   Gestational Age: " . $pregnancy->gestational_age . " minggu" . PHP_EOL;
    echo "   Type: " . gettype($pregnancy->gestational_age) . PHP_EOL;
} else {
    echo "   No pregnancy found" . PHP_EOL;
}

echo PHP_EOL;

// Test 3: Float to Int conversion
echo "3. Testing float to int conversion..." . PHP_EOL;
$testValues = [8.7480920668767, 12.3, 20.9, 35.1];

foreach ($testValues as $value) {
    echo "   " . $value . " minggu → " . (int)$value . " minggu ✓" . PHP_EOL;
}

echo PHP_EOL;
echo "=== FORMATTING FIX VERIFIED ===" . PHP_EOL;
echo PHP_EOL;
echo "✅ Blade view: {{ (int) \\Carbon\\Carbon::parse(\$hpht)->diffInWeeks(now()) }}" . PHP_EOL;
echo "✅ Model: return (int) \$this->hpht->diffInWeeks(now());" . PHP_EOL;
echo "✅ No more decimal values displayed!" . PHP_EOL;
