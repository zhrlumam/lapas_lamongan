<?php
session_start();
include "../config/koneksi.php";

// 1. SINKRONISASI KONEKSI
if (!isset($conn) && isset($pdo)) {
    $conn = $pdo;
}

// 2. PROTEKSI HALAMAN
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

include "layout/role_check.php";
check_role(['Humas']);

$admin_id = $_SESSION['admin_id'] ?? '0';
$admin_name = $_SESSION['nama'] ?? $_SESSION['admin'] ?? 'Administrator';

// 3. LOGIKA HAPUS DATA
if (isset($_GET['hapus'])) {
    $id_hapus = (int) $_GET['hapus'];
    try {
        $stmt_del = $conn->prepare("DELETE FROM informasi WHERE id_info = ?");
        $stmt_del->execute([$id_hapus]);
        $_SESSION['alert'] = ['type' => 'success', 'title' => 'TERHAPUS!', 'msg' => 'Informasi berhasil dihapus.'];
    } catch (Exception $e) {
        $_SESSION['alert'] = ['type' => 'error', 'title' => 'GAGAL!', 'msg' => 'Gagal menghapus data.'];
    }
    header("Location: kelola_informasi.php");
    exit;
}

// 4. LOGIKA SIMPAN (INSERT/UPDATE)
if (isset($_POST['simpan_informasi'])) {
    $id = !empty($_POST['id_info']) ? (int) $_POST['id_info'] : null;
    $judul = $_POST['judul_info'];
    $deskripsi = $_POST['deskripsi_singkat'];
    $link = $_POST['link_tujuan'];

    try {
        if ($id) {
            $stmt = $conn->prepare("UPDATE informasi SET judul_info=?, deskripsi_singkat=?, link_tujuan=? WHERE id_info=?");
            $stmt->execute([$judul, $deskripsi, $link, $id]);
            $msg = "Data informasi berhasil diperbarui.";
        } else {
            $stmt = $conn->prepare("INSERT INTO informasi (judul_info, deskripsi_singkat, link_tujuan) VALUES (?, ?, ?)");
            $stmt->execute([$judul, $deskripsi, $link]);
            $msg = "Informasi baru berhasil ditambahkan.";
        }
        $_SESSION['alert'] = ['type' => 'success', 'title' => 'BERHASIL!', 'msg' => $msg];
    } catch (Exception $e) {
        $_SESSION['alert'] = ['type' => 'error', 'title' => 'GAGAL!', 'msg' => 'Terjadi kesalahan sistem.'];
    }
    header("Location: kelola_informasi.php");
    exit;
}

$total_info = $conn->query("SELECT COUNT(*) FROM informasi")->fetchColumn() ?? 0;
?>

