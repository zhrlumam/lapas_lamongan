<?php
session_start();
include "../config/koneksi.php";

// 1. Cek Login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Perbaikan: Variabel admin berisi string username
$admin_name = $_SESSION['admin'];

/* =========================
   PROSES HAPUS
========================= */
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    $q = mysqli_query($conn, "SELECT gambar FROM berita WHERE id_berita='$id'");
    $row = mysqli_fetch_assoc($q);

    if ($row && $row['gambar'] && file_exists('../assets/images/'.$row['gambar'])) {
        unlink('../assets/images/'.$row['gambar']);
    }

    if(mysqli_query($conn, "DELETE FROM berita WHERE id_berita='$id'")) {
        $_SESSION['alert'] = ['type' => 'success', 'title' => 'Dihapus!', 'msg' => 'Berita berhasil dihapus.'];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'title' => 'Gagal!', 'msg' => 'Data gagal dihapus.'];
    }
    header("Location: kelola_berita.php");
    exit;
}

/* =========================
   PROSES TAMBAH & EDIT
========================= */
if (isset($_POST['simpan_berita'])) {
    $id      = $_POST['id_berita'] ?? '';
    $judul   = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi     = mysqli_real_escape_string($conn, $_POST['isi']);
    $tanggal = $_POST['tanggal'];
    $gambar_lama = $_POST['gambar_lama'] ?? '';
    $gambar = $gambar_lama;

    if (!empty($_FILES['gambar']['name'])) {
        if (!file_exists('../assets/images')) { mkdir('../assets/images', 0777, true); }
        if ($gambar_lama && file_exists('../assets/images/'.$gambar_lama)) {
            unlink('../assets/images/'.$gambar_lama);
        }
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar = time() . '-' . rand(1000,9999) . '.' . $ext;
        move_uploaded_file($_FILES['gambar']['tmp_name'], '../assets/images/' . $gambar);
    }

    if ($id) {
        $query = "UPDATE berita SET judul='$judul', isi='$isi', tanggal='$tanggal', gambar='$gambar' WHERE id_berita='$id'";
        $msg = "Berita berhasil diperbarui.";
    } else {
        $query = "INSERT INTO berita (judul, isi, gambar, tanggal) VALUES ('$judul','$isi','$gambar', '$tanggal')";
        $msg = "Berita baru telah diposting.";
    }

    if(mysqli_query($conn, $query)) {
        $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'msg' => $msg];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'title' => 'Gagal!', 'msg' => 'Terjadi kesalahan sistem.'];
    }
    header("Location: kelola_berita.php");
    exit;
}

