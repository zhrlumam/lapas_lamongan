<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    $tables = DB::select('SHOW TABLES');
    print_r($tables);
    
    $columns = Schema::getColumnListing('kunjungan');
    print_r($columns);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