<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Informasi | Lapas Lamongan</title>
    <link rel="icon" type="image/png" href="../assets/images/logo_imigrasi.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
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
            border: none !important;
            border-radius: 8px;
            font-size: 11px;
            font-weight: bold;
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
                    <h1 class="text-imipas uppercase tracking-wider text-sm hidden md:block">Pusat Layanan Informasi
                    </h1>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block text-imipas">
                        <p class="text-xs leading-none"><?= htmlspecialchars($admin_name) ?></p>
                        <p class="text-[10px] text-amber-600 uppercase mt-1 italic">Admin #<?= $admin_id ?></p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-full border-2 border-dignity flex items-center justify-center bg-slate-100">
                        <i class="fa-solid fa-user-shield text-imipas"></i>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 bg-slate-50 custom-scrollbar">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <div
                        class="bg-white px-6 py-4 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3">
                        <div class="text-imipas bg-blue-50 w-8 h-8 rounded-lg flex items-center justify-center text-sm">
                            <i class="fa-solid fa-info-circle"></i>
                        </div>
                        <div>
                            <p class="text-[9px] text-slate-400 uppercase leading-none mb-1">Total Info</p>
                            <h3 class="text-lg text-imipas leading-none"><?= $total_info ?></h3>
                        </div>
                    </div>

                    <button onclick="openModal()"
                        class="bg-imipas text-dignity px-6 py-3 rounded-xl shadow-lg hover:brightness-110 transition-all flex items-center gap-2 border-b-4 border-slate-900 group">
                        <i class="fa-solid fa-plus-circle group-hover:rotate-90 transition-transform"></i>
                        <span class="text-[11px] uppercase tracking-widest">Tambah Info Baru</span>
                    </button>
                </div>

                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-6 overflow-hidden">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table id="tabelInfo" class="w-full">
                            <thead>
                                <tr>
                                    <th class="text-center w-12">No</th>
                                    <th>Headline Informasi</th>
                                    <th class="hidden md:table-cell">Link Tujuan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-[11px]">
                                <?php
                                $no = 1;
                                $q = $conn->query("SELECT * FROM informasi ORDER BY id_info DESC");
                                while ($row = $q->fetch(PDO::FETCH_ASSOC)):
                                    ?>
                                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-all">
                                        <td class="p-4 text-center text-slate-400"><?= $no++ ?></td>
                                        <td class="p-4">
                                            <p class="text-imipas uppercase leading-tight italic">
                                                <?= htmlspecialchars($row['judul_info']) ?></p>
                                            <p class="text-[10px] text-slate-400 mt-1 line-clamp-1">
                                                <?= htmlspecialchars($row['deskripsi_singkat']) ?></p>
                                        </td>
                                        <td class="p-4 hidden md:table-cell">
                                            <?php if ($row['link_tujuan']): ?>
                                                <span class="text-blue-500 italic"><i
                                                        class="fa-solid fa-link mr-1"></i>Tersedia</span>
                                            <?php else: ?>
                                                <span class="text-slate-300">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="p-4 text-center">
                                            <div class="flex justify-center gap-2">
                                                <button onclick='editInfo(<?= json_encode($row) ?>)'
                                                    class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-imipas hover:text-dignity transition-all border border-blue-100">
                                                    <i class="fa-solid fa-edit text-[10px]"></i>
                                                </button>
                                                <button onclick="confirmDelete(<?= $row['id_info'] ?>)"
                                                    class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition-all border border-red-100">
                                                    <i class="fa-solid fa-trash-alt text-[10px]"></i>
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

    <div id="modalInfo"
        class="fixed inset-0 bg-[#07213D]/70 backdrop-blur-sm hidden flex items-center justify-center z-[100] p-4">
        <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl relative overflow-hidden flex flex-col">
            <div class="bg-white p-6 flex justify-between items-center border-b border-slate-100">
                <h2 id="modalTitle" class="text-sm text-imipas uppercase tracking-[0.2em] italic">Redaksi Informasi</h2>
                <button onclick="closeModal()" class="text-slate-300 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-times-circle text-xl"></i>
                </button>
            </div>

            <form method="POST" class="p-6 space-y-4">
                <input type="hidden" name="id_info" id="id_info">

                <div>
                    <label class="text-[9px] text-slate-400 uppercase tracking-widest mb-1 block">Judul
                        Informasi</label>
                    <input type="text" name="judul_info" id="judul_info"
                        class="w-full p-3 bg-slate-50 rounded-xl border border-slate-200 outline-none text-imipas focus:ring-2 focus:ring-imipas/20 transition-all"
                        required>
                </div>

                <div>
                    <label class="text-[9px] text-slate-400 uppercase tracking-widest mb-1 block">Deskripsi
                        Singkat</label>
                    <textarea name="deskripsi_singkat" id="deskripsi_singkat" rows="4"
                        class="w-full p-4 bg-slate-50 rounded-xl border border-slate-200 outline-none text-[12px] leading-relaxed"
                        required></textarea>
                </div>

                <div>
                    <label class="text-[9px] text-slate-400 uppercase tracking-widest mb-1 block">Link Tujuan
                        (Opsional)</label>
                    <input type="text" name="link_tujuan" id="link_tujuan"
                        class="w-full p-3 bg-slate-50 rounded-xl border border-slate-200 outline-none text-xs text-blue-500"
                        placeholder="https://...">
                </div>

                <button type="submit" name="simpan_informasi"
                    class="w-full py-4 bg-imipas text-dignity rounded-xl text-[10px] uppercase tracking-[0.2em] shadow-lg hover:brightness-110 transition-all border-b-4 border-slate-900">
                    <i class="fa-solid fa-save mr-2"></i>Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#tabelInfo').DataTable({
                "pageLength": 10,
                "ordering": false,
                "language": {
                    "search": "CARI:",
                    "lengthMenu": "_MENU_",
                    "info": "Data _START_ - _END_ dari _TOTAL_"
                }
            });
        });

        // Fungsi toggleSidebar yang konsisten agar menu mobile bisa tutup
        function toggleSidebar() {
            const sb = document.getElementById('sidebar');
            const ov = document.getElementById('sidebarOverlay');
            if (sb) sb.classList.toggle('-translate-x-full');
            if (ov) ov.classList.toggle('hidden');
        }

        function openModal() {
            $('#modalInfo').removeClass('hidden');
            $('#modalTitle').text('TAMBAH INFORMASI BARU');
            $('form')[0].reset();
            $('#id_info').val('');
        }

        function editInfo(data) {
            $('#modalInfo').removeClass('hidden');
            $('#modalTitle').text('EDIT INFORMASI');
            $('#id_info').val(data.id_info);
            $('#judul_info').val(data.judul_info);
            $('#deskripsi_singkat').val(data.deskripsi_singkat);
            $('#link_tujuan').val(data.link_tujuan);
        }

        function closeModal() { $('#modalInfo').addClass('hidden'); }

        function confirmDelete(id) {
            Swal.fire({
                title: 'HAPUS DATA?',
                text: "Data informasi akan hilang permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#07213D',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'YA, HAPUS',
                customClass: { popup: 'rounded-2xl' }
            }).then((result) => {
                if (result.isConfirmed) window.location.href = 'kelola_informasi.php?hapus=' + id;
            });
        }

        <?php if (isset($_SESSION['alert'])): ?>
            Swal.fire({
                icon: '<?= $_SESSION['alert']['type'] ?>',
                title: '<?= $_SESSION['alert']['title'] ?>',
                text: '<?= $_SESSION['alert']['msg'] ?>',
                timer: 2000,
                showConfirmButton: false,
                customClass: { popup: 'rounded-2xl' }
            });
            <?php unset($_SESSION['alert']); endif; ?>
    </script>

</body>

</html>