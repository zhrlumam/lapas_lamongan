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

// --- LOGIKA FILTER & PENCARIAN ---
$tgl_mulai = $_GET['tgl_mulai'] ?? '';
$tgl_selesai = $_GET['tgl_selesai'] ?? '';
$search = $_GET['search'] ?? '';

// --- LOGIKA HAPUS ---
if (isset($_GET['hapus'])) {
    $id_hapus = (int) $_GET['hapus'];
    try {
        $stmt_del = $pdo->prepare("DELETE FROM riwayat_integrasi WHERE id = ?");
        $stmt_del->execute([$id_hapus]);
        $_SESSION['alert'] = ['type' => 'success', 'title' => 'DIHAPUS!', 'msg' => 'Data riwayat berhasil dihapus.'];
    } catch (Exception $e) {
        $_SESSION['alert'] = ['type' => 'error', 'title' => 'GAGAL!', 'msg' => 'Terjadi kesalahan sistem.'];
    }
    header("Location: kelola_integrasi.php");
    exit;
}

// --- QUERY DATA UTAMA ---
$query_str = "SELECT * FROM riwayat_integrasi WHERE 1=1";
$params = [];

if (!empty($tgl_mulai) && !empty($tgl_selesai)) {
    $query_str .= " AND DATE(created_at) BETWEEN ? AND ?";
    $params[] = $tgl_mulai;
    $params[] = $tgl_selesai;
}

if (!empty($search)) {
    $query_str .= " AND (nama_wbp LIKE ? OR user_name LIKE ? OR jenis_layanan LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param, $search_param]);
}

