<?php
include "config/koneksi.php";

function e($string) { return htmlspecialchars($string, ENT_QUOTES, 'UTF-8'); }

$pesan_status = "";

// 1. PROSES SIMPAN
if (isset($_POST['kirim_pengaduan'])) {
    $judul    = $_POST['judul_pengaduan'];
    $nama     = $_POST['nama_pelapor'];
    $kontak   = preg_replace('/[^0-9]/', '', $_POST['kontak_pelapor']);
    $email    = $_POST['email_pelapor'] ?? ""; 
    $kategori = (int)$_POST['kategori_id'];
    $isi      = $_POST['isi_pengaduan'];

    $cek_stmt = $conn->prepare("SELECT id FROM pengaduan WHERE kontak_pelapor = ? AND status != 'Selesai'");
    $cek_stmt->bind_param("s", $kontak);
    $cek_stmt->execute();
    if ($cek_stmt->get_result()->num_rows > 0) {
        $pesan_status = "pending_ada";
    } else {
        $nama_file = "";
        if (isset($_FILES['bukti_foto']) && $_FILES['bukti_foto']['error'] == 0) {
            $target_dir = "uploads/";
            $ext = pathinfo($_FILES["bukti_foto"]["name"], PATHINFO_EXTENSION);
            $nama_file = "IMG_" . time() . "." . $ext;
            $target_file = $target_dir . $nama_file;
            if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png']) && $_FILES["bukti_foto"]["size"] < 2000000) {
                move_uploaded_file($_FILES["bukti_foto"]["tmp_name"], $target_file);
            } else { $pesan_status = "error_file"; }
        }

        if ($pesan_status != "error_file") {
            $stmt = $conn->prepare("INSERT INTO pengaduan (judul_pengaduan, nama_pelapor, kontak_pelapor, email_pelapor, kategori_id, isi_pengaduan, foto_bukti, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'Masuk', NOW())");
            $stmt->bind_param("ssssiss", $judul, $nama, $kontak, $email, $kategori, $isi, $nama_file);
            $pesan_status = ($stmt->execute()) ? "sukses" : "gagal";
            $stmt->close();
        }
    }
}

