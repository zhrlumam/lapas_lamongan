<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// --- LOGIKA HAPUS SEMUA DATA ---
if (isset($_GET['action']) && $_GET['action'] == 'deleteAll') {
    // Menghapus data detail (pengikut) terlebih dahulu karena relasi tabel
    mysqli_query($conn, "DELETE FROM kunjungan_pengunjung");
    $deleteAll = mysqli_query($conn, "DELETE FROM kunjungan");
    
    if ($deleteAll) {
        $_SESSION['alert'] = [
            'type' => 'success', 
            'title' => 'Database Bersih!', 
            'msg' => 'Semua data kunjungan dan pengikut berhasil dihapus.'
        ];
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// --- LOGIKA HAPUS PER BARIS ---
if (isset($_GET['hapus'])) {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['hapus']);
    mysqli_query($conn, "DELETE FROM kunjungan_pengunjung WHERE kunjungan_id = '$id_hapus'");
    $delete = mysqli_query($conn, "DELETE FROM kunjungan WHERE id = '$id_hapus'");
    
    if ($delete) {
        $_SESSION['alert'] = [
            'type' => 'success', 
            'title' => 'Terhapus!', 
            'msg' => 'Data kunjungan berhasil dihapus.'
        ];
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// --- LOGIKA PENCARIAN ---
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$where_clause = "";

if (!empty($search)) {
    $where_clause = "WHERE k.nama_wbp LIKE '%$search%' 
                     OR k.no_antrean LIKE '%$search%'
                     OR k.id IN (SELECT kunjungan_id FROM kunjungan_pengunjung WHERE nik_pengunjung LIKE '%$search%' OR nama_pengunjung LIKE '%$search%')";
}

$query = "SELECT k.* FROM kunjungan k $where_clause ORDER BY k.tanggal_kunjungan DESC, k.id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="60"> 
    <title>Manajemen Kunjungan | Lapas Lamongan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .table-container { overflow: auto; height: calc(100vh - 220px); border: 1px solid #e2e8f0; }
        .sheet-table { border-collapse: separate; border-spacing: 0; width: 100%; }
        .sheet-table th { 
            background: #f8fafc; color: #475569; font-size: 10px; font-weight: 800; 
            padding: 12px; border: 1px solid #cbd5e1; text-transform: uppercase;
            position: sticky; top: 0; z-index: 20; white-space: nowrap;
        }
        .sheet-table td { padding: 10px 14px; border: 1px solid #e2e8f0; font-size: 11px; white-space: nowrap; }
        .sticky-col { position: sticky; left: 0; background: white; z-index: 10; border-right: 2px solid #cbd5e1 !important; }
        .header-pengikut { background-color: #0f172a !important; color: white !important; }
        tr:hover td { background-color: #f0f9ff !important; cursor: cell; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .spin-icon:hover i { animation: spin 1s linear infinite; }
    </style>
</head>
<body class="bg-slate-50 text-slate-700">

<div class="min-h-screen flex">
    <?php include "layout/menu_admin.php"; ?>

    <div id="main-content" class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-50">
        
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center sticky top-0 z-30">
            <div class="flex items-center gap-4">
                <h1 class="text-lg font-black text-slate-800 uppercase tracking-tight italic">Kunjungan <span class="text-blue-600">Database</span></h1>
                <form action="" method="GET" class="relative hidden md:block ml-4">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari NIK atau Nama..." class="bg-slate-100 border border-slate-200 rounded-lg pl-9 pr-4 py-2 text-xs focus:ring-2 focus:ring-blue-400 outline-none w-64 transition-all">
                    <i class="fa-solid fa-search absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                </form>
            </div>

            <div class="flex items-center gap-2">
                <button onclick="window.location.reload()" class="spin-icon bg-blue-50 hover:bg-blue-100 text-blue-600 px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 transition border border-blue-200 shadow-sm">
                    <i class="fa-solid fa-sync-alt"></i> REFRESH
                </button>

                <button onclick="confirmDeleteAll()" class="bg-white hover:bg-red-50 text-red-600 px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 transition border border-red-200 shadow-sm">
                    <i class="fa-solid fa-trash-can"></i> HAPUS SEMUA
                </button>
                
                <button onclick="toggleFullscreen()" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 transition border border-slate-200">
                    <i class="fa-solid fa-expand"></i> FULL SCREEN
                </button>
            </div>
        </header>

        <main class="p-6 flex-1 flex flex-col overflow-hidden">
            <div class="mb-4 flex justify-between items-end">
                <div>
                    <h2 class="text-xl font-bold text-slate-800 uppercase italic">Spreadsheet Terintegrasi</h2>
                    <p class="text-[11px] text-slate-500 font-medium">Auto-refresh aktif (1 menit). Hasil pencarian: <b><?= $search ? $search : 'Semua Data' ?></b></p>
                </div>
                <div class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-[10px] font-black border border-indigo-100 uppercase tracking-widest">
                    Total: <?= mysqli_num_rows($result) ?> Data
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 table-container">
                <table class="sheet-table" id="dataTable">
                    <thead>
                        <tr>
                            <th class="sticky-col bg-slate-100 text-center">Aksi</th>
                            <th class="sticky-col">Antrean</th>
                            <th class="sticky-col">Nama WBP</th>
                            <th>Status WBP</th>
                            <th>Sesi</th>
                            <th class="bg-blue-50 text-blue-700">Pendaftar Utama</th>
                            <th class="bg-blue-50 text-blue-700">NIK Utama</th>
                            <?php for($i=1; $i<=4; $i++): ?>
                                <th class="header-pengikut">Nama Pengikut <?= $i ?></th>
                                <th class="header-pengikut">NIK Pengikut <?= $i ?></th>
                            <?php endfor; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        while($k = mysqli_fetch_assoc($result)): 
                            $kid = $k['id'];
                            $q_pengunjung = mysqli_query($conn, "SELECT * FROM kunjungan_pengunjung WHERE kunjungan_id = '$kid' ORDER BY id ASC");
                            $p_list = [];
                            while($p = mysqli_fetch_assoc($q_pengunjung)) { $p_list[] = $p; }
                            $utama = isset($p_list[0]) ? $p_list[0] : ['nama_pengunjung'=>'-','nik_pengunjung'=>'-'];
                        ?>
                        <tr>
                            <td class="sticky-col text-center bg-white">
                                <a href="?hapus=<?= $k['id'] ?>" class="btn-hapus text-red-500 hover:text-red-700 p-2 inline-block">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </td>
                            <td class="sticky-col font-black text-blue-600 text-center"><?= $k['no_antrean'] ?></td>
                            <td class="sticky-col font-bold uppercase"><?= htmlspecialchars($k['nama_wbp']) ?></td>
                            <td class="italic text-slate-500 font-semibold"><?= $k['status_wbp'] ?></td>
                            <td class="text-xs font-bold"><?= $k['sesi'] ?></td>
                            <td class="font-bold text-slate-800 uppercase"><?= htmlspecialchars($utama['nama_pengunjung']) ?></td>
                            <td class="font-mono bg-blue-50/50"><?= htmlspecialchars($utama['nik_pengunjung']) ?></td>

                            <?php for($i=1; $i<=4; $i++): 
                                $data_p = isset($p_list[$i]) ? $p_list[$i] : null;
                            ?>
                                <td class="bg-slate-50 font-semibold text-blue-800 uppercase"><?= $data_p ? htmlspecialchars($data_p['nama_pengunjung']) : '' ?></td>
                                <td class="bg-slate-50 font-mono text-slate-500"><?= $data_p ? htmlspecialchars($data_p['nik_pengunjung']) : '' ?></td>
                            <?php endfor; ?>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<script>
// Fungsi Hapus Semua menggunakan SweetAlert2 yang seragam dengan menu_admin.php
function confirmDeleteAll() {
    Swal.fire({
        title: 'Hapus Seluruh Data?',
        text: "Tindakan ini akan mengosongkan seluruh antrean kunjungan secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Bersihkan Semua!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "?action=deleteAll";
        }
    });
}

function toggleFullscreen() {
    let elem = document.getElementById("main-content");
    if (!document.fullscreenElement) { elem.requestFullscreen(); } 
    else { document.exitFullscreen(); }
}
</script>

</body>
</html>