$query_str .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($query_str);
$stmt->execute($params);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- STATISTIK ---
// Menggunakan query terpisah agar tidak terpengaruh filter untuk ringkasan global
$stat_total = $pdo->query("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN jenis_layanan = 'PB' THEN 1 ELSE 0 END) as pb,
    SUM(CASE WHEN jenis_layanan = 'CB' THEN 1 ELSE 0 END) as cb,
    SUM(CASE WHEN jenis_layanan = 'CMB' THEN 1 ELSE 0 END) as cmb,
    SUM(CASE WHEN jenis_layanan = 'Asimilasi' THEN 1 ELSE 0 END) as asimilasi
FROM riwayat_integrasi")->fetch(PDO::FETCH_ASSOC);

function tgl_indo($tanggal)
{
    if (empty($tanggal))
        return "-";
    $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $ts = strtotime($tanggal);
    return date('d', $ts) . " " . $bulan[(int) date('m', $ts)] . " " . date('Y', $ts) . " <span class='text-slate-400 font-normal'>(" . date('H:i', $ts) . ")</span>";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Integrasi | Lapas Lamongan</title>
    <link rel="icon" type="image/png" href="../assets/images/logo_imigrasi.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@400;600;700&display=swap" rel="stylesheet">
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
    <style>
        body {
            font-family: 'Titillium Web', sans-serif;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #07213D;
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-slate-50 flex h-screen overflow-hidden">

    <?php include 'layout/sidebar.php'; ?>

    <div class="flex-1 flex flex-col min-w-0">

        <!-- HEADER -->
        <header class="bg-white border-b-4 border-dignity shadow-sm h-20 flex items-center justify-between px-6 z-20">
            <div class="flex items-center gap-4 flex-1">
                <button onclick="toggleSidebar()" class="lg:hidden text-imipas p-2 hover:bg-slate-100 rounded-lg">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
                <h1 class="text-xl font-bold text-imipas uppercase tracking-wider hidden sm:block">Riwayat Integrasi
                </h1>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Total Permohonan</p>
                    <p class="text-lg font-black text-imipas">
                        <?= number_format((float) ($stat_total['total'] ?? 0)) ?>
                    </p>
                </div>
            </div>
        </header>

        <!-- MAIN CONTENT -->
        <main class="flex-1 overflow-y-auto p-6 custom-scrollbar">

            <!-- STATS CARDS -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div
                    class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between gap-2">
                    <div>
                        <p class="text-2xl font-bold text-blue-600">
                            <?= number_format((float) ($stat_total['pb'] ?? 0)) ?>
                        </p>
                        <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Pembebasan Bersyarat
                            (PB)</p>
                    </div>
                    <i class="fa-solid fa-file-contract text-blue-100 text-3xl"></i>
                </div>
                <div
                    class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between gap-2">
                    <div>
                        <p class="text-2xl font-bold text-emerald-600">
                            <?= number_format((float) ($stat_total['cb'] ?? 0)) ?>
                        </p>
                        <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Cuti Bersyarat (CB)</p>
                    </div>
                    <i class="fa-solid fa-file-signature text-emerald-100 text-3xl"></i>
                </div>
                <div
                    class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between gap-2">
                    <div>
                        <p class="text-2xl font-bold text-purple-600">
                            <?= number_format((float) ($stat_total['cmb'] ?? 0)) ?>
                        </p>
                        <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Cuti Menjelang Bebas
                        </p>
                    </div>
                    <i class="fa-solid fa-clock-rotate-left text-purple-100 text-3xl"></i>
                </div>
                <div
                    class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between gap-2">
                    <div>
                        <p class="text-2xl font-bold text-orange-600">
                            <?= number_format((float) ($stat_total['asimilasi'] ?? 0)) ?>
                        </p>
                        <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Asimilasi Rumah</p>
                    </div>
                    <i class="fa-solid fa-house-user text-orange-100 text-3xl"></i>
                </div>
            </div>

            <!-- TABLE SECTION -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
                <!-- TOOLBAR -->
                <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row gap-4 justify-between items-center">
                    <form action="" method="GET"
                        class="flex items-center gap-3 bg-slate-50 p-1 rounded-xl border border-slate-200 w-full md:w-auto">
                        <div class="flex items-center px-3 border-r border-slate-200">
                            <input type="date" name="tgl_mulai" value="<?= $tgl_mulai ?>"
                                class="bg-transparent text-xs outline-none">
                            <span class="mx-2 text-slate-400">-</span>
                            <input type="date" name="tgl_selesai" value="<?= $tgl_selesai ?>"
                                class="bg-transparent text-xs outline-none">
                        </div>
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                            placeholder="Cari Nama WBP / User..." class="bg-transparent text-xs outline-none px-3 w-40">
                        <button type="submit"
                            class="bg-imipas text-white h-8 w-8 rounded-lg flex items-center justify-center hover:bg-slate-800 transition"><i
                                class="fa-solid fa-filter text-xs"></i></button>
                        <a href="kelola_integrasi.php" class="text-slate-400 hover:text-red-500 px-2" title="Reset"><i
                                class="fa-solid fa-rotate-right"></i></a>
                    </form>
                </div>

                <!-- TABLE -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                            <tr>
                                <th class="p-5 text-center w-16">#</th>
                                <th class="p-5">Waktu Request</th>
                                <th class="p-5">User (Pemohon)</th>
                                <th class="p-5">Nama WBP</th>
                                <th class="p-5">Jenis Layanan</th>
                                <th class="p-5">Detail Penjamin</th>
                                <th class="p-5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <?php if (empty($result)): ?>
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-slate-400 italic">Tidak ada data ditemukan.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($result as $i => $row): ?>
                                    <tr class="hover:bg-slate-50 transition group">
                                        <td class="p-5 text-center text-slate-400">
                                            <?= $i + 1 ?>
                                        </td>
                                        <td class="p-5 font-bold text-slate-600 text-xs">
                                            <?= tgl_indo($row['created_at']) ?>
                                        </td>
                                        <td class="p-5">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-imipas text-xs">
                                                    <?= htmlspecialchars($row['user_name']) ?>
                                                </span>
                                                <span class="text-[10px] text-slate-400">
                                                    <?= htmlspecialchars($row['user_email']) ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="p-5 font-bold text-slate-700 uppercase">
                                            <?= htmlspecialchars($row['nama_wbp']) ?>
                                        </td>
                                        <td class="p-5">
                                            <?php
                                            $badge_color = 'bg-slate-100 text-slate-600';
                                            if ($row['jenis_layanan'] == 'PB')
                                                $badge_color = 'bg-blue-100 text-blue-700 border border-blue-200';
                                            if ($row['jenis_layanan'] == 'CB')
                                                $badge_color = 'bg-emerald-100 text-emerald-700 border border-emerald-200';
                                            if ($row['jenis_layanan'] == 'CMB')
                                                $badge_color = 'bg-purple-100 text-purple-700 border border-purple-200';
                                            if ($row['jenis_layanan'] == 'Asimilasi')
                                                $badge_color = 'bg-orange-100 text-orange-700 border border-orange-200';
                                            ?>
                                            <span
                                                class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wide <?= $badge_color ?>">
                                                <?= htmlspecialchars($row['jenis_layanan']) ?>
                                            </span>
                                        </td>
                                        <td class="p-5 text-xs text-slate-500">
                                            <span class="block">Hubungan: <strong class="text-slate-700">
                                                    <?= htmlspecialchars($row['hubungan']) ?>
                                                </strong></span>
                                            <span class="block">Pekerjaan:
                                                <?= htmlspecialchars($row['penjamin_pekerjaan']) ?>
                                            </span>
                                        </td>
                                        <td class="p-5 text-center">
                                            <button onclick="hapusData(<?= $row['id'] ?>)"
                                                class="text-slate-300 hover:text-rose-500 transition tooltip" title="Hapus Log">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <script>
        function hapusData(id) {
            Swal.fire({
                title: 'Hapus Riwayat?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#07213D',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?hapus=' + id;
                }
            })
        }

        <?php if (isset($_SESSION['alert'])): ?>
            Swal.fire({
                icon: '<?= $_SESSION['alert']['type'] ?>',
                title: '<?= $_SESSION['alert']['title'] ?>',
                text: '<?= $_SESSION['alert']['msg'] ?>',
                timer: 3000,
                showConfirmButton: false
            });
            <?php unset($_SESSION['alert']); ?>
        <?php endif; ?>
    </script>
</body>

</html>