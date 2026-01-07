<?php
// 0. SET TIMEZONE & ERROR HANDLING
date_default_timezone_set('Asia/Jakarta');
error_reporting(E_ALL);
ini_set('display_errors', 0); // Sembunyikan error teknis dari publik

// 1. KEAMANAN: Header Proteksi Profesional
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: camera=(), microphone=(), geolocation=()");

include "config/koneksi.php";

/** * SINKRONISASI KONEKSI:
 * Menjamin variabel $conn tetap jalan meskipun di config menggunakan PDO atau MySQLi
 */
if (!isset($conn) && isset($pdo)) {
    $conn = new mysqli($host, $user, $pass, $db);
}

// Fungsi Helper Keamanan
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// 2. Validasi ID dengan Prepared Statement (Mencegah SQL Injection)
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: berita.php");
    exit;
}

$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM berita WHERE id_berita = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    header("Location: berita.php");
    exit;
}

// 3. Persiapan Data
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
$url_berita = $protocol . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$judul_berita = e($data['judul']);

// Berita Lainnya (Sidebar)
$stmt_lain = $conn->prepare("SELECT id_berita, judul, tanggal FROM berita WHERE id_berita != ? ORDER BY tanggal DESC LIMIT 5");
$stmt_lain->bind_param("i", $id);
$stmt_lain->execute();
$berita_lain = $stmt_lain->get_result();
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title><?= $judul_berita; ?> - Lapas Kelas IIB Lamongan</title>
    
    <meta property="og:title" content="<?= $judul_berita; ?>">
    <meta property="og:description" content="Baca berita selengkapnya di website resmi Lapas Kelas IIB Lamongan.">
    <meta property="og:image" content="<?= $protocol . "://$_SERVER[HTTP_HOST]" ?>/uploads/<?= e($data['gambar']); ?>">
    <meta property="og:url" content="<?= $url_berita; ?>">

    <link rel="icon" type="image/png" href="assets/images/logo_imigrasi.png">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        imipas: { blue: '#07213D', gold: '#EEBF63', platinum: '#E0E2E3' }
                    },
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .isi-berita img { max-width: 100%; height: auto; border-radius: 8px; margin: 20px 0; }
        .isi-berita p { margin-bottom: 1.5rem; }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased overflow-x-hidden">

    <div class="bg-imipas-platinum border-b border-slate-200 py-2 px-4 block">
        <div class="max-w-7xl mx-auto flex justify-between text-[10px] font-bold uppercase tracking-widest text-imipas-blue">
            <span>Republik Indonesia</span>
            <span id="currentDate"></span>
        </div>
    </div>

    <?php if(file_exists("layout/navbar.php")) { include "layout/navbar.php"; } ?>

    <main class="pt-10 pb-20 px-4 max-w-7xl mx-auto relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

            <article class="lg:col-span-8 bg-white border border-slate-100 p-6 md:p-10 shadow-sm rounded-sm">
                <nav class="flex items-center gap-2 mb-6 text-slate-400 text-[10px] uppercase font-bold tracking-widest">
                    <a href="berita.php" class="hover:text-imipas-blue transition">Berita</a>
                    <i class="fa-solid fa-chevron-right text-[7px]"></i>
                    <span class="text-imipas-gold">Detail</span>
                </nav>

                <h1 class="text-2xl md:text-4xl font-bold text-imipas-blue uppercase leading-tight mb-6">
                    <?= $judul_berita; ?>
                </h1>

                <div class="flex flex-wrap items-center gap-6 text-[11px] font-bold uppercase text-slate-400 mb-8 border-b border-slate-50 pb-6">
                    <div class="flex items-center gap-2">
                        <i class="fa-regular fa-calendar text-imipas-gold"></i>
                        <?= date('d F Y', strtotime($data['tanggal'])); ?>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-regular fa-user text-imipas-gold"></i>
                        Admin Humas
                    </div>
                    <div class="flex items-center gap-3 ml-auto">
                        <a href="https://api.whatsapp.com/send?text=<?= urlencode($data['judul'] . " - " . $url_berita); ?>" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-full bg-green-500 text-white hover:scale-110 transition shadow-sm">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($url_berita); ?>" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-600 text-white hover:scale-110 transition shadow-sm">
                            <i class="fa-brands fa-facebook-f text-xs"></i>
                        </a>
                        <button onclick="copyToClipboard()" class="w-8 h-8 flex items-center justify-center rounded-full bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 text-white hover:scale-110 transition shadow-sm">
                            <i class="fa-brands fa-instagram"></i>
                        </button>
                    </div>
                </div>

                <div class="aspect-video w-full overflow-hidden rounded-sm bg-slate-100 mb-10 group">
                    <?php 
                        // PERBAIKAN: Folder gambar diarahkan ke uploads/ agar sinkron dengan admin
                        $img_file = "uploads/" . $data['gambar'];
                        $img_src = (!empty($data['gambar']) && file_exists($img_file)) ? $img_file : "assets/images/default-news.jpg";
                    ?>
                    <img src="<?= $img_src ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="<?= $judul_berita; ?>">
                </div>

                <div class="isi-berita text-slate-700 text-lg leading-relaxed text-justify">
                    <?= nl2br(e($data['isi'])); ?>
                </div>

                <div class="mt-16 py-6 border-y border-slate-50 flex flex-col md:flex-row items-center justify-between gap-4">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Bagikan artikel ini:</span>
                    <div class="flex gap-4">
                        <a href="https://api.whatsapp.com/send?text=<?= urlencode($data['judul'] . " - " . $url_berita); ?>" target="_blank" class="flex items-center gap-2 text-slate-400 hover:text-green-500 transition">
                            <i class="fa-brands fa-whatsapp text-xl"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($url_berita); ?>" target="_blank" class="flex items-center gap-2 text-slate-400 hover:text-blue-600 transition">
                            <i class="fa-brands fa-facebook text-xl"></i>
                        </a>
                        <button onclick="copyToClipboard()" class="text-slate-400 hover:text-pink-500 transition">
                            <i class="fa-brands fa-instagram text-xl"></i>
                        </button>
                    </div>
                </div>
                <p id="copyMessage" class="hidden text-center text-[10px] text-green-600 mt-2 font-bold italic">Link disalin! Tempel di Story Instagram.</p>
            </article>

            <aside class="lg:col-span-4 space-y-8">
                <div class="bg-imipas-blue p-8 text-white rounded-sm shadow-xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
                    
                    <h3 class="font-bold text-imipas-gold uppercase tracking-[0.2em] mb-8 border-b border-white/10 pb-4 text-sm">Berita Terkini</h3>
                    
                    <div class="space-y-8 relative z-10">
                        <?php while ($row_lain = $berita_lain->fetch_assoc()): ?>
                            <a href="detail_berita.php?id=<?= $row_lain['id_berita']; ?>" class="group flex flex-col gap-1">
                                <span class="text-[9px] text-imipas-gold/60 font-bold uppercase tracking-widest">
                                    <?= date('d M Y', strtotime($row_lain['tanggal'])); ?>
                                </span>
                                <h4 class="text-sm font-bold leading-snug group-hover:text-imipas-gold transition-colors duration-300 uppercase line-clamp-2">
                                    <?= e($row_lain['judul']); ?>
                                </h4>
                            </a>
                        <?php endwhile; ?>
                    </div>
                    
                    <a href="berita.php" class="mt-10 block text-center border border-imipas-gold/30 py-3 text-[10px] font-bold uppercase tracking-widest hover:bg-imipas-gold hover:text-imipas-blue transition-all duration-300 rounded-sm">
                        Lihat Semua Berita
                    </a>
                </div>

                <div class="border border-slate-200 p-6 rounded-sm bg-white">
                    <h4 class="text-imipas-blue font-bold uppercase text-[11px] mb-4 tracking-wider">Layanan Pengaduan</h4>
                    <p class="text-slate-500 text-xs leading-relaxed mb-4">Temukan kendala atau ingin memberikan aspirasi? Hubungi kanal resmi kami.</p>
                    <a href="pengaduan.php" class="text-imipas-gold font-bold text-[10px] uppercase hover:underline">Hubungi Kami <i class="fa-solid fa-arrow-right ml-1"></i></a>
                </div>
            </aside>

        </div>
    </main>

    <?php if(file_exists("layout/footer.php")) { include "layout/footer.php"; } ?>

    <script>
        const dateElement = document.getElementById('currentDate');
        if(dateElement) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateElement.innerText = new Date().toLocaleDateString('id-ID', options);
        }

        function copyToClipboard() {
            const linkBerita = window.location.href;
            navigator.clipboard.writeText(linkBerita).then(() => {
                const msg = document.getElementById('copyMessage');
                msg.classList.remove('hidden');
                setTimeout(() => { msg.classList.add('hidden'); }, 3000);
            });
        }
    </script>
</body>
</html>