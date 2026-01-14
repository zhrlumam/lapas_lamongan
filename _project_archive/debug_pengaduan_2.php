<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$output = "";
try {
    $output .= "Table: balasan_pengaduan\n";
    if (Schema::hasTable('balasan_pengaduan')) {
        $results = DB::select("DESCRIBE balasan_pengaduan");
        foreach($results as $row) {
            $output .= "Field: {$row->Field} | Type: {$row->Type}\n";
        }
    } else {
        $output .= "Table missing!\n";
    }
} catch (\Exception $e) {
    $output .= "Error: " . $e->getMessage() . "\n";
}

file_put_contents('debug_output_2.txt', $output);
echo "Output saved to debug_output_2.txt\n";
