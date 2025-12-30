<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Logika Hapus Satuan (Tetap Sama)
if (isset($_GET['hapus'])) {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['hapus']);
    mysqli_query($conn, "DELETE FROM tanggapan WHERE pengaduan_id = '$id_hapus'");
    mysqli_query($conn, "DELETE FROM pengaduan WHERE id = '$id_hapus'");
    header("Location: kelola_pengaduan.php");
    exit;
}

// Statistik Sesuai ENUM Database
$stat_masuk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pengaduan WHERE status='Masuk'"))['total'];
$stat_proses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pengaduan WHERE status='Sedang Diproses'"))['total'];
$stat_selesai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pengaduan WHERE status='Selesai'"))['total'];
$stat_ditolak = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pengaduan WHERE status='Ditolak'"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pengaduan | Lapas Lamongan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        table.dataTable { border: none !important; border-radius: 20px; overflow: hidden; margin-top: 20px !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current { 
            background: #2563eb !important; color: white !important; border: none; border-radius: 10px; 
        }
        .dataTables_filter input { padding: 8px 16px; border-radius: 12px; border: 1px solid #e2e8f0; outline: none; margin-bottom: 20px; }
    </style>
</head>
<body class="text-slate-700">
    <div class="min-h-screen flex">
        <?php include "layout/menu_admin.php"; ?>
        
        <div class="flex-1 p-8">
            <header class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-2xl font-black uppercase italic tracking-tight">Kelola <span class="text-blue-600">Pengaduan</span></h1>
                    <p class="text-[10px] font-bold text-slate-400 tracking-[0.3em] uppercase">Lapas Kelas IIB Lamongan</p>
                </div>
                <button onclick="location.reload()" class="p-3 bg-white shadow-sm border border-slate-200 rounded-2xl text-slate-500 hover:text-blue-600 transition-all">
                    <i class="fa-solid fa-rotate"></i>
                </button>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 flex items-center gap-5">
                    <div class="w-12 h-12 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center shadow-inner"><i class="fa-solid fa-envelope"></i></div>
                    <div><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Masuk</p><h3 class="text-2xl font-black"><?= $stat_masuk ?></h3></div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 flex items-center gap-5">
                    <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center shadow-inner"><i class="fa-solid fa-spinner"></i></div>
                    <div><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Proses</p><h3 class="text-2xl font-black"><?= $stat_proses ?></h3></div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 flex items-center gap-5">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center shadow-inner"><i class="fa-solid fa-check-double"></i></div>
                    <div><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Selesai</p><h3 class="text-2xl font-black"><?= $stat_selesai ?></h3></div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 flex items-center gap-5">
                    <div class="w-12 h-12 bg-slate-100 text-slate-600 rounded-2xl flex items-center justify-center shadow-inner"><i class="fa-solid fa-ban"></i></div>
                    <div><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ditolak</p><h3 class="text-2xl font-black"><?= $stat_ditolak ?></h3></div>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-slate-100 p-8">
                <table id="tabelAduan" class="display w-full">
                    <thead>
                        <tr class="text-left text-[10px] uppercase tracking-widest text-slate-400 border-b">
                            <th class="pb-4">Tanggal</th>
                            <th class="pb-4">Judul & Kategori</th>
                            <th class="pb-4">Pengadu & Kontak</th>
                            <th class="pb-4">Status</th>
                            <th class="pb-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <?php
                        $q = mysqli_query($conn, "SELECT p.*, k.nama_kategori FROM pengaduan p LEFT JOIN kategori_pengaduan k ON p.kategori_id = k.id ORDER BY p.created_at DESC");
                        while($row = mysqli_fetch_assoc($q)):
                            $s = $row['status'];
                            $badge = ($s == 'Masuk') ? 'bg-red-100 text-red-600 border-red-200' : 
                                     (($s == 'Sedang Diproses') ? 'bg-amber-100 text-amber-600 border-amber-200' : 
                                     (($s == 'Selesai') ? 'bg-green-100 text-green-600 border-green-200' : 'bg-slate-100 text-slate-600 border-slate-200'));
                        ?>
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-all">
                            <td class="py-6 font-mono text-[10px] text-slate-400 italic"><?= date('d/m/y H:i', strtotime($row['created_at'])) ?></td>
                            <td class="py-6">
                                <p class="font-bold text-slate-800 leading-tight uppercase mb-1">"<?= htmlspecialchars($row['judul_pengaduan']) ?>"</p>
                                <span class="text-[9px] bg-blue-50 text-blue-500 px-2 py-0.5 rounded-md font-black uppercase tracking-tighter border border-blue-100">
                                    <?= $row['nama_kategori'] ?>
                                </span>
                            </td>
                            <td class="py-6">
                                <div class="flex flex-col gap-1">
                                    <p class="font-black text-slate-700 text-xs uppercase flex items-center gap-2">
                                        <i class="fa-solid fa-user text-[10px] text-slate-300"></i> <?= htmlspecialchars($row['nama_pelapor']) ?>
                                    </p>
                                    <p class="text-[10px] font-semibold text-emerald-600 flex items-center gap-2 italic">
                                        <i class="fa-brands fa-whatsapp text-emerald-400"></i> <?= $row['kontak_pelapor'] ?>
                                    </p>
                                    <p class="text-[9px] font-medium text-slate-400 flex items-center gap-2">
                                        <i class="fa-solid fa-envelope text-slate-300"></i> <?= htmlspecialchars($row['email_pelapor']) ?>
                                    </p>
                                </div>
                            </td>
                            <td class="py-6">
                                <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase border <?= $badge ?> shadow-sm"><?= $s ?></span>
                            </td>
                            <td class="py-6 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="admin_balas.php?id=<?= $row['id'] ?>" class="w-10 h-10 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-100" title="Proses / Balas">
                                        <i class="fa-solid fa-reply-all text-xs"></i>
                                    </a>
                                    <button onclick="hapusData(<?= $row['id'] ?>)" class="w-10 h-10 flex items-center justify-center bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm border border-red-100" title="Hapus">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() { 
            $('#tabelAduan').DataTable({ 
                responsive: true,
                "order": [[0, "desc"]], // Urutkan berdasarkan tanggal terbaru
                "language": {
                    "search": "Cari Laporan:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ aduan",
                    "paginate": {
                        "previous": "<",
                        "next": ">"
                    }
                }
            }); 
        });

        function hapusData(id) {
            Swal.fire({ 
                title: 'Hapus Laporan?', 
                text: "Seluruh data tanggapan terkait juga akan terhapus!", 
                icon: 'warning', 
                showCancelButton: true, 
                confirmButtonColor: '#ef4444', 
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                borderRadius: '20px'
            }).then((result) => { 
                if (result.isConfirmed) window.location.href = 'kelola_pengaduan.php?hapus=' + id; 
            });
        }
    </script>
</body>
</html>