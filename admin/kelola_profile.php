<?php
session_start();
include "../config/koneksi.php";

// 1. SINKRONISASI KONEKSI
if (!isset($conn) && isset($pdo)) { $conn = $pdo; }

// 2. PROTEKSI & AMBIL DATA ADMIN
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

include "layout/role_check.php";
check_role(['Humas']);

$admin_id   = $_SESSION['admin_id'] ?? '0';
$admin_name = $_SESSION['nama'] ?? $_SESSION['admin'] ?? 'Administrator';

// 3. AMBIL DATA PROFIL (ID 1)
try {
    $stmt_profil = $conn->prepare("SELECT * FROM profil_lapas WHERE id = 1");
    $stmt_profil->execute();
    $d = $stmt_profil->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Gagal mengambil data profil.");
}

// 4. PROSES UPDATE
if (isset($_POST['simpan'])) {
    $nama_kepala     = $_POST['nama_kepala'];
    $jabatan_kepala  = $_POST['jabatan_kepala'];
    $sambutan_kepala = $_POST['sambutan_kepala'];
    $sejarah         = $_POST['sejarah'];
    $visi            = $_POST['visi'];
    $misi            = $_POST['misi'];

    $foto_kepala = $_FILES['foto_kepala']['name'];
    $upload_ok = true;
    $nama_file_db = $d['foto_kepala'];

    if (!empty($foto_kepala)) {
        $ekstensi_diperbolehkan = ['png', 'jpg', 'jpeg', 'webp'];
        $file_tmp = $_FILES['foto_kepala']['tmp_name'];
        $file_ext = strtolower(pathinfo($foto_kepala, PATHINFO_EXTENSION));

        if (in_array($file_ext, $ekstensi_diperbolehkan)) {
            $nama_file_baru = "pimpinan-" . time() . "." . $file_ext;
            
            if (!file_exists("../assets/images/")) mkdir("../assets/images/", 0777, true);

            if (!empty($d['foto_kepala']) && $d['foto_kepala'] != 'default.jpg') {
                if (file_exists("../assets/images/" . $d['foto_kepala'])) {
                    unlink("../assets/images/" . $d['foto_kepala']);
                }
            }
            
            if (move_uploaded_file($file_tmp, '../assets/images/' . $nama_file_baru)) {
                $nama_file_db = $nama_file_baru;
            }
        } else {
            $upload_ok = false;
            $_SESSION['alert'] = ['type' => 'warning', 'title' => 'Format Salah!', 'msg' => 'Gunakan JPG, PNG, atau WEBP.'];
        }
    }

    if ($upload_ok) {
        try {
            $sql = "UPDATE profil_lapas SET 
                    nama_kepala = ?, jabatan_kepala = ?, sambutan_kepala = ?,
                    sejarah = ?, visi = ?, misi = ?, foto_kepala = ? WHERE id = 1";
            $stmt_update = $conn->prepare($sql);
            $stmt_update->execute([$nama_kepala, $jabatan_kepala, $sambutan_kepala, $sejarah, $visi, $misi, $nama_file_db]);
            
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'BERHASIL!', 'msg' => 'Profil Instansi diperbarui.'];
        } catch (Exception $e) {
            $_SESSION['alert'] = ['type' => 'error', 'title' => 'GAGAL!', 'msg' => 'Kesalahan database.'];
        }
    }
    header("Location: kelola_profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Profil | Lapas Lamongan</title>
    <link rel="icon" type="image/png" href="../assets/images/logo_imigrasi.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { imipas: '#07213D', dignity: '#EEBF63' }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #07213D; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-100 h-full overflow-hidden text-slate-700">

<div class="flex h-screen overflow-hidden relative">
    <?php include "layout/sidebar.php"; ?>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <header class="bg-white border-b-4 border-dignity h-20 flex items-center justify-between px-6 z-20 shadow-sm sticky top-0">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="lg:hidden w-10 h-10 flex items-center justify-center bg-slate-100 rounded-xl text-imipas">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>
                <h1 class="text-xs md:text-sm font-black text-imipas uppercase tracking-tight ">Profil Instansi</h1>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right border-r pr-4 border-slate-200 hidden md:block text-imipas">
                    <p class="text-[9px] font-black uppercase tracking-widest leading-none mb-1 opacity-50">ID Admin: #<?= $admin_id ?></p>
                    <p class="text-xs font-bold leading-none"><?= htmlspecialchars($admin_name) ?></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-imipas flex items-center justify-center text-dignity shadow-sm border border-slate-700">
                    <i class="fa-solid fa-user-shield text-xs"></i>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 md:p-8 custom-scrollbar bg-slate-50">
            <div class="mb-8">
                <h2 class="text-2xl font-black text-imipas uppercase  tracking-tight">Pengaturan Profil</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em]">Identitas Resmi Lembaga Pemasyarakatan</p>
            </div>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                    
                    <div class="space-y-6">
                        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-200 shadow-sm relative overflow-hidden">
                            <h3 class="font-black text-[10px] text-imipas mb-8 uppercase tracking-[0.2em]  border-b pb-4 border-slate-100 flex items-center gap-2">
                                <i class="fa-solid fa-user-tie text-dignity"></i> Personalia Pimpinan
                            </h3>
                            
                            <div class="mb-8 flex flex-col items-center">
                                <div class="relative group">
                                    <div class="w-40 h-52 rounded-3xl overflow-hidden border-4 border-slate-50 shadow-xl bg-slate-100 ring-2 ring-dignity/30">
                                        <img id="previewFoto" src="../assets/images/<?= !empty($d['foto_kepala']) ? $d['foto_kepala'] : 'default.jpg' ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    </div>
                                    <label for="inputFoto" class="absolute -bottom-2 -right-2 cursor-pointer bg-imipas hover:bg-slate-800 text-dignity w-10 h-10 rounded-2xl flex items-center justify-center shadow-2xl transition-all border-2 border-white">
                                        <i class="fa-solid fa-camera text-xs"></i>
                                        <input type="file" id="inputFoto" name="foto_kepala" class="hidden" accept="image/*">
                                    </label>
                                </div>
                                <p id="fileName" class="text-[9px] text-blue-500 mt-4 font-black uppercase tracking-widest text-center h-4"></p>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="text-[9px] font-black uppercase text-slate-400 tracking-widest mb-1 block">Nama & Gelar</label>
                                    <input type="text" name="nama_kepala" value="<?= htmlspecialchars($d['nama_kepala'] ?? '') ?>" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-imipas/20 outline-none text-[11px] font-bold text-imipas transition-all">
                                </div>
                                <div>
                                    <label class="text-[9px] font-black uppercase text-slate-400 tracking-widest mb-1 block">Jabatan</label>
                                    <input type="text" name="jabatan_kepala" value="<?= htmlspecialchars($d['jabatan_kepala'] ?? '') ?>" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-imipas/20 outline-none text-[11px] font-bold text-imipas transition-all">
                                </div>
                                <div>
                                    <label class="text-[9px] font-black uppercase text-slate-400 tracking-widest mb-1 block">Sambutan Singkat</label>
                                    <textarea name="sambutan_kepala" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-imipas/20 outline-none text-[11px] font-medium leading-relaxed transition-all"><?= htmlspecialchars($d['sambutan_kepala'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="xl:col-span-2 space-y-6">
                        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm">
                            <h3 class="font-black text-[10px] text-imipas mb-8 uppercase tracking-[0.2em]  border-b pb-4 border-slate-100 flex items-center gap-2">
                                <i class="fa-solid fa-building-shield text-dignity"></i> Konten Publikasi
                            </h3>
                            
                            <div class="space-y-6">
                                <div>
                                    <label class="text-[9px] font-black uppercase text-slate-400 tracking-widest mb-1 block ">Sejarah Lembaga</label>
                                    <textarea name="sejarah" rows="6" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-[1.5rem] focus:ring-2 focus:ring-imipas/20 outline-none text-[12px] leading-relaxed transition-all" placeholder="Tuliskan sejarah resmi..."><?= htmlspecialchars($d['sejarah'] ?? '') ?></textarea>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fa-solid fa-eye text-blue-400 text-[10px]"></i>
                                            <label class="text-[9px] font-black uppercase text-slate-400 tracking-widest block">Visi</label>
                                        </div>
                                        <textarea name="visi" rows="5" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-[1.5rem] focus:ring-2 focus:ring-imipas/20 outline-none text-[12px] leading-relaxed transition-all"><?= htmlspecialchars($d['visi'] ?? '') ?></textarea>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fa-solid fa-bullseye text-red-400 text-[10px]"></i>
                                            <label class="text-[9px] font-black uppercase text-slate-400 tracking-widest block">Misi</label>
                                        </div>
                                        <textarea name="misi" rows="5" class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-[1.5rem] focus:ring-2 focus:ring-imipas/20 outline-none text-[12px] leading-relaxed transition-all"><?= htmlspecialchars($d['misi'] ?? '') ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-10 flex justify-end">
                                <button type="submit" name="simpan" class="bg-imipas text-dignity px-10 py-5 rounded-2xl font-black shadow-lg hover:brightness-125 transition-all flex items-center gap-4 group uppercase tracking-[0.2em] text-[10px] border-b-4 border-slate-900 active:translate-y-1 active:border-b-0">
                                    <i class="fa-solid fa-save text-sm"></i> 
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </main>
    </div>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    if (sidebar) sidebar.classList.toggle('-translate-x-full');
    if (overlay) overlay.classList.toggle('hidden');
}

// Preview Foto
const inputFoto = document.getElementById('inputFoto');
const previewFoto = document.getElementById('previewFoto');
const fileName = document.getElementById('fileName');

inputFoto.onchange = evt => {
    const [file] = inputFoto.files;
    if (file) {
        previewFoto.src = URL.createObjectURL(file);
        fileName.innerText = "Terpilih: " + file.name;
    }
}

// Notification
<?php if(isset($_SESSION['alert'])): ?>
    Swal.fire({
        icon: '<?= $_SESSION['alert']['type'] ?>',
        title: '<?= $_SESSION['alert']['title'] ?>',
        text: '<?= $_SESSION['alert']['msg'] ?>',
        confirmButtonColor: '#07213D',
        timer: 3000,
        customClass: { popup: 'rounded-[2rem]' }
    });
    <?php unset($_SESSION['alert']); ?>
<?php endif; ?>
</script>

</body>
</html>