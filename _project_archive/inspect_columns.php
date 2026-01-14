<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Kunjungan Columns:\n";
print_r(DB::getSchemaBuilder()->getColumnListing('kunjungan'));

echo "\nPengaduan Columns:\n";
print_r(DB::getSchemaBuilder()->getColumnListing('pengaduan'));

echo "\nProduk Columns:\n";
print_r(DB::getSchemaBuilder()->getColumnListing('produk'));
