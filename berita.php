<?php
// Menampilkan error untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config/koneksi.php"; 

if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}

// 1. AMBIL DATA INFORMASI (Tabel: informasi)
$query_info = mysqli_query($conn, "SELECT * FROM informasi ORDER BY id_info DESC LIMIT 3");

// 2. KONFIGURASI PAGINATION UNTUK BERITA (Tabel: berita)
$limit = 6; 
$halaman = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($halaman - 1) * $limit;

$total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM berita");
$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_halaman = ceil($total_data / $limit);

$berita = mysqli_query($conn, "SELECT * FROM berita ORDER BY tanggal DESC LIMIT $limit OFFSET $offset");
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Berita & Informasi - Lapas Kelas IIB Lamongan</title>
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
                    },
                    fontFamily: { sans: ['"Titillium Web"', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Titillium Web', sans-serif; }
        
        /* FIX JUDUL TERTUTUP: Karena navbar fixed, kita dorong konten ke bawah */
        .hero-padding {
            padding-top: 160px !important; 
        }
        @media (max-width: 768px) {
            .hero-padding { padding-top: 130px !important; }
        }

        /* MEMASTIKAN NAVBAR DI ATAS SEGALANYA */
        /* Seringkali menu mobile tidak bisa diklik karena tertutup z-index elemen lain */
        nav, header, #mobileMenu {
            z-index: 9999 !important;
            position: relative;
        }

        /* Pengaturan transisi jika navbar.php menggunakan ID mobileMenu */
        #mobileMenu {
            transition: all 0.5s ease-in-out;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased overflow-x-hidden">

    <?php if(file_exists("layout/navbar.php")) { include "layout/navbar.php"; } ?>

   <section class="pt-32 pb-24 bg-imipas-blue text-white px-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-imipas-gold/10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="max-w-7xl mx-auto relative z-10" data-aos="fade-right">
            <div class="flex items-center gap-2 mb-4">
                <div class="h-1 w-8 bg-imipas-gold"></div>
                <span class="text-imipas-gold text-[10px] font-bold uppercase tracking-[0.3em]">Pusat Media</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-bold uppercase tracking-tight mb-4">Informasi & <span class="text-imipas-gold">Berita</span></h1>
            <p class="text-imipas-platinum/70 text-sm md:text-base max-w-2xl leading-relaxed">
                Akses informasi layanan publik dan dokumentasi kegiatan resmi Lapas Kelas IIB Lamongan dalam satu pintu.
            </p>
        </div>
    </section>

    <section class="-mt-12 relative z-20 px-4 mb-20">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php if ($query_info && mysqli_num_rows($query_info) > 0): ?>
                    <?php while($info = mysqli_fetch_assoc($query_info)): ?>
                    <div class="bg-white p-6 shadow-xl border-t-4 border-imipas-gold group hover:bg-imipas-blue transition-all duration-300" data-aos="zoom-in">
                        <div class="flex items-center gap-3 mb-3 text-imipas-gold group-hover:text-white transition-colors">
                            <i class="fa-solid fa-circle-info text-lg"></i>
                            <span class="text-[10px] font-bold uppercase tracking-[0.2em]">Informasi Penting</span>
                        </div>
                        <h3 class="font-bold text-imipas-blue group-hover:text-imipas-gold text-base mb-3 leading-tight uppercase"><?= htmlspecialchars($info['judul_info'] ?? '') ?></h3>
                        <a href="<?= $info['link_tujuan'] ?? '#' ?>" class="text-imipas-blue group-hover:text-white font-bold text-[10px] uppercase border-b-2 border-imipas-gold pb-1 transition-all">Selengkapnya</a>
                    </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="py-12 px-4 max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-12" data-aos="fade-up">
            <h3 class="text-2xl font-bold uppercase text-imipas-blue">Berita <span class="text-imipas-gold">Terkini</span></h3>
            <div class="h-[1px] flex-grow ml-6 bg-slate-200 hidden md:block"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8" data-aos="fade-up">
            <?php if (mysqli_num_rows($berita) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($berita)): 
                    $gambarPath = "assets/images/" . $row['gambar'];
                    $gambar = (!empty($row['gambar']) && file_exists($gambarPath)) ? $gambarPath : "assets/images/default-news.jpg";
                ?>
                <article class="group bg-white flex flex-col border border-slate-100 shadow-sm hover:shadow-md transition">
                    <div class="aspect-video overflow-hidden bg-slate-200">
                        <img src="<?= $gambar ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="Berita">
                    </div>
                    
                    <div class="p-6">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                            <?= date('d M Y', strtotime($row['tanggal'])) ?>
                        </span>
                        
                        <h4 class="font-bold text-sm text-imipas-blue group-hover:text-imipas-gold transition mt-2 mb-3 line-clamp-2 uppercase">
                            <?= htmlspecialchars($row['judul']); ?>
                        </h4>

                        <p class="text-slate-500 text-[11px] leading-relaxed mb-4 line-clamp-2">
                            <?php 
                                $isi_database = isset($row['isi']) ? $row['isi'] : '';
                                echo substr(strip_tags($isi_database), 0, 100) . '...'; 
                            ?>
                        </p>

                        <a href="detail_berita.php?id=<?= $row['id_berita']; ?>" class="text-[10px] font-bold uppercase text-imipas-blue flex items-center gap-2">
                            Baca Selengkapnya 
                            <i class="fa-solid fa-arrow-right text-[8px]"></i>
                        </a>
                    </div>
                </article>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

        <?php if ($total_halaman > 1): ?>
            <nav class="mt-16 flex justify-center gap-2">
                <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                    <a href="?page=<?= $i ?>" class="w-10 h-10 flex items-center justify-center rounded-full text-[10px] font-bold transition-all <?= $i == $halaman ? 'bg-imipas-blue text-white shadow-lg' : 'bg-white text-slate-400 border border-slate-200 hover:border-imipas-gold' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </nav>
        <?php endif; ?>
    </section>

    <?php if(file_exists("layout/footer.php")) { include "layout/footer.php"; } ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
    </script>
</body>
</html>