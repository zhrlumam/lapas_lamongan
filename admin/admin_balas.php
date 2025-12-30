<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

$id = mysqli_real_escape_string($conn, $_GET['id']);
$admin_id = $_SESSION['admin']['id_admin']; 

// Ambil data pengaduan beserta kategori
$query = "SELECT p.*, k.nama_kategori 
          FROM pengaduan p 
          LEFT JOIN kategori_pengaduan k ON p.kategori_id = k.id 
          WHERE p.id = '$id'";
$res = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($res);

if (!$data) {
    header("Location: kelola_pengaduan.php");
    exit;
}

$status_notif = "";

if (isset($_POST['kirim_tanggapan'])) {
    $tanggapan = mysqli_real_escape_string($conn, $_POST['isi_tanggapan']);
    $status_baru = mysqli_real_escape_string($conn, $_POST['status']); 

    mysqli_begin_transaction($conn);
    try {
        // Simpan ke tabel tanggapan
        mysqli_query($conn, "INSERT INTO tanggapan (pengaduan_id, admin_id, isi_tanggapan, created_at) 
                             VALUES ('$id', '$admin_id', '$tanggapan', NOW())");
        
        // Update status di tabel pengaduan
        mysqli_query($conn, "UPDATE pengaduan SET status = '$status_baru', updated_at = NOW() WHERE id = '$id'");
        
        mysqli_commit($conn);
        $status_notif = "success";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $status_notif = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balas Pengaduan | Lapas Lamongan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .break-word { overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; }
        .swal2-popup { border-radius: 2rem !important; font-family: 'Plus Jakarta Sans', sans-serif !important; }
    </style>
</head>
<body class="bg-slate-50 pb-20">
    <div class="max-w-5xl mx-auto p-6 mt-10">
        <a href="kelola_pengaduan.php" class="text-slate-500 hover:text-slate-800 flex items-center gap-2 text-sm font-bold transition mb-6 group">
            <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar
        </a>

        <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-slate-200">
            <div class="bg-slate-900 p-10 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
                <div class="relative z-10">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex-1">
                            <p class="text-[10px] font-bold text-amber-400 uppercase tracking-[0.3em] mb-2">Subjek Pengaduan</p>
                            <h1 class="text-3xl font-black italic break-word leading-tight">"<?= htmlspecialchars($data['judul_pengaduan']) ?>"</h1>
                        </div>
                        <div class="flex flex-col items-start md:items-end gap-2">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kategori Laporan</p>
                            <span class="inline-flex items-center gap-2 bg-red-500/10 border border-red-500/50 px-4 py-2 rounded-xl text-red-400 text-xs font-black uppercase tracking-tighter shadow-sm shadow-red-900/20">
                                <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                                <?= $data['nama_kategori'] ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8 lg:p-12 grid grid-cols-1 lg:grid-cols-12 gap-12">
                <div class="lg:col-span-5 space-y-8">
                    <div>
                        <label class="block text-[11px] font-black text-slate-400 uppercase mb-4 tracking-widest">Profil Pengadu</label>
                        <div class="bg-slate-50 rounded-[2rem] border border-slate-100 p-6 space-y-4 shadow-inner">
                            <div class="flex items-center gap-4 border-b border-slate-200 pb-3">
                                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm text-slate-400"><i class="fa-solid fa-user"></i></div>
                                <span class="text-sm font-black text-slate-700 uppercase"><?= htmlspecialchars($data['nama_pelapor'] ?: 'Anonim') ?></span>
                            </div>
                            <div class="flex items-center gap-4 border-b border-slate-200 pb-3">
                                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm text-slate-400"><i class="fa-solid fa-envelope"></i></div>
                                <span class="text-sm font-bold text-slate-600 break-word"><?= htmlspecialchars($data['email_pelapor']) ?></span>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm text-emerald-500"><i class="fa-brands fa-whatsapp"></i></div>
                                <span class="text-sm font-black text-slate-700 font-mono"><?= $data['kontak_pelapor'] ?></span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-400 uppercase mb-4 tracking-widest italic">Pesan / Isi Laporan</label>
                        <div class="p-8 bg-blue-50/50 rounded-[2.5rem] border border-blue-100 relative min-h-[150px]">
                            <i class="fa-solid fa-quote-left text-blue-200 text-4xl absolute top-4 left-6 opacity-50"></i>
                            <div class="relative z-10 text-sm leading-relaxed text-slate-700 font-medium break-word whitespace-pre-wrap italic pt-2">
                                <?= htmlspecialchars($data['isi_pengaduan']) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <form action="" method="POST" class="space-y-6">
                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase mb-4 tracking-widest ml-4">Update Status Laporan</label>
                            <div class="grid grid-cols-3 gap-2 p-2 bg-slate-100 rounded-2xl">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="status" value="Sedang Diproses" class="peer hidden" <?= ($data['status'] == 'Sedang Diproses' || $data['status'] == 'Masuk') ? 'checked' : '' ?>>
                                    <div class="py-3 rounded-xl text-center peer-checked:bg-white peer-checked:text-blue-600 peer-checked:shadow-sm transition-all text-[10px] font-black uppercase text-slate-400">Proses</div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="status" value="Selesai" class="peer hidden" <?= ($data['status'] == 'Selesai') ? 'checked' : '' ?>>
                                    <div class="py-3 rounded-xl text-center peer-checked:bg-white peer-checked:text-emerald-600 peer-checked:shadow-sm transition-all text-[10px] font-black uppercase text-slate-400">Selesai</div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="status" value="Ditolak" class="peer hidden" <?= ($data['status'] == 'Ditolak') ? 'checked' : '' ?>>
                                    <div class="py-3 rounded-xl text-center peer-checked:bg-white peer-checked:text-red-600 peer-checked:shadow-sm transition-all text-[10px] font-black uppercase text-slate-400">Tolak</div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase mb-4 tracking-widest ml-4">Tulis Tanggapan Resmi</label>
                            <textarea name="isi_tanggapan" rows="10" required 
                                placeholder="Berikan jawaban resmi dari pihak Lapas Lamongan..." 
                                class="w-full p-8 bg-white border border-slate-200 rounded-[2.5rem] text-sm focus:ring-4 focus:ring-blue-100 outline-none transition-all shadow-inner"></textarea>
                        </div>
                        
                        <button type="submit" name="kirim_tanggapan" class="w-full bg-slate-900 text-amber-400 font-black py-6 rounded-2xl shadow-xl hover:bg-black hover:-translate-y-1 transition-all uppercase tracking-[0.2em] text-xs">
                            <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Jawaban Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if ($status_notif == "success"): ?>
    <script>
        Swal.fire({
            title: 'Berhasil!',
            text: 'Tanggapan telah terkirim dan status diperbarui.',
            icon: 'success',
            confirmButtonColor: '#0f172a',
            confirmButtonText: 'Kembali Ke Daftar'
        }).then(() => {
            window.location.href = 'kelola_pengaduan.php';
        });
    </script>
    <?php elseif ($status_notif == "error"): ?>
    <script>
        Swal.fire({
            title: 'Gagal!',
            text: 'Terjadi kesalahan sistem saat mengirim tanggapan.',
            icon: 'error',
            confirmButtonColor: '#ef4444'
        });
    </script>
    <?php endif; ?>
</body>
</html>