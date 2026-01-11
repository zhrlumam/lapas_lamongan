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
Route::post('/kunjungan/store', [\App\Http\Controllers\KunjunganController::class, 'store'])->name('kunjungan.store');
Route::get('/kunjungan/tiket/{id}', [\App\Http\Controllers\KunjunganController::class, 'tiket'])->name('kunjungan.tiket');
Route::get('/kunjungan/cari', [\App\Http\Controllers\KunjunganController::class, 'cari'])->name('kunjungan.cari');
Route::get('/pengaduan', [HomeController::class, 'pengaduan'])->name('pengaduan');
Route::post('/pengaduan/store', [\App\Http\Controllers\PengaduanController::class, 'store'])->name('pengaduan.store');
Route::get('/pengaduan/sukses/{kode}', [\App\Http\Controllers\PengaduanController::class, 'sukses'])->name('pengaduan.sukses');
Route::post('/pengaduan/cari', [\App\Http\Controllers\PengaduanController::class, 'cari'])->name('pengaduan.cari');
Route::get('/pengaduan/lihat/{kode}', [\App\Http\Controllers\PengaduanController::class, 'detail'])->name('pengaduan.detail');
Route::post('/pengaduan/reply/{kode}', [\App\Http\Controllers\PengaduanController::class, 'reply'])->name('pengaduan.reply');

Route::get('/produk', [HomeController::class, 'produk'])->name('produk');
Route::post('/rating/store', [\App\Http\Controllers\RatingController::class, 'store'])->name('rating.store');

// Berita & Informasi
Route::prefix('berita')->name('berita.')->group(function () {
    Route::get('/', [BeritaController::class, 'index'])->name('index');
    Route::get('/{id}', [BeritaController::class, 'show'])->name('show');
});

