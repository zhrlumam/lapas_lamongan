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

// 2. AMBIL DATA PROFIL (ID 1)
$query = mysqli_query($conn, "SELECT * FROM profil_lapas WHERE id = 1");
$d = mysqli_fetch_assoc($query);

// 3. PROSES UPDATE
if (isset($_POST['simpan'])) {
    $nama_kepala      = mysqli_real_escape_string($conn, $_POST['nama_kepala']);
    $jabatan_kepala   = mysqli_real_escape_string($conn, $_POST['jabatan_kepala']);
    $sambutan_kepala  = mysqli_real_escape_string($conn, $_POST['sambutan_kepala']);
    $sejarah          = mysqli_real_escape_string($conn, $_POST['sejarah']);
    $visi             = mysqli_real_escape_string($conn, $_POST['visi']);
    $misi             = mysqli_real_escape_string($conn, $_POST['misi']);

    $foto_kepala = $_FILES['foto_kepala']['name'];
    $upload_ok = true;
    
    if ($foto_kepala != "") {
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg', 'webp');
        $x = explode('.', $foto_kepala);
        $ekstensi = strtolower(end($x));
        $file_tmp = $_FILES['foto_kepala']['tmp_name'];
        
        // Buat folder jika belum ada
        if (!file_exists("../assets/images/")) {
            mkdir("../assets/images/", 0777, true);
        }

        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            $nama_file_baru = time() . "-" . rand(1000, 9999) . "." . $ekstensi;
            
            // Hapus foto lama jika bukan default dan file ada
            if(!empty($d['foto_kepala']) && $d['foto_kepala'] != 'default.jpg') {
                if(file_exists("../assets/images/".$d['foto_kepala'])) {
                    unlink("../assets/images/".$d['foto_kepala']);
                }
            }
            
            move_uploaded_file($file_tmp, '../assets/images/' . $nama_file_baru);
            $query_update = "UPDATE profil_lapas SET 
                nama_kepala='$nama_kepala', jabatan_kepala='$jabatan_kepala', sambutan_kepala='$sambutan_kepala',
                sejarah='$sejarah', visi='$visi', misi='$misi', foto_kepala='$nama_file_baru' WHERE id=1";
        } else {
            $upload_ok = false;
            $_SESSION['alert'] = ['type' => 'warning', 'title' => 'Format Salah!', 'msg' => 'Gunakan format JPG, JPEG, PNG, atau WEBP.'];
        }
    } else {
        $query_update = "UPDATE profil_lapas SET 
            nama_kepala='$nama_kepala', jabatan_kepala='$jabatan_kepala', sambutan_kepala='$sambutan_kepala',
            sejarah='$sejarah', visi='$visi', misi='$misi' WHERE id=1";
    }

    if ($upload_ok) {
        if (mysqli_query($conn, $query_update)) {
            $_SESSION['alert'] = ['type' => 'success', 'title' => 'Berhasil!', 'msg' => 'Profil Lapas telah diperbarui.'];
        } else {
            $_SESSION['alert'] = ['type' => 'error', 'title' => 'Gagal!', 'msg' => 'Terjadi kesalahan database.'];
        }
    }
    
    header("Location: kelola_profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kelola Profil | Lapas Lamongan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
    /* Custom Scrollbar */
    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
  </style>
</head>
<body class="bg-slate-50 text-slate-700">

<div class="min-h-screen flex">
  <?php include "layout/menu_admin.php"; ?>

  <main class="flex-1 flex flex-col min-w-0">
    <header class="bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center sticky top-0 z-20">
      <div class="flex items-center gap-4">
          <h1 class="text-xl font-bold text-slate-800">Manajemen Profil Instansi</h1>
      </div>
      <div class="flex items-center gap-3 bg-slate-50 px-3 py-1.5 rounded-2xl border border-slate-100 uppercase">
          <span class="text-xs font-bold text-slate-600"><?= htmlspecialchars($admin_name) ?></span>
          <div class="w-8 h-8 rounded-full bg-[#07213D] flex items-center justify-center text-[#EEBF63]">
              <i class="fa-solid fa-user-gear text-xs"></i>
          </div>
      </div>
    </header>

    <div class="p-6 md:p-8">
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
          
          <div class="space-y-6">
            <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm">
              <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="fa-solid fa-user-tie text-blue-500"></i> Data Pimpinan
              </h3>
              
              <div class="mb-6 flex flex-col items-center">
                <div class="relative group">
                    <div class="w-40 h-52 rounded-2xl overflow-hidden border-4 border-white shadow-lg bg-slate-100">
                        <img id="previewFoto" src="../assets/images/<?= !empty($d['foto_kepala']) ? $d['foto_kepala'] : 'default.jpg' ?>" class="w-full h-full object-cover">
                    </div>
                    <label for="inputFoto" class="absolute bottom-2 right-2 cursor-pointer bg-[#07213D] hover:bg-blue-700 text-[#EEBF63] w-10 h-10 rounded-full flex items-center justify-center shadow-lg transition transform hover:scale-110">
                        <i class="fa-solid fa-camera"></i>
                        <input type="file" id="inputFoto" name="foto_kepala" class="hidden" accept="image/png, image/jpeg, image/jpg, image/webp">
                    </label>
                </div>
                <p class="text-[10px] text-slate-400 mt-4 uppercase font-bold tracking-widest text-center">Rekomendasi: 3x4 atau 4x6</p>
                <p id="fileName" class="text-[10px] text-blue-600 mt-1 font-bold truncate max-w-[200px] text-center"></p>
              </div>

              <div class="space-y-4">
                <div>
                  <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Nama Lengkap & Gelar</label>
                  <input type="text" name="nama_kepala" value="<?= htmlspecialchars($d['nama_kepala']) ?>" required class="w-full mt-1 px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#EEBF63] outline-none text-sm font-semibold">
                </div>
                <div>
                  <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Jabatan Resmi</label>
                  <input type="text" name="jabatan_kepala" value="<?= htmlspecialchars($d['jabatan_kepala']) ?>" class="w-full mt-1 px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#EEBF63] outline-none text-sm font-semibold">
                </div>
                <div>
                  <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Ringkasan Sambutan</label>
                  <textarea name="sambutan_kepala" rows="4" class="w-full mt-1 px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#EEBF63] outline-none text-sm leading-relaxed"><?= htmlspecialchars($d['sambutan_kepala']) ?></textarea>
                </div>
              </div>
            </div>
          </div>

          <div class="xl:col-span-2 space-y-6">
            <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm">
              <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                <i class="fa-solid fa-landmark text-amber-500"></i> Informasi Instansi
              </h3>
              
              <div class="space-y-6">
                <div>
                  <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Sejarah Singkat Lapas</label>
                  <textarea name="sejarah" rows="6" class="w-full mt-1 px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#EEBF63] outline-none text-sm leading-relaxed" placeholder="Tuliskan sejarah berdirinya lapas..."><?= htmlspecialchars($d['sejarah']) ?></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Visi</label>
                    <textarea name="visi" rows="5" class="w-full mt-1 px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#EEBF63] outline-none text-sm leading-relaxed"><?= htmlspecialchars($d['visi']) ?></textarea>
                  </div>
                  <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Misi</label>
                    <textarea name="misi" rows="5" class="w-full mt-1 px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#EEBF63] outline-none text-sm leading-relaxed"><?= htmlspecialchars($d['misi']) ?></textarea>
                  </div>
                </div>
              </div>

              <div class="mt-10 flex justify-end">
                <button type="submit" name="simpan" class="bg-[#07213D] hover:brightness-125 text-[#EEBF63] px-8 py-4 rounded-2xl font-bold shadow-lg transition flex items-center gap-3 group uppercase tracking-widest text-xs">
                  <i class="fa-solid fa-floppy-disk group-hover:scale-110 transition"></i> Simpan Semua Perubahan
                </button>
              </div>
            </div>
          </div>

        </div>
      </form>
    </div>
  </main>
</div>

<script>
// Logic Preview Foto Saat Upload
const inputFoto = document.getElementById('inputFoto');
const previewFoto = document.getElementById('previewFoto');
const fileName = document.getElementById('fileName');

inputFoto.onchange = evt => {
    const [file] = inputFoto.files;
    if (file) {
        previewFoto.src = URL.createObjectURL(file);
        fileName.innerText = "File terpilih: " + file.name;
    }
}

// SweetAlert Notification
<?php if(isset($_SESSION['alert'])): ?>
    Swal.fire({
        icon: '<?= $_SESSION['alert']['type'] ?>',
        title: '<?= $_SESSION['alert']['title'] ?>',
        text: '<?= $_SESSION['alert']['msg'] ?>',
        confirmButtonColor: '#07213D',
        timer: 3500
    });
    <?php unset($_SESSION['alert']); ?>
<?php endif; ?>
</script>

</body>
</html>