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
   PROSES HAPUS
========================= */
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $q = mysqli_query($conn, "SELECT gambar FROM produk WHERE id_produk=$id");
    $row = mysqli_fetch_assoc($q);

    if ($row && $row['gambar'] && file_exists("../uploads/".$row['gambar'])) {
        unlink("../uploads/".$row['gambar']);
    }

    if(mysqli_query($conn, "DELETE FROM produk WHERE id_produk=$id")) {
        $_SESSION['alert'] = ['type' => 'success', 'title' => 'Dihapus!', 'msg' => 'Produk berhasil dihapus.'];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'title' => 'Gagal!', 'msg' => 'Produk gagal dihapus.'];
    }
    header("Location: kelola_produk.php");
    exit;
}

/* =========================
   PROSES SIMPAN (TAMBAH & EDIT)
========================= */
if (isset($_POST['simpan_produk'])) {
    $id   = $_POST['id_produk'] ?? '';
    $nama = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $kat  = mysqli_real_escape_string($conn, $_POST['kategori']);
    $desk = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $gambar_lama = $_POST['gambar_lama'] ?? '';
    $gambar = $gambar_lama;

    if (!empty($_FILES['gambar']['name'])) {
        if (!file_exists("../uploads")) {
            mkdir("../uploads", 0777, true);
        }

        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];

        if (in_array($ext, $allowed)) {
            if ($gambar_lama && file_exists("../uploads/".$gambar_lama)) {
                unlink("../uploads/".$gambar_lama);
            }
            $gambar = time()."-".rand(1000,9999).".".$ext;
            move_uploaded_file($_FILES['gambar']['tmp_name'], "../uploads/".$gambar);
        }
    }

    if ($id) {
        $query = "UPDATE produk SET nama_produk='$nama', kategori='$kat', deskripsi='$desk', gambar='$gambar' WHERE id_produk='$id'";
        $msg = "Produk berhasil diperbarui.";
    } else {
        $query = "INSERT INTO produk (nama_produk,kategori,deskripsi,gambar) VALUES ('$nama','$kat','$desk','$gambar')";
        $msg = "Produk baru berhasil ditambahkan.";
    }

    if(mysqli_query($conn, $query)) {
        $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'msg' => $msg];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'title' => 'Gagal!', 'msg' => 'Terjadi kesalahan sistem.'];
    }

    header("Location: kelola_produk.php");
    exit;
}

/* =========================
   LOGIKA PAGINATION
========================= */
$batas = 5;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

$semua_data = mysqli_query($conn, "SELECT id_produk FROM produk");
$total_data = mysqli_num_rows($semua_data);
$total_halaman = ceil($total_data / $batas);

$produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk DESC LIMIT $halaman_awal, $batas");
$no = $halaman_awal + 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Produk | Lapas Lamongan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
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
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
                <h1 class="text-lg font-bold text-slate-800">Manajemen Produk</h1>
            </div>
            <div class="flex items-center gap-2 px-3 py-1 bg-slate-50 rounded-2xl border border-slate-100 text-xs font-bold text-slate-600 uppercase">
                <?= htmlspecialchars($admin_name) ?>
                <div class="w-8 h-8 rounded-full bg-[#07213D] flex items-center justify-center text-[#EEBF63]">
                    <i class="fa-solid fa-user-gear"></i>
                </div>
            </div>
        </header>

        <div class="p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Katalog Produk</h2>
                    <p class="text-slate-500 text-sm">Produk unggulan hasil karya warga binaan Lapas Lamongan.</p>
                </div>
                <button onclick="openModal()" class="bg-[#07213D] text-[#EEBF63] px-6 py-3 rounded-2xl font-bold shadow-xl hover:brightness-110 transition flex items-center justify-center gap-2 text-xs uppercase tracking-widest">
                    <i class="fa-solid fa-plus"></i> Tambah Produk
                </button>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 uppercase">
                                <th class="p-5 text-[10px] font-black tracking-widest w-16">No</th>
                                <th class="p-5 text-[10px] font-black tracking-widest">Detail Produk</th>
                                <th class="p-5 text-[10px] font-black tracking-widest">Kategori</th>
                                <th class="p-5 text-[10px] font-black tracking-widest text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php while($r=mysqli_fetch_assoc($produk)): ?>
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-5 text-slate-400 font-bold text-sm"><?= $no++ ?></td>
                                <td class="p-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-100 overflow-hidden flex-shrink-0 shadow-inner border border-slate-100">
                                            <?php if($r['gambar']): ?>
                                                <img src="../uploads/<?= $r['gambar'] ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fa-solid fa-image"></i></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="max-w-xs">
                                            <h4 class="font-bold text-slate-800 text-sm leading-tight mb-1"><?= htmlspecialchars($r['nama_produk']) ?></h4>
                                            <p class="text-xs text-slate-400 line-clamp-1"><?= substr(strip_tags($r['deskripsi']), 0, 50) ?>...</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-5">
                                    <span class="text-[10px] font-black px-3 py-1 bg-blue-50 text-blue-600 rounded-full uppercase">
                                        <?= htmlspecialchars($r['kategori']) ?>
                                    </span>
                                </td>
                                <td class="p-5">
                                    <div class="flex justify-center gap-2">
                                        <button onclick='editProduk(<?= json_encode($r) ?>)' class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </button>
                                        
                                        <button onclick="konfirmasiHapus(<?= $r['id_produk'] ?>)" class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-500 hover:bg-red-600 hover:text-white transition">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        Hal <?= $halaman ?> dari <?= $total_halaman ?>
                    </p>
                    <div class="flex gap-2">
                        <?php if($halaman > 1): ?>
                            <a href="?halaman=<?= $halaman - 1 ?>" class="px-3 py-1 text-[10px] font-black bg-white border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-100 transition uppercase tracking-widest">Prev</a>
                        <?php endif; ?>

                        <?php if($halaman < $total_halaman): ?>
                            <a href="?halaman=<?= $halaman + 1 ?>" class="px-3 py-1 text-[10px] font-black bg-white border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-100 transition uppercase tracking-widest">Next</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<div id="modal" class="fixed inset-0 bg-[#07213D]/60 backdrop-blur-sm hidden flex items-center justify-center z-[100] p-4">
    <div class="bg-white w-full max-w-lg p-8 rounded-[2.5rem] shadow-2xl animate-in zoom-in duration-300">
        <div class="flex justify-between items-center mb-8">
            <h2 id="modalTitle" class="text-xl font-black text-[#07213D] uppercase tracking-tight">Form Produk</h2>
            <button onclick="closeModal()" class="text-slate-400 hover:text-red-500 transition"><i class="fa-solid fa-circle-xmark text-2xl"></i></button>
        </div>

        <form method="POST" enctype="multipart/form-data" class="space-y-5">
            <input type="hidden" name="id_produk" id="in_id_produk">
            <input type="hidden" name="gambar_lama" id="in_gambar_lama">

            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1 block">Nama Produk</label>
                <input type="text" name="nama_produk" id="in_nama_produk" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#EEBF63] outline-none transition text-sm font-semibold" required>
            </div>

            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1 block">Kategori</label>
                <input type="text" name="kategori" id="in_kategori" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#EEBF63] outline-none transition text-sm font-semibold" required>
            </div>

            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1 block">Deskripsi Produk</label>
                <textarea name="deskripsi" id="in_deskripsi" rows="3" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-[#EEBF63] outline-none transition text-sm font-medium" required></textarea>
            </div>

            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1 block">Foto Produk</label>
                <input type="file" name="gambar" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-[#07213D] file:text-[#EEBF63] hover:file:brightness-110 cursor-pointer transition border border-slate-200 rounded-2xl py-2 bg-slate-50">
            </div>

            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeModal()" class="flex-1 py-4 bg-slate-100 text-slate-500 font-bold rounded-2xl hover:bg-slate-200 transition text-xs uppercase tracking-widest">Batal</button>
                <button type="submit" name="simpan_produk" class="flex-1 py-4 bg-[#07213D] text-[#EEBF63] font-bold rounded-2xl hover:brightness-110 transition text-xs uppercase tracking-widest shadow-lg shadow-[#07213D]/20">Simpan Produk</button>
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
    document.getElementById('modalTitle').innerText = 'Tambah Produk Baru';
    document.querySelector('form').reset();
    document.getElementById('in_id_produk').value = '';
    document.getElementById('in_gambar_lama').value = '';
}

function editProduk(d) {
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modalTitle').innerText = 'Perbarui Produk';
    document.getElementById('in_id_produk').value = d.id_produk;
    document.getElementById('in_nama_produk').value = d.nama_produk;
    document.getElementById('in_kategori').value = d.kategori;
    document.getElementById('in_deskripsi').value = d.deskripsi;
    document.getElementById('in_gambar_lama').value = d.gambar;
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
}

function konfirmasiHapus(id) {
    Swal.fire({
        title: 'Yakin hapus produk?',
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