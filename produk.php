<?php
// 0. SET TIMEZONE & ERROR HANDLING
date_default_timezone_set('Asia/Jakarta');
error_reporting(E_ALL);
ini_set('display_errors', 0); // Sembunyikan error dari publik saat hosting

// 1. KEAMANAN: Header Proteksi
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");

include "config/koneksi.php";

/** * SINKRONISASI KONEKSI:
 * Menjamin variabel $conn tetap tersedia baik menggunakan mysqli atau pdo di config
 */
if (!isset($conn) && isset($pdo)) {
    $conn = new mysqli($host, $user, $pass, $db);
}

if (!$conn || $conn->connect_error) {
    die("Layanan sedang dalam pemeliharaan.");
}

/**
 * 1. PENGATURAN FILTER & KEAMANAN
 */
$kategori_filter = isset($_GET['kategori']) ? $_GET['kategori'] : '';

/**
 * 2. PENGATURAN PAGINATION
 */
$limit = 12;
$halaman = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)$_GET['page'] : 1;
$offset = ($halaman - 1) * $limit;

/**
 * 3. HITUNG TOTAL DATA (Dengan Prepared Statement)
 */
if (!empty($kategori_filter)) {
    $stmt_total = $conn->prepare("SELECT COUNT(*) AS total FROM produk WHERE kategori = ?");
    $stmt_total->bind_param("s", $kategori_filter);
} else {
    $stmt_total = $conn->prepare("SELECT COUNT(*) AS total FROM produk");
}
$stmt_total->execute();
$total_data = $stmt_total->get_result()->fetch_assoc()['total'];
$total_halaman = ceil($total_data / $limit);

/**
 * 4. AMBIL DAFTAR KATEGORI UNTUK FILTER
 */
$list_kategori = mysqli_query($conn, "SELECT DISTINCT kategori FROM produk ORDER BY kategori ASC");

/**
 * 5. AMBIL DATA PRODUK (Dengan Prepared Statement)
 */
if (!empty($kategori_filter)) {
    $stmt_produk = $conn->prepare("SELECT * FROM produk WHERE kategori = ? ORDER BY id_produk DESC LIMIT ? OFFSET ?");
    $stmt_produk->bind_param("sii", $kategori_filter, $limit, $offset);
} else {
    $stmt_produk = $conn->prepare("SELECT * FROM produk ORDER BY id_produk DESC LIMIT ? OFFSET ?");
    $stmt_produk->bind_param("ii", $limit, $offset);
}
$stmt_produk->execute();
$produk = $stmt_produk->get_result();

