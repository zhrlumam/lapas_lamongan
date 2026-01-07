<?php
session_start();
include "../config/koneksi.php";

// 1. SINKRONISASI KONEKSI
if (!isset($conn) && isset($pdo)) {
    $conn = $pdo;
}

// 2. PROTEKSI HALAMAN & AMBIL DATA ADMIN
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

include "layout/role_check.php";
check_role(['Humas']);

$admin_id = $_SESSION['admin_id'] ?? '0';
$admin_name = $_SESSION['nama'] ?? $_SESSION['admin'] ?? 'Administrator';

// 3. PROSES HAPUS (PDO)
if (isset($_GET['hapus'])) {
    if (!isset($_GET['token']) || $_GET['token'] !== $_SESSION['csrf_token']) {
        die("Akses ditolak: Token tidak valid.");
    }

    $id = (int) $_GET['hapus'];
    try {
        // Ambil nama file gambar terlebih dahulu sebelum data dihapus
        $stmt = $conn->prepare("SELECT gambar FROM produk WHERE id_produk = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Hapus file fisik dari folder uploads jika file tersebut ada
            if (!empty($row['gambar'])) {
                $file_path = "../uploads/" . $row['gambar'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }

            // Hapus data dari database
            $del = $conn->prepare("DELETE FROM produk WHERE id_produk = ?");
            $del->execute([$id]);

            $_SESSION['alert'] = ['type' => 'success', 'title' => 'TERHAPUS!', 'msg' => 'Produk dan gambar berhasil dihapus.'];
        }
    } catch (Exception $e) {
        $_SESSION['alert'] = ['type' => 'error', 'title' => 'GAGAL!', 'msg' => 'Kesalahan sistem saat menghapus.'];
    }
    header("Location: kelola_produk.php");
    exit;
}

// 4. PROSES SIMPAN (INSERT/UPDATE)
if (isset($_POST['simpan_produk'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Token CSRF tidak valid.");
    }

    $id = !empty($_POST['id_produk']) ? (int) $_POST['id_produk'] : null;
    $nama = $_POST['nama_produk'];
    $kat = $_POST['kategori'];
    $desk = $_POST['deskripsi'];
    $gambar_lama = $_POST['gambar_lama'] ?? '';
    $gambar = $gambar_lama;

    if (!empty($_FILES['gambar']['name'])) {
        $file_name = $_FILES['gambar']['name'];
        $file_size = $_FILES['gambar']['size'];
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        // Secure MIME Check
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($file_tmp);
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp'];

        if (in_array($file_ext, $allowed) && in_array($mime_type, $allowed_mimes) && $file_size <= 2000000) {
            if (!file_exists("../uploads"))
                mkdir("../uploads", 0777, true);

            // Hapus gambar lama jika ada saat upload gambar baru (Update)
            if ($gambar_lama && file_exists("../uploads/" . $gambar_lama)) {
                unlink("../uploads/" . $gambar_lama);
            }

            $gambar = "prod-" . time() . "-" . rand(100, 999) . "." . $file_ext;
            move_uploaded_file($file_tmp, "../uploads/" . $gambar);
        } else {
            $_SESSION['alert'] = ['type' => 'error', 'title' => 'GAGAL UPLOAD!', 'msg' => 'File harus gambar (JPG/PNG) & maks 2MB.'];
            header("Location: kelola_produk.php");
            exit;
        }
    }

    try {
        if ($id) {
            $stmt = $conn->prepare("UPDATE produk SET nama_produk=?, kategori=?, deskripsi=?, gambar=? WHERE id_produk=?");
            $stmt->execute([$nama, $kat, $desk, $gambar, $id]);
            $msg = "Produk berhasil diperbarui.";
        } else {
            $stmt = $conn->prepare("INSERT INTO produk (nama_produk, kategori, deskripsi, gambar) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nama, $kat, $desk, $gambar]);
            $msg = "Produk baru berhasil ditambahkan.";
        }
        $_SESSION['alert'] = ['type' => 'success', 'title' => 'BERHASIL!', 'msg' => $msg];
    } catch (Exception $e) {
        $_SESSION['alert'] = ['type' => 'error', 'title' => 'GAGAL!', 'msg' => 'Gagal menyimpan ke database.'];
    }
    header("Location: kelola_produk.php");
    exit;
}

/* =========================
   PAGINATION
========================= */
$batas = 5;
$halaman = isset($_GET['halaman']) ? (int) $_GET['halaman'] : 1;
$offset = ($halaman - 1) * $batas;
$total_data = $conn->query("SELECT COUNT(*) FROM produk")->fetchColumn();
$total_halaman = ceil($total_data / $batas);

$stmt = $conn->prepare("SELECT * FROM produk ORDER BY id_produk DESC LIMIT :offset, :batas");
$stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
$stmt->bindValue(':batas', (int) $batas, PDO::PARAM_INT);
$stmt->execute();
$produk = $stmt->fetchAll(PDO::FETCH_ASSOC);
$no = $offset + 1;
?>

<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Produk | Lapas Lamongan</title>
    <link rel="icon" type="image/png" href="../assets/images/logo_imigrasi.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
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
                    <h1 class="text-imipas uppercase tracking-wider text-sm hidden md:block">Manajemen Produk</h1>
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
                    <div>
                        <h2 class="text-lg text-imipas uppercase tracking-tight ">Katalog Produk</h2>
                        <p class="text-slate-400 text-[10px] uppercase tracking-widest">Karya Unggulan Warga Binaan</p>
                    </div>

                    <button onclick="openModal()"
                        class="bg-imipas text-dignity px-6 py-3 rounded-xl shadow-lg hover:brightness-110 transition-all flex items-center gap-2 border-b-4 border-slate-900 group">
                        <i class="fa-solid fa-plus-circle group-hover:rotate-90 transition-transform"></i>
                        <span class="text-[11px] uppercase tracking-widest">Tambah Produk</span>
                    </button>
                </div>

                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-6 overflow-hidden">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-widest font-bold">
                                    <th class="p-5 text-center w-16">No</th>
                                    <th class="p-5 text-left">Informasi Produk</th>
                                    <th class="p-5 text-left">Kategori</th>
                                    <th class="p-5 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-[11px]">
                                <?php foreach ($produk as $r): ?>
                                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-all">
                                        <td class="p-5 text-center text-slate-400"><?= $no++ ?></td>
                                        <td class="p-5">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="w-12 h-12 rounded-lg bg-slate-100 overflow-hidden border border-slate-200 shadow-sm flex-shrink-0">
                                                    <?php if ($r['gambar']): ?>
                                                        <img src="../uploads/<?= htmlspecialchars($r['gambar']) ?>"
                                                            class="w-full h-full object-cover">
                                                    <?php else: ?>
                                                        <div
                                                            class="w-full h-full flex items-center justify-center text-slate-300 text-[8px]">
                                                            No Img</div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-imipas uppercase leading-tight line-clamp-1 italic">
                                                        <?= htmlspecialchars($r['nama_produk']) ?>
                                                    </p>
                                                    <p class="text-[9px] text-slate-400 mt-1 line-clamp-1">
                                                        <?= htmlspecialchars(strip_tags($r['deskripsi'])) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-5">
                                            <span
                                                class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[9px] uppercase tracking-tighter border border-blue-100">
                                                <?= htmlspecialchars($r['kategori']) ?>
                                            </span>
                                        </td>
                                        <td class="p-5 text-center">
                                            <div class="flex justify-center gap-2">
                                                <button onclick='editProduk(<?= json_encode($r) ?>)'
                                                    class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-imipas hover:text-dignity transition-all border border-blue-100">
                                                    <i class="fa-solid fa-edit text-[10px]"></i>
                                                </button>
                                                <button onclick="konfirmasiHapus(<?= $r['id_produk'] ?>)"
                                                    class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition-all border border-red-100">
                                                    <i class="fa-solid fa-trash-alt text-[10px]"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest">Hal <?= $halaman ?> dari
                            <?= $total_halaman ?>
                        </p>
                        <div class="flex gap-2">
                            <?php if ($halaman > 1): ?>
                                <a href="?halaman=<?= $halaman - 1 ?>"
                                    class="px-4 py-2 bg-slate-100 text-imipas rounded-xl text-[10px] uppercase hover:bg-imipas hover:text-dignity transition-all">Prev</a>
                            <?php endif; ?>
                            <?php if ($halaman < $total_halaman): ?>
                                <a href="?halaman=<?= $halaman + 1 ?>"
                                    class="px-4 py-2 bg-imipas text-dignity rounded-xl text-[10px] uppercase hover:brightness-125 transition-all">Next</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <div id="modal"
        class="fixed inset-0 bg-[#07213D]/70 backdrop-blur-sm hidden flex items-center justify-center z-[100] p-4">
        <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl relative overflow-hidden flex flex-col">
            <div class="bg-white p-6 flex justify-between items-center border-b border-slate-100">
                <h2 id="modalTitle" class="text-sm text-imipas uppercase tracking-[0.2em] italic">Redaksi Produk</h2>
                <button onclick="closeModal()" class="text-slate-300 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-times-circle text-xl"></i>
                </button>
            </div>

            <form method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                <input type="hidden" name="id_produk" id="in_id_produk">
                <input type="hidden" name="gambar_lama" id="in_gambar_lama">

                <div>
                    <label class="text-[9px] text-slate-400 uppercase tracking-widest mb-1 block">Nama Produk</label>
                    <input type="text" name="nama_produk" id="in_nama_produk"
                        class="w-full p-3 bg-slate-50 rounded-xl border border-slate-200 outline-none text-imipas text-xs focus:ring-2 focus:ring-imipas/20 transition-all"
                        required>
                </div>

                <div>
                    <label class="text-[9px] text-slate-400 uppercase tracking-widest mb-1 block">Kategori</label>
                    <input type="text" name="kategori" id="in_kategori"
                        class="w-full p-3 bg-slate-50 rounded-xl border border-slate-200 outline-none text-imipas text-xs"
                        required>
                </div>

                <div>
                    <label class="text-[9px] text-slate-400 uppercase tracking-widest mb-1 block">Deskripsi
                        Produk</label>
                    <textarea name="deskripsi" id="in_deskripsi" rows="3"
                        class="w-full p-3 bg-slate-50 rounded-xl border border-slate-200 outline-none text-[11px] leading-relaxed"
                        required></textarea>
                </div>

                <div>
                    <label class="text-[9px] text-slate-400 uppercase tracking-widest mb-1 block">Foto Produk (Maks
                        2MB)</label>
                    <input type="file" name="gambar" accept="image/*"
                        class="w-full text-[10px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-imipas file:text-dignity border border-slate-100 rounded-xl bg-slate-50">
                </div>

                <button type="submit" name="simpan_produk"
                    class="w-full py-4 bg-imipas text-dignity rounded-xl text-[10px] uppercase tracking-[0.2em] shadow-lg hover:brightness-110 active:scale-[0.98] transition-all border-b-4 border-slate-900">
                    <i class="fa-solid fa-save mr-2"></i>Simpan Data
                </button>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sb = document.getElementById('sidebar');
            const ov = document.getElementById('sidebarOverlay');
            if (sb) sb.classList.toggle('-translate-x-full');
            if (ov) ov.classList.toggle('hidden');
        }

        function openModal() {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('modalTitle').innerText = 'TAMBAH PRODUK BARU';
            document.querySelector('form').reset();
            document.getElementById('in_id_produk').value = '';
            document.getElementById('in_gambar_lama').value = '';
        }

        function editProduk(d) {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('modalTitle').innerText = 'PERBARUI DATA PRODUK';
            document.getElementById('in_id_produk').value = d.id_produk;
            document.getElementById('in_nama_produk').value = d.nama_produk;
            document.getElementById('in_kategori').value = d.kategori;
            document.getElementById('in_deskripsi').value = d.deskripsi;
            document.getElementById('in_gambar_lama').value = d.gambar;
        }

        function closeModal() { document.getElementById('modal').classList.add('hidden'); }

        function konfirmasiHapus(id) {
            Swal.fire({
                title: 'HAPUS PRODUK?',
                text: "Data akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#07213D',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'YA, HAPUS',
                customClass: { popup: 'rounded-2xl' }
            }).then((result) => {
                if (result.isConfirmed) window.location.href = '?hapus=' + id + '&token=<?= $_SESSION['csrf_token'] ?>';
            })
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
            <?php unset($_SESSION['alert']); ?>
        <?php endif; ?>
    </script>

</body>

</html>