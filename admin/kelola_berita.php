<?php
session_start();
include "../config/koneksi.php";

// 1. SINKRONISASI KONEKSI
if (!isset($conn) && isset($pdo)) {
    $conn = $pdo;
}

// 2. FIX SESSION
if (!isset($_SESSION['admin']) && !isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

include "layout/role_check.php";
check_role(['Humas']);

$admin_name = $_SESSION['nama'] ?? $_SESSION['admin'] ?? 'Administrator';

// folder tujuan upload tunggal
$target_dir = '../uploads/';

// 3. LOGIKA HAPUS DATA
if (isset($_GET['hapus'])) {
    if (!isset($_GET['token']) || $_GET['token'] !== $_SESSION['csrf_token']) {
        die("Akses ditolak: Token tidak valid.");
    }
    $id_hapus = (int) $_GET['hapus'];
    try {
        $stmt_img = $conn->prepare("SELECT gambar FROM berita WHERE id_berita = ?");
        $stmt_img->execute([$id_hapus]);
        $data_img = $stmt_img->fetch();

        if ($data_img && $data_img['gambar'] && file_exists($target_dir . $data_img['gambar'])) {
            unlink($target_dir . $data_img['gambar']);
        }

        $stmt_del = $conn->prepare("DELETE FROM berita WHERE id_berita = ?");
        $stmt_del->execute([$id_hapus]);

        $_SESSION['alert'] = ['type' => 'success', 'title' => 'TERHAPUS!', 'msg' => 'Berita berhasil dihapus.'];
    } catch (Exception $e) {
        $_SESSION['alert'] = ['type' => 'error', 'title' => 'GAGAL!', 'msg' => 'Gagal menghapus berita.'];
    }
    header("Location: kelola_berita.php");
    exit;
}

// 4. LOGIKA SIMPAN (INSERT/UPDATE)
if (isset($_POST['simpan_berita'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Token CSRF tidak valid.");
    }

    $id = !empty($_POST['id_berita']) ? (int) $_POST['id_berita'] : null;
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $tanggal = $_POST['tanggal'];
    $gambar_lama = $_POST['gambar_lama'] ?? '';
    $gambar = $gambar_lama;

    try {
        if (!empty($_FILES['gambar']['name'])) {
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_name = $_FILES['gambar']['name'];
            $file_tmp = $_FILES['gambar']['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];

            // Secure MIME Type Validation
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime_type = $finfo->file($file_tmp);
            $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp'];

            if (in_array($file_ext, $allowed_exts) && in_array($mime_type, $allowed_mimes) && $_FILES['gambar']['size'] <= 2000000) {
                // Hapus gambar lama jika ada pergantian gambar
                if ($gambar_lama && file_exists($target_dir . $gambar_lama)) {
                    unlink($target_dir . $gambar_lama);
                }

                $gambar = 'news-' . time() . '-' . rand(1000, 9999) . '.' . $file_ext;
                move_uploaded_file($file_tmp, $target_dir . $gambar);
            } else {
                $_SESSION['alert'] = ['type' => 'error', 'title' => 'GAGAL UPLOAD!', 'msg' => 'Format file harus JPG/PNG/WEBP dan maks 2MB.'];
                header("Location: kelola_berita.php");
                exit;
            }
        }

        if ($id) {
            $stmt = $conn->prepare("UPDATE berita SET judul=?, isi=?, tanggal=?, gambar=? WHERE id_berita=?");
            $stmt->execute([$judul, $isi, $tanggal, $gambar, $id]);
            $msg = "Berita berhasil diperbarui.";
        } else {
            $stmt = $conn->prepare("INSERT INTO berita (judul, isi, gambar, tanggal) VALUES (?, ?, ?, ?)");
            $stmt->execute([$judul, $isi, $gambar, $tanggal]);
            $msg = "Berita baru berhasil diposting.";
        }
        $_SESSION['alert'] = ['type' => 'success', 'title' => 'BERHASIL!', 'msg' => $msg];
    } catch (Exception $e) {
        $_SESSION['alert'] = ['type' => 'error', 'title' => 'GAGAL!', 'msg' => 'Terjadi kesalahan sistem.'];
    }
    header("Location: kelola_berita.php");
    exit;
}

// 5. STATISTIK
$total_berita = $conn->query("SELECT COUNT(*) FROM berita")->fetchColumn() ?? 0;
$berita_bulan = $conn->query("SELECT COUNT(*) FROM berita WHERE MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE())")->fetchColumn() ?? 0;
?>

<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Berita | Lapas Lamongan</title>
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
                    <h1 class="text-imipas uppercase tracking-wider text-sm hidden md:block">Manajemen Berita</h1>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block text-imipas">
                        <p class="text-xs leading-none"><?= htmlspecialchars($admin_name) ?></p>
                        <p class="text-[10px] text-amber-600 uppercase mt-1 italic">Admin Redaksi</p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-full border-2 border-dignity flex items-center justify-center bg-slate-100">
                        <i class="fa-solid fa-user-shield text-imipas"></i>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 bg-slate-50 custom-scrollbar">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <div class="flex gap-4">
                        <div
                            class="bg-white px-6 py-4 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3">
                            <div
                                class="text-imipas bg-blue-50 w-8 h-8 rounded-lg flex items-center justify-center text-sm">
                                <i class="fa-solid fa-database"></i>
                            </div>
                            <div>
                                <p class="text-[9px] text-slate-400 uppercase leading-none mb-1">Total</p>
                                <h3 class="text-lg text-imipas leading-none"><?= $total_berita ?></h3>
                            </div>
                        </div>
                        <div
                            class="bg-white px-6 py-4 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3">
                            <div
                                class="text-amber-500 bg-amber-50 w-8 h-8 rounded-lg flex items-center justify-center text-sm">
                                <i class="fa-solid fa-calendar-day"></i>
                            </div>
                            <div>
                                <p class="text-[9px] text-slate-400 uppercase leading-none mb-1">Bulan Ini</p>
                                <h3 class="text-lg text-imipas leading-none"><?= $berita_bulan ?></h3>
                            </div>
                        </div>
                    </div>

                    <button onclick="openModal()"
                        class="bg-imipas text-dignity px-6 py-3 rounded-xl shadow-lg hover:brightness-110 transition-all flex items-center gap-2 border-b-4 border-slate-900 group">
                        <i class="fa-solid fa-plus-circle group-hover:rotate-90 transition-transform"></i>
                        <span class="text-[11px] uppercase tracking-widest">Tulis Berita Baru</span>
                    </button>
                </div>

                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-6 overflow-hidden">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table id="tabelBerita" class="w-full">
                            <thead>
                                <tr>
                                    <th class="text-center w-12">No</th>
                                    <th>Informasi Berita</th>
                                    <th class="hidden md:table-cell">Tanggal</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-[11px]">
                                <?php
                                $no = 1;
                                $q = $conn->query("SELECT * FROM berita ORDER BY tanggal DESC");
                                while ($row = $q->fetch(PDO::FETCH_ASSOC)):
                                    ?>
                                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-all">
                                        <td class="p-4 text-center text-slate-400"><?= $no++ ?></td>
                                        <td class="p-4">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="w-16 h-12 rounded-lg overflow-hidden shadow-sm border border-slate-200 flex-shrink-0">
                                                    <?php if ($row['gambar']): ?>
                                                        <img src="../uploads/<?= $row['gambar'] ?>"
                                                            class="w-full h-full object-cover">
                                                    <?php else: ?>
                                                        <div
                                                            class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300 text-[8px]">
                                                            No Img</div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-imipas uppercase leading-tight line-clamp-1 italic">
                                                        <?= htmlspecialchars($row['judul']) ?>
                                                    </p>
                                                    <p class="text-[9px] text-slate-400 mt-1 md:hidden italic"><i
                                                            class="fa-regular fa-clock mr-1"></i><?= date('d/m/Y', strtotime($row['tanggal'])) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4 whitespace-nowrap hidden md:table-cell">
                                            <span class="text-slate-500 uppercase text-[10px]">
                                                <i
                                                    class="fa-regular fa-calendar-alt mr-1"></i><?= date('d M Y', strtotime($row['tanggal'])) ?>
                                            </span>
                                        </td>
                                        <td class="p-4 text-center">
                                            <div class="flex justify-center gap-2">
                                                <button onclick='editBerita(<?= json_encode($row) ?>)'
                                                    class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-imipas hover:text-dignity transition-all shadow-sm border border-blue-100">
                                                    <i class="fa-solid fa-edit text-[10px]"></i>
                                                </button>
                                                <button onclick="confirmDelete(<?= $row['id_berita'] ?>)"
                                                    class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition-all shadow-sm border border-red-100">
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

    <div id="modalBerita"
        class="fixed inset-0 bg-[#07213D]/70 backdrop-blur-sm hidden flex items-center justify-center z-[100] p-4">
        <div class="bg-white w-full max-w-xl rounded-3xl shadow-2xl relative overflow-hidden flex flex-col">
            <div class="bg-white p-6 flex justify-between items-center border-b border-slate-100">
                <h2 id="modalTitle" class="text-sm text-imipas uppercase tracking-[0.2em] italic">Redaksi Konten</h2>
                <button onclick="closeModal()" class="text-slate-300 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-times-circle text-xl"></i>
                </button>
            </div>

            <form method="POST" enctype="multipart/form-data"
                class="p-6 space-y-4 max-h-[80vh] overflow-y-auto custom-scrollbar">
                <input type="hidden" name="id_berita" id="id_berita">
                <input type="hidden" name="gambar_lama" id="gambar_lama">

                <div>
                    <label class="text-[9px] text-slate-400 uppercase tracking-widest mb-1 block">Headlines</label>
                    <input type="text" name="judul" id="judul"
                        class="w-full p-3 bg-slate-50 rounded-xl border border-slate-200 outline-none text-imipas focus:ring-2 focus:ring-imipas/20 transition-all"
                        placeholder="Input Judul..." required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[9px] text-slate-400 uppercase tracking-widest mb-1 block">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal_input"
                            class="w-full p-3 bg-slate-50 rounded-xl border border-slate-200 outline-none text-xs"
                            required>
                    </div>
                    <div>
                        <label class="text-[9px] text-slate-400 uppercase tracking-widest mb-1 block">Gambar</label>
                        <input type="file" name="gambar" id="imgInput" class="hidden" accept="image/*">
                        <label for="imgInput"
                            class="w-full p-3 bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl flex items-center justify-center gap-2 cursor-pointer hover:bg-slate-100 transition-all">
                            <i class="fa-solid fa-image text-slate-400"></i>
                            <span class="text-[10px] text-slate-500" id="fileName">Pilih...</span>
                        </label>
                    </div>
                </div>

                <div id="previewContainer" class="hidden">
                    <img id="imgPreview" src="#"
                        class="w-full h-32 object-cover rounded-xl border border-slate-100 shadow-sm">
                </div>

                <div>
                    <label class="text-[9px] text-slate-400 uppercase tracking-widest mb-1 block">Isi Berita</label>
                    <textarea name="isi" id="isi" rows="6"
                        class="w-full p-4 bg-slate-50 rounded-xl border border-slate-200 outline-none text-[13px] leading-relaxed"
                        placeholder="Tuliskan berita lengkap..." required></textarea>
                </div>

                <button type="submit" name="simpan_berita"
                    class="w-full py-3.5 bg-imipas text-dignity rounded-xl text-[10px] uppercase tracking-[0.2em] shadow-lg hover:brightness-110 active:scale-[0.98] transition-all border-b-4 border-slate-900">
                    <i class="fa-solid fa-paper-plane mr-2"></i>Publish Konten
                </button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#tabelBerita').DataTable({
                "pageLength": 10,
                "ordering": false,
                "language": {
                    "search": "CARI BERITA:",
                    "lengthMenu": "_MENU_",
                    "info": "Data _START_ - _END_ dari _TOTAL_"
                }
            });

            $("#imgInput").change(function () {
                const file = this.files[0];
                if (file) {
                    $("#fileName").text(file.name);
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        $("#imgPreview").attr("src", e.target.result);
                        $("#previewContainer").removeClass("hidden");
                    }
                    reader.readAsDataURL(file);
                }
            });
        });

        function toggleSidebar() {
            const sb = document.getElementById('sidebar');
            const ov = document.getElementById('sidebarOverlay');
            if (sb) sb.classList.toggle('-translate-x-full');
            if (ov) ov.classList.toggle('hidden');
        }

        function openModal() {
            $('#modalBerita').removeClass('hidden');
            $('#modalTitle').text('TULIS BERITA BARU');
            $('form')[0].reset();
            $('#id_berita').val('');
            $('#gambar_lama').val('');
            $('#previewContainer').addClass('hidden');
            $('#fileName').text('Pilih...');
            $('#tanggal_input').val(new Date().toISOString().split('T')[0]);
        }

        function editBerita(data) {
            $('#modalBerita').removeClass('hidden');
            $('#modalTitle').text('PERBARUI BERITA');
            $('#id_berita').val(data.id_berita);
            $('#judul').val(data.judul);
            $('#isi').val(data.isi);
            $('#gambar_lama').val(data.gambar);
            $('#tanggal_input').val(data.tanggal);

            if (data.gambar) {
                $("#imgPreview").attr("src", "../uploads/" + data.gambar);
                $("#previewContainer").removeClass("hidden");
                $("#fileName").text("Ganti gambar...");
            } else {
                $("#previewContainer").addClass("hidden");
            }
        }

        function closeModal() { $('#modalBerita').addClass('hidden'); }

        function confirmDelete(id) {
            Swal.fire({
                title: 'HAPUS BERITA?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#07213D',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'YA, HAPUS',
                customClass: { popup: 'rounded-2xl' }
            }).then((result) => {
                if (result.isConfirmed) window.location.href = 'kelola_berita.php?hapus=' + id + '&token=<?= $_SESSION['csrf_token'] ?>';
            });
        }

        <?php if (isset($_SESSION['alert'])): ?>
            Swal.fire({
                icon: '<?= $_SESSION['alert']['type'] ?>',
                title: '<?= $_SESSION['alert']['title'] ?>',
                text: '<?= $_SESSION['alert']['msg'] ?>',
                confirmButtonColor: '#07213D',
                customClass: { popup: 'rounded-2xl' }
            });
            <?php unset($_SESSION['alert']); ?>
        <?php endif; ?>
    </script>
</body>

</html>