/**
 * 6. HELPER KEAMANAN (XSS Protection)
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>Katalog Produk Unggulan - Lapas Lamongan</title>
    
    <link rel="icon" type="image/png" href="assets/images/logo_imigrasi.png">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        imipas: { blue: '#07213D', gold: '#EEBF63', platinum: '#E0E2E3' }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Titillium Web', sans-serif; }
        .hero-section { padding-top: 160px !important; }
        @media (max-width: 768px) { .hero-section { padding-top: 120px !important; } }
        
        .product-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1); }
        
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased overflow-x-hidden">

    <div class="bg-imipas-platinum border-b border-slate-200 py-2 px-4 hidden md:block relative z-[100]">
        <div class="max-w-7xl mx-auto flex justify-between text-[10px] font-bold uppercase tracking-widest text-imipas-blue">
            <span>Republik Indonesia</span>
            <span id="currentDate"></span>
        </div>
    </div>

    <?php if(file_exists("layout/navbar.php")) { include "layout/navbar.php"; } ?>

    <section class="hero-section pb-20 bg-imipas-blue text-white px-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-imipas-gold/10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="max-w-7xl mx-auto relative z-10" data-aos="fade-up">
            <div class="flex items-center gap-2 mb-4">
                <div class="h-px w-8 bg-imipas-gold"></div>
                <span class="text-imipas-gold text-xs font-bold uppercase tracking-[0.3em]">Kemandirian WBP</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-bold uppercase tracking-tight mb-4 leading-tight">
                Katalog <span class="text-imipas-gold">Produk</span>
            </h1>
            <p class="text-imipas-platinum/70 text-sm md:text-base max-w-2xl leading-relaxed">
                Menampilkan hasil karya warga binaan sebagai wujud pembinaan kemandirian Lapas Kelas IIB Lamongan.
            </p>
        </div>
    </section>

    <div class="bg-white border-b border-slate-100 relative z-30">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center overflow-x-auto no-scrollbar py-6 gap-4">
                <a href="produk.php" 
                   class="whitespace-nowrap px-6 py-2 rounded-full text-[10px] font-bold uppercase tracking-widest transition-all <?= empty($kategori_filter) ? 'bg-imipas-blue text-white shadow-md' : 'bg-slate-100 text-slate-400 hover:bg-slate-200' ?>">
                    Semua Produk
                </a>
                <?php while($cat = mysqli_fetch_assoc($list_kategori)): ?>
                <a href="?kategori=<?= urlencode($cat['kategori']) ?>" 
                   class="whitespace-nowrap px-6 py-2 rounded-full text-[10px] font-bold uppercase tracking-widest transition-all <?= ($kategori_filter == $cat['kategori']) ? 'bg-imipas-blue text-white shadow-md' : 'bg-slate-100 text-slate-400 hover:bg-slate-200' ?>">
                    <?= e($cat['kategori']) ?>
                </a>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <main class="py-16 px-4 max-w-7xl mx-auto relative z-20">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php if ($produk->num_rows > 0): ?>
                <?php while ($row = $produk->fetch_assoc()): 
                    $gambarPath = "uploads/" . $row['gambar'];
                    $gambar = (!empty($row['gambar']) && file_exists($gambarPath)) ? $gambarPath : "assets/images/no-image.png";
                ?>
                    <article data-aos="fade-up" class="product-card group bg-white border border-slate-200 shadow-sm flex flex-col h-full relative overflow-hidden">
                        
                        <a href="detail_produk.php?id=<?= (int)$row['id_produk'] ?>" class="absolute inset-0 z-20" aria-label="Detail <?= e($row['nama_produk']) ?>"></a>

                        <div class="relative aspect-square overflow-hidden bg-slate-100">
                            <img src="<?= e($gambar) ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Produk">
                            <div class="absolute top-0 right-0 p-3 z-10">
                                <span class="bg-imipas-blue/90 backdrop-blur-sm text-white text-[9px] font-bold px-3 py-1 uppercase tracking-tighter">
                                    <?= e($row['kategori']) ?>
                                </span>
                            </div>
                        </div>

                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="font-bold text-imipas-blue text-sm mb-2 group-hover:text-imipas-gold transition uppercase line-clamp-2">
                                <?= e($row['nama_produk']) ?>
                            </h3>
                            <p class="text-slate-500 text-[11px] line-clamp-2 mb-6 leading-relaxed">
                                <?= e($row['deskripsi']) ?>
                            </p>

                            <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                    </span>
                                    <span class="text-[9px] font-bold text-green-600 uppercase">Tersedia</span>
                                </div>
                                <div class="text-[10px] font-bold text-imipas-blue group-hover:text-imipas-gold uppercase tracking-widest flex items-center gap-2 transition-all">
                                    Detail <i class="fa-solid fa-arrow-right-long transition-transform group-hover:translate-x-1"></i>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-full py-20 text-center">
                    <i class="fa-solid fa-box-open text-4xl text-slate-200 mb-4"></i>
                    <p class="text-slate-400 italic">Produk tidak tersedia dalam kategori ini.</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($total_halaman > 1): ?>
            <nav class="mt-20 flex flex-col items-center gap-6" data-aos="fade-up">
                <div class="flex items-center gap-2 bg-white p-2 rounded-full shadow-lg border border-slate-100">
                    <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                        <a href="?page=<?= $i ?><?= !empty($kategori_filter) ? '&kategori='.urlencode($kategori_filter) : '' ?>"
                            class="w-10 h-10 flex items-center justify-center rounded-full text-[10px] font-bold transition-all <?= $i == $halaman ? 'bg-imipas-blue text-white shadow-md' : 'text-slate-400 hover:text-imipas-blue hover:bg-slate-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </nav>
        <?php endif; ?>
    </main>

    <?php if(file_exists("layout/footer.php")) { include "layout/footer.php"; } ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });

        const dateEl = document.getElementById('currentDate');
        if (dateEl) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateEl.innerText = new Date().toLocaleDateString('id-ID', options);
        }
    </script>
</body>
</html>