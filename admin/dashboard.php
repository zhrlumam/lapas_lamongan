<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include "../config/koneksi.php"; 

// Keamanan: Cek Login
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

$role = $_SESSION['role'] ?? 'Admin';
$nama = $_SESSION['nama'] ?? 'Petugas';

// AMBIL DATA DENGAN PREPARED STATEMENTS (Lebih Aman & Cepat)
try {
    // 1. Statistik Hunian
    $stmt_hunian = $pdo->query("SELECT * FROM data_warga_binaan ORDER BY tanggal_update DESC LIMIT 1");
    $hunian = $stmt_hunian->fetch(PDO::FETCH_ASSOC) ?: [
        'tahanan' => 0, 
        'narapidana' => 0, 
        'total_penghuni' => 0, 
        'tanggal_update' => date('Y-m-d')
    ];

    // 2. Survey Kepuasan (Hanya ambil yang aktif)
    $stmt_survey = $pdo->query("SELECT * FROM survey_kepuasan WHERE is_active = 1 LIMIT 1");
    $survey = $stmt_survey->fetch(PDO::FETCH_ASSOC) ?: [
        'bulan' => 'Data Kosong',
        'skor_ikm' => '0',
        'skor_ipk' => '0',
        'keterangan' => '-'
    ];

    // 3. Aduan Terbaru (Limit 3 untuk efisiensi dashboard)
    $stmt_aduan = $pdo->query("SELECT id, nama_pelapor, judul_pengaduan FROM pengaduan ORDER BY created_at DESC LIMIT 3");
    $list_aduan = $stmt_aduan->fetchAll(PDO::FETCH_ASSOC);

    // 4. Hitung Total (Menggunakan COUNT(*) agar tidak membebani memori)
    $total_berita = $pdo->query("SELECT COUNT(*) FROM berita")->fetchColumn() ?: 0;
    $total_produk = $pdo->query("SELECT COUNT(*) FROM produk")->fetchColumn() ?: 0;
    $total_kunjungan = $pdo->query("SELECT COUNT(*) FROM kunjungan")->fetchColumn() ?: 0;

} catch (PDOException $e) {
    // Log error jika diperlukan: error_log($e->getMessage());
    $hunian = ['tahanan'=>0,'narapidana'=>0,'total_penghuni'=>0,'tanggal_update'=>'-']; 
    $survey = ['bulan'=>'-','skor_ikm'=>0,'skor_ipk'=>0,'keterangan'=>'-']; 
    $list_aduan = [];
    $total_berita = $total_produk = $total_kunjungan = 0;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Kontrol Terpadu | Lapas Lamongan</title>
      <link rel="icon" type="image/png" href="../assets/images/logo_imigrasi.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@400;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { imipas: '#07213D', dignity: '#EEBF63', platinum: '#E0E2E3' },
                    fontFamily: { titillium: ['Titillium Web', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #07213D; border-radius: 10px; }
    </style>
</head>
<body class="font-titillium bg-slate-50 overflow-hidden">

    <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden"></div>

    <div class="flex h-screen">
        <?php include 'layout/sidebar.php'; ?>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="bg-white border-b-4 border-dignity shadow-sm h-20 flex items-center justify-between px-6 z-20">
                <button onclick="toggleSidebar()" class="lg:hidden text-imipas p-2 hover:bg-slate-100 rounded-lg">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>

                <div class="hidden md:flex items-center gap-4">
                    <div class="bg-imipas/5 px-4 py-2 rounded-xl border border-imipas/10">
                        <span id="clock" class="text-imipas font-bold tracking-widest text-lg">00:00:00</span>
                    </div>
                    <div class="text-slate-400 font-bold text-[10px] uppercase tracking-tighter">
                        Pusat Kontrol Terpadu<br>Lapas Kelas IIB Lamongan
                    </div>
                </div>

                <div class="relative" x-data="{ profileMenu: false }">
                    <button @click="profileMenu = !profileMenu" @click.away="profileMenu = false" class="flex items-center gap-3 group">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-imipas leading-none"><?= htmlspecialchars($nama) ?></p>
                            <p class="text-[10px] text-amber-600 font-semibold uppercase mt-1 italic"><?= $role ?></p>
                        </div>
                        <div class="w-10 h-10 rounded-full border-2 border-dignity flex items-center justify-center bg-slate-100 group-hover:bg-dignity transition-colors">
                            <i class="fa-solid fa-user-tie text-imipas"></i>
                        </div>
                    </button>
                    <div x-show="profileMenu" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="absolute right-0 mt-3 w-48 bg-white border border-slate-100 shadow-xl rounded-xl py-2 z-30">
                        <a href="logout.php" class="block px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 font-bold italic">
                            <i class="fa-solid fa-power-off mr-2"></i> Keluar Aplikasi
                        </a>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 bg-slate-50 custom-scrollbar">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    
                    <div class="lg:col-span-8 space-y-6">
                        <div class="bg-white rounded-[2rem] p-8 border border-slate-200 shadow-sm relative overflow-hidden">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="font-bold text-imipas text-sm uppercase tracking-widest flex items-center gap-2">
                                    <i class="fa-solid fa-building-shield text-amber-500"></i> Kondisi Hunian Terkini
                                </h3>
                                <span class="text-[10px] bg-slate-100 px-3 py-1 rounded-full font-bold text-slate-500 uppercase italic">Update: <?= date('d M Y', strtotime($hunian['tanggal_update'])) ?></span>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-4 mb-8">
                                <div class="text-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                    <p class="text-2xl font-bold text-imipas"><?= number_format($hunian['tahanan']) ?></p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase">Tahanan</p>
                                </div>
                                <div class="text-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                    <p class="text-2xl font-bold text-imipas"><?= number_format($hunian['narapidana']) ?></p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase">Narapidana</p>
                                </div>
                                <div class="text-center p-4 bg-imipas rounded-2xl border-b-4 border-dignity shadow-lg shadow-imipas/20">
                                    <p class="text-2xl font-bold text-dignity"><?= number_format($hunian['total_penghuni']) ?></p>
                                    <p class="text-[10px] font-bold text-white/50 uppercase italic">Total Penghuni</p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="flex justify-between text-[10px] font-bold uppercase italic text-slate-500">
                                    <span>Kapasitas Optimal: 500 Orang</span>
                                    <span class="text-imipas"><?= round((($hunian['total_penghuni'])/500)*100) ?>%</span>
                                </div>
                                <div class="h-3 bg-slate-100 rounded-full overflow-hidden p-0.5 border border-slate-200">
                                    <?php $persen = min(round((($hunian['total_penghuni'])/500)*100), 100); ?>
                                    <div class="h-full bg-imipas rounded-full transition-all duration-1000" style="width: <?= $persen ?>%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-[2rem] p-8 border border-slate-200 shadow-sm">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="font-bold text-imipas text-sm uppercase tracking-widest flex items-center gap-2">
                                    <i class="fa-solid fa-comment-dots text-rose-500"></i> Aduan Terbaru
                                </h3>
                                <a href="kelola_pengaduan.php" class="text-xs font-bold text-amber-600 hover:underline">Lihat Semua &raquo;</a>
                            </div>
                            <div class="space-y-3">
                                <?php if(empty($list_aduan)): ?>
                                    <div class="text-center py-4 text-slate-400 italic text-xs">Belum ada pengaduan masuk.</div>
                                <?php else: ?>
                                    <?php foreach($list_aduan as $a): ?>
                                    <div onclick="window.location='kelola_pengaduan.php'" class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-transparent hover:border-dignity/30 transition-all cursor-pointer group">
                                        <div class="flex items-center gap-4">
                                            <div class="h-10 w-10 bg-white rounded-xl flex items-center justify-center text-rose-500 shadow-sm border border-slate-100 group-hover:bg-rose-500 group-hover:text-white transition-all">
                                                <i class="fa-solid fa-envelope-open-text"></i>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase"><?= htmlspecialchars($a['nama_pelapor']) ?></p>
                                                <p class="text-sm font-bold text-imipas leading-tight"><?= htmlspecialchars(mb_strimwidth($a['judul_pengaduan'], 0, 50, "...")) ?></p>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-circle-chevron-right text-slate-300 group-hover:text-imipas group-hover:translate-x-1 transition-all"></i>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-4 space-y-6">
                        <div class="bg-imipas rounded-[2rem] p-8 text-white shadow-xl relative overflow-hidden border-b-8 border-dignity">
                            <div class="relative z-10">
                                <p class="text-[10px] font-bold text-dignity uppercase tracking-[0.2em] mb-1">Survey Kepuasan</p>
                                <h3 class="text-xl font-bold italic mb-6"><?= $survey['bulan'] ?></h3>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-white/10 p-4 rounded-2xl border border-white/10 backdrop-blur-sm">
                                        <p class="text-[9px] font-bold text-slate-400 uppercase">Skor IKM</p>
                                        <p class="text-2xl font-bold text-dignity"><?= $survey['skor_ikm'] ?></p>
                                    </div>
                                    <div class="bg-white/10 p-4 rounded-2xl border border-white/10 backdrop-blur-sm">
                                        <p class="text-[9px] font-bold text-slate-400 uppercase">Skor IPK</p>
                                        <p class="text-2xl font-bold text-blue-400"><?= $survey['skor_ipk'] ?></p>
                                    </div>
                                </div>
                                <div class="mt-4 py-2 bg-dignity text-imipas rounded-xl text-center text-xs font-black uppercase tracking-wider">
                                    PREDIKAT: <?= $survey['keterangan'] ?>
                                </div>
                            </div>
                            <i class="fa-solid fa-award text-8xl text-white/5 absolute -right-4 -bottom-4"></i>
                        </div>

                        <div class="space-y-3">
                            <a href="kelola_berita.php" class="bg-white p-5 rounded-2xl border border-slate-200 flex items-center justify-between group hover:border-imipas transition-all shadow-sm">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 bg-slate-100 rounded-xl flex items-center justify-center text-imipas group-hover:bg-imipas group-hover:text-white transition-all text-xl">
                                        <i class="fa-solid fa-newspaper"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase">Total Berita</p>
                                        <p class="text-lg font-bold text-imipas"><?= $total_berita ?></p>
                                    </div>
                                </div>
                                <i class="fa-solid fa-arrow-up-right-from-square text-slate-300 group-hover:text-imipas transition-colors"></i>
                            </a>

                            <a href="kelola_produk.php" class="bg-white p-5 rounded-2xl border border-slate-200 flex items-center justify-between group hover:border-imipas transition-all shadow-sm">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 bg-slate-100 rounded-xl flex items-center justify-center text-imipas group-hover:bg-imipas group-hover:text-white transition-all text-xl">
                                        <i class="fa-solid fa-tags"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase">Katalog Produk</p>
                                        <p class="text-lg font-bold text-imipas"><?= $total_produk ?></p>
                                    </div>
                                </div>
                                <i class="fa-solid fa-arrow-up-right-from-square text-slate-300 group-hover:text-imipas transition-colors"></i>
                            </a>

                            <a href="kelola_kunjungan.php" class="bg-white p-5 rounded-2xl border border-slate-200 flex items-center justify-between group hover:border-imipas transition-all shadow-sm">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 bg-slate-100 rounded-xl flex items-center justify-center text-imipas group-hover:bg-imipas group-hover:text-white transition-all text-xl">
                                        <i class="fa-solid fa-calendar-check"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase">Total Antrean</p>
                                        <p class="text-lg font-bold text-imipas"><?= $total_kunjungan ?></p>
                                    </div>
                                </div>
                                <i class="fa-solid fa-arrow-up-right-from-square text-slate-300 group-hover:text-imipas transition-colors"></i>
                            </a>
                        </div>

                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Fungsi Sidebar Toggle yang Diperbaiki
        function toggleSidebar() {
            const sb = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if(sb) {
                sb.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }
        }

        // Jam Digital Realtime
        function updateClock() {
            const now = new Date();
            const h = now.getHours().toString().padStart(2, '0');
            const m = now.getMinutes().toString().padStart(2, '0');
            const s = now.getSeconds().toString().padStart(2, '0');
            const clockEl = document.getElementById('clock');
            if(clockEl) clockEl.innerText = h + ":" + m + ":" + s;
        }
        setInterval(updateClock, 1000); 
        updateClock();
    </script>
</body>
</html>