// Integrasi (Login & Form Surat Jaminan)
// Integrasi (Login & Form Surat Jaminan)
Route::prefix('integrasi')->name('integrasi.')->group(function () {
    // 1. Halaman Login
    Route::get('/', [IntegrasiController::class, 'index'])->name('login');
    Route::post('/login', [IntegrasiController::class, 'handleLogin'])->name('login.post');
    
    // 2. Google Auth Routes
    Route::get('/google', [\App\Http\Controllers\GoogleController::class, 'redirectToGoogle'])->name('google');
    Route::get('/google/callback', [\App\Http\Controllers\GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

    // 3. Halaman Form & Proses (Setelah Login)
    // Untuk saat ini kita biarkan tanpa middleware 'auth' ketat di route jika controller handle session manual,
    // ATAU jika kita sudah pakai Auth::login() via Google, kita bisa pakai middleware 'auth'.
    // Namun IntegrasiController eksisting mungkin pakai session manual ('nik_penjamin').
    // Jadi biarkan 'web' middleware group (standard), logic login check ada di controller.
    
    Route::get('/form', [IntegrasiController::class, 'form'])->name('form');
    Route::post('/pdf', [IntegrasiController::class, 'generatePDF'])->name('pdf');
    Route::get('/dashboard', [IntegrasiController::class, 'dashboard'])->name('dashboard');
    Route::get('/complete-profile', [IntegrasiController::class, 'completeProfile'])->name('complete_profile');
    Route::post('/complete-profile', [IntegrasiController::class, 'storeProfile'])->name('complete_profile.post');
    Route::post('/logout', [IntegrasiController::class, 'logout'])->name('logout');
    Route::get('/download-template', [IntegrasiController::class, 'downloadTemplate'])->name('download_template');
});

Route::get('/admin/login', [\App\Http\Controllers\Admin\LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [\App\Http\Controllers\Admin\LoginController::class, 'login'])->name('admin.login.post');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/search', [\App\Http\Controllers\Admin\SearchController::class, 'globalSearch'])->name('search');
    Route::post('/logout', [\App\Http\Controllers\Admin\LoginController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [\App\Http\Controllers\Admin\LoginController::class, 'dashboard'])->name('dashboard');

    // Berita Management
    Route::resource('berita', \App\Http\Controllers\Admin\BeritaController::class);
    
    // Profil Management
    Route::get('/profil', [\App\Http\Controllers\Admin\ProfilController::class, 'index'])->name('profil.index');
    Route::post('/profil/update', [\App\Http\Controllers\Admin\ProfilController::class, 'update'])->name('profil.update');
    
    // Informasi Management
    Route::resource('informasi', \App\Http\Controllers\Admin\InformasiController::class);
    
    // Hunian Management
    Route::get('/hunian', [\App\Http\Controllers\Admin\HunianController::class, 'index'])->name('hunian.index');
    Route::post('/hunian', [\App\Http\Controllers\Admin\HunianController::class, 'store'])->name('hunian.store');

    // Produk Management
    Route::resource('produk', \App\Http\Controllers\Admin\ProdukController::class);

    // Galeri Management
    Route::resource('galeri', \App\Http\Controllers\Admin\GaleriController::class);

    // Pengaduan Management
    Route::get('/pengaduan', [\App\Http\Controllers\Admin\PengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/{id}', [\App\Http\Controllers\Admin\PengaduanController::class, 'show'])->name('pengaduan.show');
    Route::post('/pengaduan/{id}/status', [\App\Http\Controllers\Admin\PengaduanController::class, 'updateStatus'])->name('pengaduan.status');
    Route::post('/pengaduan/{id}/reply', [\App\Http\Controllers\Admin\PengaduanController::class, 'reply'])->name('pengaduan.reply');
    Route::delete('/pengaduan/{id}', [\App\Http\Controllers\Admin\PengaduanController::class, 'destroy'])->name('pengaduan.destroy');

    // Integrasi Management
    Route::get('/integrasi', [\App\Http\Controllers\Admin\IntegrasiController::class, 'index'])->name('integrasi.index');
    Route::get('/integrasi/{id}', [\App\Http\Controllers\Admin\IntegrasiController::class, 'show'])->name('integrasi.show');
    Route::post('/integrasi/{id}/status', [\App\Http\Controllers\Admin\IntegrasiController::class, 'updateStatus'])->name('integrasi.status');
    Route::post('/integrasi/bulk-status', [\App\Http\Controllers\Admin\IntegrasiController::class, 'bulkStatus'])->name('integrasi.bulk-status');
    Route::delete('/integrasi/{id}', [\App\Http\Controllers\Admin\IntegrasiController::class, 'destroy'])->name('integrasi.destroy');
    Route::post('/integrasi/bulk-delete', [\App\Http\Controllers\Admin\IntegrasiController::class, 'bulkDelete'])->name('integrasi.bulk-delete');

    // Kunjungan Management
    Route::get('/kunjungan', [\App\Http\Controllers\Admin\KunjunganController::class, 'index'])->name('kunjungan.index');
    Route::post('/kunjungan/{id}/status/{status}', [\App\Http\Controllers\Admin\KunjunganController::class, 'updateStatus'])->name('kunjungan.status');
    Route::delete('/kunjungan/bulk-delete', [\App\Http\Controllers\Admin\KunjunganController::class, 'bulkDelete'])->name('kunjungan.bulk-delete');
    Route::delete('/kunjungan/{id}', [\App\Http\Controllers\Admin\KunjunganController::class, 'destroy'])->name('kunjungan.destroy');

    // Laporan Management
    Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/wbp/pdf', [\App\Http\Controllers\Admin\LaporanController::class, 'exportWBP'])->name('laporan.wbp.pdf');
    Route::get('/laporan/wbp/excel', [\App\Http\Controllers\Admin\LaporanController::class, 'exportWBPExcel'])->name('laporan.wbp.excel');
    Route::get('/laporan/kunjungan/pdf', [\App\Http\Controllers\Admin\LaporanController::class, 'exportKunjungan'])->name('laporan.kunjungan.pdf');
    // Survey Management
    Route::get('/survey', [\App\Http\Controllers\Admin\SurveyController::class, 'index'])->name('survey.index');
    Route::post('/survey', [\App\Http\Controllers\Admin\SurveyController::class, 'store'])->name('survey.store');
    Route::put('/survey/{id}', [\App\Http\Controllers\Admin\SurveyController::class, 'update'])->name('survey.update');
    Route::post('/survey/{id}/activate', [\App\Http\Controllers\Admin\SurveyController::class, 'activate'])->name('survey.activate');
    Route::delete('/survey/{id}', [\App\Http\Controllers\Admin\SurveyController::class, 'destroy'])->name('survey.destroy');

    // Rating/Layanan Management
    Route::get('/rating', [\App\Http\Controllers\Admin\RatingAdminController::class, 'index'])->name('rating.index');
    Route::delete('/rating/{id}', [\App\Http\Controllers\Admin\RatingAdminController::class, 'destroy'])->name('rating.destroy');

    Route::get('/laporan/traffic', [\App\Http\Controllers\Admin\LaporanController::class, 'traffic'])->name('laporan.traffic');
    Route::get('/laporan/kunjungan/excel', [\App\Http\Controllers\Admin\LaporanController::class, 'exportKunjunganExcel'])->name('laporan.kunjungan.excel');
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
