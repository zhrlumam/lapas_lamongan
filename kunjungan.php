<?php
include "config/koneksi.php";

$pendaftaran_sukses = false;
$pesan_error = "";
$data_tiket = [];

if (isset($_POST['kirim'])) {
    $nama_wbp      = $_POST['nama_wbp'];
    $status_wbp    = $_POST['status_wbp']; 
    $tgl_kunjungan = $_POST['tanggal_kunjungan'];
    $sesi          = $_POST['sesi'];
    $barang        = $_POST['barang_bawaan'];
    
    $hari_pilihan = date('l', strtotime($tgl_kunjungan));
    $valid = true;
    
    // Validasi Keamanan Sisi Server
    if ($status_wbp == "Tahanan" && !in_array($hari_pilihan, ['Monday', 'Thursday'])) $valid = false;
    if ($status_wbp == "Narapidana" && !in_array($hari_pilihan, ['Tuesday', 'Saturday'])) $valid = false;

    if ($valid) {
        $prefiks = (strpos($sesi, 'Sesi I') !== false) ? "A" : "B";
        $stmt_cek = $conn->prepare("SELECT COUNT(*) as total FROM kunjungan WHERE tanggal_kunjungan = ? AND sesi = ?");
        $stmt_cek->bind_param("ss", $tgl_kunjungan, $sesi);
        $stmt_cek->execute();
        $res_antrean = $stmt_cek->get_result()->fetch_assoc();
        
        $no_urut     = str_pad($res_antrean['total'] + 1, 2, '0', STR_PAD_LEFT);
        $no_antrean  = $prefiks . "-" . $no_urut;

        $stmt_ins = $conn->prepare("INSERT INTO kunjungan (nama_wbp, status_wbp, tanggal_kunjungan, sesi, barang_bawaan, no_antrean, status) VALUES (?, ?, ?, ?, ?, ?, 'Menunggu Verifikasi')");
        $stmt_ins->bind_param("ssssss", $nama_wbp, $status_wbp, $tgl_kunjungan, $sesi, $barang, $no_antrean);
        
        if ($stmt_ins->execute()) {
            $kunjungan_id = $conn->insert_id;
            if (isset($_POST['nama_pengunjung'])) {
                $stmt_p = $conn->prepare("INSERT INTO kunjungan_pengunjung (kunjungan_id, nama_pengunjung, nik_pengunjung, jk, hubungan, alamat) VALUES (?, ?, ?, ?, ?, ?)");
                foreach ($_POST['nama_pengunjung'] as $key => $val) {
                    if (!empty($val)) {
                        // Mengambil alamat sesuai index pengikut agar tidak NULL
                        $alamat_pengunjung = isset($_POST['alamat'][$key]) ? $_POST['alamat'][$key] : '';
                        $stmt_p->bind_param("isssss", $kunjungan_id, $_POST['nama_pengunjung'][$key], $_POST['nik_pengunjung'][$key], $_POST['jk_pengunjung'][$key], $_POST['hubungan'][$key], $alamat_pengunjung);
                        $stmt_p->execute();
                    }
                }
            }
            $data_tiket = ['antrean' => $no_antrean, 'wbp' => $nama_wbp, 'tgl' => $tgl_kunjungan, 'sesi' => $sesi];
            $pendaftaran_sukses = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Kunjungan - Lapas Lamongan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { imipas: { blue: '#07213D', gold: '#EEBF63' } } } }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .ticket-design { clip-path: polygon(0 0, 100% 0, 100% 75%, 95% 80%, 100% 85%, 100% 100%, 0 100%, 0 85%, 5% 80%, 0 75%); }
        .animate-fade-in { animation: fadeIn 0.4s ease forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-imipas-gold">
                <h3 class="font-bold text-imipas-blue text-sm flex items-center gap-2">
                    <span class="bg-imipas-gold text-white w-5 h-5 rounded-full flex items-center justify-center text-[10px]">1</span> Pilih Jadwal
                </h3>
                <p class="text-[11px] text-slate-500 mt-1">Sesuaikan hari dengan status WBP.</p>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-imipas-blue">
                <h3 class="font-bold text-imipas-blue text-sm flex items-center gap-2">
                    <span class="bg-imipas-blue text-white w-5 h-5 rounded-full flex items-center justify-center text-[10px]">2</span> Data Pengunjung
                </h3>
                <p class="text-[11px] text-slate-500 mt-1">Lengkapi NIK KTP semua rombongan.</p>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-green-500">
                <h3 class="font-bold text-imipas-blue text-sm flex items-center gap-2">
                    <span class="bg-green-500 text-white w-5 h-5 rounded-full flex items-center justify-center text-[10px]">3</span> Simpan Tiket
                </h3>
                <p class="text-[11px] text-slate-500 mt-1">Ambil screenshot nomor antrean Anda.</p>
            </div>
        </div>

        <form method="POST" id="formKunjungan" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                    <h2 class="text-imipas-blue font-extrabold text-sm uppercase flex items-center gap-2 mb-6 border-b pb-4">
                        <i data-lucide="calendar-days" class="w-5 h-5 text-imipas-gold"></i> Detail Kunjungan
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Tanggal Kunjungan</label>
                            <input type="date" name="tanggal_kunjungan" id="tgl_kunjungan" required 
                                   class="w-full border-2 border-slate-50 p-4 rounded-2xl text-lg font-bold outline-none focus:border-imipas-gold bg-slate-50/50 text-imipas-blue">
                            <p id="tgl_indo_display" class="mt-2 text-xs font-bold text-imipas-gold italic"></p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Status WBP</label>
                            <input type="text" id="status_display" readonly placeholder="Pilih tanggal..." 
                                   class="w-full border-2 border-slate-100 p-4 rounded-2xl text-sm bg-slate-100 font-bold text-imipas-blue outline-none">
                            <input type="hidden" name="status_wbp" id="status_wbp_hidden">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Nama Lengkap WBP</label>
                            <input type="text" name="nama_wbp" placeholder="Nama WBP" required 
                                   class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm outline-none focus:border-imipas-gold bg-slate-50/50">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Sesi</label>
                                <select name="sesi" class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm bg-slate-50/50">
                                    <option>Sesi I (08.00-10.15)</option>
                                    <option>Sesi II (10.15-11.00)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Barang Bawaan</label>
                                <input type="text" name="barang_bawaan" placeholder="Misal: Nasi, Roti" class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm bg-slate-50/50">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                    <h2 class="text-imipas-blue font-extrabold text-sm uppercase flex items-center gap-2 mb-6 border-b pb-4">
                        <i data-lucide="user-check" class="w-5 h-5 text-imipas-blue"></i> Pengunjung Utama
                    </h2>
                    <div class="space-y-4">
                        <input type="text" name="nama_pengunjung[]" placeholder="Nama Sesuai KTP" required class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm bg-slate-50/50">
                        <input type="number" name="nik_pengunjung[]" placeholder="NIK (16 Digit)" required class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm bg-slate-50/50">
                        <div class="grid grid-cols-2 gap-4">
                            <select name="jk_pengunjung[]" class="border-2 border-slate-50 p-4 rounded-2xl text-sm bg-slate-50/50">
                                <option>Laki-Laki</option><option>Perempuan</option>
                            </select>
                            <select name="hubungan[]" class="border-2 border-slate-50 p-4 rounded-2xl text-sm bg-slate-50/50">
                                <option>Istri</option><option>Suami</option><option>Anak</option><option>Orang Tua</option><option>Saudara</option>
                            </select>
                        </div>
                        <textarea name="alamat[]" placeholder="Alamat Sesuai KTP" required class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm h-24 bg-slate-50/50"></textarea>
                    </div>
                </div>

                <div id="containerPengikut" class="space-y-4"></div>

                <div class="flex flex-col gap-4">
                    <button type="button" onclick="tambahForm()" class="w-full py-4 border-2 border-dashed border-slate-300 rounded-2xl text-slate-400 font-bold text-[10px] uppercase hover:border-imipas-gold transition-all">
                        + Tambah Pengikut
                    </button>
                    <button type="submit" name="kirim" class="w-full bg-imipas-blue text-white font-bold py-5 rounded-2xl shadow-xl uppercase text-sm active:scale-95 transition-all">
                        Kirim Pendaftaran
                    </button>
                </div>
            </div>
        </form>
    </main>

    <?php if ($pendaftaran_sukses): ?>
    <div class="fixed inset-0 bg-black/90 z-[100] flex items-center justify-center p-6 backdrop-blur-md">
        <div class="bg-white w-full max-w-sm rounded-[2.5rem] ticket-design overflow-hidden shadow-2xl border-2 border-imipas-gold animate-fade-in text-center">
            <div class="bg-imipas-blue p-8 text-white relative">
                <p class="text-[10px] font-bold uppercase tracking-[0.4em] text-imipas-gold mb-2">No. Antrean</p>
                <h4 class="text-7xl font-black italic"><?= $data_tiket['antrean'] ?></h4>
            </div>
            <div class="p-8 space-y-5 bg-white">
                <div class="space-y-2 text-left">
                    <div class="flex justify-between text-xs"><span>WBP:</span><span class="font-bold"><?= htmlspecialchars($data_tiket['wbp']) ?></span></div>
                    <div class="flex justify-between text-xs"><span>Tanggal:</span><span class="font-bold"><?= date('d/m/Y', strtotime($data_tiket['tgl'])) ?></span></div>
                    <div class="flex justify-between text-xs"><span>Sesi:</span><span class="font-bold"><?= $data_tiket['sesi'] ?></span></div>
                </div>
                <div class="bg-red-50 p-3 rounded-xl border border-red-100">
                    <p class="text-[9px] text-red-500 font-bold">SIMPAN SCREENSHOT TIKET INI!</p>
                </div>
                <button onclick="window.location.href='kunjungan.php'" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold text-xs">TUTUP</button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script>
        lucide.createIcons();
        
        const dateElement = document.getElementById('currentDate');
        if(dateElement) {
            dateElement.innerText = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        }

        const tglInput = document.getElementById('tgl_kunjungan');
        const statusDisplay = document.getElementById('status_display');
        const statusHidden = document.getElementById('status_wbp_hidden');
        const tglIndoDisplay = document.getElementById('tgl_indo_display');

        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        tglInput.min = tomorrow.toISOString().split("T")[0];

        tglInput.addEventListener('change', function() {
            if (!this.value) return;
            const date = new Date(this.value);
            tglIndoDisplay.innerText = "Terpilih: " + date.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });

            const day = date.getDay(); 
            if (day === 1 || day === 4) { 
                statusDisplay.value = "KHUSUS TAHANAN";
                statusHidden.value = "Tahanan";
                statusDisplay.className = "w-full border-2 border-blue-200 p-4 rounded-2xl text-sm bg-blue-50 font-bold text-blue-700 outline-none";
            } else if (day === 2 || day === 6) { 
                statusDisplay.value = "KHUSUS NARAPIDANA";
                statusHidden.value = "Narapidana";
                statusDisplay.className = "w-full border-2 border-green-200 p-4 rounded-2xl text-sm bg-green-50 font-bold text-green-700 outline-none";
            } else {
                Swal.fire({ icon: 'error', title: 'Jadwal Libur', text: 'Pilih Senin/Kamis (Tahanan) atau Selasa/Sabtu (Napi).', confirmButtonColor: '#07213D' });
                this.value = '';
                tglIndoDisplay.innerText = '';
                statusDisplay.value = "LIBUR";
            }
        });

        let pengikutIndex = 0;
        function tambahForm() {
            const currentInputs = document.getElementsByName('nama_pengunjung[]').length;
            if (currentInputs >= 5) {
                return Swal.fire({ icon: 'warning', title: 'Maksimal', text: 'Maksimal 5 orang per rombongan.', confirmButtonColor: '#07213D' });
            }

            pengikutIndex++;
            const container = document.getElementById('containerPengikut');
            const html = `
            <div id="item-${pengikutIndex}" class="bg-white p-6 rounded-3xl border border-slate-200 relative animate-fade-in shadow-sm">
                <button type="button" onclick="document.getElementById('item-${pengikutIndex}').remove()" class="absolute top-4 right-4 text-red-400">
                    <i data-lucide="x-circle" class="w-5 h-5"></i>
                </button>
                <h3 class="text-[10px] font-bold text-imipas-blue uppercase mb-4 flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-3 h-3 text-imipas-gold"></i> Pengikut Tambahan
                </h3>
                <div class="space-y-4">
                    <input type="text" name="nama_pengunjung[]" placeholder="Nama Lengkap" required class="w-full border-2 border-slate-50 p-4 text-sm rounded-2xl bg-slate-50/50">
                    <input type="number" name="nik_pengunjung[]" placeholder="NIK Sesuai KK" required class="w-full border-2 border-slate-50 p-4 text-sm rounded-2xl bg-slate-50/50">
                    <div class="grid grid-cols-2 gap-3">
                        <select name="jk_pengunjung[]" class="border-2 border-slate-50 p-4 text-sm rounded-2xl bg-slate-50/50"><option>Laki-Laki</option><option>Perempuan</option></select>
                        <select name="hubungan[]" class="border-2 border-slate-50 p-4 text-sm rounded-2xl bg-slate-50/50"><option>Anak</option><option>Saudara</option><option>Lainnya</option></select>
                    </div>
                    <textarea name="alamat[]" placeholder="Alamat Sesuai KK" required class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm h-24 bg-slate-50/50"></textarea>
                </div>
            </div>`;
            container.insertAdjacentHTML('beforeend', html);
            lucide.createIcons();
        }
    </script>
</body>
</html>