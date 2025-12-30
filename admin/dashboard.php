<?php
session_start();
include "../config/koneksi.php";

// 1. CEK LOGIN
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Ambil data session (berupa string username)
$admin_name = $_SESSION['admin'];

// 2. AMBIL DATA STATISTIK (Dibuat aman dengan pengecekan query)
$q_berita = mysqli_query($conn, "SELECT COUNT(*) as total FROM berita");
$total_berita = ($q_berita) ? mysqli_fetch_assoc($q_berita)['total'] : 0;

$q_produk = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk");
$total_produk = ($q_produk) ? mysqli_fetch_assoc($q_produk)['total'] : 0;

$q_kunjungan = mysqli_query($conn, "SELECT COUNT(*) as total FROM kunjungan");
$total_kunjungan = ($q_kunjungan) ? mysqli_fetch_assoc($q_kunjungan)['total'] : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin | Lapas Lamongan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-700">

<div class="min-h-screen flex">
    <div id="overlay" class="fixed inset-0 bg-black/50 z-30 hidden md:hidden" onclick="toggleSidebar()"></div>

    <?php include "layout/menu_admin.php"; ?>

    <main class="flex-1 flex flex-col min-w-0">
        
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center sticky top-0 z-20">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="md:hidden text-slate-600 p-2 hover:bg-slate-100 rounded-lg">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>
                <h1 class="text-xl font-bold text-slate-800 hidden sm:block">Ringkasan Data</h1>
            </div>

            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-slate-900"><?= htmlspecialchars($admin_name) ?></p>
                    <p class="text-[9px] text-green-600 font-bold uppercase tracking-widest">Sistem Online</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-slate-100 border flex items-center justify-center text-slate-500 text-lg">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
            </div>
        </header>

        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Berita</p>
                    <div class="flex justify-between items-end">
                        <h3 class="text-2xl font-black text-slate-800"><?= $total_berita ?></h3>
                        <i class="fa-solid fa-newspaper text-green-500 opacity-20 text-2xl"></i>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Produk</p>
                    <div class="flex justify-between items-end">
                        <h3 class="text-2xl font-black text-slate-800"><?= $total_produk ?></h3>
                        <i class="fa-solid fa-box text-blue-500 opacity-20 text-2xl"></i>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm ring-2 ring-teal-500/10">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kunjungan</p>
                    <div class="flex justify-between items-end">
                        <h3 class="text-2xl font-black text-slate-800"><?= $total_kunjungan ?></h3>
                        <i class="fa-solid fa-users text-teal-500 opacity-20 text-2xl"></i>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Integrasi</p>
                    <div class="flex justify-between items-end">
                        <h3 class="text-2xl font-black text-slate-800">0</h3>
                        <i class="fa-solid fa-scale-balanced text-yellow-500 opacity-20 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 rounded-[2rem] p-8 md:p-12 text-white relative overflow-hidden shadow-xl">
                <div class="relative z-10 max-w-2xl">
                    <h2 class="text-3xl font-extrabold mb-4">Pusat Kendali Layanan</h2>
                    <p class="text-slate-400 mb-8 leading-relaxed">
                        Selamat datang di sistem manajemen informasi Lapas Kelas IIB Lamongan. Kelola data pendaftaran kunjungan, pengaduan masyarakat, dan konten informasi publik dalam satu pintu.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="kelola_kunjungan.php" class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-xl font-bold text-sm transition flex items-center gap-2">
                            <i class="fa-solid fa-id-card"></i> Cek Kunjungan
                        </a>
                        <a href="../index.php" target="_blank" class="bg-slate-800 hover:bg-slate-700 px-6 py-3 rounded-xl font-bold text-sm transition border border-slate-700">
                            Lihat Website
                        </a>
                    </div>
                </div>
                <i class="fa-solid fa-shield-halved absolute -right-10 -bottom-10 text-[15rem] text-white opacity-5 pointer-events-none"></i>
            </div>
        </div>
    </main>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    if (sidebar) sidebar.classList.toggle('-translate-x-full');
    if (overlay) overlay.classList.toggle('hidden');
}
</script>

</body>
</html>