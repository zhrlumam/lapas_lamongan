<?php
include "config/koneksi.php";

$pendaftaran_sukses = false;
$data_laporan = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Menangkap data baru: Judul dan Email
    $judul    = mysqli_real_escape_string($conn, $_POST['judul_pengaduan']);
    $email    = mysqli_real_escape_string($conn, $_POST['email_pelapor']);
    
    $nama     = mysqli_real_escape_string($conn, $_POST['nama_pelapor']);
    $kontak   = mysqli_real_escape_string($conn, $_POST['kontak_pelapor']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori_id']);
    $isi      = mysqli_real_escape_string($conn, $_POST['isi_pengaduan']);

    // Update query INSERT untuk menyertakan judul_pengaduan dan email_pelapor
    $query = mysqli_query($conn, "INSERT INTO pengaduan (nama_pelapor, kontak_pelapor, email_pelapor, judul_pengaduan, kategori_id, isi_pengaduan, status) 
                                 VALUES ('$nama', '$kontak', '$email', '$judul', '$kategori', '$isi', 'Masuk')");

    if ($query) {
        $data_laporan = [
            'nama'   => $nama,
            'kontak' => $kontak,
            'judul'  => $judul, // Menyimpan judul untuk ditampilkan di halaman sukses
            'tgl'    => date('Y-m-d H:i:s')
        ];
        $pendaftaran_sukses = true;
    } else {
        echo "<script>alert('Gagal mengirim pengaduan: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sukses Terkirim - Lapas Kelas IIB Lamongan</title>
    <link rel="icon" type="image/png" href="assets/images/logo_imigrasi.png">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { imipas: { blue: '#07213D', gold: '#EEBF63' } } } }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .success-card { border-radius: 2.5rem; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-6">

    <?php if ($pendaftaran_sukses): ?>
    <div class="w-full max-w-md bg-white success-card shadow-2xl shadow-blue-100/50 border border-slate-100 overflow-hidden animate-in fade-in zoom-in duration-500">
        
        <div class="bg-imipas-blue p-10 text-center relative overflow-hidden">
            <div class="relative z-10">
                <div class="w-20 h-20 bg-imipas-gold rounded-3xl rotate-12 mx-auto flex items-center justify-center shadow-xl shadow-imipas-gold/20 mb-6">
                    <i data-lucide="check-circle-2" class="w-10 h-10 text-imipas-blue -rotate-12"></i>
                </div>
                <h2 class="text-white text-2xl font-black uppercase tracking-tight">Laporan Terkirim!</h2>
                <p class="text-imipas-gold/80 text-xs font-bold mt-2 tracking-[0.2em] uppercase">Lapas Kelas IIB Lamongan</p>
            </div>
            <i data-lucide="shield-check" class="absolute -right-4 -bottom-4 w-32 h-32 text-white/5 -rotate-12"></i>
        </div>

        <div class="p-8 space-y-6">
            <div class="space-y-4">
                <div class="text-center px-4">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Judul Pengaduan:</p>
                    <h4 class="text-sm font-bold text-imipas-blue italic">"<?= htmlspecialchars($data_laporan['judul']) ?>"</h4>
                </div>

                <div class="flex flex-col items-center text-center p-6 bg-slate-50 rounded-[2rem] border-2 border-dashed border-slate-200">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Gunakan No. WA Untuk Melacak:</p>
                    <h3 class="text-2xl font-black text-imipas-blue tracking-widest"><?= htmlspecialchars($data_laporan['kontak']) ?></h3>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-slate-50 rounded-2xl">
                        <p class="text-[9px] font-bold text-slate-400 uppercase mb-1">Nama Pelapor</p>
                        <p class="text-xs font-black text-slate-700 truncate"><?= htmlspecialchars($data_laporan['nama']) ?></p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl">
                        <p class="text-[9px] font-bold text-slate-400 uppercase mb-1">Waktu Kirim</p>
                        <p class="text-xs font-black text-slate-700"><?= date('H:i', strtotime($data_laporan['tgl'])) ?> WIB</p>
                    </div>
                </div>
            </div>

            <div class="flex items-start gap-4 p-4 bg-blue-50 rounded-2xl border border-blue-100">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i data-lucide="info" class="w-4 h-4 text-blue-600"></i>
                </div>
                <p class="text-[11px] text-blue-700 leading-relaxed">
                    Laporan Anda telah diterima. Petugas kami akan segera menindaklanjuti. Pantau terus status aduan Anda melalui menu <b>Lacak</b>.
                </p>
            </div>

            <div class="space-y-3">
                <a href="pengaduan.php?kontak_lacak=<?= $data_laporan['kontak'] ?>" class="group w-full bg-imipas-blue text-imipas-gold py-5 rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] text-center shadow-xl shadow-blue-900/10 hover:bg-slate-800 transition-all flex items-center justify-center gap-3">
                    Lihat Status Laporan <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                </a>
                
                <a href="pengaduan.php" class="w-full block py-2 text-center text-slate-400 font-bold text-[10px] uppercase tracking-widest hover:text-imipas-blue transition-colors">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>