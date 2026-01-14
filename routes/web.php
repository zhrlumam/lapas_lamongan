<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\IntegrasiController;

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
    // Untuk saat ini kita biarkan tanpa middleware 'auth' ketat di route jika controller handle session manual,
    // ATAU jika kita sudah pakai Auth::login() via Google, kita bisa pakai middleware 'auth'.
    // Namun IntegrasiController eksisting mungkin pakai session manual ('nik_penjamin').
    // Jadi biarkan 'web' middleware group (standard), logic login check ada di controller.
    
    Route::get('/form', [IntegrasiController::class, 'form'])->name('form');
    
    // SECURITY: Rate limit PDF generation - max 3 per minute
    Route::post('/pdf', [IntegrasiController::class, 'generatePDF'])
        ->middleware('throttle:3,1')
        ->name('pdf');
        
    Route::get('/dashboard', [IntegrasiController::class, 'dashboard'])->name('dashboard');
    Route::get('/complete-profile', [IntegrasiController::class, 'completeProfile'])->name('complete_profile');
    
    // SECURITY: Rate limit profile update - max 5 per minute
    Route::post('/complete-profile', [IntegrasiController::class, 'storeProfile'])
        ->middleware('throttle:5,1')
        ->name('complete_profile.post');
        
    Route::post('/logout', [IntegrasiController::class, 'logout'])->name('logout');
    Route::get('/download-template', [IntegrasiController::class, 'downloadTemplate'])->name('download_template');
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
        Route::get('/laporan/traffic', [\App\Http\Controllers\Admin\LaporanController::class, 'traffic'])->name('laporan.traffic');
    });

    // 3. Komunikasi & Respon (Pengaduan)
    Route::middleware(['can:pengaduan'])->group(function () {
        Route::resource('pengaduan', \App\Http\Controllers\Admin\PengaduanController::class)->only(['index', 'show', 'destroy']);
        Route::post('/pengaduan/{id}/status', [\App\Http\Controllers\Admin\PengaduanController::class, 'updateStatus'])->name('pengaduan.status');
        Route::post('/pengaduan/{id}/reply', [\App\Http\Controllers\Admin\PengaduanController::class, 'reply'])->name('pengaduan.reply');
        
        Route::resource('rating', \App\Http\Controllers\Admin\RatingAdminController::class)->only(['index', 'destroy']);
    });

    // 4. Pengaturan & Master Data (Akses Semua Admin)
    Route::get('/profil', [\App\Http\Controllers\Admin\ProfilController::class, 'index'])->name('profil.index');
    Route::post('/profil/update', [\App\Http\Controllers\Admin\ProfilController::class, 'update'])->name('profil.update');
    Route::resource('produk', \App\Http\Controllers\Admin\ProdukController::class);
    Route::resource('survey', \App\Http\Controllers\Admin\SurveyController::class);
    Route::post('/survey/{id}/activate', [\App\Http\Controllers\Admin\SurveyController::class, 'activate'])->name('survey.activate');

    // 5. Advanced User Management (Super Admin ONLY)
    Route::middleware(['can:super'])->group(function () {
        Route::resource('manage-admin', \App\Http\Controllers\Admin\AdminManageController::class);
        Route::resource('manage-users', \App\Http\Controllers\Admin\UserManageController::class);
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
