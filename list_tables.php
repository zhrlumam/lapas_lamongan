<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = DB::connection()->getSchemaBuilder()->getTableListing();
foreach($tables as $table) {
    echo $table . PHP_EOL;
}
