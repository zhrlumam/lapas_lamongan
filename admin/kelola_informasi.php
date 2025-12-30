<?php
session_start();
include "../config/koneksi.php";

// 1. CEK LOGIN
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Perbaikan: Ambil session sebagai string sesuai sistem login Anda
$admin_name = $_SESSION['admin'];

/* =========================
   PROSES HAPUS INFORMASI
========================= */
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    
    if(mysqli_query($conn, "DELETE FROM informasi WHERE id_info='$id'")) {
        $_SESSION['alert'] = ['type' => 'success', 'title' => 'Dihapus!', 'msg' => 'Informasi berhasil dihapus.'];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'title' => 'Gagal!', 'msg' => 'Data gagal dihapus.'];
    }
    header("Location: kelola_informasi.php");
    exit;
}

/* =========================
   PROSES TAMBAH & EDIT INFORMASI
========================= */
if (isset($_POST['simpan_informasi'])) {
    $id        = $_POST['id_info'] ?? '';
    $judul     = mysqli_real_escape_string($conn, $_POST['judul_info']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi_singkat']);
    $link      = mysqli_real_escape_string($conn, $_POST['link_tujuan']);

    if ($id) {
        $query = "UPDATE informasi SET judul_info='$judul', deskripsi_singkat='$deskripsi', link_tujuan='$link' WHERE id_info='$id'";
        $msg = "Informasi berhasil diperbarui.";
    } else {
        $query = "INSERT INTO informasi (judul_info, deskripsi_singkat, link_tujuan) VALUES ('$judul','$deskripsi','$link')";
        $msg = "Informasi baru berhasil ditambahkan.";
    }

    if(mysqli_query($conn, $query)) {
        $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'msg' => $msg];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'title' => 'Gagal!', 'msg' => 'Terjadi kesalahan sistem.'];
    }
    header("Location: kelola_informasi.php");
    exit;
}

$informasi = mysqli_query($conn, "SELECT * FROM informasi ORDER BY id_info DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Informasi | Admin Lapas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-700">

<div class="min-h-screen flex relative">
    <?php include "layout/menu_admin.php"; ?>

    <main class="flex-1 overflow-x-hidden flex flex-col">
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center sticky top-0 z-20">
            <h1 class="text-lg font-bold text-slate-800">Manajemen Informasi & Layanan</h1>
            <div class="bg-slate-50 px-3 py-1.5 rounded-2xl border border-slate-100 text-xs font-bold text-slate-600 uppercase">
                Admin: <?= htmlspecialchars($admin_name) ?>
            </div>
        </header>

        <div class="p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Daftar Informasi</h2>
                    <p class="text-slate-500 text-sm">Kelola pengumuman atau layanan yang tampil di kotak atas halaman berita.</p>
                </div>
                <button onclick="openModal()" class="bg-[#EEBF63] text-[#07213D] px-6 py-3 rounded-2xl font-bold shadow-xl hover:brightness-110 transition flex items-center gap-2 text-sm uppercase">
                    <i class="fa-solid fa-bullhorn"></i> Tambah Informasi
                </button>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 text-[10px] font-black uppercase tracking-widest">
                                <th class="p-5 w-16">No</th>
                                <th class="p-5">Judul Informasi</th>
                                <th class="p-5">Deskripsi</th>
                                <th class="p-5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $no=1; while($row=mysqli_fetch_assoc($informasi)): ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-5 font-bold text-slate-400"><?= $no++ ?></td>
                                <td class="p-5 font-bold text-[#07213D]"><?= htmlspecialchars($row['judul_info']) ?></td>
                                <td class="p-5 text-xs text-slate-500 max-w-xs"><?= htmlspecialchars($row['deskripsi_singkat']) ?></td>
                                <td class="p-5">
                                    <div class="flex justify-center gap-2">
                                        <button onclick='editInfo(<?= json_encode($row) ?>)' class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </button>
                                        <button onclick="hapusInfo(<?= $row['id_info'] ?>)" class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-500 hover:bg-red-600 hover:text-white transition">
                                            <i class="fa-solid fa-trash text-xs"></i>
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
    </main>
</div>

<div id="modal" class="fixed inset-0 bg-[#07213D]/60 backdrop-blur-sm hidden flex items-center justify-center z-[100] p-4">
    <div class="bg-white w-full max-w-lg p-8 rounded-[2.5rem] shadow-2xl relative">
        <h2 id="modalTitle" class="text-xl font-black text-[#07213D] mb-6 uppercase">Form Informasi</h2>
        <form method="POST" class="space-y-4">
            <input type="hidden" name="id_info" id="id_info">
            <div>
                <label class="text-[10px] font-bold uppercase text-slate-400 ml-2">Judul Informasi</label>
                <input type="text" name="judul_info" id="judul_info" class="w-full p-4 bg-slate-50 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div>
                <label class="text-[10px] font-bold uppercase text-slate-400 ml-2">Deskripsi Singkat</label>
                <textarea name="deskripsi_singkat" id="deskripsi_singkat" rows="3" class="w-full p-4 bg-slate-50 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none" required></textarea>
            </div>
            <div>
                <label class="text-[10px] font-bold uppercase text-slate-400 ml-2">Link Tujuan (Opsional)</label>
                <input type="text" name="link_tujuan" id="link_tujuan" placeholder="Contoh: https://google.com atau #" class="w-full p-4 bg-slate-50 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeModal()" class="flex-1 py-4 bg-slate-100 rounded-2xl font-bold uppercase text-xs tracking-widest">Batal</button>
                <button type="submit" name="simpan_informasi" class="flex-1 py-4 bg-[#07213D] text-[#EEBF63] font-bold rounded-2xl shadow-lg uppercase text-xs tracking-widest">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modalTitle').innerText = 'Tambah Informasi Baru';
    document.querySelector('form').reset();
    document.getElementById('id_info').value = '';
}

function editInfo(data) {
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modalTitle').innerText = 'Edit Informasi';
    document.getElementById('id_info').value = data.id_info;
    document.getElementById('judul_info').value = data.judul_info;
    document.getElementById('deskripsi_singkat').value = data.deskripsi_singkat;
    document.getElementById('link_tujuan').value = data.link_tujuan;
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
}

// Fungsi konfirmasi hapus
function hapusInfo(id) {
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Informasi ini akan hilang secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '?hapus=' + id;
        }
    });
}

// SweetAlert Notification
<?php if(isset($_SESSION['alert'])): ?>
    Swal.fire({
        icon: '<?= $_SESSION['alert']['type'] ?>',
        title: '<?= $_SESSION['alert']['title'] ?>',
        text: '<?= $_SESSION['alert']['msg'] ?>',
        timer: 3000,
        showConfirmButton: false
    });
    <?php unset($_SESSION['alert']); ?>
<?php endif; ?>
</script>

</body>
</html>