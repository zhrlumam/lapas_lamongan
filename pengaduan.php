<?php
include "config/koneksi.php";

// 1. Ambil Kategori untuk Form
$kategori_query = mysqli_query($conn, "SELECT * FROM kategori_pengaduan ORDER BY nama_kategori ASC");

// 2. Logika Lacak Berdasarkan Nomor HP / WhatsApp
$hasil_list = null;

if (isset($_GET['kontak_lacak'])) {
    $kontak = mysqli_real_escape_string($conn, $_GET['kontak_lacak']);
    
    // Pastikan kolom nomor_tiket dipanggil dalam query
    $query_list = mysqli_query($conn, "SELECT p.*, k.nama_kategori 
                                       FROM pengaduan p 
                                       LEFT JOIN kategori_pengaduan k ON p.kategori_id = k.id 
                                       WHERE p.kontak_pelapor = '$kontak'
                                       ORDER BY p.created_at DESC");
    
    $hasil_list = [];
    while($row = mysqli_fetch_assoc($query_list)) {
        $id_p = $row['id'];
        $t_query = mysqli_query($conn, "SELECT t.*, a.nama as nama_admin 
                                         FROM tanggapan t 
                                         LEFT JOIN admin a ON t.admin_id = a.id_admin 
                                         WHERE t.pengaduan_id = '$id_p' 
                                         ORDER BY t.created_at ASC");
        
        $tanggapan = [];
        while($t = mysqli_fetch_assoc($t_query)) { $tanggapan[] = $t; }
        $row['list_tanggapan'] = $tanggapan;
        $hasil_list[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lapas Kelas IIB Lamongan - Portal Pengaduan</title>
    <link rel="icon" type="image/png" href="assets/images/logo_imigrasi.png">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <script>
        tailwind.config = {
            theme: { 
                extend: { 
                    colors: { 
                        imipas: { blue: '#07213D', gold: '#EEBF63' } 
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                } 
            }
        }
    </script>
    <style>
        body { background-color: #f8fafc; }
    </style>
</head>
<body class="pb-20">

    <?php include "layout/navbar.php"; ?>

    <main class="max-w-6xl mx-auto p-4 mt-8">
        <div class="mb-10 text-center lg:text-left">
            <h1 class="text-3xl md:text-4xl font-extrabold text-imipas-blue tracking-tight uppercase">Portal <span class="text-imipas-gold italic">Pengaduan</span></h1>
            <p class="text-slate-500 font-medium tracking-tight">Lengkapi data di bawah untuk menyampaikan aspirasi atau keluhan Anda.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <div class="lg:col-span-6">
                <form action="proses_pengaduan.php" method="POST" class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-200 space-y-6">
                    <h2 class="text-imipas-blue font-bold text-xs uppercase flex items-center gap-2 border-b pb-4 tracking-[0.2em]">
                        <i data-lucide="file-text" class="w-4 h-4 text-imipas-gold"></i> Formulir Aduan Resmi
                    </h2>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase ml-1 tracking-widest">Judul Laporan / Pengaduan</label>
                        <input type="text" name="judul_pengaduan" required class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm font-bold focus:border-imipas-gold outline-none transition-all" placeholder="Contoh: Keluhan Pelayanan Kunjungan">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase ml-1 tracking-widest">Nama Pelapor</label>
                            <input type="text" name="nama_pelapor" required class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm focus:border-imipas-gold outline-none transition-all" placeholder="Nama Lengkap">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase ml-1 tracking-widest">WhatsApp (Aktif)</label>
                            <input type="text" name="kontak_pelapor" required class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm focus:border-imipas-gold outline-none transition-all" placeholder="0812xxxx">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase ml-1 tracking-widest">Alamat Email</label>
                        <div class="relative">
                            <i data-lucide="mail" class="absolute left-4 top-4 w-4 h-4 text-slate-300"></i>
                            <input type="email" name="email_pelapor" required class="w-full border-2 border-slate-50 p-4 pl-12 rounded-2xl text-sm focus:border-imipas-gold outline-none transition-all" placeholder="nama@gmail.com">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase ml-1 tracking-widest">Kategori Laporan</label>
                        <select name="kategori_id" required class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm outline-none bg-slate-50 focus:border-imipas-gold">
                            <option value="" disabled selected>Pilih kategori...</option>
                            <?php while($cat = mysqli_fetch_assoc($kategori_query)): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nama_kategori']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase ml-1 tracking-widest">Detail Aduan</label>
                        <textarea name="isi_pengaduan" required class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm h-32 focus:border-imipas-gold outline-none" placeholder="Ceritakan detail kejadian atau keluhan Anda..."></textarea>
                    </div>

                    <button type="submit" name="kirim_pengaduan" class="w-full bg-imipas-blue text-imipas-gold font-black py-5 rounded-2xl shadow-xl shadow-blue-100 uppercase tracking-[0.2em] text-[10px] hover:bg-slate-800 transition-all flex items-center justify-center gap-3">
                        <i data-lucide="send-horizontal" class="w-4 h-4"></i> Kirim Aduan Sekarang
                    </button>
                </form>
            </div>

            <div class="lg:col-span-6 space-y-6">
                <div class="bg-imipas-blue p-8 rounded-[2.5rem] shadow-2xl text-white relative overflow-hidden">
                    <h2 class="font-bold text-[10px] uppercase flex items-center gap-2 mb-6 text-imipas-gold tracking-[0.2em]">
                        <i data-lucide="search" class="w-4 h-4"></i> Lacak Balasan via WhatsApp Yang Sudah Terdaftar
                    </h2>
                    <form action="" method="GET" class="flex gap-2">
                        <input type="text" name="kontak_lacak" value="<?= isset($_GET['kontak_lacak']) ? htmlspecialchars($_GET['kontak_lacak']) : '' ?>" 
                               placeholder="Masukkan Nomor WhatsApp Anda" required 
                               class="flex-1 p-4 rounded-2xl text-sm bg-white/10 border border-white/20 outline-none text-white placeholder:text-white/30 font-bold focus:bg-white/20 transition-all">
                        <button type="submit" class="bg-imipas-gold text-imipas-blue p-4 rounded-2xl hover:bg-white transition-all">
                            <i data-lucide="arrow-right" class="w-5 h-5"></i>
                        </button>
                    </form>
                </div>

                <?php if ($hasil_list !== null): ?>
                    <div class="space-y-4">
                        <?php if (count($hasil_list) > 0): ?>
                            <?php foreach ($hasil_list as $aduan): ?>
                            <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-200">
                                <div class="flex justify-between items-start mb-4">
                                    <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-lg text-[9px] font-black uppercase tracking-widest italic border border-slate-200">
                                        ID: <?= htmlspecialchars(str_replace('TIX-', '', ($aduan['nomor_tiket'] ?? 'N/A'))) ?>
                                    </span>
                                    <?php 
                                        $s = $aduan['status'] ?? 'Masuk';
                                        $c = ($s == 'Masuk') ? 'bg-red-100 text-red-600 border-red-200' : (($s == 'Sedang Diproses') ? 'bg-amber-100 text-amber-600 border-amber-200' : 'bg-green-100 text-green-600 border-green-200');
                                    ?>
                                    <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase border <?= $c ?>"><?= $s ?></span>
                                </div>
                                
                                <h3 class="font-black text-imipas-blue text-sm mb-1 uppercase tracking-tight"><?= htmlspecialchars($aduan['judul_pengaduan']) ?></h3>
                                <p class="text-[10px] text-slate-400 font-bold mb-4 uppercase"><?= htmlspecialchars($aduan['nama_kategori']) ?></p>
                                
                                <div class="bg-slate-50/50 p-4 rounded-2xl border border-dashed border-slate-200 mb-4">
                                    <p class="text-xs text-slate-500 italic leading-relaxed">"<?= htmlspecialchars($aduan['isi_pengaduan']) ?>"</p>
                                </div>

                                <div class="space-y-3 border-t pt-4">
                                    <?php if (count($aduan['list_tanggapan']) > 0): ?>
                                        <?php foreach ($aduan['list_tanggapan'] as $t): ?>
                                            <div class="bg-blue-50/50 p-4 rounded-2xl border-l-4 border-imipas-blue shadow-sm">
                                                <p class="text-xs text-slate-700 font-medium">"<?= nl2br(htmlspecialchars($t['isi_tanggapan'])) ?>"</p>
                                                <p class="mt-3 text-[9px] text-slate-400 font-bold uppercase">
                                                    Petugas: <?= htmlspecialchars($t['nama_admin'] ?? 'Admin') ?> • <?= date('d M Y', strtotime($t['created_at'])) ?>
                                                </p>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="p-4 bg-amber-50 border border-amber-100 rounded-2xl flex items-center gap-3">
                                            <i data-lucide="clock" class="w-4 h-4 text-amber-500"></i>
                                            <p class="text-[10px] text-amber-700 font-bold uppercase">Menunggu Respon Petugas...</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-10 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200">
                                <i data-lucide="user-x" class="w-10 h-10 text-slate-300 mx-auto mb-3"></i>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Nomor WhatsApp Tidak Terdaftar</p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-20 bg-slate-100/50 rounded-[2.5rem] border-2 border-dashed border-slate-200">
                        <i data-lucide="message-square" class="w-12 h-12 text-slate-300 mx-auto mb-4"></i>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest px-10">Masukkan nomor WhatsApp untuk melihat riwayat aduan & balasan petugas</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script> lucide.createIcons(); </script>
</body>
</html>