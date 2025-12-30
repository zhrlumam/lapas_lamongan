<?php
// Mendapatkan nama file yang sedang dibuka
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-30 hidden transition-opacity duration-300 md:hidden"
    onclick="toggleSidebar()"></div>

<aside id="sidebar"
    class="fixed md:static z-40 inset-y-0 left-0 w-64 bg-slate-900 text-slate-300 transform -translate-x-full md:translate-x-0 transition duration-300 flex flex-col shadow-2xl">
    
    <div class="p-6 border-b border-slate-800 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <img src="../assets/images/logolap.png" class="h-8" alt="Logo">
            <div class="flex flex-col">
                <span class="font-bold text-white tracking-wider uppercase text-[10px]">Panel Kontrol</span>
                <span class="text-[9px] text-slate-500 uppercase">Lapas Lamongan</span>
            </div>
        </div>
        <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-white">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
    </div>

    <nav class="flex-1 p-4 space-y-1 text-sm overflow-y-auto">
        <p class="text-[10px] font-bold text-slate-500 uppercase px-4 mb-2 tracking-widest">Utama</p>
        <a href="dashboard.php"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($current_page == 'dashboard.php') ? 'bg-slate-800 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' ?>">
            <i class="fa-solid fa-chart-line w-5 text-indigo-400"></i> Dashboard Utama
        </a>

        <p class="text-[10px] font-bold text-slate-500 uppercase px-4 mt-6 mb-2 tracking-widest">Konten Website</p>
        
        <a href="kelola_berita.php"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($current_page == 'kelola_berita.php') ? 'bg-slate-800 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' ?>">
            <i class="fa-solid fa-newspaper w-5 text-green-400"></i> Kelola Berita
        </a>

        <a href="kelola_informasi.php"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($current_page == 'kelola_informasi.php') ? 'bg-slate-800 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' ?>">
            <i class="fa-solid fa-bullhorn w-5 text-amber-400"></i> Pengumuman & Info
        </a>

        <a href="kelola_produk.php"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($current_page == 'kelola_produk.php') ? 'bg-slate-800 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' ?>">
            <i class="fa-solid fa-tags w-5 text-blue-400"></i> Produk WBP
        </a>

        <a href="kelola_profile.php"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($current_page == 'kelola_profile.php') ? 'bg-slate-800 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' ?>">
            <i class="fa-solid fa-building-shield w-5 text-rose-400"></i> Profil Instansi
        </a>

        <p class="text-[10px] font-bold text-slate-500 uppercase px-4 mt-6 mb-2 tracking-widest">Layanan Publik</p>

        <a href="kelola_kunjungan.php"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($current_page == 'kelola_kunjungan.php') ? 'bg-slate-800 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' ?>">
            <i class="fa-solid fa-id-card-clip w-5 text-teal-400"></i> Kunjungan WBP
        </a>

        <a href="kelola_integrasi.php"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($current_page == 'kelola_integrasi.php') ? 'bg-slate-800 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' ?>">
            <i class="fa-solid fa-scale-balanced w-5 text-yellow-400"></i> Hak Integrasi
        </a>

        <a href="kelola_pengaduan.php"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition <?= ($current_page == 'kelola_pengaduan.php') ? 'bg-slate-800 text-white shadow-lg' : 'hover:bg-slate-800 hover:text-white' ?>">
            <i class="fa-solid fa-comment-dots w-5 text-red-400"></i> Pengaduan Masyarakat
        </a>

        <p class="text-[10px] font-bold text-slate-500 uppercase px-4 mt-6 mb-2 tracking-widest">Sistem</p>
        <a href="logout.php"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-400 hover:bg-red-500/10 transition group">
            <i class="fa-solid fa-power-off w-5 group-hover:animate-pulse"></i> Keluar Aplikasi
        </a>
    </nav>
</aside>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // 1. GLOBAL NOTIFICATION
    <?php if (isset($_SESSION['alert'])): ?>
        Swal.fire({
            icon: '<?= $_SESSION['alert']['type'] ?>',
            title: '<?= $_SESSION['alert']['title'] ?>',
            text: '<?= $_SESSION['alert']['msg'] ?>',
            confirmButtonColor: '#07213D',
            timer: 3000,
            timerProgressBar: true
        });
    <?php unset($_SESSION['alert']); endif; ?>

    // 2. GLOBAL CONFIRMATION DELETE
    document.addEventListener('click', function (e) {
        if (e.target.closest('.btn-hapus')) {
            e.preventDefault();
            const link = e.target.closest('.btn-hapus').getAttribute('href');
            
            Swal.fire({
                title: 'Hapus Data?',
                text: "Tindakan ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = link;
                }
            });
        }
    });
</script>