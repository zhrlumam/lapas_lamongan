<?php
include "config/koneksi.php";

// Sinkronisasi koneksi
if (!isset($conn) && isset($pdo)) { $conn = $pdo; }

function e($string) { return htmlspecialchars($string, ENT_QUOTES, 'UTF-8'); }

$pesan_status = "";

// 1. PROSES SIMPAN
if (isset($_POST['kirim_pengaduan'])) {
    $judul    = $_POST['judul_pengaduan'];
    $nama     = $_POST['nama_pelapor'];
    $kontak   = preg_replace('/[^0-9]/', '', $_POST['kontak_pelapor']);
    $email    = ""; 
    $kategori = (int)$_POST['kategori_id'];
    $isi      = $_POST['isi_pengaduan'];

    try {
        $cek_stmt = $conn->prepare("SELECT id FROM pengaduan WHERE kontak_pelapor = ? AND status != 'Selesai'");
        if (method_exists($cek_stmt, 'bind_param')) {
            $cek_stmt->bind_param("s", $kontak);
            $cek_stmt->execute();
            $count = $cek_stmt->get_result()->num_rows;
        } else {
            $cek_stmt->execute([$kontak]);
            $count = $cek_stmt->rowCount();
        }

        if ($count > 0) {
            $pesan_status = "pending_ada";
        } else {
            $nama_file = "";
            if (isset($_FILES['bukti_foto']) && $_FILES['bukti_foto']['error'] == 0) {
                $target_dir = "uploads/"; 
                if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
                $ext = pathinfo($_FILES["bukti_foto"]["name"], PATHINFO_EXTENSION);
                $nama_file = "ADUAN_" . time() . "." . strtolower($ext);
                $target_file = $target_dir . $nama_file;
                if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png']) && $_FILES["bukti_foto"]["size"] < 2000000) {
                    move_uploaded_file($_FILES["bukti_foto"]["tmp_name"], $target_file);
                } else { $pesan_status = "error_file"; }
            }

            if ($pesan_status != "error_file") {
                $stmt = $conn->prepare("INSERT INTO pengaduan (judul_pengaduan, nama_pelapor, kontak_pelapor, email_pelapor, kategori_id, isi_pengaduan, foto_bukti, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'Masuk', NOW())");
                if (method_exists($stmt, 'bind_param')) {
                    $stmt->bind_param("ssssiss", $judul, $nama, $kontak, $email, $kategori, $isi, $nama_file);
                    $pesan_status = ($stmt->execute()) ? "sukses" : "gagal";
                } else {
                    $pesan_status = ($stmt->execute([$judul, $nama, $kontak, $email, $kategori, $isi, $nama_file])) ? "sukses" : "gagal";
                }
            }
        }
    } catch (Exception $e) { $pesan_status = "gagal"; }
}

