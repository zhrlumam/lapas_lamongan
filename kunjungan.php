<?php
include "config/koneksi.php";

$pendaftaran_sukses = false;
$data_tiket = [];

if (isset($_POST['kirim'])) {
    $nama_wbp      = mysqli_real_escape_string($conn, $_POST['nama_wbp']);
    $status_wbp    = mysqli_real_escape_string($conn, $_POST['status_wbp']);
    $tgl_kunjungan = mysqli_real_escape_string($conn, $_POST['tanggal_kunjungan']);
    $sesi          = mysqli_real_escape_string($conn, $_POST['sesi']);
    $barang        = mysqli_real_escape_string($conn, $_POST['barang_bawaan']);
    
    $prefiks = (strpos($sesi, 'Sesi I') !== false) ? "A" : "B";
    $cek_antrean = mysqli_query($conn, "SELECT COUNT(*) as total FROM kunjungan WHERE tanggal_kunjungan = '$tgl_kunjungan' AND sesi = '$sesi'");
    $res_antrean = mysqli_fetch_assoc($cek_antrean);
    $no_urut     = str_pad($res_antrean['total'] + 1, 2, '0', STR_PAD_LEFT);
    $no_antrean  = $prefiks . "-" . $no_urut;

    $query_induk = "INSERT INTO kunjungan (nama_wbp, status_wbp, tanggal_kunjungan, sesi, barang_bawaan, no_antrean, status) 
                    VALUES ('$nama_wbp', '$status_wbp', '$tgl_kunjungan', '$sesi', '$barang', '$no_antrean', 'Menunggu Verifikasi')";
    
    if (mysqli_query($conn, $query_induk)) {
        $kunjungan_id = mysqli_insert_id($conn);
        if (isset($_POST['nama_pengunjung'])) {
            foreach ($_POST['nama_pengunjung'] as $key => $val) {
                if (!empty($val)) {
                    $nama_p = mysqli_real_escape_string($conn, $_POST['nama_pengunjung'][$key]);
                    $nik_p  = mysqli_real_escape_string($conn, $_POST['nik_pengunjung'][$key]);
                    $jk_p   = mysqli_real_escape_string($conn, $_POST['jk_pengunjung'][$key]);
                    $hub_p  = mysqli_real_escape_string($conn, $_POST['hubungan'][$key]);
                    $alm_p  = mysqli_real_escape_string($conn, $_POST['alamat'][$key]);
                    mysqli_query($conn, "INSERT INTO kunjungan_pengunjung (kunjungan_id, nama_pengunjung, nik_pengunjung, jk, hubungan, alamat) 
                                         VALUES ('$kunjungan_id', '$nama_p', '$nik_p', '$jk_p', '$hub_p', '$alm_p')");
                }
            }
        }
        $data_tiket = ['antrean' => $no_antrean, 'wbp' => $nama_wbp, 'tgl' => $tgl_kunjungan, 'sesi' => $sesi];
        $pendaftaran_sukses = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapas Lamongan - Kunjungan Online</title>
      <link rel="icon" type="image/png" href="assets/images/logo_imigrasi.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { imipas: { blue: '#07213D', gold: '#EEBF63' } } } }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f1f5f9; }
        .ticket-design { clip-path: polygon(0 0, 100% 0, 100% 75%, 95% 80%, 100% 85%, 100% 100%, 0 100%, 0 85%, 5% 80%, 0 75%); }
        input:focus, select:focus, textarea:focus { border-color: #EEBF63 !important; ring: 2px; --tw-ring-color: #EEBF63; }
    </style>
</head>
<body class="pb-20">

  <body class="bg-white text-slate-900 antialiased overflow-x-hidden">

  <div class="bg-imipas-platinum border-b border-slate-200 py-2 px-4 hidden md:block">
    <div class="max-w-7xl mx-auto flex justify-between text-[10px] font-bold uppercase tracking-widest text-imipas-blue">
      <span>Republik Indonesia</span>
      <span id="currentDate"></span>
    </div>
  </div>

  <?php include "layout/navbar.php"; ?>

    <main class="max-w-4xl mx-auto p-4 mt-4">
        
        <div class="bg-white p-5 rounded-2xl mb-6 border border-slate-200 shadow-sm">
            <div class="flex items-center gap-2 mb-3 border-b pb-2">
                <i data-lucide="calendar-check" class="w-4 h-4 text-imipas-blue"></i>
                <h3 class="font-bold text-imipas-blue text-[11px] uppercase tracking-wider">Jadwal & Ketentuan</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-[11px] md:text-xs text-slate-600">
                <ul class="space-y-1">
                    <li class="flex gap-2"><span>•</span> <b>Tahanan:</b> Senin & Kamis</li>
                    <li class="flex gap-2"><span>•</span> <b>Narapidana:</b> Selasa & Sabtu</li>
                    <li class="flex gap-2"><span>•</span> <b>Sesi I:</b> 08.00-10.15 | <b>Sesi II:</b> 10.15-11.00</li>
                </ul>
                <ul class="space-y-1 text-red-600 font-semibold italic">
                    <li class="flex gap-2"><i data-lucide="alert-circle" class="w-3 h-3 mt-0.5"></i> Wajib bawa KTP Fisik Asli</li>
                    <li class="flex gap-2"><i data-lucide="clock" class="w-3 h-3 mt-0.5"></i> Daftar H-1 sebelum kunjungan</li>
                </ul>
            </div>
        </div>

        <form method="POST" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4 h-fit">
                <h2 class="text-imipas-blue font-bold text-xs uppercase flex items-center gap-2 border-b pb-3">
                    <span class="w-1.5 h-4 bg-imipas-gold block rounded-full"></span> 
                    <i data-lucide="user-search" class="w-4 h-4"></i> Data WBP
                </h2>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 ml-1">Nama WBP (Bin/Binti)</label>
                    <input type="text" name="nama_wbp" placeholder="Ahmad Bin Slamet" required class="w-full border p-3 rounded-xl text-sm outline-none transition-all">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 ml-1">Status WBP</label>
                        <select name="status_wbp" required class="w-full border p-3 rounded-xl text-sm bg-white outline-none">
                            <option value="Narapidana">Narapidana</option>
                            <option value="Tahanan">Tahanan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 ml-1">Barang Bawaan</label>
                        <input type="text" name="barang_bawaan" placeholder="Contoh: Makanan" class="w-full border p-3 rounded-xl text-sm outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 ml-1">Tanggal</label>
                        <input type="date" name="tanggal_kunjungan" required class="w-full border p-3 rounded-xl text-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 ml-1">Pilih Sesi</label>
                        <select name="sesi" required class="w-full border p-3 rounded-xl text-sm bg-white outline-none">
                            <option>Sesi I (08.00-10.15)</option>
                            <option>Sesi II (10.15-11.00)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4 h-fit">
                <h2 class="text-imipas-blue font-bold text-xs uppercase flex items-center gap-2 border-b pb-3">
                    <span class="w-1.5 h-4 bg-imipas-blue block rounded-full"></span> 
                    <i data-lucide="user" class="w-4 h-4"></i> Pengunjung Utama
                </h2>
                <input type="text" name="nama_pengunjung[]" placeholder="Nama Lengkap KTP" required class="w-full border p-3 rounded-xl text-sm outline-none">
                <input type="number" name="nik_pengunjung[]" placeholder="NIK KTP (16 Digit)" required class="w-full border p-3 rounded-xl text-sm outline-none">
                
                <div class="grid grid-cols-2 gap-3">
                    <select name="jk_pengunjung[]" required class="border p-3 rounded-xl text-sm bg-white outline-none">
                        <option value="Laki-Laki">Laki-Laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                    <select name="hubungan[]" required class="border p-3 rounded-xl text-sm bg-white outline-none">
                        <option value="" disabled selected>Pilih Hubungan</option>
                        <option>Suami</option><option>Istri</option><option>Ayah</option>
                        <option>Ibu</option><option>Anak</option><option>Saudara</option>
                        <option>Lainnya</option>
                    </select>
                </div>
                <textarea name="alamat[]" placeholder="Alamat Lengkap" required class="w-full border p-3 rounded-xl text-sm h-20 outline-none"></textarea>
            </div>

            <div class="lg:col-span-2 space-y-4">
                <div id="containerPengikut" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
                
                <div class="flex flex-col md:flex-row gap-4">
                    <button type="button" onclick="tambahForm()" class="flex-1 py-4 border-2 border-dashed border-slate-300 rounded-2xl text-slate-500 font-bold text-[10px] uppercase tracking-widest hover:bg-white hover:border-imipas-gold hover:text-imipas-gold transition-all flex items-center justify-center gap-2">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Pengikut
                    </button>
                    <button type="submit" name="kirim" class="flex-1 bg-imipas-blue text-imipas-gold font-bold py-4 rounded-2xl shadow-xl uppercase tracking-widest text-sm active:scale-95 transition-all flex items-center justify-center gap-2">
                        <i data-lucide="send" class="w-4 h-4"></i> Kirim Pendaftaran
                    </button>
                </div>
            </div>
        </form>
    </main>

    <?php if ($pendaftaran_sukses): ?>
    <div class="fixed inset-0 bg-black/80 z-[100] flex items-center justify-center p-6 backdrop-blur-sm">
        <div class="bg-white w-full max-w-sm rounded-3xl ticket-design overflow-hidden shadow-2xl animate-fade-in border-2 border-imipas-gold">
            <div class="bg-imipas-blue p-6 text-center text-white border-b-2 border-dashed border-slate-400 relative">
                <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-imipas-gold mb-1">No. Antrean</p>
                <h4 class="text-5xl font-black italic tracking-tighter"><?= $data_tiket['antrean'] ?></h4>
            </div>
            <div class="p-8 space-y-4 bg-white text-sm">
                <div class="space-y-2 border-b pb-4">
                    <div class="flex justify-between"><span class="text-slate-400">WBP:</span><span class="font-bold text-imipas-blue"><?= htmlspecialchars($data_tiket['wbp']) ?></span></div>
                    <div class="flex justify-between"><span class="text-slate-400">Tgl:</span><span class="font-bold text-imipas-blue"><?= date('d/m/Y', strtotime($data_tiket['tgl'])) ?></span></div>
                    <div class="flex justify-between"><span class="text-slate-400">Sesi:</span><span class="font-bold text-imipas-blue"><?= $data_tiket['sesi'] ?></span></div>
                </div>
                <div class="bg-red-50 p-2 rounded-lg text-[10px] text-red-500 font-bold text-center leading-tight">
                    <i data-lucide="camera" class="w-3 h-3 inline mr-1"></i> SCREENSHOT TIKET INI UNTUK PETUGAS
                </div>
                <div class="flex flex-col gap-2 pt-2">
                    <a href="https://wa.me/628113405959?text=Konfirmasi%20Pendaftaran%20Online%20Antrean:%20<?= $data_tiket['antrean'] ?>" class="w-full bg-green-500 text-white py-3 rounded-xl font-bold text-[10px] uppercase text-center shadow-md flex items-center justify-center gap-2">
                         Kirim WhatsApp
                    </a>
                    <button onclick="window.location.href='kunjungan.php'" class="w-full bg-slate-100 text-slate-600 py-3 rounded-xl font-bold text-[10px] uppercase tracking-widest">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    
    <?php endif; ?>

    <script>
        lucide.createIcons();

        let count = 1;
        function tambahForm() {
            if(count >= 5) return alert("Maksimal 5 pengunjung");
            count++;
            const html = `
            <div id="f-${count}" class="bg-slate-50 p-5 rounded-2xl border border-slate-200 relative shadow-inner animate-fade-in">
                <button type="button" onclick="document.getElementById('f-${count}').remove(); count--;" class="absolute top-3 right-3 text-red-400 hover:text-red-600 transition-colors">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
                <p class="text-[9px] font-bold text-imipas-blue uppercase mb-3 tracking-widest flex items-center gap-1">
                    <i data-lucide="users" class="w-3 h-3"></i> Pengikut #${count-1}
                </p>
                <div class="space-y-3">
                    <input type="text" name="nama_pengunjung[]" placeholder="Nama Lengkap" required class="w-full border p-2.5 text-xs rounded-xl outline-none">
                    <input type="number" name="nik_pengunjung[]" placeholder="NIK" required class="w-full border p-2.5 text-xs rounded-xl outline-none">
                    <div class="grid grid-cols-2 gap-2">
                        <select name="jk_pengunjung[]" required class="border p-2.5 text-xs rounded-xl bg-white outline-none">
                            <option value="Laki-Laki">Laki-Laki</option><option value="Perempuan">Perempuan</option>
                        </select>
                        <select name="hubungan[]" required class="border p-2.5 text-xs rounded-xl bg-white outline-none">
                            <option value="" disabled selected>Hubungan</option>
                            <option>Suami</option><option>Istri</option><option>Ayah</option>
                            <option>Ibu</option><option>Anak</option><option>Saudara</option><option>Lainnya</option>
                        </select>
                    </div>
                    <textarea name="alamat[]" placeholder="Alamat" required class="w-full border p-2.5 text-xs rounded-xl outline-none h-12"></textarea>
                </div>
            </div>`;
            document.getElementById('containerPengikut').insertAdjacentHTML('beforeend', html);
            lucide.createIcons();
        }
    </script>
</body>
</html>