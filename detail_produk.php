<?php 
include "config/koneksi.php";

// Validasi ID produk
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: produk.php");
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = '$id'");
$data = mysqli_fetch_assoc($query);

// Jika produk tidak ditemukan
if (!$data) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location='produk.php';</script>";
    exit;
}

$gambarPath = "uploads/".$data['gambar'];
$gambar = (!empty($data['gambar']) && file_exists($gambarPath)) ? $gambarPath : "assets/images/no-image.png";
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title><?= htmlspecialchars($data['nama_produk']) ?> - Lapas Lamongan</title>
    <link rel="icon" type="image/png" href="assets/images/logo_imigrasi.png">
    
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        imipas: { blue: '#07213D', gold: '#EEBF63', platinum: '#E0E2E3' }
                    },
                    fontFamily: { sans: ['"Titillium Web"', 'sans-serif'] }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Titillium Web', sans-serif; }
        
        /* FIX POSISI: Memberikan jarak yang pas di bawah navbar fixed */
        .content-wrapper {
            padding-top: 140px !important; /* Jarak untuk desktop */
        }
        @media (max-width: 768px) {
            .content-wrapper {
                padding-top: 110px !important; /* Jarak untuk mobile */
            }
        }

        /* Memastikan Navbar tetap berada di lapisan paling depan */
        nav, header {
            z-index: 9999 !important;
        }

        /* Styling tambahan untuk visual */
        .img-container {
            background-image: radial-gradient(circle, #f8fafc 0%, #e2e8f0 100%);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased overflow-x-hidden">

    <div class="bg-imipas-platinum border-b border-slate-200 py-2 px-4 hidden md:block relative z-[10000]">
        <div class="max-w-7xl mx-auto flex justify-between text-[10px] font-bold uppercase tracking-widest text-imipas-blue">
            <span>Republik Indonesia</span>
            <span id="currentDate"></span>
        </div>
    </div>

    <?php if(file_exists("layout/navbar.php")) include "layout/navbar.php"; ?>

    <main class="content-wrapper pb-24 px-4 max-w-7xl mx-auto">
        
        <div class="mb-8">
            <a href="produk.php" class="inline-flex items-center gap-3 text-imipas-blue font-bold text-[11px] uppercase tracking-[0.2em] group">
                <div class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center group-hover:bg-imipas-blue group-hover:text-white transition-all">
                    <i class="fa-solid fa-chevron-left"></i>
                </div>
                Kembali ke Katalog
            </a>
        </div>

        <div class="bg-white border border-slate-200 shadow-2xl rounded-sm overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                
                <div class="img-container relative p-8 md:p-16 flex items-center justify-center">
                    <div class="absolute top-6 right-6">
                        <span class="bg-imipas-blue text-white text-[10px] font-bold px-4 py-1.5 uppercase tracking-widest">
                            <?= htmlspecialchars($data['kategori']) ?>
                        </span>
                    </div>
                    
                    <img src="<?= $gambar ?>" alt="<?= htmlspecialchars($data['nama_produk']) ?>" 
                         class="w-full h-auto max-h-[500px] object-contain drop-shadow-2xl transform transition-transform hover:scale-105 duration-500">
                </div>

                <div class="p-8 md:p-16 flex flex-col justify-center border-t lg:border-t-0 lg:border-l border-slate-100">
                    <div class="mb-4 flex items-center gap-2">
                        <div class="h-1 w-6 bg-imipas-gold"></div>
                        <span class="text-imipas-gold text-[10px] font-bold uppercase tracking-[0.3em]">Detail Produk</span>
                    </div>

                    <h1 class="text-3xl md:text-5xl font-bold text-imipas-blue mb-8 uppercase tracking-tight leading-tight">
                        <?= htmlspecialchars($data['nama_produk']) ?>
                    </h1>

                    <div class="flex items-center gap-4 mb-10">
                        <div class="inline-flex items-center gap-3 px-4 py-2 bg-green-50 text-green-700 border border-green-100 rounded-sm">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-600"></span>
                            </span>
                            <span class="text-[10px] font-bold uppercase">Tersedia / Pre-Order</span>
                        </div>
                    </div>

                    <div class="mb-12">
                        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">Informasi Produk:</h3>
                        <p class="text-slate-600 leading-relaxed text-base md:text-lg border-l-4 border-imipas-gold pl-6 py-2">
                            <?= nl2br(htmlspecialchars($data['deskripsi'])) ?>
                        </p>
                    </div>

                    <div class="bg-slate-50 p-6 rounded-sm border border-slate-200 mb-12">
                        <div class="flex gap-4">
                            <i class="fa-solid fa-circle-info text-imipas-blue text-lg"></i>
                            <p class="text-[11px] text-slate-500 italic leading-relaxed">
                                Produk ini merupakan hasil karya pembinaan kemandirian Warga Binaan Pemasyarakatan (WBP) Lapas Kelas IIB Lamongan. Dengan membeli, Anda mendukung proses reintegrasi sosial mereka.
                            </p>
                        </div>
                    </div>

                    <a href="https://wa.me/628113405959?text=Halo, saya tertarik dengan produk: <?= urlencode($data['nama_produk']) ?>" 
                       target="_blank"
                       class="flex items-center justify-center gap-3 bg-imipas-blue text-white py-5 px-8 text-xs font-bold uppercase tracking-[0.2em] hover:bg-imipas-gold hover:text-imipas-blue transition-all shadow-xl active:scale-95">
                        <i class="fa-brands fa-whatsapp text-lg"></i>
                        Pesan via WhatsApp Sekarang
                    </a>
                </div>

            </div>
        </div>
    </main>

    <?php if(file_exists("layout/footer.php")) include "layout/footer.php"; ?>

    <script>
        // Set Tanggal Otomatis
        const dateEl = document.getElementById('currentDate');
        if(dateEl) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateEl.innerText = new Date().toLocaleDateString('id-ID', options);
        }
    </script>
</body>
</html>