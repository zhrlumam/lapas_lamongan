<?php
session_start();
include "../config/koneksi.php";

// Keamanan: Cek Login
if (!isset($_SESSION['admin_id'])) { 
    header("Location: index.php"); 
    exit; 
}

$admin_name = $_SESSION['nama'] ?? 'Admin';

// Ambil status dari session untuk SweetAlert
$status_update = $_SESSION['status_survey'] ?? "";
unset($_SESSION['status_survey']);

// PROSES SIMPAN DATA (PDO)
if (isset($_POST['simpan_survey'])) {
    $bulan = $_POST['bulan'];
    $skor_ikm = $_POST['skor_ikm'];
    $skor_ipk = $_POST['skor_ipk'];
    $keterangan = $_POST['keterangan'];
    
    try {
        // Nonaktifkan semua data lama
        $pdo->query("UPDATE survey_kepuasan SET is_active = 0");
        
        // Masukkan data baru sebagai is_active = 1
        $stmt = $pdo->prepare("INSERT INTO survey_kepuasan (bulan, skor_ikm, skor_ipk, keterangan, is_active) VALUES (?, ?, ?, ?, 1)");
        
        if ($stmt->execute([$bulan, $skor_ikm, $skor_ipk, $keterangan])) { 
            $_SESSION['status_survey'] = "success"; 
        } else { 
            $_SESSION['status_survey'] = "error"; 
        }
    } catch (PDOException $e) {
        $_SESSION['status_survey'] = "error";
    }

    header("Location: kelola_survey.php");
    exit;
}

// Ambil data yang sedang aktif (Untuk ditampilkan di form dan di card samping)
$stmt_current = $pdo->query("SELECT * FROM survey_kepuasan WHERE is_active = 1 LIMIT 1");
$current = $stmt_current->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Survey IKM/IPK | Lapas Lamongan</title>
    <link rel="icon" type="image/png" href="../assets/images/logo_imigrasi.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@400;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { imipas: '#07213D', dignity: '#EEBF63' },
                    fontFamily: { titillium: ['Titillium Web', 'sans-serif'] }
                }
            }
        }
    </script>
