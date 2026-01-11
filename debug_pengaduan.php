<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$output = "";
try {
    $output .= "Table: pengaduan\n";
    $results = DB::select("DESCRIBE pengaduan");
    foreach($results as $row) {
        $output .= "Field: {$row->Field} | Type: {$row->Type} | Null: {$row->Null} | Key: {$row->Key} | Default: {$row->Default}\n";
    }
} catch (\Exception $e) {
    $output .= "Error: " . $e->getMessage() . "\n";
}

file_put_contents('debug_output.txt', $output);
echo "Output saved to debug_output.txt\n";
