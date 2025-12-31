<?php
// 1. KEAMANAN: Header Proteksi
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");

include "config/koneksi.php"; 

if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}

// 2. FUNGSI HELPER KEAMANAN (Mencegah XSS)
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// 3. AMBIL DATA INFORMASI 
$query_info = mysqli_query($conn, "SELECT judul_info, link_tujuan FROM informasi ORDER BY id_info DESC LIMIT 3");

// 4. KONFIGURASI PAGINATION
$limit = 6; 
$halaman = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) ? (int)$_GET['page'] : 1;
$offset = ($halaman - 1) * $limit;

$total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM berita");
$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_halaman = ceil($total_data / $limit);

$stmt = $conn->prepare("SELECT id_berita, judul, gambar, tanggal, isi FROM berita ORDER BY tanggal DESC LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$berita = $stmt->get_result();
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
        body { overflow-x: hidden; width: 100%; }
        [data-aos] { pointer-events: none; }
        [data-aos].aos-animate { pointer-events: auto; }
        .img-placeholder { background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: loading 1.5s infinite; }
        @keyframes loading { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased">
    
   <div class="bg-imipas-platinum border-b border-slate-200 py-2 px-4 hidden md:block">
    <div class="max-w-7xl mx-auto flex justify-between text-[10px] font-bold uppercase tracking-widest text-imipas-blue">
        <span>Republik Indonesia</span>
        <span id="currentDate"></span> </div>
</div>

    <?php if(file_exists("layout/navbar.php")) { include "layout/navbar.php"; } ?>

    <section class="pt-40 pb-24 bg-imipas-blue text-white px-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-imipas-gold/10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="max-w-7xl mx-auto relative z-10" data-aos="fade-right">
            <div class="flex items-center gap-2 mb-4">
                <div class="h-1 w-8 bg-imipas-gold"></div>
                <span class="text-imipas-gold text-[10px] font-bold uppercase tracking-[0.3em]">Pusat Media</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-bold uppercase tracking-tight mb-4 leading-tight">Informasi & <br><span class="text-imipas-gold">Berita</span></h1>
            <p class="text-imipas-platinum/70 text-sm md:text-base max-w-2xl leading-relaxed">
                Akses informasi layanan publik dan dokumentasi kegiatan resmi Lapas Kelas IIB Lamongan dalam satu pintu.
            </p>
        </div>
    </section>

    <section class="relative z-20 px-4 -mt-10 mb-16">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php while($info = mysqli_fetch_assoc($query_info)): ?>
                <div class="bg-white p-6 shadow-xl border-t-4 border-imipas-gold group hover:bg-imipas-blue transition-all duration-300 rounded-sm" data-aos="zoom-in" data-aos-delay="100">
                    <div class="flex items-center gap-3 mb-3 text-imipas-gold group-hover:text-white transition-colors">
                        <i class="fa-solid fa-circle-info text-lg"></i>
                        <span class="text-[10px] font-bold uppercase tracking-[0.2em]">Informasi Penting</span>
                    </div>
                    <h3 class="font-bold text-imipas-blue group-hover:text-imipas-gold text-base mb-4 leading-tight uppercase line-clamp-2"><?= e($info['judul_info']) ?></h3>
                    <a href="<?= e($info['link_tujuan']) ?>" class="text-imipas-blue group-hover:text-white font-bold text-[10px] uppercase border-b-2 border-imipas-gold pb-1 transition-all inline-block">Selengkapnya</a>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

 <section class="py-12 px-4 max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-12" data-aos="fade-up">
        <h2 class="text-2xl font-bold uppercase text-imipas-blue">Berita <span class="text-imipas-gold">Terkini</span></h2>
        <div class="h-[1px] flex-grow ml-6 bg-slate-200 hidden md:block"></div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php if ($berita->num_rows > 0): ?>
            <?php while ($row = $berita->fetch_assoc()): 
                $gambarPath = "assets/images/" . $row['gambar'];
                $gambar = (!empty($row['gambar']) && file_exists($gambarPath)) ? $gambarPath : "assets/images/default-news.jpg";
                $linkBerita = "detail_berita.php?id=" . (int)$row['id_berita'];
            ?>
            <article class="group bg-white flex flex-col border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-500 rounded-sm overflow-hidden relative" data-aos="fade-up">
                
                <a href="<?= $linkBerita ?>" class="absolute inset-0 z-10" aria-label="Baca selengkapnya tentang <?= e($row['judul']) ?>"></a>

                <div class="aspect-video overflow-hidden bg-slate-200 relative">
                    <img src="<?= e($gambar) ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="Berita">
                </div>
                
                <div class="p-7 flex flex-col flex-grow">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fa-regular fa-calendar text-imipas-gold text-[10px]"></i>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                            <?= date('d M Y', strtotime($row['tanggal'])) ?>
                        </span>
                    </div>
                    
                    <h4 class="font-bold text-base text-imipas-blue group-hover:text-imipas-gold transition-colors duration-300 mb-4 line-clamp-2 uppercase leading-snug">
                        <?= e($row['judul']); ?>
                    </h4>

                    <p class="text-slate-500 text-[11px] leading-relaxed mb-6 line-clamp-2">
                        <?= e(substr(strip_tags($row['isi']), 0, 100)) . '...'; ?>
                    </p>

                    <div class="mt-auto pt-2 text-[10px] font-bold uppercase text-imipas-blue flex items-center gap-2 group-hover:text-imipas-gold transition-all">
                        Baca Selengkapnya 
                        <i class="fa-solid fa-arrow-right text-[8px] transform group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>
            </article>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-full py-20 text-center text-slate-400 italic">Belum ada berita yang diterbitkan.</div>
        <?php endif; ?>
    </div>
    
    ...
</section>

    <?php if(file_exists("layout/footer.php")) { include "layout/footer.php"; } ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ 
            duration: 800, 
            once: true,
            offset: 100
        });
        
        window.addEventListener('load', function() {
            AOS.refresh();
        });
    </script>
    <script>
    function updateDate() {
        const dateElement = document.getElementById('currentDate');
        if (dateElement) {
            const sekarang = new Date();
            const opsi = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            // Menggunakan locale Indonesia (id-ID)
            dateElement.innerText = sekarang.toLocaleDateString('id-ID', opsi);
        }
    }

    // Jalankan fungsi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', updateDate);
</script>
</body>
</html>