</head>
<body class="font-titillium bg-slate-50 overflow-hidden text-slate-800">

    <div class="flex h-screen">
        <?php include 'layout/sidebar.php'; ?>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="bg-white border-b-4 border-dignity shadow-sm h-20 flex items-center justify-between px-6 z-20">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="lg:hidden text-imipas p-2 hover:bg-slate-100 rounded-lg transition">
                        <i class="fa-solid fa-bars-staggered text-xl"></i>
                    </button>
                    <h1 class="text-imipas uppercase tracking-wider text-sm hidden md:block">Manajemen Survey Kepuasan Masyarakat</h1>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block text-imipas">
                        <p class="text-xs leading-none"><?= htmlspecialchars($admin_name) ?></p>
                        <p class="text-[10px] text-amber-600 uppercase mt-1 italic tracking-wider">Administrator</p>
                    </div>
                    <div class="w-10 h-10 rounded-full border-2 border-dignity flex items-center justify-center bg-slate-100 shadow-sm">
                        <i class="fa-solid fa-user-tie text-imipas"></i>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 bg-slate-50">
                <nav class="flex mb-8">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li>
                            <a href="dashboard.php" class="text-slate-400 hover:text-imipas text-[10px] uppercase tracking-[0.2em] flex items-center gap-2 transition">
                                <i class="fa-solid fa-house"></i> Dashboard
                            </a>
                        </li>
                        <li class="flex items-center">
                            <i class="fa-solid fa-chevron-right text-slate-300 text-[10px] mx-2"></i>
                            <span class="text-imipas text-[10px] uppercase tracking-[0.2em]">Survey IKM/IPK</span>
                        </li>
                    </ol>
                </nav>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    <div class="lg:col-span-8 bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                            <div class="h-8 w-8 bg-emerald-500 rounded-lg flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                            <h2 class="text-xs uppercase tracking-widest text-imipas">Update Skor Periode Baru</h2>
                        </div>
                        
                        <form action="" method="POST" class="p-8 space-y-6">
                            <div class="space-y-2">
                                <label class="text-[11px] text-imipas uppercase ml-1 tracking-wider">Periode Bulan & Tahun</label>
                                <input type="text" name="bulan" value="<?= $current['bulan'] ?? '' ?>" placeholder="Contoh: JANUARI 2026" required
                                    class="w-full border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm focus:border-dignity outline-none transition-all bg-slate-50 focus:bg-white text-imipas">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[11px] text-imipas uppercase ml-1 tracking-wider">Skor IKM (0 - 4.00)</label>
                                    <input type="number" step="0.01" name="skor_ikm" value="<?= $current['skor_ikm'] ?? '' ?>" required
                                        class="w-full border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm focus:border-emerald-500 outline-none transition-all bg-slate-50 focus:bg-white text-imipas">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[11px] text-imipas uppercase ml-1 tracking-wider">Skor IPK (0 - 4.00)</label>
                                    <input type="number" step="0.01" name="skor_ipk" value="<?= $current['skor_ipk'] ?? '' ?>" required
                                        class="w-full border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm focus:border-blue-500 outline-none transition-all bg-slate-50 focus:bg-white text-imipas">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[11px] text-imipas uppercase ml-1 tracking-wider">Predikat Capaian</label>
                                <input type="text" name="keterangan" value="<?= $current['keterangan'] ?? '' ?>" placeholder="Contoh: SANGAT BAIK" required
                                    class="w-full border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm focus:border-dignity outline-none transition-all bg-slate-50 focus:bg-white text-imipas uppercase">
                            </div>

                            <button type="submit" name="simpan_survey" 
                                class="w-full bg-imipas hover:bg-slate-800 text-dignity py-4 rounded-2xl text-xs uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-3 shadow-xl border-b-4 border-dignity group">
                                <i class="fa-solid fa-paper-plane group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i> Publikasikan Data Ke Publik
                            </button>
                        </form>
                    </div>

                    <div class="lg:col-span-4 space-y-6">
                        <div class="bg-imipas rounded-[2rem] p-8 text-white shadow-xl relative overflow-hidden border-b-8 border-dignity">
                            <div class="relative z-10">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="h-2 w-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-widest">Status: Sedang Tayang</p>
                                </div>
                                <h3 class="text-xl italic mb-8"><?= $current['bulan'] ?? 'Belum Ada Data' ?></h3>
                                
                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    <div class="bg-white/5 p-4 rounded-2xl border border-white/10 text-center">
                                        <p class="text-[9px] text-slate-500 uppercase">IKM</p>
                                        <p class="text-3xl text-dignity"><?= $current['skor_ikm'] ?? '0' ?></p>
                                    </div>
                                    <div class="bg-white/5 p-4 rounded-2xl border border-white/10 text-center">
                                        <p class="text-[9px] text-slate-500 uppercase">IPK</p>
                                        <p class="text-3xl text-blue-400"><?= $current['skor_ipk'] ?? '0' ?></p>
                                    </div>
                                </div>
                                
                                <div class="w-full py-3 bg-white/10 rounded-xl text-center border border-white/5 backdrop-blur-sm">
                                    <p class="text-[10px] text-slate-400 uppercase tracking-widest mb-1">Predikat</p>
                                    <p class="text-sm text-emerald-400 uppercase"><?= $current['keterangan'] ?? 'N/A' ?></p>
                                </div>
                            </div>
                            <i class="fa-solid fa-award text-8xl text-white/5 absolute -right-4 -top-4"></i>
                        </div>

                        <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm flex items-center gap-4 group">
                            <div class="h-12 w-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl group-hover:bg-imipas group-hover:text-white transition-all duration-500">
                                <i class="fa-solid fa-circle-info"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase italic">Integrasi Sistem</p>
                                <p class="text-[11px] leading-relaxed text-imipas">Data ini otomatis diperbarui pada Website Utama dan TV Informasi.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <script>
    function toggleSidebar() {
        const sb = document.getElementById('sidebar');
        const ov = document.getElementById('sidebarOverlay');
        sb.classList.toggle('-translate-x-full');
        ov.classList.toggle('hidden');
    }

    <?php if($status_update == "success"): ?>
        Swal.fire({
            icon: 'success',
            title: 'PUBLIKASI BERHASIL',
            text: 'Data skor IKM/IPK periode terbaru telah tayang.',
            background: '#ffffff',
            confirmButtonColor: '#07213D',
            timer: 2500,
            showConfirmButton: false
        });
    <?php elseif($status_update == "error"): ?>
        Swal.fire({
            icon: 'error',
            title: 'SISTEM ERROR',
            text: 'Gagal memperbarui database.',
            confirmButtonColor: '#ef4444'
        });
    <?php endif; ?>
    </script>

</body>
</html>