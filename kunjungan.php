<?php
include "config/koneksi.php";

$conn = $pdo;
$pendaftaran_sukses = false;
$pesan_error = "";
$data_tiket = [];

// LOGIKA CARI TIKET
if (isset($_POST['cari_tiket'])) {
    $nik_cari = $_POST['nik_cari'];
    try {
        $stmt_cari = $pdo->prepare("SELECT k.* FROM kunjungan k 
                                    JOIN kunjungan_pengunjung kp ON k.id = kp.kunjungan_id 
                                    WHERE kp.nik_pengunjung = ? ORDER BY k.id DESC LIMIT 1");
        $stmt_cari->execute([$nik_cari]);
        $res_cari = $stmt_cari->fetch(PDO::FETCH_ASSOC);

        if ($res_cari) {
            $data_tiket = [
                'antrean' => $res_cari['no_antrean'],
                'wbp' => $res_cari['nama_wbp'],
                'tgl' => $res_cari['tanggal_kunjungan'],
                'sesi' => $res_cari['sesi']
            ];
            $pendaftaran_sukses = true;
        } else {
            echo "<script>setTimeout(() => { Swal.fire('Tidak Ditemukan', 'NIK tidak terdaftar dalam sistem.', 'error'); }, 100);</script>";
        }
    } catch (PDOException $e) {
        $pesan_error = $e->getMessage();
    }
}

// LOGIKA PENDAFTARAN BARU (DENGAN PROTEKSI NOMOR URUT)
if (isset($_POST['kirim'])) {
    $nama_wbp = $_POST['nama_wbp'];
    $status_wbp = $_POST['status_wbp'];
    $tgl_kunjungan = $_POST['tanggal_kunjungan'];
    $sesi = $_POST['sesi'];
    $barang = $_POST['barang_bawaan'];
    $nik_utama = $_POST['nik_pengunjung'][0];

    $hari_pilihan = date('l', strtotime($tgl_kunjungan));
    $valid = true;

    if ($status_wbp == "Tahanan" && !in_array($hari_pilihan, ['Monday', 'Thursday']))
        $valid = false;
    if ($status_wbp == "Narapidana" && !in_array($hari_pilihan, ['Tuesday', 'Saturday']))
        $valid = false;

    if ($valid) {
        try {
            $pdo->beginTransaction(); // Mulai Transaksi untuk mencegah tabrakan data

            $stmt_cek_ganda = $pdo->prepare("SELECT k.no_antrean FROM kunjungan k 
                                             JOIN kunjungan_pengunjung kp ON k.id = kp.kunjungan_id 
                                             WHERE kp.nik_pengunjung = ? AND k.tanggal_kunjungan = ?");
            $stmt_cek_ganda->execute([$nik_utama, $tgl_kunjungan]);
            $duplikat = $stmt_cek_ganda->fetch();

            if ($duplikat) {
                $pdo->rollBack();
                echo "<script>setTimeout(() => { 
                    Swal.fire('Sudah Terdaftar', 'Anda sudah mendaftar untuk tanggal ini dengan No: " . $duplikat['no_antrean'] . "', 'warning'); 
                }, 100);</script>";
            } else {
                $prefiks = (strpos($sesi, 'Sesi I') !== false) ? "A" : "B";

                // Ambil nomor urut tertinggi pada hari dan sesi tersebut
                $stmt_urut = $pdo->prepare("SELECT MAX(CAST(SUBSTRING(no_antrean, 3) AS UNSIGNED)) as terakhir 
                                            FROM kunjungan WHERE tanggal_kunjungan = ? AND sesi = ?");
                $stmt_urut->execute([$tgl_kunjungan, $sesi]);
                $res_urut = $stmt_urut->fetch(PDO::FETCH_ASSOC);

                $next_no = ($res_urut['terakhir'] ?? 0) + 1;
                $no_antrean = $prefiks . "-" . str_pad($next_no, 2, '0', STR_PAD_LEFT);

                $stmt_ins = $pdo->prepare("INSERT INTO kunjungan (nama_wbp, status_wbp, tanggal_kunjungan, sesi, barang_bawaan, no_antrean, status) VALUES (?, ?, ?, ?, ?, ?, 'Menunggu Verifikasi')");

                if ($stmt_ins->execute([$nama_wbp, $status_wbp, $tgl_kunjungan, $sesi, $barang, $no_antrean])) {
                    $kunjungan_id = $pdo->lastInsertId();
                    if (isset($_POST['nama_pengunjung'])) {
                        $stmt_p = $pdo->prepare("INSERT INTO kunjungan_pengunjung (kunjungan_id, nama_pengunjung, nik_pengunjung, jk, hubungan, alamat) VALUES (?, ?, ?, ?, ?, ?)");
                        foreach ($_POST['nama_pengunjung'] as $key => $val) {
                            if (!empty($val)) {
                                $stmt_p->execute([$kunjungan_id, $val, $_POST['nik_pengunjung'][$key], $_POST['jk_pengunjung'][$key], $_POST['hubungan'][$key], $_POST['alamat'][$key]]);
                            }
                        }
                    }
                    $pdo->commit(); // Simpan permanen
                    $data_tiket = ['antrean' => $no_antrean, 'wbp' => $nama_wbp, 'tgl' => $tgl_kunjungan, 'sesi' => $sesi];
                    $pendaftaran_sukses = true;
                }
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            $pesan_error = "Kesalahan: " . $e->getMessage();
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
    <link rel="icon" type="image/png" href="assets/images/logo_imigrasi.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { imipas: { blue: '#07213D', gold: '#EEBF63' } } } }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            overflow-x: hidden;
        }

        .ticket-design {
            clip-path: polygon(0 0, 100% 0, 100% 75%, 95% 80%, 100% 85%, 100% 100%, 0 100%, 0 85%, 5% 80%, 0 75%);
        }

        /* Animasi Muncul Berurutan */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-reveal {
            animation: slideUp 0.8s cubic-bezier(0.2, 1, 0.3, 1) forwards;
            opacity: 0;
        }

        .delay-1 {
            animation-delay: 0.1s;
        }

        .delay-2 {
            animation-delay: 0.2s;
        }

        .delay-3 {
            animation-delay: 0.3s;
        }

        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="antialiased pb-20">

    <div class="bg-white border-b border-slate-200 py-2 px-4 hidden lg:block">
        <div
            class="max-w-7xl mx-auto flex justify-between text-[10px] font-bold uppercase tracking-widest text-imipas-blue">
            <span>Republik Indonesia</span>
            <span id="currentDate"></span>
        </div>
    </div>

    <?php include "layout/navbar.php"; ?>

    <main class="max-w-5xl mx-auto p-4 mt-6">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 animate-reveal">
            <div>
                <h1 class="text-2xl font-extrabold text-imipas-blue tracking-tight">Pendaftaran Kunjungan</h1>
                <p class="text-slate-500 text-sm">Lapas Kelas IIB Lamongan</p>
            </div>
            <form method="POST" class="flex gap-2 w-full md:w-auto">
                <input type="number" name="nik_cari" placeholder="Cek NIK Tiket" required
                    class="bg-white border border-slate-200 px-4 py-2 rounded-xl text-xs outline-none focus:border-imipas-gold w-full md:w-48 transition-all">
                <button type="submit" name="cari_tiket"
                    class="bg-imipas-blue text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-slate-800 transition-all active:scale-95">Cek</button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div
                class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-imipas-gold animate-reveal delay-1 hover-lift">
                <h3 class="font-bold text-imipas-blue text-sm flex items-center gap-2">
                    <span
                        class="bg-imipas-gold text-white w-5 h-5 rounded-full flex items-center justify-center text-[10px]">1</span>
                    Pilih Jadwal
                </h3>
                <p class="text-[11px] text-slate-500 mt-1">Sesuaikan hari dengan status WBP.</p>
            </div>
            <div
                class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-imipas-blue animate-reveal delay-2 hover-lift">
                <h3 class="font-bold text-imipas-blue text-sm flex items-center gap-2">
                    <span
                        class="bg-imipas-blue text-white w-5 h-5 rounded-full flex items-center justify-center text-[10px]">2</span>
                    Data Pengunjung
                </h3>
                <p class="text-[11px] text-slate-500 mt-1">Lengkapi NIK KTP semua rombongan.</p>
            </div>
            <div
                class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-green-500 animate-reveal delay-3 hover-lift">
                <h3 class="font-bold text-imipas-blue text-sm flex items-center gap-2">
                    <span
                        class="bg-green-500 text-white w-5 h-5 rounded-full flex items-center justify-center text-[10px]">3</span>
                    Simpan Tiket
                </h3>
                <p class="text-[11px] text-slate-500 mt-1">Gunakan NIK untuk cek tiket kembali.</p>
            </div>
        </div>

        <form method="POST" id="formKunjungan" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="space-y-6 animate-reveal delay-2">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                    <h2
                        class="text-imipas-blue font-extrabold text-sm uppercase flex items-center gap-2 mb-6 border-b pb-4">
                        <i data-lucide="calendar-days" class="w-5 h-5 text-imipas-gold"></i> Detail Kunjungan
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Tanggal
                                Kunjungan</label>
                            <input type="date" name="tanggal_kunjungan" id="tgl_kunjungan" required
                                class="w-full border-2 border-slate-50 p-4 rounded-2xl text-lg font-bold outline-none focus:border-imipas-gold bg-slate-50/50 text-imipas-blue transition-all">
                            <p id="tgl_indo_display" class="mt-2 text-xs font-bold text-imipas-gold italic"></p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Status WBP</label>
                            <input type="text" id="status_display" readonly placeholder="Pilih tanggal..."
                                class="w-full border-2 border-slate-100 p-4 rounded-2xl text-sm bg-slate-100 font-bold text-imipas-blue outline-none transition-all duration-500">
                            <input type="hidden" name="status_wbp" id="status_wbp_hidden">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Nama Lengkap
                                WBP</label>
                            <input type="text" name="nama_wbp" placeholder="Nama WBP" required
                                class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm outline-none focus:border-imipas-gold bg-slate-50/50">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Sesi</label>
                                <select name="sesi"
                                    class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm bg-slate-50/50 outline-none focus:border-imipas-gold">
                                    <option>Sesi I (08.00-10.15)</option>
                                    <option>Sesi II (10.15-11.00)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Barang
                                    Bawaan</label>
                                <input type="text" name="barang_bawaan" placeholder="Misal: Nasi, Roti"
                                    class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm bg-slate-50/50 outline-none focus:border-imipas-gold">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6 animate-reveal delay-3">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                    <h2
                        class="text-imipas-blue font-extrabold text-sm uppercase flex items-center gap-2 mb-6 border-b pb-4">
                        <i data-lucide="user-check" class="w-5 h-5 text-imipas-blue"></i> Pengunjung Utama
                    </h2>
                    <div class="space-y-4">
                        <input type="text" name="nama_pengunjung[]" placeholder="Nama Sesuai KTP" required
                            class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm bg-slate-50/50 outline-none focus:border-imipas-gold">
                        <input type="number" name="nik_pengunjung[]" placeholder="NIK (16 Digit)" required
                            class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm bg-slate-50/50 outline-none focus:border-imipas-gold">
                        <div class="grid grid-cols-2 gap-4">
                            <select name="jk_pengunjung[]"
                                class="border-2 border-slate-50 p-4 rounded-2xl text-sm bg-slate-50/50 outline-none focus:border-imipas-gold">
                                <option>Laki-Laki</option>
                                <option>Perempuan</option>
                            </select>
                            <select name="hubungan[]"
                                class="border-2 border-slate-50 p-4 rounded-2xl text-sm bg-slate-50/50 outline-none focus:border-imipas-gold">
                                <option>Istri</option>
                                <option>Suami</option>
                                <option>Anak</option>
                                <option>Orang Tua</option>
                                <option>Saudara</option>
                            </select>
                        </div>
                        <textarea name="alamat[]" placeholder="Alamat Sesuai KTP" required
                            class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm h-24 bg-slate-50/50 outline-none focus:border-imipas-gold"></textarea>
                    </div>
                </div>

                <div id="containerPengikut" class="space-y-4"></div>

                <div class="flex flex-col gap-4">
                    <button type="button" onclick="tambahForm()"
                        class="w-full py-4 border-2 border-dashed border-slate-300 rounded-2xl text-slate-400 font-bold text-[10px] uppercase hover:border-imipas-gold hover:text-imipas-gold transition-all">
                        + Tambah Pengikut
                    </button>
                    <button type="submit" name="kirim"
                        class="w-full bg-imipas-blue text-white font-bold py-5 rounded-2xl shadow-xl uppercase text-sm active:scale-95 hover:bg-slate-800 transition-all duration-300">
                        Kirim Pendaftaran
                    </button>
                </div>
            </div>
        </form>
    </main>

    <?php if ($pendaftaran_sukses): ?>
        <div
            class="fixed inset-0 bg-black/90 z-[100] flex items-center justify-center p-6 backdrop-blur-md animate-fade-in">
            <div class="w-full max-w-sm">
                <div id="areaDownload"
                    class="bg-white w-full rounded-[2.5rem] ticket-design overflow-hidden shadow-2xl border-2 border-imipas-gold text-center">
                    <div class="bg-imipas-blue p-8 text-white relative">
                        <p class="text-[10px] font-bold uppercase tracking-[0.4em] text-imipas-gold mb-2">No. Antrean</p>
                        <h4 class="text-7xl font-black italic">
                            <?= $data_tiket['antrean'] ?>
                        </h4>
                    </div>
                    <div class="p-8 space-y-5 bg-white">
                        <div class="space-y-2 text-left">
                            <div class="flex justify-between text-xs"><span>WBP:</span><span class="font-bold">
                                    <?= htmlspecialchars($data_tiket['wbp']) ?></span></div>
                            <div class="flex justify-between text-xs"><span>Tanggal:</span><span class="font-bold"><?= date('d/m/Y', strtotime($data_tiket['tgl'])) ?>
                                </span></div>
                            <div class="flex justify-between text-xs"><span>Sesi:</span><span class="font-bold">
                                    <?= $data_tiket['sesi'] ?>
                                </span></div>
                        </div>
                        <div class="bg-blue-50 p-3 rounded-xl border border-blue-100">
                            <p class="text-[9px] text-blue-600 font-bold uppercase tracking-tight">Lapas Kelas IIB Lamongan
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex flex-col gap-2">
                    <button onclick="downloadTiket()"
                        class="w-full bg-imipas-gold text-imipas-blue py-4 rounded-2xl font-black text-xs uppercase shadow-lg flex items-center justify-center gap-2 active:scale-95 transition-all">
                        <i data-lucide="download" class="w-4 h-4"></i> Simpan Gambar Tiket
                    </button>
                    <button onclick="window.location.href='kunjungan.php'"
                        class="w-full bg-white/10 text-white py-4 rounded-2xl font-bold text-xs uppercase border border-white/20 hover:bg-white/20 transition-all">Tutup</button>
                </div>
            </div>
            </div>
        <?php endif; ?>

        <script>
            lucide.createIcons();

            function downloadTiket() {
                const element = document.getElementById('areaDownload');
                html2canvas(element, { scale: 3, backgroundColor: "#ffffff", logging: false }).then(canvas => {
                    const image = canvas.toDataURL("image/jpeg", 0.9);
                    const link = document.createElement('a');
                    link.href = image;
                    link.download = 'Tiket_<?= $data_tiket['antrean'] ?? "Kunjungan" ?>.jpg';
                    link.click();
                });
            }

            const dateElement = document.getElementById('currentDate');
            if (dateElement) dateElement.innerText = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

            const tglInput = document.getElementById('tgl_kunjungan');
            const statusDisplay = document.getElementById('status_display');
            const statusHidden = document.getElementById('status_wbp_hidden');
            const tglIndoDisplay = document.getElementById('tgl_indo_display');

            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            tglInput.setAttribute('min', tomorrow.toISOString().split("T")[0]);

            tglInput.addEventListener('change', function () {
                if (!this.value) return;

                const dateParts = this.value.split('-');
                const date = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);

                tglIndoDisplay.innerText = "Terpilih: " + date.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });

                const day = date.getDay();
                statusDisplay.className = "w-full border-2 p-4 rounded-2xl text-sm font-bold outline-none transition-all duration-500 ";

                if (day === 1 || day === 4) {
                    statusDisplay.value = "KHUSUS TAHANAN";
                    statusHidden.value = "Tahanan";
                    statusDisplay.classList.add("border-blue-200", "bg-blue-50", "text-blue-700");
                } else if (day === 2 || day === 6) {
                    statusDisplay.value = "KHUSUS NARAPIDANA";
                    statusHidden.value = "Narapidana";
                    statusDisplay.classList.add("border-green-200", "bg-green-50", "text-green-700");
                } else {
                    Swal.fire({ icon: 'error', title: 'Jadwal Libur', text: 'Senin/Kamis (Tahanan) atau Selasa/Sabtu (Napi).', confirmButtonColor: '#07213D' });
                    this.value = ''; tglIndoDisplay.innerText = ''; statusDisplay.value = "LIBUR";
                    statusDisplay.classList.add("border-slate-100", "bg-slate-100", "text-slate-400");
                }
            });

            let pengikutIndex = 0;
            function tambahForm() {
                if (document.getElementsByName('nama_pengunjung[]').length >= 5) {
                    return Swal.fire({ icon: 'warning', title: 'Maksimal', text: 'Maksimal 5 orang per rombongan.' });
                }
                pengikutIndex++;
                const container = document.getElementById('containerPengikut');
                const html = `
        <div id="item-${pengikutIndex}" class="bg-white p-6 rounded-3xl border border-slate-200 relative animate-reveal shadow-sm">
            <button type="button" onclick="document.getElementById('item-${pengikutIndex}').remove()" class="absolute top-4 right-4 text-red-400 hover:text-red-600 transition-colors">
                <i data-lucide="x-circle" class="w-5 h-5"></i>
            </button>
            <div class="space-y-4">
                <input type="text" name="nama_pengunjung[]" placeholder="Nama Lengkap" required class="w-full border-2 border-slate-50 p-4 text-sm rounded-2xl bg-slate-50/50 outline-none focus:border-imipas-gold">
                <input type="number" name="nik_pengunjung[]" placeholder="NIK Sesuai KK" required class="w-full border-2 border-slate-50 p-4 text-sm rounded-2xl bg-slate-50/50 outline-none focus:border-imipas-gold">
                <div class="grid grid-cols-2 gap-3">
                    <select name="jk_pengunjung[]" class="border-2 border-slate-50 p-4 text-sm rounded-2xl bg-slate-50/50 outline-none focus:border-imipas-gold"><option>Laki-Laki</option><option>Perempuan</option></select>
                    <select name="hubungan[]" class="border-2 border-slate-50 p-4 text-sm rounded-2xl bg-slate-50/50 outline-none focus:border-imipas-gold"><option>Anak</option><option>Saudara</option><option>Lainnya</option></select>
                </div>
                <textarea name="alamat[]" placeholder="Alamat Sesuai KK" required class="w-full border-2 border-slate-50 p-4 rounded-2xl text-sm h-24 bg-slate-50/50 outline-none focus:border-imipas-gold"></textarea>
            </div>
        </div>`;
                container.insertAdjacentHTML('beforeend', html);
                lucide.createIcons();
            }
        </script>
</body>

</html>