// 2. LOGIKA LACAK
$hasil_list = null;
if (isset($_GET['kontak_lacak'])) {
    $kontak_lacak = preg_replace('/[^0-9]/', '', $_GET['kontak_lacak']);
    $stmt_lacak = $conn->prepare("SELECT p.*, k.nama_kategori FROM pengaduan p LEFT JOIN kategori_pengaduan k ON p.kategori_id = k.id WHERE p.kontak_pelapor = ? ORDER BY p.created_at DESC");
    if (method_exists($stmt_lacak, 'bind_param')) {
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
    } else {
        $stmt_lacak->execute([$kontak_lacak]);
        $hasil_list = $stmt_lacak->fetchAll(PDO::FETCH_ASSOC);
        foreach ($hasil_list as &$row) {
            $stmt_t = $conn->prepare("SELECT t.*, a.nama as nama_admin FROM tanggapan t LEFT JOIN admin a ON t.admin_id = a.id_admin WHERE t.pengaduan_id = ? ORDER BY t.created_at ASC");
            $stmt_t->execute([$row['id']]);
            $row['list_tanggapan'] = $stmt_t->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}

$kategori_query = (method_exists($conn, 'query')) ? $conn->query("SELECT * FROM kategori_pengaduan ORDER BY nama_kategori ASC") : mysqli_query($conn, "SELECT * FROM kategori_pengaduan ORDER BY nama_kategori ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Portal Pengaduan - Lapas Lamongan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: { extend: { colors: { imipas: { blue: '#07213D', gold: '#EEBF63' } } } }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }
        .animate-reveal { animation: slideUp 0.8s cubic-bezier(0.2, 1, 0.3, 1) forwards; opacity: 0; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body class="antialiased pb-20">
    <div class="bg-white border-b border-slate-200 py-2 px-4 hidden lg:block">
        <div class="max-w-7xl mx-auto flex justify-between text-[10px] font-bold uppercase tracking-widest text-imipas-blue">
            <span>Republik Indonesia</span>
            <span id="currentDate"></span>
        </div>
    </div>

    <?php include "layout/navbar.php"; ?>
    
    <main class="max-w-5xl mx-auto p-4 mt-6">
        <div class="flex flex-col mb-8 animate-reveal">
            <h1 class="text-2xl font-extrabold text-imipas-blue tracking-tight">Portal Pengaduan</h1>
            <p class="text-slate-500 text-sm">Layanan Aspirasi & Pengaduan Masyarakat Lapas Lamongan</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-imipas-gold animate-reveal delay-1 hover-lift">
                <h3 class="font-bold text-imipas-blue text-sm flex items-center gap-2">
                    <span class="bg-imipas-gold text-white w-5 h-5 rounded-full flex items-center justify-center text-[10px]">1</span>
                    Tulis Laporan
                </h3>
                <p class="text-[11px] text-slate-500 mt-1">Sampaikan aduan dengan data yang valid.</p>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-imipas-blue animate-reveal delay-2 hover-lift">
                <h3 class="font-bold text-imipas-blue text-sm flex items-center gap-2">
                    <span class="bg-imipas-blue text-white w-5 h-5 rounded-full flex items-center justify-center text-[10px]">2</span>
                    Lampirkan Bukti
                </h3>
                <p class="text-[11px] text-slate-500 mt-1">Sertakan foto pendukung laporan Anda.</p>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-green-500 animate-reveal delay-3 hover-lift">
                <h3 class="font-bold text-imipas-blue text-sm flex items-center gap-2">
                    <span class="bg-green-500 text-white w-5 h-5 rounded-full flex items-center justify-center text-[10px]">3</span>
                    Pantau Respon
                </h3>
                <p class="text-[11px] text-slate-500 mt-1">Gunakan fitur Lacak di kolom riwayat.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-7 space-y-6 animate-reveal delay-2">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
                    <h2 class="text-imipas-blue font-extrabold text-sm uppercase flex items-center gap-2 mb-6 border-b pb-4">
                        <i data-lucide="edit-3" class="w-5 h-5 text-imipas-gold"></i> Formulir Pengaduan
                    </h2>

                    <form action="" method="POST" enctype="multipart/form-data" class="space-y-5" id="mainForm">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Judul Laporan</label>
                            <input type="text" name="judul_pengaduan" required 
                                class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm outline-none focus:border-imipas-gold bg-slate-50/50 text-imipas-blue transition-all" 
                                placeholder="Judul Pengaduan">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Nama Pelapor</label>
                                <input type="text" name="nama_pelapor" required 
                                    class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm outline-none focus:border-imipas-gold bg-slate-50/50 text-imipas-blue transition-all" 
                                    placeholder="Nama Lengkap">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">WhatsApp</label>
                                <input type="tel" name="kontak_pelapor" required 
                                    class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm outline-none focus:border-imipas-gold bg-slate-50/50 text-imipas-blue transition-all" 
                                    placeholder="08xxxxxxxxxx">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Kategori Layanan</label>
                            <select name="kategori_id" required 
                                class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm outline-none focus:border-imipas-gold bg-slate-50/50 text-imipas-blue transition-all cursor-pointer">
                                <option value="">-- Pilih Kategori --</option>
                                <?php if($kategori_query) { foreach($kategori_query as $k): ?>
                                    <option value="<?= $k['id'] ?>"><?= e($k['nama_kategori']) ?></option>
                                <?php endforeach; } ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Isi Laporan</label>
                            <textarea name="isi_pengaduan" required 
                                class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm h-32 outline-none focus:border-imipas-gold bg-slate-50/50 text-imipas-blue transition-all" 
                                placeholder="Jelaskan detail pengaduan..."></textarea>
                        </div>

                        <div class="p-6 border-2 border-dashed border-slate-200 rounded-[2rem] bg-slate-50/30 text-center hover:bg-slate-50 transition-colors group relative">
                            <div id="preview-container" class="hidden mb-4 relative inline-block">
                                <img id="image-preview" src="#" class="max-h-48 rounded-2xl border-4 border-white shadow-md">
                                <button type="button" onclick="clearPreview()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-lg hover:bg-red-600 transition-colors">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <div id="upload-instruction">
                                <i data-lucide="camera" class="w-8 h-8 text-slate-300 mb-2 mx-auto"></i>
                                <label class="text-[10px] uppercase text-slate-500 mb-3 block font-bold">Foto Bukti (Maks 2MB)</label>
                                <input type="file" name="bukti_foto" id="fileInput" onchange="previewImage(this)" accept="image/*" 
                                    class="text-[10px] file:bg-imipas-blue file:text-white file:rounded-full file:px-6 file:py-2 file:border-none file:uppercase file:font-bold file:mr-4 file:cursor-pointer cursor-pointer text-slate-400">
                            </div>
                        </div>

                        <button type="submit" name="kirim_pengaduan" id="submitBtn" 
                            class="w-full bg-imipas-blue text-white font-bold py-5 rounded-2xl shadow-xl uppercase text-sm active:scale-95 hover:bg-slate-800 transition-all duration-300 flex items-center justify-center gap-3">
                            <span id="btnText">Kirim Pengaduan</span>
                            <i data-lucide="send" class="w-4 h-4 text-imipas-gold"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-5 space-y-6">
                <div class="bg-imipas-blue p-8 rounded-[2.5rem] text-white shadow-xl border-4 border-imipas-gold/10 animate-reveal delay-3">
                    <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-imipas-gold mb-6 flex items-center gap-2">
                        <i data-lucide="search" class="w-4 h-4"></i> Lacak Pengaduan
                    </h3>
                    <form action="" method="GET" class="space-y-4">
                        <input type="text" name="kontak_lacak" placeholder="Masukkan WhatsApp Pelapor" 
                               value="<?= isset($_GET['kontak_lacak']) ? e($_GET['kontak_lacak']) : '' ?>"
                               class="w-full p-4 rounded-2xl bg-white/10 border border-white/20 outline-none text-white focus:bg-white/20 transition-all text-sm">
                        <button class="w-full bg-imipas-gold text-imipas-blue p-4 rounded-2xl font-bold uppercase tracking-widest text-[10px] hover:bg-white transition-all shadow-lg active:scale-95">
                            Cari Laporan
                        </button>
                    </form>
                </div>

                <?php if ($hasil_list !== null): ?>
                    <div class="space-y-4">
                        <?php if (empty($hasil_list)): ?>
                            <div class="bg-white p-10 rounded-3xl border border-slate-200 text-center">
                                <p class="text-slate-400 text-sm font-bold">Data tidak ditemukan.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach($hasil_list as $aduan): ?>
                                <div class="bg-white p-6 rounded-3xl border border-slate-200 border-l-4 border-l-imipas-gold shadow-sm">
                                    <div class="flex justify-between items-center mb-4 text-[9px] font-bold uppercase">
                                        <span class="px-3 py-1 bg-slate-100 rounded-lg text-slate-500 italic"><?= date('d M Y', strtotime($aduan['created_at'])) ?></span>
                                        <span class="px-3 py-1 rounded-lg <?= ($aduan['status'] == 'Selesai') ? 'bg-green-100 text-green-600' : 'bg-amber-100 text-amber-600' ?>">
                                            <?= $aduan['status'] ?>
                                        </span>
                                    </div>
                                    <h4 class="font-bold text-imipas-blue uppercase text-xs mb-2"><?= e($aduan['judul_pengaduan']) ?></h4>
                                    <p class="text-[11px] text-slate-500 mb-4"><?= e($aduan['isi_pengaduan']) ?></p>
                                    
                                    <?php if($aduan['foto_bukti']): ?>
                                        <button onclick="window.open('uploads/<?= $aduan['foto_bukti'] ?>')" 
                                            class="text-[9px] font-bold text-imipas-blue bg-slate-50 border border-slate-100 px-4 py-2 rounded-xl mb-4 uppercase hover:bg-imipas-blue hover:text-white transition-all flex items-center gap-2">
                                            <i data-lucide="image" class="w-3 h-3"></i> Lihat Lampiran
                                        </button>
                                    <?php endif; ?>

                                    <?php if(!empty($aduan['list_tanggapan'])): ?>
                                        <div class="space-y-3 mt-4 border-t pt-4">
                                            <?php foreach($aduan['list_tanggapan'] as $t): ?>
                                                <div class="bg-blue-50/50 p-4 rounded-2xl border-l-2 border-imipas-blue">
                                                    <p class="text-[11px] text-slate-700"><?= nl2br(e($t['isi_tanggapan'])) ?></p>
                                                    <p class="text-[9px] font-bold text-imipas-blue uppercase mt-3">Petugas: <?= e($t['nama_admin']) ?></p>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();

        const dateElement = document.getElementById('currentDate');
        if (dateElement) dateElement.innerText = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const container = document.getElementById('preview-container');
            const instruction = document.getElementById('upload-instruction');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                    instruction.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function clearPreview() {
            document.getElementById('fileInput').value = '';
            document.getElementById('preview-container').classList.add('hidden');
            document.getElementById('upload-instruction').classList.remove('hidden');
        }

        <?php if($pesan_status == "sukses"): ?>
            Swal.fire({ title: 'BERHASIL!', text: 'Laporan Anda telah terkirim.', icon: 'success', confirmButtonColor: '#07213D' });
        <?php elseif($pesan_status == "pending_ada"): ?>
            Swal.fire({ title: 'PERHATIAN!', text: 'Anda masih memiliki laporan yang sedang diproses.', icon: 'warning', confirmButtonColor: '#07213D' });
        <?php elseif($pesan_status == "error_file"): ?>
            Swal.fire({ title: 'GAGAL!', text: 'Format file salah atau ukuran terlalu besar.', icon: 'error', confirmButtonColor: '#07213D' });
        <?php elseif($pesan_status == "gagal"): ?>
            Swal.fire({ title: 'KESALAHAN!', text: 'Terjadi gangguan sistem.', icon: 'error', confirmButtonColor: '#07213D' });
        <?php endif; ?>
    </script>
</body>
</html>