<?php
session_start();
include "../config/koneksi.php";

// Keamanan: Cek Login
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

include "layout/role_check.php";
check_role(['Registrasi']);

$admin_name = $_SESSION['nama'] ?? 'Admin';

// Query Statistik
$stats = $pdo->query("SELECT 
    COUNT(*) as total,
    AVG(rating) as rata_rata,
    SUM(CASE WHEN layanan = 'Kunjungan' THEN 1 ELSE 0 END) as kunjungan,
    SUM(CASE WHEN layanan = 'Pengaduan' THEN 1 ELSE 0 END) as pengaduan,
    SUM(CASE WHEN layanan = 'Integrasi' THEN 1 ELSE 0 END) as integrasi
FROM penilaian_layanan")->fetch(PDO::FETCH_ASSOC);

// Query Data
$penilaian = $pdo->query("SELECT * FROM penilaian_layanan ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

function tgl_indo($tanggal) {
    return date('d/m/Y H:i', strtotime($tanggal));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Penilaian Layanan | Lapas Lamongan</title>
    <link rel="icon" type="image/png" href="../assets/images/logo_imigrasi.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@400;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { imipas: '#07213D', dignity: '#EEBF63' },
                    fontFamily: { titillium: ['Titillium Web', 'sans-serif'] }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden font-titillium">

    <?php include 'layout/sidebar.php'; ?>

    <div class="flex-1 flex flex-col min-w-0">
        <header class="bg-white border-b-4 border-dignity shadow-sm h-20 flex items-center justify-between px-6 z-20">
            <h1 class="text-xl font-bold text-imipas uppercase tracking-wider">Penilaian Layanan</h1>
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <p class="text-xs font-bold text-slate-400 uppercase"><?= htmlspecialchars($admin_name) ?></p>
                    <p class="text-[10px] text-amber-600 uppercase italic">Administrator</p>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Total Masuk</p>
                    <p class="text-3xl font-bold text-imipas"><?= $stats['total'] ?></p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Rating Rata-rata</p>
                    <p class="text-3xl font-bold text-amber-500"><?= number_format((float)$stats['rata_rata'], 1) ?> <span class="text-sm">/ 5.0</span></p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Layanan Populer</p>
                    <p class="text-lg font-bold text-slate-700">Kunjungan (<?= $stats['kunjungan'] ?>)</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Pengaduan/Integrasi</p>
                    <p class="text-lg font-bold text-slate-700"><?= $stats['pengaduan'] ?> / <?= $stats['integrasi'] ?></p>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                            <tr>
                                <th class="p-5 w-16 text-center">#</th>
                                <th class="p-5">Waktu</th>
                                <th class="p-5">Layanan</th>
                                <th class="p-5">Rating</th>
                                <th class="p-5">Ulasan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <?php foreach ($penilaian as $i => $row): ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-5 text-center text-slate-400"><?= $i+1 ?></td>
                                <td class="p-5 font-bold text-slate-600 whitespace-nowrap"><?= tgl_indo($row['created_at']) ?></td>
                                <td class="p-5">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase
                                        <?= $row['layanan'] == 'Kunjungan' ? 'bg-blue-100 text-blue-700' : 
                                           ($row['layanan'] == 'Pengaduan' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') ?>">
                                        <?= $row['layanan'] ?>
                                    </span>
                                </td>
                                <td class="p-5 text-amber-400 font-bold">
                                    <?php for($s=1; $s<=5; $s++): ?>
                                        <i class="fa-<?= $s <= $row['rating'] ? 'solid' : 'regular' ?> fa-star"></i>
                                    <?php endfor; ?>
                                </td>
                                <td class="p-5 text-slate-500 italic"><?= htmlspecialchars($row['ulasan'] ?: '-') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
