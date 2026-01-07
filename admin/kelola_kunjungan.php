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

// --- LOGIKA FILTER ---
$tgl_mulai = $_GET['tgl_mulai'] ?? '';
$tgl_selesai = $_GET['tgl_selesai'] ?? '';
$search = $_GET['search'] ?? '';

// --- LOGIKA HAPUS SEMUA ---
if (isset($_GET['action']) && $_GET['action'] == 'deleteAll') {
    try {
        $pdo->beginTransaction();
        $pdo->query("DELETE FROM kunjungan_pengunjung");
        $pdo->query("DELETE FROM kunjungan");
        $pdo->commit();
        $_SESSION['alert'] = ['type' => 'success', 'title' => 'BERSIH!', 'msg' => 'Semua data kunjungan telah dikosongkan.'];
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['alert'] = ['type' => 'error', 'title' => 'GAGAL!', 'msg' => 'Terjadi kesalahan sistem.'];
    }
    header("Location: kelola_kunjungan.php");
    exit;
}

// --- LOGIKA HAPUS SATUAN ---
if (isset($_GET['hapus'])) {
    $id_hapus = (int) $_GET['hapus'];
    try {
        $pdo->beginTransaction();
        $pdo->prepare("DELETE FROM kunjungan_pengunjung WHERE kunjungan_id = ?")->execute([$id_hapus]);
        $pdo->prepare("DELETE FROM kunjungan WHERE id = ?")->execute([$id_hapus]);
        $pdo->commit();
        $_SESSION['alert'] = ['type' => 'success', 'title' => 'TERHAPUS!', 'msg' => 'Data antrean berhasil dihapus.'];
    } catch (Exception $e) {
        $pdo->rollBack();
    }
    header("Location: kelola_kunjungan.php");
    exit;
}

// --- QUERY DINAMIS ---
$query_str = "SELECT * FROM kunjungan WHERE 1=1";
$params = [];

if (!empty($tgl_mulai) && !empty($tgl_selesai)) {
    $query_str .= " AND tanggal_kunjungan BETWEEN ? AND ?";
    $params[] = $tgl_mulai;
    $params[] = $tgl_selesai;
}

if (!empty($search)) {
    $query_str .= " AND (nama_wbp LIKE ? OR no_antrean LIKE ? OR id IN (SELECT kunjungan_id FROM kunjungan_pengunjung WHERE nik_pengunjung LIKE ? OR nama_pengunjung LIKE ?))";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param, $search_param, $search_param]);
}

$query_str .= " ORDER BY tanggal_kunjungan DESC, sesi ASC, no_antrean ASC";

$stmt = $pdo->prepare($query_str);
$stmt->execute($params);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

function tgl_indo($tanggal)
{
    $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $hari = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
    $ts = strtotime($tanggal);
    return $hari[date('l', $ts)] . ", " . date('d', $ts) . " " . $bulan[(int) date('m', $ts)] . " " . date('Y', $ts);
}
?>

<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Kunjungan | Lapas Lamongan</title>
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
    <style>
        body {
            font-family: 'Titillium Web', sans-serif;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #07213D;
            border-radius: 10px;
        }

        .table-container {
            height: calc(100vh - 180px);
            overflow: auto;
        }

        @media (min-width: 1024px) {
            th {
                position: sticky;
                top: 0;
                z-index: 40;
                background: #f8fafc !important;
                color: #64748b;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                border-bottom: 1px solid #f1f5f9;
            }

            .sticky-left-1 {
                position: sticky;
                left: 0;
                z-index: 30;
                background: white;
                border-right: 1px solid #f1f5f9;
            }

            .sticky-left-2 {
                position: sticky;
                left: 60px;
                z-index: 30;
                background: white;
                border-right: 1px solid #f1f5f9;
            }

            .sticky-left-3 {
                position: sticky;
                left: 130px;
                z-index: 30;
                background: white;
                border-right: 1px solid #e2e8f0 !important;
            }
        }

        .copyable {
            cursor: pointer;
            transition: all 0.2s;
        }

        .copyable:hover {
            background-color: #f8fafc !important;
        }
    </style>
</head>

