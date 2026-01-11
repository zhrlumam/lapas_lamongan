<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "Updating column...\n";
    DB::statement("ALTER TABLE kunjungan MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'masuk', 'keluar') DEFAULT 'pending'");
    echo "Column updated.\n";
    
    echo "Updating record ID 6...\n";
    $updated = DB::update("UPDATE kunjungan SET status = 'masuk', check_in_at = '2026-01-10 15:35:45' WHERE id = ?", [6]);
    echo "Updated $updated rows.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
