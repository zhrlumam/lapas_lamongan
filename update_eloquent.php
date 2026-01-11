<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $row = \App\Models\Kunjungan::find(6);
    if ($row) {
        $row->status = 'masuk';
        $row->check_in_at = '2026-01-10 15:35:45';
        $row->save();
        echo "Update Successful via Eloquent for ID 6\n";
    } else {
        echo "ID 6 not found\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