<body class="bg-slate-50 h-full overflow-hidden">

    <div class="flex h-full overflow-hidden">
        <?php include 'layout/sidebar.php'; ?>

        <div class="flex-1 flex flex-col min-w-0 h-full">

            <header
                class="bg-white border-b-4 border-dignity shadow-sm h-20 flex items-center justify-between px-6 z-30">
                <div class="flex items-center gap-4 flex-1">
                    <button onclick="toggleSidebar()" class="lg:hidden text-imipas p-2 hover:bg-slate-100 rounded-lg">
                        <i class="fa-solid fa-bars-staggered text-xl"></i>
                    </button>

                    <form action="" method="GET"
                        class="hidden md:flex items-center gap-3 bg-slate-100 p-1.5 rounded-2xl border border-slate-200">
                        <div class="flex items-center px-3 border-r border-slate-300">
                            <i class="fa-solid fa-calendar-day text-imipas text-xs mr-2"></i>
                            <input type="date" name="tgl_mulai" value="<?= $tgl_mulai ?>"
                                class="bg-transparent text-[11px] outline-none uppercase">
                            <span class="mx-2 text-slate-400">-</span>
                            <input type="date" name="tgl_selesai" value="<?= $tgl_selesai ?>"
                                class="bg-transparent text-[11px] outline-none uppercase">
                        </div>
                        <div class="flex items-center px-3">
                            <i class="fa-solid fa-magnifying-glass text-slate-400 text-xs mr-2"></i>
                            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                                placeholder="Cari WBP atau NIK..." class="bg-transparent text-[11px] outline-none w-48">
                        </div>
                        <button type="submit"
                            class="bg-imipas text-white px-4 py-1.5 rounded-xl text-[10px] font-black hover:bg-slate-800 transition">APPLY</button>
                        <a href="export_kunjungan.php?tgl_mulai=<?= $tgl_mulai ?>&tgl_selesai=<?= $tgl_selesai ?>&search=<?= urlencode($search) ?>"
                            target="_blank"
                            class="bg-emerald-500 text-white px-4 py-1.5 rounded-xl text-[10px] font-black hover:bg-emerald-600 transition flex items-center gap-2">
                            <i class="fa-solid fa-file-excel"></i> EXCEL
                        </a>
                    </form>
                </div>

                <div class="flex items-center gap-6">
                    <div class="hidden sm:block text-right border-r pr-6 border-slate-200">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Database</p>
                        <p class="text-sm font-black text-imipas"><?= number_format(count($result)) ?> Antrean</p>
                    </div>
                    <button onclick="confirmDeleteAll()"
                        class="flex items-center gap-2 text-rose-600 hover:text-rose-800 transition">
                        <i class="fa-solid fa-trash-can text-sm"></i>
                        <span class="text-[10px] font-black uppercase tracking-tighter">Kosongkan</span>
                    </button>
                </div>
            </header>

            <main class="flex-1 p-6 overflow-hidden">
                <div
                    class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden h-full flex flex-col">
                    <div class="table-container custom-scrollbar overflow-x-auto">
                        <table class="w-full text-left min-w-[1400px]">
                            <thead>
                                <tr class="text-[10px] uppercase tracking-wider text-slate-500">
                                    <th class="p-5 text-center lg:sticky-left-1">Opsi</th>
                                    <th class="p-5 lg:sticky-left-2 text-center">No</th>
                                    <th class="p-5 lg:sticky-left-3">Nama WBP</th>
                                    <th class="p-5">Status</th>
                                    <th class="p-5 text-center">Sesi</th>
                                    <th class="p-5">Pengunjung Utama</th>
                                    <th class="p-5">NIK Utama</th>
                                    <?php for ($i = 1; $i <= 4; $i++): ?>
                                        <th class="p-5 text-slate-400 border-l border-white/10 font-normal">Pengikut
                                            <?= $i ?></th>
                                        <th class="p-5 text-slate-400 font-normal">NIK <?= $i ?></th>
                                    <?php endfor; ?>
                                </tr>
                            </thead>
                            <tbody class="text-[12px] text-slate-600">
                                <?php
                                $last_date = "";
                                foreach ($result as $k):
                                    if ($last_date != $k['tanggal_kunjungan']) {
                                        $last_date = $k['tanggal_kunjungan'];
                                        echo "<tr class='bg-slate-50'>
                                                <td colspan='15' class='p-3 px-8 font-black text-imipas text-[10px] uppercase tracking-widest border-y border-slate-100 sticky left-0'>
                                                    <i class='fa-solid fa-calendar-check text-amber-500 mr-2'></i> " . tgl_indo($last_date) . "
                                                </td>
                                              </tr>";
                                    }

                                    $stmt_p = $pdo->prepare("SELECT * FROM kunjungan_pengunjung WHERE kunjungan_id = ? ORDER BY id ASC");
                                    $stmt_p->execute([$k['id']]);
                                    $p_list = $stmt_p->fetchAll(PDO::FETCH_ASSOC);
                                    $utama = $p_list[0] ?? ['nama_pengunjung' => '-', 'nik_pengunjung' => '-'];
                                    ?>
                                    <tr class="border-b border-slate-50 group hover:bg-slate-50/50">
                                        <td
                                            class="p-4 text-center lg:sticky-left-1 bg-white group-hover:bg-slate-50 transition-colors">
                                            <button onclick="confirmDelete(<?= $k['id'] ?>)"
                                                class="text-slate-300 hover:text-rose-600 transition-colors">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </td>
                                        <td
                                            class="p-4 lg:sticky-left-2 bg-white group-hover:bg-slate-50 text-center transition-colors">
                                            <span><?= $k['no_antrean'] ?></span>
                                        </td>
                                        <td class="p-4 uppercase lg:sticky-left-3 bg-white group-hover:bg-slate-50 transition-colors copyable"
                                            onclick="copyText('<?= addslashes($k['nama_wbp']) ?>', this)">
                                            <?= htmlspecialchars($k['nama_wbp']) ?>
                                        </td>
                                        <td class="p-4 text-slate-400 italic"><?= $k['status_wbp'] ?></td>
                                        <td class="p-4 text-center">
                                            <span
                                                class="text-[10px] uppercase <?= strpos($k['sesi'], '1') !== false ? 'text-blue-600' : 'text-emerald-600' ?>">
                                                <?= $k['sesi'] ?>
                                            </span>
                                        </td>
                                        <td class="p-4 uppercase copyable text-slate-700"
                                            onclick="copyText('<?= addslashes($utama['nama_pengunjung']) ?>', this)">
                                            <?= htmlspecialchars($utama['nama_pengunjung']) ?>
                                        </td>
                                        <td class="p-4 font-mono text-slate-400 copyable"
                                            onclick="copyText('<?= addslashes($utama['nik_pengunjung']) ?>', this)">
                                            <?= htmlspecialchars($utama['nik_pengunjung']) ?>
                                        </td>
                                        <?php for ($i = 1; $i <= 4; $i++):
                                            $data_p = $p_list[$i] ?? null;
                                            ?>
                                            <td class="p-4 uppercase text-slate-500 border-l border-slate-50 <?= $data_p ? 'copyable' : '' ?>"
                                                onclick="<?= $data_p ? "copyText('" . addslashes($data_p['nama_pengunjung']) . "', this)" : "" ?>">
                                                <?= $data_p ? htmlspecialchars($data_p['nama_pengunjung']) : '-' ?>
                                            </td>
                                            <td class="p-4 font-mono text-slate-300 text-[10px] <?= $data_p ? 'copyable' : '' ?>"
                                                onclick="<?= $data_p ? "copyText('" . addslashes($data_p['nik_pengunjung']) . "', this)" : "" ?>">
                                                <?= $data_p ? htmlspecialchars($data_p['nik_pengunjung']) : '-' ?>
                                            </td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sb = document.getElementById('sidebar');
            const ov = document.getElementById('sidebarOverlay');
            if (sb) sb.classList.toggle('-translate-x-full');
            if (ov) ov.classList.toggle('hidden');
        }

        function copyText(text, element) {
            if (!text || text === '-' || text === 'null') return;
            navigator.clipboard.writeText(text).then(() => {
                const original = element.innerHTML;
                element.classList.add('text-emerald-600');
                element.innerHTML = '<i class="fa-solid fa-check mr-1"></i> COPIED';
                setTimeout(() => {
                    element.classList.remove('text-emerald-600');
                    element.innerHTML = original;
                }, 700);
            });
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Antrean?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#07213D',
                cancelButtonColor: '#f43f5e',
                confirmButtonText: 'Ya, Hapus',
                customClass: { popup: 'rounded-3xl' }
            }).then((result) => { if (result.isConfirmed) { window.location.href = "?hapus=" + id; } });
        }

        function confirmDeleteAll() {
            Swal.fire({
                title: 'KOSONGKAN DATA?',
                text: "Seluruh data akan dihapus permanen!",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#f43f5e',
                confirmButtonText: 'Hapus Semua',
                customClass: { popup: 'rounded-3xl' }
            }).then((result) => { if (result.isConfirmed) { window.location.href = "?action=deleteAll"; } });
        }

        <?php if (isset($_SESSION['alert'])): ?>
            Swal.fire({
                icon: '<?= $_SESSION['alert']['type'] ?>',
                title: '<?= $_SESSION['alert']['title'] ?>',
                text: '<?= $_SESSION['alert']['msg'] ?>',
                confirmButtonColor: '#07213D',
                customClass: { popup: 'rounded-3xl' }
            });
            <?php unset($_SESSION['alert']); ?>
        <?php endif; ?>
    </script>
</body>

</html>