<?php
// 0. SET TIMEZONE & ERROR HANDLING
date_default_timezone_set('Asia/Jakarta');
error_reporting(E_ALL);
ini_set('display_errors', 0);

// 1. KEAMANAN: Header Proteksi Profesional
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: camera=(), microphone=(), geolocation=()");

include "config/koneksi.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panduan Layanan - Lapas Lamongan</title>
    <link rel="icon" type="image/png" href="assets/images/logo_imigrasi.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { imipas: { blue: '#07213D', gold: '#EEBF63' } },
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; scroll-behavior: smooth; }
        .step-card { transition: all 0.3s ease; }
        .step-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <?php include "layout/navbar.php"; ?>

    <!-- HERO SECTION -->
    <header class="bg-imipas-blue py-16 px-4 text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 pointer-events-none flex items-center justify-center">
            <i data-lucide="help-circle" class="w-64 h-64 text-white"></i>
        </div>
        <div class="max-w-4xl mx-auto relative z-10">
            <span class="inline-block bg-imipas-gold/20 text-imipas-gold text-[10px] font-bold px-4 py-1 rounded-full uppercase tracking-widest mb-4">Pusat Bantuan</span>
            <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-6 leading-tight">Panduan Mudah <br> Layanan Lapas Lamongan</h1>
            <p class="text-slate-300 text-sm md:text-lg max-w-2xl mx-auto leading-relaxed"> Kami menyederhanakan cara Anda menggunakan layanan kami agar lebih mudah dimengerti oleh semua kalangan, termasuk orang tua. </p>
        </div>
    </header>

    <!-- QUICK NAV -->
    <div class="sticky top-20 z-40 bg-white/80 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-center gap-4 md:gap-8 overflow-x-auto no-scrollbar">
            <a href="#kunjungan" class="whitespace-now80 text-[10px] font-bold uppercase text-imipas-blue hover:text-imipas-gold transition-colors">Kunjungan</a>
            <a href="#pengaduan" class="whitespace-nowrap text-[10px] font-bold uppercase text-imipas-blue hover:text-imipas-gold transition-colors">Pengaduan</a>
            <a href="#integrasi" class="whitespace-nowrap text-[10px] font-bold uppercase text-imipas-blue hover:text-imipas-gold transition-colors">Integrasi</a>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main class="max-w-5xl mx-auto px-4 py-16 space-y-24">

        <!-- 1. KUNJUNGAN ONLINE -->
        <section id="kunjungan" class="space-y-12">
            <div class="text-center">
                <div class="inline-flex p-4 rounded-3xl bg-blue-50 text-imipas-blue mb-6">
                    <i data-lucide="users" class="w-10 h-10"></i>
                </div>
                <h2 class="text-3xl font-bold text-slate-800">Cara Daftar Kunjungan</h2>
                <p class="text-slate-500 mt-2 max-w-lg mx-auto">Pastikan Anda sudah menyiapkan NIK/KTP sebelum mendaftar.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Step 1 -->
                <div class="step-card bg-white p-8 rounded-3xl shadow-sm border border-slate-100 relative group">
                    <span class="absolute -top-4 -left-4 w-12 h-12 bg-imipas-blue text-white rounded-2xl flex items-center justify-center font-bold text-xl shadow-lg ring-8 ring-slate-50">1</span>
                    <h3 class="text-lg font-bold text-slate-800 mb-3 mt-2">Buka Menu Kunjungan</h3>
                    <p class="text-sm text-slate-500 leading-relaxed italic">"Klik tombol 'Kunjungan' di bagian atas layar atau layar utama."</p>
                </div>
                <!-- Step 2 -->
                <div class="step-card bg-white p-8 rounded-3xl shadow-sm border border-slate-100 relative">
                    <span class="absolute -top-4 -left-4 w-12 h-12 bg-imipas-blue text-white rounded-2xl flex items-center justify-center font-bold text-xl shadow-lg ring-8 ring-slate-50">2</span>
                    <h3 class="text-lg font-bold text-slate-800 mb-3 mt-2">Isi Data Keluarga</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Masukkan Nama, NIK, dan pilih siapa yang ingin Anda tengok (Warga Binaaan).</p>
                </div>
                <!-- Step 3 -->
                <div class="step-card bg-white p-8 rounded-3xl shadow-sm border border-slate-100 relative">
                    <span class="absolute -top-4 -left-4 w-12 h-12 bg-imipas-blue text-white rounded-2xl flex items-center justify-center font-bold text-xl shadow-lg ring-8 ring-slate-50">3</span>
                    <h3 class="text-lg font-bold text-slate-800 mb-3 mt-2">Dapatkan Tiket</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Setelah klik daftar, Anda akan mendapat <span class="font-bold text-imipas-blue">Nomor Antrean</span>. Simpan atau bawa HP Anda saat ke Lapas.</p>
                </div>
            </div>
            <div class="bg-blue-50 p-6 rounded-3xl flex items-start gap-4">
                <i data-lucide="info" class="w-6 h-6 text-imipas-blue flex-shrink-0 mt-1"></i>
                <div class="text-sm text-imipas-blue leading-relaxed">
                    <p class="font-bold mb-1 uppercase tracking-wider text-[10px]">Penting Untuk Diingat :</p>
                    <p>Mohon datang 15 menit sebelum sesi dimulai. Jangan lupa membawa KTP asli untuk verifikasi di loket.</p>
                </div>
            </div>
        </section>

        <!-- 2. PENGADUAN -->
        <section id="pengaduan" class="space-y-12 pt-12">
            <div class="text-center">
                <div class="inline-flex p-4 rounded-3xl bg-amber-50 text-amber-600 mb-6">
                    <i data-lucide="message-square" class="w-10 h-10"></i>
                </div>
                <h2 class="text-3xl font-bold text-slate-800">Cara Melapor (Pengaduan)</h2>
                <p class="text-slate-500 mt-2 max-w-lg mx-auto">Kami menjamin kerahasiaan identitas Anda saat melapor.</p>
            </div>

            <div class="max-w-4xl mx-auto space-y-6">
                <!-- Step 1 -->
                <div class="flex items-start gap-6 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 flex-shrink-0 font-extrabold text-2xl">01</div>
                    <div>
                        <h4 class="font-bold text-slate-800 mb-1">Ketikan Keluhan Anda</h4>
                        <p class="text-sm text-slate-500">Tuliskan apa yang ingin Anda laporkan, jangan lupa lampirkan foto bukti jika ada agar cepat diproses.</p>
                    </div>
                </div>
                <!-- Step 2 -->
                <div class="flex items-start gap-6 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 flex-shrink-0 font-extrabold text-2xl">02</div>
                    <div>
                        <h4 class="font-bold text-slate-800 mb-1">Simpan Nomor WA</h4>
                        <p class="text-sm text-slate-500">Gunakan nomor WhatsApp Anda yang aktif agar tim kami bisa menghubungi Anda kembali.</p>
                    </div>
                </div>
                <!-- Step 3 -->
                <div class="flex items-start gap-6 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 flex-shrink-0 font-extrabold text-2xl">03</div>
                    <div>
                        <h4 class="font-bold text-slate-800 mb-1">Cek Status Laporan</h4>
                        <p class="text-sm text-slate-500">Klik 'Lacak Pengaduan', masukkan nomor WA Anda, dan Anda bisa <span class="font-bold text-imipas-blue">mengobrol/chatting</span> langsung dengan petugas.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. INTEGRASI -->
        <section id="integrasi" class="space-y-12 pt-12 bg-white rounded-[40px] p-8 md:p-16 border border-slate-100 shadow-2xl">
            <div class="text-center">
                <div class="inline-flex p-4 rounded-3xl bg-slate-50 text-slate-800 mb-6">
                    <i data-lucide="file-text" class="w-10 h-10"></i>
                </div>
                <h2 class="text-3xl font-bold text-slate-800">Cara Urus Surat Jaminan (Integrasi)</h2>
                <p class="text-slate-500 mt-2 max-w-lg mx-auto">Sekarang tidak perlu datang jauh-jauh ke Lapas hanya untuk ambil formulir.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="space-y-8">
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-imipas-blue text-white flex items-center justify-center flex-shrink-0 font-bold">A</div>
                        <div>
                            <h5 class="font-bold text-slate-800 mb-2 underline decoration-imipas-gold decoration-4 underline-offset-4">Masuk Pakai Google</h5>
                            <p class="text-sm text-slate-500">Klik lambang Google yang berwarna-warni. Ini cara tercepat dan paling aman untuk login.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-imipas-blue text-white flex items-center justify-center flex-shrink-0 font-bold">B</div>
                        <div>
                            <h5 class="font-bold text-slate-800 mb-2">Pilih Jenis Surat</h5>
                            <p class="text-sm text-slate-500 font-medium">Contoh: 'Pembebasan Bersyarat' atau 'Cuti Bersyarat'.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-imipas-blue text-white flex items-center justify-center flex-shrink-0 font-bold">C</div>
                        <div>
                            <h5 class="font-bold text-slate-800 mb-2">Unduh & Print</h5>
                            <p class="text-sm text-slate-500">Sistem akan membuatkan surat otomatis (PDF). Anda tinggal download, cetak, dan tanda tangani di rumah.</p>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 p-8 rounded-3xl flex flex-col items-center justify-center text-center space-y-4">
                    <img src="assets/images/logo_imigrasi.png" class="h-20 w-auto opacity-80" alt="Logo">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest leading-loose">Layanan ini gratis tanpa dipungut biaya apapun sesuai komitmen kami melayani sepenuh hati.</p>
                </div>
            </div>
        </section>

    </main>

    <!-- FOOTER HELPER -->
    <div class="bg-imipas-blue py-12 px-4 text-center">
        <h3 class="text-white text-xl font-bold mb-4">Masih bingung? Hubungi kami langsung</h3>
        <a href="https://wa.me/628113405959" target="_blank" class="inline-flex items-center gap-3 bg-green-500 text-white px-8 py-4 rounded-2xl font-bold hover:bg-green-600 transition-all shadow-xl">
            <i class="fa-brands fa-whatsapp text-2xl"></i>
            Tanya Lewat WhatsApp
        </a>
    </div>

    <?php include "layout/footer.php"; ?>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
