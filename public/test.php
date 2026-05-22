<?php
// Simple test file to check if basic PHP and Laravel work
echo "Testing basic Laravel functionality...\n\n";

try {
    // Test 1: Basic PHP
    echo "✓ PHP is working\n";

    // Test 2: Vendor autoload
    require __DIR__ . '/../vendor/autoload.php';
    echo "✓ Vendor autoload successful\n";

    // Test 3: Laravel bootstrap
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "✓ Laravel bootstrap successful\n";

    // Test 4: Database connection via model
    $count = \App\Models\Antrian::count();
    echo "✓ Database connection successful (Antrian count: $count)\n";

    echo "\n✅ All tests passed! Laravel is working properly.\n";

} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
