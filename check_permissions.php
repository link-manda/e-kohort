<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Spatie\Permission\Models\Permission;

try {
    $permissions = Permission::all()->pluck('name')->toArray();
    echo "Available permissions:\n";
    foreach ($permissions as $perm) {
        echo "- $perm\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}