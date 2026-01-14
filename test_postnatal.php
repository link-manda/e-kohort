<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pregnancy;

try {
    // Get test pregnancy
    $pregnancy = Pregnancy::find(59);

    if (!$pregnancy) {
        echo "❌ Test pregnancy not found\n";
        exit;
    }

    echo "🔍 Testing PostnatalEntry Component:\n";
    echo "Pregnancy ID: {$pregnancy->id}, Status: {$pregnancy->status}\n";

    // Test component instantiation
    $component = new App\Livewire\PostnatalEntry();
    echo "✅ Component instantiated successfully\n";

    // Test mount method
    $component->mount($pregnancy);
    echo "✅ Mount method executed successfully\n";

    // Check errorMessage property
    echo "ErrorMessage: '" . ($component->errorMessage ?? 'null') . "'\n";
    echo "Visit Date Warning: '" . ($component->visit_date_warning ?? 'null') . "'\n";

    echo "✅ All tests passed!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}