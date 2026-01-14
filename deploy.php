<?php

/**
 * LARAVEL DEPLOYMENT HELPER
 * Gunakan file ini jika hosting Anda tidak memiliki akses SSH/Terminal.
 * Akses via: namadomain.com/deploy.php
 */

use Illuminate\Support\Facades\Artisan;

// Keamanan: Masukkan password di sini agar tidak sembarang orang bisa akses
$password = 'lapas_lamongan_2026'; // GANTI INI!

if (!isset($_GET['pass']) || $_GET['pass'] !== $password) {
    die('Akses Ditolak. Gunakan ?pass=PASSWORD untuk menjalankan.');
}

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain');

echo "--- STARTING DEPLOYMENT ---\n\n";

try {
    echo "1. Clearing Cache...\n";
    Artisan::call('cache:clear');
    echo Artisan::output();

    echo "2. Caching Config...\n";
    Artisan::call('config:cache');
    echo Artisan::output();

    echo "3. Caching Routes...\n";
    Artisan::call('route:cache');
    echo Artisan::output();

    echo "4. Caching Views...\n";
    Artisan::call('view:cache');
    echo Artisan::output();

    echo "5. Linking Storage...\n";
    Artisan::call('storage:link');
    echo Artisan::output();

    echo "6. Running Migrations...\n";
    // Artisan::call('migrate --force'); // Aktifkan jika mau migrasi otomatis
    echo "Skipped (Run manually if needed)\n";

    echo "\n--- DEPLOYMENT SUCCESSFUL! ---";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
