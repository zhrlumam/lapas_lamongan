<?php
// Proteksi: Pastikan session menggunakan kunci yang benar
// Gunakan admin_id atau admin sesuai session_start() di dashboard Anda
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['admin'])) { exit; }

$current_page = basename($_SERVER['PHP_SELF']);
?>

<div id="sidebarOverlay" class="fixed inset-0 bg-black/60 z-30 hidden lg:hidden" onclick="toggleSidebar()"></div>

<aside id="sidebar" class="fixed lg:static z-40 inset-y-0 left-0 w-72 bg-[#07213D] text-slate-300 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col shadow-2xl border-r border-white/5">
    
    <div class="p-6 border-b border-white/10 bg-[#05192E]">
        <div class="flex items-center gap-4">
            <div class="bg-white p-1 rounded-lg">
                <img src="../assets/images/logolap.png" class="h-10 w-auto" alt="Logo">
            </div>
            <div class="flex flex-col">
                <span class="text-white font-bold text-sm tracking-widest uppercase leading-tight">Lapas Lamongan</span>
                <span class="text-[10px] text-[#EEBF63] font-semibold uppercase tracking-wider mt-1 italic">Kemenimipas RI</span>
            </div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto p-4 space-y-1 text-sm custom-scrollbar">
        
        <?php $role = $_SESSION['role'] ?? 'Petugas'; ?>

        <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($current_page == 'dashboard.php') ? 'bg-[#EEBF63] text-[#07213D] font-bold shadow-lg' : 'hover:bg-white/10 hover:text-white' ?>">
            <i class="fa-solid fa-gauge-high w-6 text-center text-lg"></i> 
            <span>Dashboard Utama</span>
        </a>

        <!-- DATA STRATEGIS (Registrasi) -->
        <?php if ($role == 'Super Admin' || $role == 'Registrasi'): ?>
        <p class="text-[10px] font-bold text-slate-500 uppercase px-4 pt-6 pb-2 tracking-[0.2em]">Data Strategis</p>

        <a href="kelola_hunian.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($current_page == 'kelola_hunian.php') ? 'bg-slate-800 text-white border-l-4 border-blue-400' : 'hover:bg-white/5 hover:text-white' ?>">
            <i class="fa-solid fa-users-viewfinder w-6 text-center text-blue-400"></i> 
            <span>Data Hunian WBP</span>
        </a>

        <a href="kelola_survey.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($current_page == 'kelola_survey.php') ? 'bg-slate-800 text-white border-l-4 border-emerald-500' : 'hover:bg-white/5 hover:text-white' ?>">
            <i class="fa-solid fa-chart-simple w-6 text-center text-emerald-400"></i> 
            <span>Survey IKM / IPK</span>
        </a>

        <a href="kelola_penilaian.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($current_page == 'kelola_penilaian.php') ? 'bg-slate-800 text-white border-l-4 border-amber-500' : 'hover:bg-white/5 hover:text-white' ?>">
            <i class="fa-solid fa-star w-6 text-center text-amber-400"></i> 
            <span>Penilaian Layanan</span>
        </a>
        <?php endif; ?>

        <!-- LAYANAN PUBLIK (Registrasi & Pengaduan) -->
        <?php if ($role == 'Super Admin' || $role == 'Registrasi' || $role == 'Pengaduan'): ?>
        <p class="text-[10px] font-bold text-slate-500 uppercase px-4 pt-6 pb-2 tracking-[0.2em]">Layanan Publik</p>

        <?php if ($role == 'Super Admin' || $role == 'Registrasi'): ?>
        <a href="kelola_kunjungan.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($current_page == 'kelola_kunjungan.php') ? 'bg-slate-800 text-white border-l-4 border-indigo-500' : 'hover:bg-white/5 hover:text-white' ?>">
            <i class="fa-solid fa-id-badge w-6 text-center text-indigo-400"></i> 
            <span>Antrean Kunjungan</span>
        </a>

        <a href="kelola_integrasi.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($current_page == 'kelola_integrasi.php') ? 'bg-slate-800 text-white border-l-4 border-cyan-500' : 'hover:bg-white/5 hover:text-white' ?>">
            <i class="fa-solid fa-file-signature w-6 text-center text-cyan-400"></i> 
            <span>Hak Integrasi (PB/CB)</span>
        </a>
        <?php endif; ?>

        <?php if ($role == 'Super Admin' || $role == 'Pengaduan'): ?>
        <a href="kelola_pengaduan.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($current_page == 'kelola_pengaduan.php') ? 'bg-slate-800 text-white border-l-4 border-rose-500' : 'hover:bg-white/5 hover:text-white' ?>">
            <i class="fa-solid fa-bullhorn w-6 text-center text-rose-400"></i> 
            <span>Aduan Masyarakat</span>
        </a>
        <?php endif; ?>
        <?php endif; ?>

        <!-- MANAJEMEN KONTEN (Humas) -->
        <?php if ($role == 'Super Admin' || $role == 'Humas'): ?>
        <p class="text-[10px] font-bold text-slate-500 uppercase px-4 pt-6 pb-2 tracking-[0.2em]">Manajemen Konten</p>

        <a href="kelola_berita.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($current_page == 'kelola_berita.php') ? 'bg-slate-800 text-white' : 'hover:bg-white/5 hover:text-white' ?>">
            <i class="fa-solid fa-newspaper w-6 text-center text-amber-400"></i> 
            <span>Berita & Artikel</span>
        </a>

        <a href="kelola_informasi.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($current_page == 'kelola_informasi.php') ? 'bg-slate-800 text-white' : 'hover:bg-white/5 hover:text-white' ?>">
            <i class="fa-solid fa-circle-info w-6 text-center text-sky-400"></i> 
            <span>Pengumuman</span>
        </a>

        <a href="kelola_produk.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($current_page == 'kelola_produk.php') ? 'bg-slate-800 text-white' : 'hover:bg-white/5 hover:text-white' ?>">
            <i class="fa-solid fa-basket-shopping w-6 text-center text-emerald-400"></i> 
            <span>Produk Unggulan</span>
        </a>

        <a href="kelola_profile.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= ($current_page == 'kelola_profile.php') ? 'bg-slate-800 text-white' : 'hover:bg-white/5 hover:text-white' ?>">
            <i class="fa-solid fa-building-user w-6 text-center text-slate-400"></i> 
            <span>Profil Instansi</span>
        </a>
        <?php endif; ?>

        <div class="pt-10 pb-10">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-rose-400 hover:bg-rose-500 hover:text-white transition-all border border-rose-500/20 group">
                <i class="fa-solid fa-power-off w-6 text-center group-hover:rotate-90 transition-transform"></i> 
                <span class="font-bold">Keluar Aplikasi</span>
            </a>
        </div>
    </nav>
</aside>

<style>
    /* Styling scrollbar agar cantik di sidebar gelap */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(238, 191, 99, 0.2); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(238, 191, 99, 0.5); }
</style>

<script>
    // Fungsi untuk membuka/tutup sidebar di mobile
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
</script>