$berita = mysqli_query($conn, "SELECT * FROM berita ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Berita | Lapas Lamongan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
</head>
<body class="bg-slate-50 text-slate-700">

<div class="min-h-screen flex relative">
    <div id="overlay" class="fixed inset-0 bg-black/50 z-30 hidden md:hidden" onclick="toggleSidebar()"></div>
    <?php include "layout/menu_admin.php"; ?>

    <main class="flex-1 overflow-x-hidden flex flex-col">
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center sticky top-0 z-20">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="md:hidden text-slate-600 p-2 hover:bg-slate-100 rounded-lg">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>
                <h1 class="text-lg font-bold text-slate-800">Manajemen Berita</h1>
            </div>
            <div class="flex items-center gap-3 bg-slate-50 px-3 py-1.5 rounded-2xl border border-slate-100">
                <span class="text-xs font-bold text-slate-600 uppercase"><?= htmlspecialchars($admin_name) ?></span>
                <div class="w-8 h-8 rounded-full bg-[#07213D] flex items-center justify-center text-[#EEBF63] text-[10px]">
                    <i class="fa-solid fa-user-gear"></i>
                </div>
            </div>
        </header>

        <div class="p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Daftar Artikel</h2>
                    <p class="text-slate-500 text-sm">Kelola konten berita yang tampil di halaman depan website.</p>
                </div>
                <button onclick="openModal()" class="bg-[#07213D] text-[#EEBF63] px-6 py-3 rounded-2xl font-bold shadow-xl hover:brightness-110 transition flex items-center justify-center gap-2 text-sm uppercase tracking-wider">
                    <i class="fa-solid fa-plus"></i> Tambah Berita
                </button>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100 text-slate-400">
                                <th class="p-5 text-[10px] font-black uppercase tracking-widest w-16">No</th>
                                <th class="p-5 text-[10px] font-black uppercase tracking-widest">Informasi Berita</th>
                                <th class="p-5 text-[10px] font-black uppercase tracking-widest">Tanggal</th>
                                <th class="p-5 text-[10px] font-black uppercase tracking-widest text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $no=1; while($row=mysqli_fetch_assoc($berita)): ?>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="p-5 text-slate-400 font-bold text-sm"><?= $no++ ?></td>
                                <td class="p-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-100 overflow-hidden flex-shrink-0 shadow-inner">
                                            <?php if($row['gambar']): ?>
                                                <img src="../assets/images/<?= $row['gambar'] ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center text-slate-300 text-[10px]">No Image</div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="max-w-md">
                                            <h4 class="font-bold text-slate-800 text-sm mb-1 leading-snug"><?= htmlspecialchars($row['judul']) ?></h4>
                                            <p class="text-xs text-slate-400 line-clamp-2"><?= substr(strip_tags($row['isi']), 0, 100) ?>...</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[10px] font-bold uppercase tracking-tighter">
                                        <i class="fa-regular fa-calendar-check text-blue-500"></i> <?= date('d M Y', strtotime($row['tanggal'])) ?>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <div class="flex justify-center gap-2">
                                        <button onclick='editBerita(<?= json_encode($row) ?>)' class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition shadow-sm">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </button>
                                        <button onclick="hapusData(<?= $row['id_berita'] ?>)" class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-500 hover:bg-red-600 hover:text-white transition shadow-sm">
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
    </main>
</div>

<div id="modal" class="fixed inset-0 bg-[#07213D]/60 backdrop-blur-sm hidden flex items-center justify-center z-[100] p-4">
    <div class="bg-white w-full max-w-xl p-8 rounded-[2.5rem] shadow-2xl animate-in zoom-in duration-300 relative border border-white/20">
        <div class="flex justify-between items-center mb-8">
            <h2 id="modalTitle" class="text-xl font-black text-[#07213D] uppercase tracking-tight">Form Berita</h2>
            <button onclick="closeModal()" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form method="POST" enctype="multipart/form-data" class="space-y-5">
            <input type="hidden" name="id_berita" id="id_berita">
            <input type="hidden" name="gambar_lama" id="gambar_lama">
            
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Judul Berita</label>
                <input type="text" name="judul" id="judul" class="w-full mt-1 p-4 bg-slate-50 rounded-2xl border border-slate-200 outline-none focus:ring-2 focus:ring-blue-500 font-bold text-sm" required>
            </div>

            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Posting</label>
                <input type="date" name="tanggal" id="tanggal_input" class="w-full mt-1 p-4 bg-slate-50 rounded-2xl border border-slate-200 outline-none focus:ring-2 focus:ring-blue-500 font-bold text-sm" required>
            </div>

            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Gambar Sampul</label>
                <input type="file" name="gambar" class="w-full mt-1 p-2 text-xs">
            </div>

            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Isi Berita</label>
                <textarea name="isi" id="isi" rows="5" class="w-full mt-1 p-4 bg-slate-50 rounded-2xl border border-slate-200 outline-none focus:ring-2 focus:ring-blue-500 text-sm" required></textarea>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeModal()" class="flex-1 py-4 bg-slate-100 rounded-2xl font-bold text-sm">BATAL</button>
                <button type="submit" name="simpan_berita" class="flex-1 py-4 bg-[#07213D] text-[#EEBF63] font-bold rounded-2xl text-sm uppercase tracking-widest shadow-lg shadow-blue-900/20">SIMPAN DATA</button>
            </div>
        </form>
    </div>
</div>

<script>
// Alert System
<?php if(isset($_SESSION['alert'])): ?>
    Swal.fire({
        icon: '<?= $_SESSION['alert']['type'] ?>',
        title: '<?= $_SESSION['alert']['title'] ?>',
        text: '<?= $_SESSION['alert']['msg'] ?>',
        confirmButtonColor: '#07213D'
    });
    <?php unset($_SESSION['alert']); ?>
<?php endif; ?>

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}

function openModal() {
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modalTitle').innerText = 'Posting Berita Baru';
    document.querySelector('form').reset();
    document.getElementById('id_berita').value = '';
    document.getElementById('gambar_lama').value = '';
    document.getElementById('tanggal_input').value = new Date().toISOString().split('T')[0];
}

function editBerita(data) {
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modalTitle').innerText = 'Perbarui Berita';
    document.getElementById('id_berita').value = data.id_berita;
    document.getElementById('judul').value = data.judul;
    document.getElementById('isi').value = data.isi;
    document.getElementById('gambar_lama').value = data.gambar;
    document.getElementById('tanggal_input').value = data.tanggal.split(' ')[0];
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
}

function hapusData(id) {
    Swal.fire({
        title: 'Yakin hapus?',
        text: "Data yang dihapus tidak bisa dikembalikan!",
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
    })
}
</script>

</body>
</html>