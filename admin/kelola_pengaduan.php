<?php
session_start();
include "../config/koneksi.php";

// 1. SINKRONISASI KONEKSI
if (!isset($conn) && isset($pdo)) {
    $conn = $pdo;
}

// 2. FIX SESSION (Pastikan konsisten dengan login_admin.php)
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

include "layout/role_check.php";
check_role(['Pengaduan']);

$admin_name = $_SESSION['nama'] ?? 'Administrator';

// 3. LOGIKA HAPUS DATA & FILE FISIK
if (isset($_GET['hapus'])) {
    if (!isset($_GET['token']) || $_GET['token'] !== $_SESSION['csrf_token']) {
        die("Akses ditolak: Token keamanan tidak valid.");
    }
    $id_hapus = (int) $_GET['hapus'];

    try {
        // Ambil nama file sebelum dihapus dari DB
        $stmt_file = $conn->prepare("SELECT foto_bukti FROM pengaduan WHERE id = ?");
        $stmt_file->execute([$id_hapus]);
        $data_foto = $stmt_file->fetch(PDO::FETCH_ASSOC);

        $conn->beginTransaction();

        // Hapus tanggapan terkait
        $stmt1 = $conn->prepare("DELETE FROM tanggapan WHERE pengaduan_id = ?");
        $stmt1->execute([$id_hapus]);

        // Hapus pengaduan
        $stmt2 = $conn->prepare("DELETE FROM pengaduan WHERE id = ?");
        $stmt2->execute([$id_hapus]);

        $conn->commit();

        // Hapus file fisik jika ada di folder uploads
        if (!empty($data_foto['foto_bukti'])) {
            $path_file = "../uploads/" . $data_foto['foto_bukti'];
            if (file_exists($path_file)) {
                unlink($path_file);
            }
        }

        $_SESSION['alert'] = ['type' => 'success', 'title' => 'TERHAPUS!', 'msg' => 'Laporan dan file bukti berhasil dihapus.'];
    } catch (Exception $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        $_SESSION['alert'] = ['type' => 'error', 'title' => 'GAGAL!', 'msg' => 'Gagal menghapus data.'];
    }
    header("Location: kelola_pengaduan.php");
    exit;
}

// 4. FUNGSI TANGGAL INDONESIA
function tgl_indo($tanggal)
{
    if (!$tanggal)
        return "-";
    $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $hari = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
    $timestamp = strtotime($tanggal);
    $nama_hari = $hari[date('l', $timestamp)];
    return $nama_hari . ", " . date('d', $timestamp) . " " . $bulan[(int) date('m', $timestamp)] . " " . date('Y', $timestamp);
}

// 5. STATISTIK
$stat_masuk = $conn->query("SELECT COUNT(*) FROM pengaduan WHERE status='Masuk'")->fetchColumn() ?: 0;
$stat_proses = $conn->query("SELECT COUNT(*) FROM pengaduan WHERE status LIKE '%Proses%'")->fetchColumn() ?: 0;
$stat_selesai = $conn->query("SELECT COUNT(*) FROM pengaduan WHERE status='Selesai'")->fetchColumn() ?: 0;
$stat_ditolak = $conn->query("SELECT COUNT(*) FROM pengaduan WHERE status='Ditolak'")->fetchColumn() ?: 0;
?>

