<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\IntegrasiController;
use App\Http\Controllers\SuratJaminanController;

/*
|--------------------------------------------------------------------------
| Frontend Routes (Public Access)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profil', [HomeController::class, 'profile'])->name('profile');
Route::get('/layanan', [HomeController::class, 'layanan'])->name('layanan');
Route::get('/galeri', [HomeController::class, 'galeri'])->name('galeri');
Route::get('/kunjungan', [HomeController::class, 'kunjungan'])->name('kunjungan');
// SECURITY: Rate limit kunjungan - max 3 submissions per 5 minutes
Route::post('/kunjungan/store', [\App\Http\Controllers\KunjunganController::class, 'store'])
    ->middleware('throttle:3,5')
    ->name('kunjungan.store');
Route::get('/kunjungan/tiket/{id}', [\App\Http\Controllers\KunjunganController::class, 'tiket'])->name('kunjungan.tiket');
Route::get('/kunjungan/cari', [\App\Http\Controllers\KunjunganController::class, 'cari'])->name('kunjungan.cari');
Route::get('/pengaduan', [HomeController::class, 'pengaduan'])->name('pengaduan');
// SECURITY: Rate limit pengaduan - max 3 submissions per 10 minutes
Route::post('/pengaduan/store', [\App\Http\Controllers\PengaduanController::class, 'store'])
    ->middleware('throttle:3,10')
    ->name('pengaduan.store');
Route::get('/pengaduan/sukses/{kode}', [\App\Http\Controllers\PengaduanController::class, 'sukses'])->name('pengaduan.sukses');
Route::post('/pengaduan/cari', [\App\Http\Controllers\PengaduanController::class, 'cari'])->name('pengaduan.cari');
Route::get('/pengaduan/lihat/{kode}', [\App\Http\Controllers\PengaduanController::class, 'detail'])->name('pengaduan.detail');
Route::post('/pengaduan/reply/{kode}', [\App\Http\Controllers\PengaduanController::class, 'reply'])->name('pengaduan.reply');

Route::get('/produk', [HomeController::class, 'produk'])->name('produk');
// SECURITY: Rate limit rating - max 5 submissions per hour
Route::post('/rating/store', [\App\Http\Controllers\RatingController::class, 'store'])
    ->middleware('throttle:5,60')
    ->name('rating.store');

// Berita & Informasi
Route::prefix('berita')->name('berita.')->group(function () {
    Route::get('/', [BeritaController::class, 'index'])->name('index');
    Route::get('/{slug}', [BeritaController::class, 'show'])->name('show');
});

// SEO Routes
Route::get('/sitemap.xml', function () {
    $berita = \App\Models\Berita::published()->latest('tanggal')->get();
    return response()->view('sitemap', compact('berita'))
        ->header('Content-Type', 'text/xml');
})->name('sitemap');

Route::get('/robots.txt', function () {
    $content = "User-agent: *\n";
    $content .= "Allow: /\n";
    $content .= "Disallow: /admin/\n";
    $content .= "Disallow: /integrasi/\n\n";
    $content .= "Sitemap: " . url('/sitemap.xml') . "\n";
    
    return response($content, 200)->header('Content-Type', 'text/plain');
})->name('robots');


// Integrasi (Login & Form Surat Jaminan)
// SECURITY FIX: Tambahkan rate limiting untuk mencegah brute force & spam
Route::prefix('integrasi')->name('integrasi.')->group(function () {
    // 1. Halaman Login
    Route::get('/', [IntegrasiController::class, 'index'])->name('login');
    
    // SECURITY: Rate limit login - max 5 attempts per minute
    Route::post('/login', [IntegrasiController::class, 'handleLogin'])
        ->middleware('throttle:5,1')
        ->name('login.post');
    
    // 2. Google Auth Routes
    Route::get('/google', [\App\Http\Controllers\GoogleController::class, 'redirectToGoogle'])->name('google');
    Route::get('/google/callback', [\App\Http\Controllers\GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

    // 3. Halaman Form & Proses (Setelah Login)
    // SECURITY FIX: Protected routes dengan middleware auth:penjamin
    Route::middleware(['auth:penjamin'])->group(function () {
        // Dashboard route
        Route::get('/dashboard', [IntegrasiController::class, 'dashboard'])->name('dashboard');

        Route::get('/form', [IntegrasiController::class, 'form'])->name('form');
        
        // Submit pengajuan (TAHAP 1)
        Route::post('/submit', [IntegrasiController::class, 'submitPengajuan'])
            ->middleware('throttle:10,1')
            ->name('submit');
        
        // Download WORD untuk yang sudah approved (TAHAP 3)
        Route::get('/download/{id}', [IntegrasiController::class, 'downloadApprovedPDF'])
            ->name('download.pdf');
            
        Route::get('/complete-profile', [IntegrasiController::class, 'completeProfile'])->name('complete_profile');
        
        // SECURITY: Rate limit profile update - max 5 per minute
        Route::post('/complete-profile', [IntegrasiController::class, 'storeProfile'])
            ->middleware('throttle:5,1')
            ->name('complete_profile.post');
            
        Route::post('/logout', [IntegrasiController::class, 'logout'])->name('logout');
        Route::get('/download-template', [IntegrasiController::class, 'downloadTemplate'])->name('download_template');
    });
});

// TEST ROUTE - Cek template placeholders
Route::get('/test-template', function() {
    $templatePath = storage_path('app/templates/template_jaminan.docx');
    
    if (!file_exists($templatePath)) {
        return "Template tidak ditemukan!";
    }
    
    try {
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
        $variables = $templateProcessor->getVariables();
        
        $output = "<h1>Testing Template</h1>";
        $output .= "<p>Template path: $templatePath</p>";
        $output .= "<h2>Placeholder yang ditemukan:</h2>";
        
        if (empty($variables)) {
            $output .= "<p style='color:red'><strong>TIDAK ADA!</strong> Template tidak punya placeholder yang valid.</p>";
            $output .= "<p>Kemungkinan penyebab:</p>";
            $output .= "<ul>";
            $output .= "<li>Placeholder menggunakan formatting (bold, italic, dll)</li>";
            $output .= "<li>Placeholder terpisah oleh formatting</li>";
            $output .= "<li>Placeholder tidak menggunakan format \${...}</li>";
            $output .= "</ul>";
            $output .= "<p><strong>Solusi:</strong> Buat ulang template dengan ketik manual \${nama_penjamin} tanpa formatting</p>";
        } else {
            $output .= "<ul>";
            foreach ($variables as $var) {
                $output .= "<li>\${" . $var . "}</li>";
            }
            $output .= "</ul>";
        }
        
        return $output;
        
    } catch (\Exception $e) {
        return "ERROR: " . $e->getMessage();
    }
});

// TEST ROUTE - Cek data terbaru
Route::get('/test-data', function() {
    $latest = \App\Models\Integrasi::latest()->first();
    
    if (!$latest) {
        return "<h1>Tidak ada data di database!</h1><p>Silakan submit pengajuan baru.</p>";
    }
    
    return "<h1>Data Terbaru (ID: {$latest->id})</h1>" .
           "<table border='1' cellpadding='10'>" .
           "<tr><td>Nama Penjamin</td><td>{$latest->nama_penjamin}</td></tr>" .
           "<tr><td>Umur Penjamin</td><td>" . ($latest->umur_penjamin ?? '<span style=\"color:red\">NULL</span>') . "</td></tr>" .
           "<tr><td>Pekerjaan</td><td>" . ($latest->pekerjaan_penjamin ?? '<span style=\"color:red\">NULL</span>') . "</td></tr>" .
           "<tr><td>Hubungan</td><td>" . ($latest->hubungan_penjamin ?? '<span style=\"color:red\">NULL</span>') . "</td></tr>" .
           "<tr><td>Nama WBP</td><td>{$latest->nama_wbp}</td></tr>" .
           "<tr><td>Umur WBP</td><td>" . ($latest->umur_wbp ?? '<span style=\"color:red\">NULL</span>') . "</td></tr>" .
           "<tr><td>Created At</td><td>{$latest->created_at}</td></tr>" .
           "<tr><td>Status</td><td>{$latest->status}</td></tr>" .
           "</table>";
});

Route::get('/login', function() { return redirect()->route('admin.login'); })->name('login');
Route::get('/admin/login', [\App\Http\Controllers\Admin\LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [\App\Http\Controllers\Admin\LoginController::class, 'login'])->name('admin.login.post');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Public/Common Admin Access
    Route::get('/search', [\App\Http\Controllers\Admin\SearchController::class, 'globalSearch'])->name('search');
    Route::post('/logout', [\App\Http\Controllers\Admin\LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [\App\Http\Controllers\Admin\LoginController::class, 'dashboard'])->name('dashboard');

    // 1. Konten Media (Humas)
    Route::middleware(['can:humas'])->group(function () {
        Route::resource('berita', \App\Http\Controllers\Admin\BeritaController::class)->parameters(['berita' => 'berita']);
        Route::resource('galeri', \App\Http\Controllers\Admin\GaleriController::class);
        Route::resource('informasi', \App\Http\Controllers\Admin\InformasiController::class);
    });

    // 2. Layanan Utama (Layanan)
    Route::middleware(['can:layanan'])->group(function () {
        Route::resource('hunian', \App\Http\Controllers\Admin\HunianController::class)->only(['index', 'store', 'destroy']);
        Route::resource('kunjungan', \App\Http\Controllers\Admin\KunjunganController::class)->except(['create', 'edit', 'update']);
        Route::post('/kunjungan/{id}/status/{status}', [\App\Http\Controllers\Admin\KunjunganController::class, 'updateStatus'])->name('kunjungan.status');
        Route::delete('/kunjungan/bulk-delete', [\App\Http\Controllers\Admin\KunjunganController::class, 'bulkDelete'])->name('kunjungan.bulk-delete');
        
        Route::resource('integrasi', \App\Http\Controllers\Admin\IntegrasiController::class)->except(['create', 'edit', 'update']);
        Route::post('/integrasi/{id}/status', [\App\Http\Controllers\Admin\IntegrasiController::class, 'updateStatus'])->name('integrasi.status');
        Route::post('/integrasi/bulk-status', [\App\Http\Controllers\Admin\IntegrasiController::class, 'bulkStatus'])->name('integrasi.bulk-status');
        Route::post('/integrasi/bulk-delete', [\App\Http\Controllers\Admin\IntegrasiController::class, 'bulkDelete'])->name('integrasi.bulk-delete');

        Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/wbp/pdf', [\App\Http\Controllers\Admin\LaporanController::class, 'exportWBP'])->name('laporan.wbp.pdf');
        Route::get('/laporan/wbp/excel', [\App\Http\Controllers\Admin\LaporanController::class, 'exportWBPExcel'])->name('laporan.wbp.excel');
        Route::get('/laporan/kunjungan/pdf', [\App\Http\Controllers\Admin\LaporanController::class, 'exportKunjungan'])->name('laporan.kunjungan.pdf');
        Route::get('/laporan/kunjungan/excel', [\App\Http\Controllers\Admin\LaporanController::class, 'exportKunjunganExcel'])->name('laporan.kunjungan.excel');
        
        Route::resource('survey', \App\Http\Controllers\Admin\SurveyController::class);
        Route::post('/survey/{id}/activate', [\App\Http\Controllers\Admin\SurveyController::class, 'activate'])->name('survey.activate');
    });

    // 3. Komunikasi & Respon (Pengaduan)
    Route::middleware(['can:pengaduan'])->group(function () {
        Route::resource('pengaduan', \App\Http\Controllers\Admin\PengaduanController::class)->only(['index', 'show', 'destroy']);
        Route::post('/pengaduan/{id}/status', [\App\Http\Controllers\Admin\PengaduanController::class, 'updateStatus'])->name('pengaduan.status');
        Route::post('/pengaduan/{id}/reply', [\App\Http\Controllers\Admin\PengaduanController::class, 'reply'])->name('pengaduan.reply');
    });

    // 4. Pengaturan & Master Data (Akses Semua Admin)
    Route::get('/profil', [\App\Http\Controllers\Admin\ProfilController::class, 'index'])->name('profil.index');
    Route::post('/profil/update', [\App\Http\Controllers\Admin\ProfilController::class, 'update'])->name('profil.update');
    Route::resource('produk', \App\Http\Controllers\Admin\ProdukController::class);
    Route::get('/laporan/traffic', [\App\Http\Controllers\Admin\LaporanController::class, 'traffic'])->name('laporan.traffic');
    Route::resource('rating', \App\Http\Controllers\Admin\RatingAdminController::class)->only(['index', 'destroy']);

    // 5. Advanced User Management (Super Admin ONLY)
    Route::middleware(['can:super'])->group(function () {
        Route::resource('manage-admin', \App\Http\Controllers\Admin\AdminManageController::class);
        Route::resource('manage-users', \App\Http\Controllers\Admin\UserManageController::class);
        
        // Backup Database Management
        Route::get('/backup', [\App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backup.index');
        Route::post('/backup/create', [\App\Http\Controllers\Admin\BackupController::class, 'create'])->name('backup.create');
        Route::get('/backup/download/{filename}', [\App\Http\Controllers\Admin\BackupController::class, 'download'])->name('backup.download');
        Route::delete('/backup/delete/{filename}', [\App\Http\Controllers\Admin\BackupController::class, 'destroy'])->name('backup.destroy');
        Route::post('/backup/restore', [\App\Http\Controllers\Admin\BackupController::class, 'restore'])->name('backup.restore');
    });
});

/*
|--------------------------------------------------------------------------
| Health Check & Monitoring (For Server Uptime Tracking)
|--------------------------------------------------------------------------
*/

Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now(),
        'app' => config('app.name'),
    ]);
})->name('health.check');
