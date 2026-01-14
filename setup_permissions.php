<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

try {
    $user = User::first();
    if ($user) {
        $user->givePermissionTo(['create-pregnancies', 'edit-pregnancies', 'view-all-pregnancies']);
        echo "✅ Permissions assigned to user: {$user->name}\n";
        echo "   - create-pregnancies\n";
        echo "   - edit-pregnancies\n";
        echo "   - view-all-pregnancies\n";
    } else {
        echo "❌ No users found. Please create a user first.\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}