<?php
session_start();
include "../config/koneksi.php";

// Keamanan: Cek Login
if (!isset($_SESSION['admin_id'])) { 
    header("Location: index.php"); 
    exit; 
}

include "layout/role_check.php";
check_role(['Registrasi']);

$admin_name = $_SESSION['nama'] ?? 'Admin';
$daya_muat = 344;

// Ambil status dari session untuk SweetAlert
$status_update = $_SESSION['status_hunian'] ?? "";
unset($_SESSION['status_hunian']);

// PROSES UPDATE DATA (Menggunakan PDO)
if (isset($_POST['update_hunian'])) {
    $tahanan = $_POST['tahanan'];
    $narapidana = $_POST['narapidana'];
    $sidang = $_POST['sidang'];
    $berobat = $_POST['berobat_luar'];
    $total = $tahanan + $narapidana + $sidang + $berobat;
    $tanggal = date('Y-m-d');

    try {
        $pdo->beginTransaction();

        // 1. Cek apakah data hari ini sudah ada
        $stmt_check = $pdo->prepare("SELECT id_data FROM data_warga_binaan WHERE tanggal_update = ?");
        $stmt_check->execute([$tanggal]);
        
        if ($stmt_check->rowCount() > 0) {
            $stmt = $pdo->prepare("UPDATE data_warga_binaan SET tahanan=?, narapidana=?, sidang=?, berobat_luar=?, total_penghuni=? WHERE tanggal_update=?");
            $stmt->execute([$tahanan, $narapidana, $sidang, $berobat, $total, $tanggal]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO data_warga_binaan (tahanan, narapidana, sidang, berobat_luar, total_penghuni, tanggal_update) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$tahanan, $narapidana, $sidang, $berobat, $total, $tanggal]);
        }

        // 2. OTOMATIS HAPUS DATA LAMA (Agar tidak menumpuk di database)
        $pdo->exec("DELETE FROM data_warga_binaan WHERE tanggal_update < DATE_SUB(CURDATE(), INTERVAL 7 DAY)");

        $pdo->commit();
        $_SESSION['status_hunian'] = "success";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['status_hunian'] = "error";
    }

    header("Location: kelola_hunian.php");
    exit;
}

// Ambil data terbaru
$stmt_current = $pdo->query("SELECT * FROM data_warga_binaan ORDER BY tanggal_update DESC LIMIT 1");
$data = $stmt_current->fetch(PDO::FETCH_ASSOC);

