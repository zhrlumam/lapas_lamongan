<?php
session_start();
include "../config/koneksi.php";

// 1. SINKRONISASI KONEKSI
if (!isset($conn) && isset($pdo)) { $conn = $pdo; }

// 2. PROTEKSI HALAMAN & SESSION
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}
$admin_name = $_SESSION['nama'] ?? 'Administrator';
$admin_id_session = $_SESSION['admin_id'];

// 3. AMBIL ID DARI URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: kelola_pengaduan.php");
    exit;
}

// 4. AMBIL DATA PENGADUAN
$stmt_p = $conn->prepare("SELECT p.*, k.nama_kategori 
                         FROM pengaduan p 
                         LEFT JOIN kategori_pengaduan k ON p.kategori_id = k.id 
                         WHERE p.id = ?");
$stmt_p->execute([$id]);
$data = $stmt_p->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    header("Location: kelola_pengaduan.php");
    exit;
}

$status_notif = "";

// 5. PROSES KIRIM TANGGAPAN
if (isset($_POST['kirim_tanggapan'])) {
    $tanggapan = $_POST['isi_tanggapan'];
    $status_baru = $_POST['status']; 

    try {
        $conn->beginTransaction();
        $stmt_t = $conn->prepare("INSERT INTO tanggapan (pengaduan_id, admin_id, isi_tanggapan, created_at) VALUES (?, ?, ?, NOW())");
        $stmt_t->execute([$id, $admin_id_session, $tanggapan]);
        
        $stmt_u = $conn->prepare("UPDATE pengaduan SET status = ?, updated_at = NOW() WHERE id = ?");
        $stmt_u->execute([$status_baru, $id]);
        
        $conn->commit();
        $status_notif = "success";
    } catch (Exception $e) {
        if ($conn->inTransaction()) { $conn->rollBack(); }
        $status_notif = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Balas Pengaduan | Lapas Lamongan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { imipas: '#07213D', dignity: '#EEBF63' },
                    fontFamily: { jakarta: ['Plus Jakarta Sans', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #07213D; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-100 h-full overflow-hidden">

<div class="flex h-screen">
    <?php include "layout/sidebar.php"; ?>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <header class="bg-white border-b-4 border-dignity h-20 flex items-center justify-between px-6 z-20 shadow-sm">
            <div class="flex items-center gap-4">
                <a href="kelola_pengaduan.php" class="text-imipas p-2 hover:bg-slate-100 rounded-lg transition">
                    <i class="fa-solid fa-arrow-left text-xl"></i>
                </a>
                <h1 class="text-imipas font-bold uppercase tracking-wider text-sm hidden md:block">Balas Pengaduan</h1>
            </div>

            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block text-imipas">
                    <p class="text-xs font-bold leading-none"><?= htmlspecialchars($admin_name) ?></p>
                    <p class="text-[10px] text-amber-600 uppercase mt-1 italic font-semibold">Administrator</p>
                </div>
                <div class="w-10 h-10 rounded-full border-2 border-dignity flex items-center justify-center bg-slate-100">
                    <i class="fa-solid fa-user-shield text-imipas"></i>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 bg-slate-50 custom-scrollbar">
            <div class="max-w-5xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    
                    <div class="lg:col-span-5 space-y-6">
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                            <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Data Laporan</h3>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase font-bold">Pelapor</p>
                                    <p class="text-sm font-bold text-imipas uppercase"><?= htmlspecialchars($data['nama_pelapor']) ?></p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase font-bold">Isi Aduan</p>
                                    <p class="text-xs font-bold text-slate-800 mb-2 uppercase"><?= htmlspecialchars($data['judul_pengaduan']) ?></p>
                                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-sm text-slate-700 font-semibold leading-relaxed">
                                        <?= nl2br(htmlspecialchars($data['isi_pengaduan'])) ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                            <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Bukti Foto</h3>
                            <?php 
                            $foto_path = "../uploads/" . $data['foto_bukti'];
                            if (!empty($data['foto_bukti']) && file_exists($foto_path)): 
                            ?>
                                <a href="<?= $foto_path ?>" target="_blank" class="block rounded-xl overflow-hidden border-2 border-slate-100 shadow-sm">
                                    <img src="<?= $foto_path ?>" class="w-full h-auto">
                                </a>
                            <?php else: ?>
                                <p class="text-[10px] text-slate-400 font-bold uppercase text-center py-4 border-2 border-dashed rounded-xl">Tidak ada foto</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="lg:col-span-7">
                        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                            <form action="" method="POST" class="space-y-6">
                                
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Status Baru</label>
                                    <div class="grid grid-cols-1 gap-2">
                                        <label class="flex items-center p-4 bg-white border-2 rounded-xl cursor-pointer hover:border-amber-400 transition-all">
                                            <input type="radio" name="status" value="Sedang Diproses" class="w-4 h-4 text-amber-500" <?= (strpos($data['status'], 'Proses') !== false || $data['status'] == 'Masuk') ? 'checked' : '' ?>>
                                            <span class="ml-3 text-xs font-bold text-amber-600 uppercase">Proses Tindak Lanjut</span>
                                        </label>
                                        <label class="flex items-center p-4 bg-white border-2 rounded-xl cursor-pointer hover:border-emerald-400 transition-all">
                                            <input type="radio" name="status" value="Selesai" class="w-4 h-4 text-emerald-500" <?= ($data['status'] == 'Selesai') ? 'checked' : '' ?>>
                                            <span class="ml-3 text-xs font-bold text-emerald-600 uppercase">Laporan Selesai</span>
                                        </label>
                                        <label class="flex items-center p-4 bg-white border-2 rounded-xl cursor-pointer hover:border-red-400 transition-all">
                                            <input type="radio" name="status" value="Ditolak" class="w-4 h-4 text-red-500" <?= ($data['status'] == 'Ditolak') ? 'checked' : '' ?>>
                                            <span class="ml-3 text-xs font-bold text-red-600 uppercase">Laporan Ditolak</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between items-center mb-3">
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggapan Petugas</label>
                                        <div class="flex gap-1">
                                            <button type="button" onclick="isiOtomatis('terima')" class="px-2 py-1 bg-amber-100 text-amber-700 rounded text-[9px] font-bold hover:bg-amber-200 transition uppercase">Template Proses</button>
                                            <button type="button" onclick="isiOtomatis('selesai')" class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-[9px] font-bold hover:bg-emerald-200 transition uppercase">Template Selesai</button>
                                        </div>
                                    </div>
                                    <textarea id="box_tanggapan" name="isi_tanggapan" rows="8" required 
                                        class="w-full p-5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:border-imipas outline-none transition-all"
                                        placeholder="Ketik balasan di sini..."></textarea>
                                </div>

                                <button type="submit" name="kirim_tanggapan" class="w-full bg-imipas text-dignity font-bold py-5 rounded-xl shadow-lg hover:opacity-90 transition-all uppercase tracking-widest text-xs">
                                    <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Balasan
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</div>

<script>
    function isiOtomatis(tipe) {
        const box = document.getElementById('box_tanggapan');
        const nama = "<?= htmlspecialchars($data['nama_pelapor']) ?>";
        
        if(tipe === 'terima') {
            box.value = "Yth. " + nama + ",\n\nLaporan Anda telah kami terima dan saat ini sedang dalam tahap verifikasi untuk segera ditindaklanjuti oleh petugas terkait. Mohon kesediaannya untuk menunggu perkembangan selanjutnya.\n\nTerima kasih.";
        } else if(tipe === 'selesai') {
            box.value = "Yth. " + nama + ",\n\nLaporan Anda telah selesai kami tindaklanjuti. Terima kasih atas kerja samanya dalam membantu meningkatkan kualitas layanan kami.\n\nSalam, Tim Layanan Pengaduan.";
        }
    }

    function toggleSidebar() {
        const sb = document.getElementById('sidebar');
        if(sb) sb.classList.toggle('-translate-x-full');
    }

    <?php if ($status_notif == "success"): ?>
    Swal.fire({
        title: 'BERHASIL!',
        text: 'Tanggapan telah terkirim.',
        icon: 'success',
        confirmButtonColor: '#07213D',
        customClass: { popup: 'rounded-3xl' }
    }).then(() => { window.location.href = 'kelola_pengaduan.php'; });
    <?php elseif ($status_notif == "error"): ?>
    Swal.fire({
        title: 'GAGAL!',
        text: 'Gagal memproses data.',
        icon: 'error',
        confirmButtonColor: '#ef4444',
        customClass: { popup: 'rounded-3xl' }
    });
    <?php endif; ?>
</script>

</body>
</html>