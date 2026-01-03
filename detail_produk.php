<?php 
// 0. SET TIMEZONE & ERROR HANDLING
date_default_timezone_set('Asia/Jakarta');
error_reporting(E_ALL);
ini_set('display_errors', 0); 

// 1. KEAMANAN: Header Proteksi
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");

include "config/koneksi.php";

/** * SINKRONISASI KONEKSI:
 * Menjamin variabel $conn tetap tersedia
 */
if (!isset($conn) && isset($pdo)) {
    $conn = new mysqli($host, $user, $pass, $db);
}

// 2. KEAMANAN: Prepared Statements
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: produk.php");
    exit;
}

$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM produk WHERE id_produk = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location='produk.php';</script>";
    exit;
}

// 3. HELPER KEAMANAN
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

$gambarPath = "uploads/".$data['gambar'];
$gambar = (!empty($data['gambar']) && file_exists($gambarPath)) ? $gambarPath : "assets/images/no-image.png";
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title><?= e($data['nama_produk']) ?> - Lapas Lamongan</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300;400;600;700&display=swap" rel="stylesheet">
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
        
        /* MENGHAPUS SPACE BERLEBIH */
        .content-wrapper {
            padding-top: 10px !important; 
        }

        /* CARD GAMBAR MINIMALIS */
        .premium-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 2px;
            position: relative;
            transition: all 0.3s ease;
        }

        .img-display-area {
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .card-corner {
            position: absolute;
            top: 0;
            right: 0;
            width: 40px;
            height: 40px;
            background: linear-gradient(225deg, #EEBF63 50%, transparent 50%);
            opacity: 0.8;
        }
    </style>
</head>

<body class="bg-white text-slate-900 antialiased">

    <div class="bg-imipas-platinum border-b border-slate-200 py-2 px-4 hidden md:block relative z-[10000]">
        <div class="max-w-7xl mx-auto flex justify-between text-[10px] font-bold uppercase tracking-widest text-imipas-blue">
            <span>Republik Indonesia</span>
            <span id="currentDate"></span>
        </div>
    </div>

    <?php if(file_exists("layout/navbar.php")) include "layout/navbar.php"; ?>

    <main class="max-w-7xl mx-auto px-4">
        <div class="content-wrapper">
            
            <div class="py-4 border-b border-slate-50 mb-6">
                <a href="produk.php" class="inline-flex items-center gap-2 text-imipas-blue font-bold text-[10px] uppercase tracking-[0.2em] hover:text-imipas-gold transition-all">
                    <i class="fa-solid fa-arrow-left-long"></i> Katalog Produk
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                <div class="lg:col-span-6">
                    <div class="premium-card shadow-sm">
                        <div class="card-corner"></div>
                        <div class="img-display-area aspect-square group">
                            <img src="<?= $gambar ?>" 
                                 alt="<?= e($data['nama_produk']) ?>" 
                                 class="w-full h-full object-contain p-10 transform transition-transform duration-700 group-hover:scale-105">
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6">
                    <div class="space-y-6">
                        <div>
                            <span class="text-imipas-gold text-[10px] font-bold uppercase tracking-[0.4em] border-b-2 border-imipas-gold pb-1">
                                <?= e($data['kategori']) ?>
                            </span>
                            <h1 class="text-4xl md:text-5xl font-extrabold text-imipas-blue mt-4 uppercase tracking-tighter leading-none">
                                <?= e($data['nama_produk']) ?>
                            </h1>
                        </div>

                        <div class="py-6 border-y border-slate-100">
                            <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Informasi Lengkap :</h3>
                            <div class="text-slate-600 leading-relaxed text-lg font-light">
                                <?= nl2br(e($data['deskripsi'])) ?>
                            </div>
                        </div>

                        <div class="flex flex-col gap-4 pt-4">
                            <a href="https://wa.me/628113405959?text=Halo, saya ingin memesan: <?= urlencode($data['nama_produk']) ?>" 
                               target="_blank"
                               class="flex items-center justify-center gap-4 bg-imipas-blue text-white py-5 px-8 text-xs font-bold uppercase tracking-[0.2em] hover:bg-slate-800 transition-all shadow-lg active:scale-95">
                                <i class="fa-brands fa-whatsapp text-xl"></i>
                                Hubungi Untuk Pemesanan
                            </a>

                            <div class="flex items-start gap-4 p-4 bg-slate-50 border border-slate-200">
                                <i class="fa-solid fa-award text-imipas-gold text-xl"></i>
                                <p class="text-[11px] text-slate-500 leading-relaxed uppercase tracking-wider font-semibold">
                                    Produk Unggulan Hasil Pembinaan Kemandirian WBP Lapas Kelas IIB Lamongan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <?php if(file_exists("layout/footer.php")) include "layout/footer.php"; ?>

    <script>
        const dateEl = document.getElementById('currentDate');
        if(dateEl) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateEl.innerText = new Date().toLocaleDateString('id-ID', options);
        }
    </script>
</body>
</html>