$total_sekarang = $data['total_penghuni'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Hunian | Lapas Lamongan</title>
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
<body class="font-titillium bg-slate-50 overflow-hidden">

    <div class="flex h-screen">
        <?php include 'layout/sidebar.php'; ?>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="bg-white border-b-4 border-dignity shadow-sm h-20 flex items-center justify-between px-6 z-20">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="text-imipas p-2 hover:bg-slate-100 rounded-lg">
                        <i class="fa-solid fa-bars-staggered text-xl"></i>
                    </button>
                    <h1 class="text-imipas uppercase tracking-wider text-sm hidden md:block">Update Data Strategis Hunian</h1>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block text-imipas">
                        <p class="text-xs leading-none"><?= htmlspecialchars($admin_name) ?></p>
                        <p class="text-[10px] text-amber-600 uppercase mt-1">Administrator</p>
                    </div>
                    <div class="w-10 h-10 rounded-full border-2 border-dignity flex items-center justify-center bg-slate-100">
                        <i class="fa-solid fa-user-tie text-imipas"></i>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 bg-slate-50">
                <nav class="flex mb-6" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="dashboard.php" class="text-slate-400 hover:text-imipas text-xs uppercase tracking-widest flex items-center gap-2">
                                <i class="fa-solid fa-house"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fa-solid fa-chevron-right text-slate-300 text-[10px] mx-2"></i>
                                <span class="text-imipas text-xs uppercase tracking-widest">Kelola Hunian</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    <div class="lg:col-span-8 bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                            <h2 class="text-xs uppercase tracking-widest text-imipas flex items-center gap-2">
                                <i class="fa-solid fa-pen-to-square text-amber-500"></i> Form Input Data WBP
                            </h2>
                            <span class="text-[10px] text-slate-400">Tanggal: <?= date('d F Y') ?></span>
                        </div>
                        
                        <form action="" method="POST" class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <div class="space-y-2">
                                    <label class="text-[11px] text-imipas uppercase ml-1 tracking-wider">Jumlah Tahanan</label>
                                    <input type="number" name="tahanan" value="<?= $data['tahanan'] ?? 0 ?>" required
                                        class="w-full border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm focus:border-dignity outline-none transition-all bg-slate-50 focus:bg-white text-imipas">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[11px] text-imipas uppercase ml-1 tracking-wider">Jumlah Narapidana</label>
                                    <input type="number" name="narapidana" value="<?= $data['narapidana'] ?? 0 ?>" required
                                        class="w-full border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm focus:border-dignity outline-none transition-all bg-slate-50 focus:bg-white text-imipas">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[11px] text-imipas uppercase ml-1 tracking-wider">WBP Sidang</label>
                                    <input type="number" name="sidang" value="<?= $data['sidang'] ?? 0 ?>" required
                                        class="w-full border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm focus:border-dignity outline-none transition-all bg-slate-50 focus:bg-white text-imipas">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[11px] text-imipas uppercase ml-1 tracking-wider">Berobat Luar</label>
                                    <input type="number" name="berobat_luar" value="<?= $data['berobat_luar'] ?? 0 ?>" required
                                        class="w-full border-2 border-slate-100 rounded-2xl px-5 py-4 text-sm focus:border-dignity outline-none transition-all bg-slate-50 focus:bg-white text-imipas">
                                </div>
                            </div>

                            <button type="submit" name="update_hunian" 
                                class="w-full bg-imipas hover:bg-slate-800 text-dignity py-4 rounded-2xl text-xs uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-3 shadow-xl border-b-4 border-dignity">
                                <i class="fa-solid fa-cloud-arrow-up"></i> Sinkronisasi Data Database
                            </button>
                        </form>
                    </div>

                    <div class="lg:col-span-4 space-y-6">
                        <div class="bg-imipas rounded-[2rem] p-8 text-white shadow-xl relative overflow-hidden border-b-8 border-dignity group">
                            <i class="fa-solid fa-users-viewfinder absolute -right-4 -bottom-4 text-8xl text-white/5 transition-transform"></i>
                            <p class="text-[10px] text-dignity uppercase tracking-widest mb-1">Total Penghuni Saat Ini</p>
                            <div class="text-6xl tracking-tighter"><?= $total_sekarang ?></div>
                            <p class="text-[10px] text-slate-400 mt-2 uppercase">Kapasitas: <?= $daya_muat ?></p>
                            <div class="mt-4 pt-4 border-t border-white/10 flex items-center justify-between">
                                <span class="text-[10px] text-slate-400 font-medium">Terakhir Update:</span>
                                <span class="text-[10px] bg-dignity text-imipas px-2 py-1 rounded-lg"><?= $data['tanggal_update'] ?? '-' ?></span>
                            </div>
                        </div>

                        <div class="bg-white rounded-[2rem] p-6 border border-slate-200 shadow-sm">
                            <h3 class="text-[10px] text-imipas uppercase tracking-widest mb-5 flex items-center gap-2 text-slate-400">
                                <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Update Terakhir
                            </h3>
                            <div class="space-y-3">
                                <?php
                                $stmt_history = $pdo->query("SELECT * FROM data_warga_binaan ORDER BY tanggal_update DESC LIMIT 5");
                                while($row = $stmt_history->fetch(PDO::FETCH_ASSOC)):
                                ?>
                                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-transparent hover:border-dignity/30 transition-all group">
                                    <div class="flex flex-col">
                                        <p class="text-[11px] text-imipas"><?= date('d M Y', strtotime($row['tanggal_update'])) ?></p>
                                        <p class="text-[9px] text-slate-400 uppercase">WBP Terdata</p>
                                    </div>
                                    <span class="text-lg text-imipas group-hover:text-amber-600"><?= $row['total_penghuni'] ?></span>
                                </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const isDesktop = window.innerWidth >= 1024;

        if (isDesktop) {
            sidebar.classList.toggle('lg:hidden');
        } else {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    }

    <?php if($status_update == "success"): ?>
        Swal.fire({
            icon: 'success',
            title: 'SINKRONISASI BERHASIL',
            text: 'Data Hunian strategis telah diperbarui.',
            background: '#ffffff',
            confirmButtonColor: '#07213D',
            timer: 2500
        });
    <?php elseif($status_update == "error"): ?>
        Swal.fire({
            icon: 'error',
            title: 'SINKRONISASI GAGAL',
            text: 'Periksa koneksi database Anda.',
            confirmButtonColor: '#ef4444'
        });
    <?php endif; ?>
    </script>

</body>
</html>