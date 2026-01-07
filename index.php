<?php
// SET TIMEZONE
date_default_timezone_set('Asia/Jakarta');

// 1. KEAMANAN
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: camera=(), microphone=(), geolocation=()");

include "config/koneksi.php";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi Database Gagal");
}

function e($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// 2. QUERY DATA
$berita_query = $conn->query("SELECT id_berita, judul, gambar, tanggal FROM berita ORDER BY tanggal DESC LIMIT 3");

$profil_stmt = $conn->prepare("SELECT nama_kepala, foto_kepala, sambutan_kepala FROM profil_lapas WHERE id = ?");
$id_fixed = 1;
$profil_stmt->bind_param("i", $id_fixed);
$profil_stmt->execute();
$profil = $profil_stmt->get_result()->fetch_assoc();

$produk_query = $conn->query("SELECT id_produk, nama_produk, gambar, deskripsi FROM produk ORDER BY id_produk DESC LIMIT 4");

$survey_query = $conn->query("SELECT * FROM survey_kepuasan WHERE is_active = 1 LIMIT 1");
$survey = $survey_query->fetch_assoc();

$hunian_query = $conn->query("SELECT * FROM data_warga_binaan ORDER BY tanggal_update DESC LIMIT 1");
$hunian = $hunian_query->fetch_assoc() ?: ['tahanan' => 0, 'narapidana' => 0, 'total_penghuni' => 0, 'sidang' => 0, 'berobat_luar' => 0, 'tanggal_update' => date('Y-m-d')];

// Query Visitor
$visitor_query = $conn->query("SELECT COUNT(DISTINCT ip_address) as total FROM visitor_logs");
$visitor_count = $visitor_query->fetch_assoc()['total'] ?? 0;
$today_visitor = mt_rand(5, 50); // Simulasi/Placeholder
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Lapas Kelas IIB Lamongan</title>
    <meta name="description" content="Situs Resmi Lembaga Pemasyarakatan Kelas IIB Lamongan.">
    <link rel="icon" type="image/png" href="assets/images/logo_imigrasi.png">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        imipas: {
                            blue: '#07213D',
                            gold: '#EEBF63',
                            platinum: '#E0E2E3',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        .hero-overlay {
            background: linear-gradient(to bottom, rgba(7, 33, 61, 0.9), rgba(7, 33, 61, 0.6));
        }
        .floating-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        /* Animasi Popup Visitor Responsive */
        #visitorPopup {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            transform-origin: bottom left;
        }
        .visitor-hidden {
            opacity: 0;
            transform: scale(0.9) translateY(10px);
            pointer-events: none;
            visibility: hidden;
        }
        .visitor-visible {
            opacity: 1;
            transform: scale(1) translateY(0);
            pointer-events: auto;
            visibility: visible;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased font-sans flex flex-col min-h-screen">

    <div class="bg-imipas-platinum border-b border-slate-200 py-2 px-4 block">
        <div class="max-w-7xl mx-auto flex justify-between text-[11px] font-bold uppercase tracking-widest text-imipas-blue">
            <span>Republik Indonesia</span>
            <span id="currentDate"></span>
        </div>
    </div>

    <?php include "layout/navbar.php"; ?>

    <section id="heroSection" class="relative min-h-[550px] flex flex-col items-center justify-center text-center px-4 pt-28 pb-32 bg-cover bg-center bg-no-repeat transition-all duration-1000">
        <div class="absolute inset-0 hero-overlay z-0"></div>

        <div class="relative z-10 max-w-4xl mx-auto w-full" data-aos="fade-up">
            <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4 uppercase tracking-tight drop-shadow-lg leading-snug">
                Lapas Kelas IIB Lamongan
            </h1>
            <p class="text-imipas-platinum text-sm md:text-lg mb-8 font-light max-w-2xl mx-auto drop-shadow-md leading-relaxed">
                Menyediakan layanan pemasyarakatan yang transparan, akuntabel, dan humanis untuk masyarakat.
            </p>

            <div class="bg-white/10 backdrop-blur-md p-2 rounded-full border border-white/20 shadow-2xl max-w-xl mx-auto flex items-center mb-6">
                <input type="text" placeholder="Cari layanan..." class="bg-transparent text-white placeholder-slate-300 w-full px-4 py-2 outline-none rounded-full text-sm md:text-base">
                <button class="bg-imipas-gold hover:bg-yellow-500 text-imipas-blue w-10 h-10 rounded-full flex items-center justify-center transition-all shadow-lg">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
    </section>

    <section class="px-4 -mt-24 relative z-20 mb-12 block">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 md:gap-4 shadow-xl bg-white rounded-2xl p-6 border border-slate-100">
                <a href="kunjungan.php" class="floating-card flex flex-col items-center justify-center p-4 rounded-xl hover:bg-slate-50 transition group cursor-pointer text-center">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-blue-600 group-hover:text-white transition">
                        <i data-lucide="calendar-check" class="w-6 h-6"></i>
                    </div>
                    <span class="text-sm font-bold text-slate-700">Kunjungan</span>
                </a>
                <a href="layanan.php" class="floating-card flex flex-col items-center justify-center p-4 rounded-xl hover:bg-slate-50 transition group cursor-pointer text-center">
                    <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-orange-600 group-hover:text-white transition">
                        <i data-lucide="file-text" class="w-6 h-6"></i>
                    </div>
                    <span class="text-sm font-bold text-slate-700">Integrasi PB/CB</span>
                </a>
                <a href="pengaduan.php" class="floating-card flex flex-col items-center justify-center p-4 rounded-xl hover:bg-slate-50 transition group cursor-pointer text-center">
                    <div class="w-12 h-12 bg-red-50 text-red-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-red-600 group-hover:text-white transition">
                        <i data-lucide="message-square-warning" class="w-6 h-6"></i>
                    </div>
                    <span class="text-sm font-bold text-slate-700">Pengaduan</span>
                </a>
                <a href="produk.php" class="floating-card flex flex-col items-center justify-center p-4 rounded-xl hover:bg-slate-50 transition group cursor-pointer text-center">
                    <div class="w-12 h-12 bg-green-50 text-green-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-green-600 group-hover:text-white transition">
                        <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                    </div>
                    <span class="text-sm font-bold text-slate-700">Produk WBP</span>
                </a>
                <a href="profile.php" class="col-span-2 md:col-span-1 floating-card flex flex-col items-center justify-center p-4 rounded-xl hover:bg-slate-50 transition group cursor-pointer text-center">
                    <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center mb-3 group-hover:bg-purple-600 group-hover:text-white transition">
                        <i data-lucide="building-2" class="w-6 h-6"></i>
                    </div>
                    <span class="text-sm font-bold text-slate-700">Profil Instansi</span>
                </a>
            </div>
        </div>
    </section>

    <section class="py-8 bg-slate-50 border-b border-slate-200" data-aos="fade-up">
        <div class="max-w-5xl mx-auto px-4">
            <div class="text-center mb-6">
                <span class="text-imipas-gold font-bold uppercase text-[10px] tracking-widest">Transparansi Data</span>
                <h2 class="text-xl md:text-2xl font-bold text-imipas-blue mt-1">Data Penghuni Lapas</h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white border border-slate-200 p-4 rounded-xl text-center hover:shadow-md transition">
                    <div class="text-3xl font-extrabold text-imipas-blue mb-1"><?= $hunian['tahanan'] ?></div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wide">Tahanan</div>
                </div>

                <div class="bg-white border border-slate-200 p-4 rounded-xl text-center hover:shadow-md transition">
                    <div class="text-3xl font-extrabold text-imipas-blue mb-1"><?= $hunian['narapidana'] ?></div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wide">Narapidana</div>
                </div>

                <div class="bg-white border-2 border-imipas-gold/30 p-4 rounded-xl text-center hover:shadow-md transition relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-imipas-gold text-[8px] px-2 py-0.5 font-bold text-imipas-blue rounded-bl-lg">TOTAL</div>
                    <div class="text-3xl font-extrabold text-imipas-gold mb-1"><?= $hunian['total_penghuni'] ?></div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wide">Total WBP</div>
                </div>

                <div class="bg-green-50 border border-green-100 p-4 rounded-xl text-center hover:shadow-md transition">
                    <div class="text-3xl font-extrabold text-green-700 mb-1">344</div>
                    <div class="text-xs font-bold text-green-600 uppercase tracking-wide">Kapasitas</div>
                </div>
            </div>

            <div class="flex justify-center gap-4 mt-4">
                 <div class="text-[10px] bg-white px-3 py-1 rounded-full border border-slate-200 text-slate-500">
                    Update: <span class="font-bold text-imipas-blue"><?= date('d F Y', strtotime($hunian['tanggal_update'])) ?></span>
                </div>
                <div class="text-[10px] bg-white px-3 py-1 rounded-full border border-slate-200 text-slate-500">
                    Sidang: <span class="font-bold text-imipas-blue"><?= $hunian['sidang'] ?></span>
                </div>
                <div class="text-[10px] bg-white px-3 py-1 rounded-full border border-slate-200 text-slate-500">
                    Berobat: <span class="font-bold text-imipas-blue"><?= $hunian['berobat_luar'] ?></span>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 bg-white relative overflow-hidden hidden md:block">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="relative group" data-aos="fade-right">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl border-4 border-slate-100 aspect-video group-hover:scale-[1.01] transition duration-500">
                    <img src="assets/images/hero.jpg" class="w-full h-full object-cover">
                    <a href="https://www.youtube.com/" target="_blank" class="absolute inset-0 bg-black/30 flex items-center justify-center group-hover:bg-black/20 transition">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border border-white/50 text-white shadow-lg group-hover:scale-110 transition">
                            <i data-lucide="play" class="w-6 h-6 fill-current"></i>
                        </div>
                    </a>
                </div>
            </div>
            <div data-aos="fade-left">
                <span class="text-imipas-gold font-bold uppercase text-[10px] tracking-widest">Profil Instansi</span>
                <h2 class="text-3xl font-extrabold text-imipas-blue mt-2 mb-4">Transformasi Pemasyarakatan Semakin PASTI</h2>
                <p class="text-slate-600 leading-relaxed mb-6">
                    Lapas Kelas IIB Lamongan terus berbenah memberikan pelayanan terbaik berbasis teknologi informasi. Transparan, Akuntabel, dan Humanis.
                </p>
                <div class="flex gap-6">
                    <div>
                        <div class="text-2xl font-bold text-imipas-blue">WBK</div>
                        <div class="text-[10px] text-slate-500 uppercase">Menuju Wilayah<br>Bebas Korupsi</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-imipas-blue">24 Jam</div>
                        <div class="text-[10px] text-slate-500 uppercase">Layanan<br>Pengaduan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 px-4 bg-slate-50" data-aos="fade-up">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-imipas-blue">Berita Terkini</h2>
                </div>
                <a href="berita.php" class="text-sm font-bold text-imipas-blue hover:text-imipas-gold transition flex items-center gap-1">
                    Lihat Semua <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php while ($row = $berita_query->fetch_assoc()): ?>
                    <article class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                        <div class="aspect-[16/9] overflow-hidden">
                            <img src="uploads/<?= e($row['gambar']); ?>" class="w-full h-full object-cover hover:scale-105 transition duration-500" alt="Berita">
                        </div>
                        <div class="p-5">
                            <span class="text-[10px] font-bold text-slate-500 mb-2 block">
                                <?= date('d M Y', strtotime($row['tanggal'])) ?>
                            </span>
                            <h3 class="font-bold text-lg text-imipas-blue mb-3 line-clamp-2 leading-snug">
                                <?= e($row['judul']); ?>
                            </h3>
                            <a href="detail_berita.php?id=<?= (int) $row['id_berita']; ?>" class="text-xs font-bold text-imipas-gold hover:text-imipas-blue transition">
                                Baca Selengkapnya
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <section class="py-16 px-4 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            
            <div class="flex gap-6 items-start" data-aos="fade-right">
                <img src="assets/images/<?= e($profil['foto_kepala']) ?>" class="w-24 h-24 rounded-full object-cover border-4 border-slate-50 shadow-lg flex-shrink-0" alt="Kalapas">
                <div>
                    <span class="text-imipas-gold font-bold uppercase text-[10px] tracking-widest">Kepala Lapas</span>
                    <h3 class="text-xl font-bold text-imipas-blue"><?= e($profil['nama_kepala']) ?></h3>
                    <p class="text-slate-600 italic text-sm mt-3 leading-relaxed">
                        "<?= e($profil['sambutan_kepala']) ?>"
                    </p>
                </div>
            </div>

            <div class="bg-imipas-blue rounded-2xl p-8 text-white relative overflow-hidden" data-aos="fade-left">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                
                <h3 class="text-lg font-bold mb-6 border-b border-white/10 pb-4">Indeks Kepuasan Masyarakat</h3>
                <div class="flex justify-between items-center text-center">
                    <div>
                        <div class="text-4xl font-bold text-imipas-gold mb-1"><?= (float)($survey['skor_ikm'] ?? 0) ?></div>
                        <div class="text-[10px] uppercase font-bold tracking-widest opacity-70">Indeks IKM</div>
                    </div>
                    <div class="h-10 w-[1px] bg-white/20"></div>
                    <div>
                        <div class="text-4xl font-bold text-white mb-1"><?= (float)($survey['skor_ipk'] ?? 0) ?></div>
                        <div class="text-[10px] uppercase font-bold tracking-widest opacity-70">Indeks IPK</div>
                    </div>
                </div>
                <p class="text-center text-[10px] opacity-50 mt-6">Periode: <?= e($survey['bulan'] ?? '-') ?></p>
            </div>
        </div>
    </section>

    <section class="bg-slate-50 border-t border-slate-200 py-12 text-center" data-aos="fade-up">
        <div class="max-w-3xl mx-auto px-4">
            <i data-lucide="scale" class="w-10 h-10 text-imipas-gold mx-auto mb-4"></i>
            <h2 class="text-imipas-blue font-bold uppercase tracking-widest mb-4">Maklumat Pelayanan</h2>
            <p class="text-slate-700 font-medium italic mb-6 leading-relaxed">
                "Dengan ini kami menyatakan sanggup menyelenggarakan pelayanan sesuai dengan standar pelayanan yang telah ditetapkan dan apabila tidak menepati janji kami siap menerima sanksi sesuai perundang-undangan yang berlaku."
            </p>
            <div class="text-xs font-bold text-slate-400 uppercase">Kepala Lapas Kelas IIB Lamongan</div>
        </div>
    </section>

    <?php include "layout/footer.php"; ?>

    <div class="fixed bottom-4 left-4 md:bottom-6 md:left-6 z-50">
        
        <div id="visitorPopup" class="visitor-hidden absolute bottom-full left-0 mb-4 bg-white rounded-xl shadow-2xl border border-slate-100 p-4 w-64">
            <div class="flex justify-between items-center mb-3 border-b border-slate-100 pb-2">
                <h4 class="text-xs font-bold text-imipas-blue uppercase tracking-wide">Statistik Pengunjung</h4>
                <button onclick="toggleVisitor()" class="text-slate-300 hover:text-red-500 transition">
                    <i data-lucide="x" class="w-3 h-3"></i>
                </button>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-500">Total Kunjungan</span>
                    <span class="text-sm font-extrabold text-imipas-blue"><?= number_format($visitor_count, 0, ',', '.') ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-500">Hari Ini</span>
                    <span class="text-sm font-bold text-green-600">+<?= $today_visitor ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-500">Status</span>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-[10px] font-bold text-slate-700">Online</span>
                    </div>
                </div>
            </div>
            
            <div class="absolute -bottom-1 left-6 w-3 h-3 bg-white border-b border-r border-slate-100 transform rotate-45"></div>
        </div>

        <button onclick="toggleVisitor()" class="group bg-gradient-to-br from-imipas-gold to-yellow-600 w-12 h-12 md:w-14 md:h-14 rounded-full flex items-center justify-center shadow-lg hover:shadow-yellow-500/50 hover:scale-110 active:scale-95 transition-all duration-300 relative z-50">
            <i data-lucide="bar-chart-2" class="w-5 h-5 md:w-6 md:h-6 text-imipas-blue fill-imipas-blue/10"></i>
            <span class="absolute top-0 right-0 flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
            </span>
        </button>
    </div>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true, offset: 50 });
        lucide.createIcons();

        // LOGIKA POPUP
        function toggleVisitor() {
            const popup = document.getElementById('visitorPopup');
            const isHidden = popup.classList.contains('visitor-hidden');
            
            if (isHidden) {
                popup.classList.remove('visitor-hidden');
                popup.classList.add('visitor-visible');
            } else {
                popup.classList.remove('visitor-visible');
                popup.classList.add('visitor-hidden');
            }
        }

        const dateElement = document.getElementById('currentDate');
        if (dateElement) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateElement.innerText = new Date().toLocaleDateString('id-ID', options);
        }

        const heroSection = document.getElementById('heroSection');
        const heroImages = ['assets/images/hero.jpg', 'assets/images/hero_slide_2.png'];
        let currentSlide = 0;

        heroImages.forEach(src => { const i = new Image(); i.src = src; });

        setInterval(() => {
            currentSlide = (currentSlide + 1) % heroImages.length;
            heroSection.style.backgroundImage = `url('${heroImages[currentSlide]}')`;
        }, 5000);
        heroSection.style.backgroundImage = `url('${heroImages[0]}')`;

        console.log('Lapas Kelas IIB Lamongan - Official Website');
    </script>
</body>
</html>