// 2. LOGIKA LACAK
$hasil_list = null;
if (isset($_GET['kontak_lacak'])) {
    $kontak_lacak = preg_replace('/[^0-9]/', '', $_GET['kontak_lacak']);
    $stmt_lacak = $conn->prepare("SELECT p.*, k.nama_kategori FROM pengaduan p LEFT JOIN kategori_pengaduan k ON p.kategori_id = k.id WHERE p.kontak_pelapor = ? ORDER BY p.created_at DESC");
    $stmt_lacak->bind_param("s", $kontak_lacak);
    $stmt_lacak->execute();
    $query_list = $stmt_lacak->get_result();
    $hasil_list = [];
    while($row = $query_list->fetch_assoc()) {
        $stmt_t = $conn->prepare("SELECT t.*, a.nama as nama_admin FROM tanggapan t LEFT JOIN admin a ON t.admin_id = a.id_admin WHERE t.pengaduan_id = ? ORDER BY t.created_at ASC");
        $stmt_t->bind_param("i", $row['id']);
        $stmt_t->execute();
        $res_t = $stmt_t->get_result();
        $tanggapan = [];
        while($t = $res_t->fetch_assoc()) { $tanggapan[] = $t; }
        $row['list_tanggapan'] = $tanggapan;
        $hasil_list[] = $row;
    }
}
$kategori_query = mysqli_query($conn, "SELECT * FROM kategori_pengaduan ORDER BY nama_kategori ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Portal Pengaduan - Lapas Lamongan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'lapas-blue': '#0F172A',
                        'lapas-gold': '#E2B93B',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px); }
        .input-focus:focus { border-color: #E2B93B; background-color: white; outline: none; }
    </style>
</head>
<body class="bg-[#F8FAFC] antialiased pb-20">
    <div class="bg-white border-b border-slate-200 py-2 px-4 hidden lg:block">
        <div class="max-w-7xl mx-auto flex justify-between text-[10px] font-black uppercase tracking-widest text-lapas-blue">
            <span>Republik Indonesia</span>
            <span id="currentDate"></span>
        </div>
    </div>

    <?php include "layout/navbar.php"; ?>
   
    <main class="max-w-6xl mx-auto px-4 mt-8">
        
        <section class="mb-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <?php 
                $steps = [
                    ['n' => '1', 't' => 'Isi Form'],
                    ['n' => '2', 't' => 'Bukti Foto'],
                    ['n' => '3', 't' => 'Kirim Aduan'],
                    ['n' => '4', 't' => 'Cek Balasan']
                ];
                foreach($steps as $s): ?>
                <div class="bg-white p-5 rounded-[2rem] border border-slate-100 text-center">
                    <div class="w-10 h-10 bg-lapas-blue text-lapas-gold rounded-2xl flex items-center justify-center font-black mx-auto mb-3">
                        <?= $s['n'] ?>
                    </div>
                    <p class="text-[10px] font-black text-slate-500 uppercase leading-tight"><?= $s['t'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <?php if($pesan_status): ?>
            <div class="mb-8">
                <?php if($pesan_status == "sukses"): ?>
                    <div class="p-4 bg-emerald-500 text-white rounded-2xl flex items-center gap-3 font-bold text-sm">
                        <i data-lucide="check-circle" class="w-5 h-5"></i> Laporan Berhasil Dikirim ke Sistem
                    </div>
                <?php elseif($pesan_status == "pending_ada"): ?>
                    <div class="p-4 bg-amber-500 text-white rounded-2xl flex items-center gap-3 font-bold text-sm">
                        <i data-lucide="alert-circle" class="w-5 h-5"></i> Anda Masih Memiliki Laporan Aktif
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-7">
                <div class="bg-white p-8 md:p-10 rounded-[2.5rem] border border-slate-200">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="p-3 bg-lapas-blue rounded-2xl text-lapas-gold">
                            <i data-lucide="file-text" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-lapas-blue uppercase">Buat Laporan</h2>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Sampaikan Keluhan atau Aspirasi Anda</p>
                        </div>
                    </div>

                    <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-2 tracking-widest">Judul Aduan</label>
                            <input type="text" name="judul_pengaduan" required 
                                class="w-full bg-slate-50 border-2 border-transparent p-4 rounded-2xl input-focus transition-all text-sm font-bold text-slate-700" 
                                placeholder="Contoh: Keluhan Fasilitas Ruang Tunggu">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase text-slate-400 ml-2 tracking-widest">Nama Pelapor</label>
                                <input type="text" name="nama_pelapor" required 
                                    class="w-full bg-slate-50 border-2 border-transparent p-4 rounded-2xl input-focus transition-all text-sm font-bold text-slate-700" 
                                    placeholder="Nama Sesuai KTP">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase text-slate-400 ml-2 tracking-widest">WhatsApp</label>
                                <input type="tel" name="kontak_pelapor" required 
                                    class="w-full bg-slate-50 border-2 border-transparent p-4 rounded-2xl input-focus transition-all text-sm font-bold text-slate-700" 
                                    placeholder="08xxxxxxxxxx">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-2 tracking-widest">Email (Gmail)</label>
                            <input type="email" name="email_pelapor" required 
                                class="w-full bg-slate-50 border-2 border-transparent p-4 rounded-2xl input-focus transition-all text-sm font-bold text-slate-700" 
                                placeholder="alamat@gmail.com">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-2 tracking-widest">Kategori Layanan</label>
                            <select name="kategori_id" required 
                                class="w-full bg-slate-50 border-2 border-transparent p-4 rounded-2xl input-focus transition-all text-sm font-bold text-slate-700">
                                <option value="">-- Pilih Kategori --</option>
                                <?php while($k = mysqli_fetch_assoc($kategori_query)): ?>
                                    <option value="<?= $k['id'] ?>"><?= e($k['nama_kategori']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-2 tracking-widest">Detail Aduan</label>
                            <textarea name="isi_pengaduan" required 
                                class="w-full bg-slate-50 border-2 border-transparent p-4 rounded-2xl input-focus transition-all text-sm font-bold text-slate-700 h-32" 
                                placeholder="Tuliskan laporan Anda secara lengkap..."></textarea>
                        </div>

                        <div class="p-6 border-2 border-dashed border-slate-200 rounded-[2rem] bg-slate-50/50 flex flex-col items-center justify-center text-center">
                            <i data-lucide="image-plus" class="w-8 h-8 text-slate-300 mb-2"></i>
                            <label class="text-[10px] font-black uppercase text-slate-500 mb-3">Lampiran Foto Bukti</label>
                            <input type="file" name="bukti_foto" class="text-[10px] file:bg-lapas-blue file:text-lapas-gold file:rounded-full file:px-6 file:py-2 file:border-none file:font-black file:uppercase">
                        </div>

                        <button type="submit" name="kirim_pengaduan" 
                            class="w-full bg-lapas-blue text-white py-5 rounded-2xl font-black uppercase tracking-[0.2em] hover:bg-slate-800 transition-all flex items-center justify-center gap-3 text-xs">
                            <i data-lucide="send" class="w-4 h-4 text-lapas-gold"></i> Kirim Laporan
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-5 space-y-6">
                <div class="bg-lapas-blue p-8 rounded-[2.5rem] text-white relative overflow-hidden">
                    <i data-lucide="search" class="absolute -right-6 -top-6 w-32 h-32 opacity-10 text-lapas-gold"></i>
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] text-lapas-gold mb-6 flex items-center gap-2">
                        <i data-lucide="shield-check" class="w-4 h-4"></i> Lacak Laporan
                    </h3>
                    <form action="" method="GET" class="space-y-4">
                        <input type="text" name="kontak_lacak" placeholder="Masukkan WhatsApp" 
                            class="w-full p-4 rounded-2xl bg-white/10 border border-white/20 outline-none text-white placeholder:text-white/40 font-bold focus:bg-white/20 transition-all"
                            value="<?= isset($_GET['kontak_lacak']) ? e($_GET['kontak_lacak']) : '' ?>">
                        <button class="w-full bg-lapas-gold text-lapas-blue p-4 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:scale-[1.02] transition-all">
                            Cek Progres Sekarang
                        </button>
                    </form>
                </div>

                <?php if ($hasil_list !== null): ?>
                    <div class="space-y-4">
                        <?php if(empty($hasil_list)): ?>
                            <div class="p-8 text-center bg-white rounded-[2rem] border-2 border-dashed border-slate-200">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Data Tidak Ditemukan</p>
                            </div>
                        <?php endif; ?>

                        <?php foreach($hasil_list as $aduan): ?>
                            <div class="bg-white p-6 rounded-[2rem] border border-slate-200 border-l-4 border-l-lapas-gold">
                                <div class="flex justify-between items-start mb-4">
                                    <span class="px-3 py-1 bg-slate-100 rounded-lg text-[9px] font-black text-slate-500 uppercase tracking-tighter">
                                        <?= date('d M Y', strtotime($aduan['created_at'])) ?>
                                    </span>
                                    <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-tighter 
                                        <?= ($aduan['status'] == 'Selesai') ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' ?>">
                                        <?= $aduan['status'] ?>
                                    </span>
                                </div>
                                <h4 class="font-black text-lapas-blue uppercase text-xs mb-2 leading-tight"><?= e($aduan['judul_pengaduan']) ?></h4>
                                <p class="text-[11px] text-slate-500 line-clamp-2 mb-4 italic">"<?= e($aduan['isi_pengaduan']) ?>"</p>
                                
                                <?php if($aduan['foto_bukti']): ?>
                                    <a href="uploads/<?= $aduan['foto_bukti'] ?>" target="_blank" 
                                        class="inline-flex items-center gap-2 text-[9px] font-black text-lapas-blue bg-slate-100 px-3 py-2 rounded-xl mb-4 hover:bg-lapas-gold transition-colors uppercase">
                                        <i data-lucide="image" class="w-3 h-3"></i> Lihat Lampiran
                                    </a>
                                <?php endif; ?>

                                <?php if(!empty($aduan['list_tanggapan'])): ?>
                                    <div class="mt-4 pt-4 border-t border-dashed border-slate-100 space-y-3">
                                        <?php foreach($aduan['list_tanggapan'] as $t): ?>
                                            <div class="bg-slate-50 p-4 rounded-2xl relative overflow-hidden">
                                                <div class="absolute left-0 top-0 w-1 h-full bg-lapas-gold"></div>
                                                <p class="text-[11px] font-bold text-slate-700 leading-relaxed"><?= nl2br(e($t['isi_tanggapan'])) ?></p>
                                                <div class="mt-3 flex items-center justify-between opacity-50">
                                                    <span class="text-[9px] font-black text-lapas-blue uppercase">Admin: <?= e($t['nama_admin']) ?></span>
                                                    <span class="text-[9px] font-bold"><?= date('d/m/y', strtotime($t['created_at'])) ?></span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="mt-4 pt-4 border-t border-dashed border-slate-100 flex items-center gap-2">
                                        <div class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></div>
                                        <p class="text-[9px] font-black text-amber-500 uppercase italic">Sedang Menunggu Tanggapan Petugas</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();
        function updateDate() {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('currentDate').innerText = new Date().toLocaleDateString('id-ID', options);
        }
        updateDate();
    </script>
</body>
</html>