<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Pengaduan | Lapas Lamongan</title>
    <link rel="icon" type="image/png" href="../assets/images/logo_imigrasi.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { imipas: '#07213D', dignity: '#EEBF63' },
                    fontFamily: { jakarta: ['Plus Jakarta Sans', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .dataTables_wrapper .dataTables_filter input {
            padding: 8px 16px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            outline: none;
            margin-bottom: 20px;
            font-size: 11px;
            width: 200px;
            background: #f8fafc;
        }

        table.dataTable thead th {
            background: #f8fafc !important;
            color: #64748b !important;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 20px !important;
            border-bottom: 1px solid #f1f5f9 !important;
            font-weight: 700;
        }

        table.dataTable tbody td {
            padding: 20px !important;
            border-bottom: 1px solid #f1f5f9 !important;
            font-size: 12px;
        }

        table.dataTable.no-footer {
            border-bottom: none !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #07213D !important;
            color: #EEBF63 !important;
            border-radius: 8px;
            font-size: 11px;
            font-weight: bold;
            border: none !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #07213D;
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-slate-100 h-full overflow-hidden">

    <div class="flex h-screen">
        <?php include "layout/sidebar.php"; ?>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header
                class="bg-white border-b-4 border-dignity h-20 flex items-center justify-between px-6 z-20 shadow-sm">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="lg:hidden text-imipas p-2 hover:bg-slate-100 rounded-lg">
                        <i class="fa-solid fa-bars-staggered text-xl"></i>
                    </button>
                    <h1 class="text-imipas font-bold uppercase tracking-wider text-sm hidden md:block">Manajemen
                        Pengaduan</h1>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block text-imipas">
                        <p class="text-xs font-bold leading-none"><?= htmlspecialchars($admin_name) ?></p>
                        <p class="text-[10px] text-amber-600 uppercase mt-1 italic font-semibold">Administrator</p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-full border-2 border-dignity flex items-center justify-center bg-slate-100 shadow-inner">
                        <i class="fa-solid fa-user-shield text-imipas"></i>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 bg-slate-50 custom-scrollbar">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div
                        class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden group hover:border-imipas transition-all">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1 relative z-10">
                            Masuk</p>
                        <h3 class="text-3xl font-bold text-imipas relative z-10"><?= $stat_masuk ?></h3>
                        <i
                            class="fa-solid fa-envelope absolute -right-2 -bottom-2 text-slate-50 text-5xl group-hover:text-red-50 transition-colors"></i>
                    </div>
                    <div
                        class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden group hover:border-amber-400 transition-all">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1 relative z-10">
                            Proses</p>
                        <h3 class="text-3xl font-bold text-amber-500 relative z-10"><?= $stat_proses ?></h3>
                        <i
                            class="fa-solid fa-spinner absolute -right-2 -bottom-2 text-slate-50 text-5xl group-hover:text-amber-50 transition-colors"></i>
                    </div>
                    <div
                        class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden group hover:border-emerald-400 transition-all">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1 relative z-10">
                            Selesai</p>
                        <h3 class="text-3xl font-bold text-emerald-500 relative z-10"><?= $stat_selesai ?></h3>
                        <i
                            class="fa-solid fa-check-double absolute -right-2 -bottom-2 text-slate-50 text-5xl group-hover:text-emerald-50 transition-colors"></i>
                    </div>
                    <div
                        class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden group hover:border-slate-400 transition-all">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1 relative z-10">
                            Ditolak</p>
                        <h3 class="text-3xl font-bold text-slate-300 relative z-10"><?= $stat_ditolak ?></h3>
                        <i
                            class="fa-solid fa-ban absolute -right-2 -bottom-2 text-slate-50 text-5xl group-hover:text-slate-100 transition-colors"></i>
                    </div>
                </div>

                <div
                    class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-6 overflow-hidden transition-all">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table id="tabelAduan" class="w-full">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Waktu & Tanggal</th>
                                    <th>Subjek Laporan</th>
                                    <th>Identitas Pelapor</th>
                                    <th>Status</th>
                                    <th class="text-center">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="text-[11px]">
                                <?php
                                $no = 1;
                                $q = $conn->query("SELECT p.*, k.nama_kategori FROM pengaduan p LEFT JOIN kategori_pengaduan k ON p.kategori_id = k.id ORDER BY p.created_at DESC");
                                while ($row = $q->fetch(PDO::FETCH_ASSOC)):
                                    $s = $row['status'];
                                    $badge = ($s == 'Masuk') ? 'bg-red-50 text-red-600 border-red-100' :
                                        ((strpos($s, 'Proses') !== false) ? 'bg-amber-50 text-amber-600 border-amber-100' :
                                            (($s == 'Selesai') ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-50 text-slate-600 border-slate-200'));
                                    ?>
                                    <tr class="border-b border-slate-50 hover:bg-slate-50/80 transition-all group">
                                        <td class="p-4 text-center text-slate-400 font-bold"><?= $no++ ?></td>
                                        <td class="p-4">
                                            <p class="text-imipas font-bold uppercase whitespace-nowrap">
                                                <?= tgl_indo($row['created_at']) ?>
                                            </p>
                                            <p class="text-[10px] text-slate-400 font-semibold"><i
                                                    class="fa-regular fa-clock mr-1"></i><?= date('H:i', strtotime($row['created_at'])) ?>
                                                WIB</p>
                                        </td>
                                        <td class="p-4 min-w-[200px]">
                                            <p
                                                class="text-slate-800 font-bold uppercase mb-1 line-clamp-1 group-hover:text-imipas">
                                                <?= htmlspecialchars($row['judul_pengaduan']) ?>
                                            </p>
                                            <span
                                                class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider"><?= htmlspecialchars($row['nama_kategori'] ?? 'Umum') ?></span>
                                        </td>
                                        <td class="p-4 whitespace-nowrap">
                                            <p class="text-imipas font-bold uppercase">
                                                <?= htmlspecialchars($row['nama_pelapor']) ?>
                                            </p>
                                            <p class="text-emerald-600 font-semibold italic"><i
                                                    class="fa-brands fa-whatsapp mr-1"></i><?= htmlspecialchars($row['kontak_pelapor']) ?>
                                            </p>
                                        </td>
                                        <td class="p-4 whitespace-nowrap">
                                            <span
                                                class="px-3 py-1 rounded-lg text-[9px] font-bold uppercase border <?= $badge ?>">
                                                <?= htmlspecialchars($s) ?>
                                            </span>
                                        </td>
                                        <td class="p-4 text-center">
                                            <div class="flex justify-center gap-2">
                                                <a href="admin_balas.php?id=<?= $row['id'] ?>"
                                                    class="w-8 h-8 flex items-center justify-center bg-imipas text-dignity rounded-lg hover:scale-110 transition-transform shadow-sm border border-slate-700"
                                                    title="Proses / Balas">
                                                    <i class="fa-solid fa-reply text-[10px]"></i>
                                                </a>
                                                <button onclick="confirmDelete(<?= $row['id'] ?>)"
                                                    class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded-lg border border-red-100 hover:bg-red-500 hover:text-white transition-all shadow-sm"
                                                    title="Hapus">
                                                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#tabelAduan').DataTable({
                "pageLength": 10,
                "ordering": false,
                "language": {
                    "search": "CARI DATA:",
                    "lengthMenu": "_MENU_",
                    "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    "paginate": {
                        "previous": "<i class='fa-solid fa-arrow-left'></i>",
                        "next": "<i class='fa-solid fa-arrow-right'></i>"
                    }
                }
            });
        });

        function toggleSidebar() {
            const sb = document.getElementById('sidebar');
            if (sb) {
                sb.classList.toggle('-translate-x-full');
            }
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'HAPUS LAPORAN?',
                text: "Data pengaduan dan file bukti akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#07213D',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'YA, HAPUS',
                cancelButtonText: 'BATAL',
                customClass: { popup: 'rounded-3xl' }
            }).then((result) => {
                if (result.isConfirmed) window.location.href = 'kelola_pengaduan.php?hapus=' + id + '&token=<?= $_SESSION['csrf_token'] ?>';
            });
        }

        <?php if (isset($_SESSION['alert'])): ?>
            Swal.fire({
                icon: '<?= $_SESSION['alert']['type'] ?>',
                title: '<?= $_SESSION['alert']['title'] ?>',
                text: '<?= $_SESSION['alert']['msg'] ?>',
                confirmButtonColor: '#07213D',
                customClass: { popup: 'rounded-3xl' }
            });
            <?php unset($_SESSION['alert']); ?>
        <?php endif; ?>
    </script>

</body>

</html>