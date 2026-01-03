<?php
// SET TIMEZONE AGAR SESUAI DENGAN WAKTU DATABASE (WIB)
date_default_timezone_set('Asia/Jakarta');

// 1. KEAMANAN: Header Proteksi
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");

include "config/koneksi.php";

/** * PERBAIKAN FATAL ERROR:
 * Karena index.php menggunakan gaya MySQLi ($conn->query), 
 * kita harus membuat variabel $conn menggunakan MySQLi 
 * agar sinkron dengan variabel $host, $user, $pass, dan $db dari koneksi.php
 */
$conn = new mysqli($host, $user, $pass, $db);

// Cek jika koneksi mysqli gagal
if ($conn->connect_error) {
    die("Koneksi MySQLi Gagal: " . $conn->connect_error);
}

function e($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// 2. QUERY DATA DINAMIS (Sekarang $conn sudah tidak null lagi)
$berita_query = $conn->query("SELECT id_berita, judul, gambar, tanggal FROM berita ORDER BY tanggal DESC LIMIT 6");
$profil_stmt = $conn->prepare("SELECT nama_kepala, foto_kepala, sambutan_kepala FROM profil_lapas WHERE id = ?");
$id_fixed = 1;
$profil_stmt->bind_param("i", $id_fixed);
$profil_stmt->execute();
$profil = $profil_stmt->get_result()->fetch_assoc();

$produk_query = $conn->query("SELECT id_produk, nama_produk, gambar, deskripsi FROM produk ORDER BY id_produk DESC LIMIT 4");

$sdp_query = $conn->query("SELECT * FROM data_warga_binaan ORDER BY tanggal_update DESC LIMIT 1");
$sdp = $sdp_query->fetch_assoc();

$survey_query = $conn->query("SELECT * FROM survey_kepuasan WHERE is_active = 1 LIMIT 1");
$survey = $survey_query->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Lapas Kelas IIB Lamongan - Situs Resmi</title>
    <link rel="icon" type="image/png" href="assets/images/logo_imigrasi.png">

    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300;400;600;700&display=swap"
        rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

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
                    fontFamily: { sans: ['"Titillium Web"', 'sans-serif'] }
                }
            }
        }
    </script>

    <style>
        .hero-overlay {
            background: linear-gradient(to bottom, rgba(7, 33, 61, 0.98), rgba(7, 33, 61, 0.80));
        }

        body {
            overflow-x: hidden;
            width: 100%;
        }

        .aspect-square-img {
            aspect-ratio: 1 / 1;
            object-fit: cover;
        }

        [data-aos] {
            pointer-events: none;
        }

        [data-aos].aos-animate {
            pointer-events: auto;
        }
    </style>
</head>

<body class="bg-white text-slate-900 antialiased font-sans">

    <div class="bg-imipas-platinum border-b border-slate-200 py-2 px-4 hidden lg:block">
        <div
            class="max-w-7xl mx-auto flex justify-between text-[10px] font-bold uppercase tracking-widest text-imipas-blue">
            <span>Republik Indonesia</span>
            <span id="currentDate"></span>
        </div>
    </div>

    <?php include "layout/navbar.php"; ?>

    <section
        class="relative min-h-[80vh] md:min-h-[85vh] flex items-center justify-center py-20 px-4 bg-[url('assets/images/hero.jpg')] bg-cover bg-center text-center">
        <div class="absolute inset-0 hero-overlay"></div>
        <div class="relative z-10 max-w-4xl mx-auto" data-aos="fade-up" data-aos-duration="1200">
            <span
                class="bg-imipas-gold text-imipas-blue text-[9px] md:text-[11px] px-3 py-1 font-bold uppercase tracking-widest mb-6 inline-block">Portal
                Informasi Resmi</span>
            <h1 class="text-3xl md:text-7xl font-bold text-white mb-6 leading-tight uppercase px-2">
                Transformasi Pemasyarakatan <br> <span class="text-imipas-gold">Berintegritas</span>
            </h1>
            <div class="h-1 w-16 bg-imipas-gold mx-auto mb-8"></div>
            <p class="text-imipas-platinum text-xs md:text-lg mb-10 leading-relaxed max-w-2xl mx-auto font-light px-4">
                Mewujudkan sistem pemasyarakatan yang transparan, humanis, dan akuntabel di Lembaga Pemasyarakatan Kelas
                IIB Lamongan.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center px-4">
                <a href="kunjungan.php"
                    class="bg-white text-imipas-blue px-8 py-4 text-[11px] font-bold uppercase transition hover:bg-imipas-platinum border-b-4 border-imipas-gold w-full sm:w-64">Daftar
                    Kunjungan</a>
                <a href="pengaduan.php"
                    class="bg-imipas-gold text-imipas-blue px-8 py-4 text-[11px] font-bold uppercase transition hover:opacity-90 border-b-4 border-[#C9A052] w-full sm:w-64">Portal
                    Pengaduan</a>
            </div>
        </div>
    </section>

    <section id="stats-section" class="max-w-7xl mx-auto px-4 -mt-10 md:-mt-16 relative z-30" data-aos="fade-up"
        data-aos-delay="200">
        <div
            class="grid grid-cols-2 md:grid-cols-5 shadow-2xl rounded-sm overflow-hidden border border-slate-200 md:border-none bg-white">
            <div
                class="bg-imipas-blue p-5 md:p-6 text-center border-b md:border-b-0 md:border-r border-white/10 col-span-2 md:col-span-1">
                <div class="text-3xl font-bold text-imipas-gold mb-1 counter"
                    data-target="<?= (int) ($sdp['total_penghuni'] ?? 0) ?>">0</div>
                <div class="text-[9px] text-white uppercase font-bold tracking-widest">Total Penghuni</div>
            </div>
            <div class="bg-white p-5 md:p-6 text-center border-r border-b border-slate-100">
                <div class="text-xl md:text-2xl font-bold text-imipas-blue mb-1 counter"
                    data-target="<?= (int) ($sdp['tahanan'] ?? 0) ?>">0</div>
                <div class="text-[9px] text-slate-500 uppercase font-bold">Tahanan</div>
            </div>
            <div class="bg-white p-5 md:p-6 text-center border-b border-slate-100 md:border-r">
                <div class="text-xl md:text-2xl font-bold text-imipas-blue mb-1 counter"
                    data-target="<?= (int) ($sdp['narapidana'] ?? 0) ?>">0</div>
                <div class="text-[9px] text-slate-500 uppercase font-bold">Narapidana</div>
            </div>
            <div class="bg-white p-5 md:p-6 text-center border-r border-slate-100">
                <div class="text-xl md:text-2xl font-bold text-orange-600 mb-1 counter"
                    data-target="<?= (int) ($sdp['sidang'] ?? 0) ?>">0</div>
                <div class="text-[9px] text-slate-500 uppercase font-bold">Sidang</div>
            </div>
            <div class="bg-white p-5 md:p-6 text-center">
                <div class="text-xl md:text-2xl font-bold text-red-600 mb-1 counter"
                    data-target="<?= (int) ($sdp['berobat_luar'] ?? 0) ?>">0</div>
                <div class="text-[9px] text-slate-500 uppercase font-bold">Berobat</div>
            </div>
        </div>
        <p class="text-[9px] text-slate-400 mt-4 text-center uppercase tracking-widest italic">
            Update Terakhir:
            <?= !empty($sdp['tanggal_update']) ? date('d M Y | H:i', strtotime($sdp['tanggal_update'])) : '-' ?> WIB
        </p>
    </section>

    <section class="max-w-7xl mx-auto px-4 mt-12 md:mt-20 relative z-20" data-aos="fade-up">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-0 shadow-lg bg-white border-t-4 border-imipas-gold">
            <a href="kunjungan.php"
                class="p-10 flex flex-col items-center text-center border-b md:border-r border-slate-100 hover:bg-slate-50 transition group">
                <div
                    class="w-14 h-14 flex items-center justify-center bg-slate-100 text-imipas-blue mb-5 rounded-full group-hover:bg-imipas-blue group-hover:text-white transition-all">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <h4 class="font-bold text-imipas-blue uppercase text-xs mb-2 tracking-widest">Pendaftaran Kunjungan</h4>
                <p class="text-slate-500 text-[11px] leading-relaxed">Pendaftaran online kunjungan tatap muka & titipan
                    barang.</p>
            </a>
            <a href="integritas.php"
                class="p-10 flex flex-col items-center text-center border-b md:border-r border-slate-100 hover:bg-slate-50 transition group">
                <div
                    class="w-14 h-14 flex items-center justify-center bg-slate-100 text-imipas-blue mb-5 rounded-full group-hover:bg-imipas-blue group-hover:text-white transition-all">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <h4 class="font-bold text-imipas-blue uppercase text-xs mb-2 tracking-widest">Hak Integrasi</h4>
                <p class="text-slate-500 text-[11px] leading-relaxed">Pantau progres usulan PB, CB, CMB & Asimilasi.</p>
            </a>
            <a href="pengaduan.php"
                class="p-10 flex flex-col items-center text-center border-b md:border-none hover:bg-slate-50 transition group">
                <div
                    class="w-14 h-14 flex items-center justify-center bg-slate-100 text-imipas-blue mb-5 rounded-full group-hover:bg-imipas-blue group-hover:text-white transition-all">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <h4 class="font-bold text-imipas-blue uppercase text-xs mb-2 tracking-widest">Kontak & Pengaduan</h4>
                <p class="text-slate-500 text-[11px] leading-relaxed">Layanan pengaduan masyarakat atas layanan publik.
                </p>
            </a>
        </div>
    </section>

    <section class="py-24 px-4 bg-white overflow-hidden" data-aos="fade-right">
        <div class="max-w-6xl mx-auto flex flex-col lg:flex-row items-center justify-center gap-12 lg:gap-20">
            <div class="relative w-full max-w-[300px] md:max-w-[320px] flex-shrink-0">
                <div class="absolute -bottom-4 -right-4 w-full h-full bg-imipas-gold/20 -z-10"></div>
                <img src="assets/images/<?= e($profil['foto_kepala']) ?>"
                    class="w-full h-auto shadow-xl border-b-8 border-imipas-blue object-cover" alt="Kepala Lapas">
            </div>
            <div class="text-center lg:text-left">
                <span class="text-imipas-gold font-bold text-[11px] uppercase tracking-widest mb-3 block">Sambutan
                    Pimpinan</span>
                <h3 class="text-2xl md:text-3xl font-bold text-imipas-blue mb-6 uppercase leading-tight">
                    <?= e($profil['nama_kepala']) ?></h3>
                <p class="text-slate-600 italic text-sm md:text-base leading-relaxed mb-8 font-light max-w-xl">
                    "<?= e($profil['sambutan_kepala']) ?>"</p>
                <a href="profile.php"
                    class="inline-block bg-imipas-blue text-white px-10 py-3.5 text-[10px] font-bold uppercase hover:bg-imipas-gold hover:text-imipas-blue transition">Lihat
                    Profil Lengkap</a>
            </div>
        </div>
    </section>

    <section id="survey-section" class="py-20 bg-imipas-blue text-white relative overflow-hidden" data-aos="zoom-in">
        <div class="absolute top-0 right-0 opacity-10 -translate-y-1/2 translate-x-1/4">
            <svg class="w-96 h-96 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
            </svg>
        </div>
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-12">
                <div class="text-center lg:text-left max-w-md">
                    <h3 class="text-2xl font-bold uppercase mb-6 text-imipas-gold tracking-wide">Survey Kepuasan
                        Masyarakat</h3>

                    <div class="flex items-center justify-center lg:justify-start gap-3 mb-2">
                        <span class="text-[10px] font-bold text-white/50 w-20 uppercase">Indeks IKM</span>
                        <div class="flex gap-1">
                            <?php
                            $skor_ikm = (float) ($survey['skor_ikm'] ?? 0);
                            $star_ikm = ($skor_ikm <= 5) ? ceil($skor_ikm) : ceil($skor_ikm / 100 * 5);
                            for ($i = 1; $i <= 5; $i++): ?>
                                <svg class="w-4 h-4 <?= ($i <= $star_ikm) ? 'text-imipas-gold' : 'text-white/10' ?>"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="flex items-center justify-center lg:justify-start gap-3 mb-4">
                        <span class="text-[10px] font-bold text-white/50 w-20 uppercase">Indeks IPK</span>
                        <div class="flex gap-1">
                            <?php
                            $skor_ipk = (float) ($survey['skor_ipk'] ?? 0);
                            $star_ipk = ($skor_ipk <= 5) ? ceil($skor_ipk) : ceil($skor_ipk / 100 * 5);
                            for ($i = 1; $i <= 5; $i++): ?>
                                <svg class="w-4 h-4 <?= ($i <= $star_ipk) ? 'text-imipas-gold' : 'text-white/10' ?>"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <p class="text-slate-300 text-sm font-light leading-relaxed">Nilai persepsi masyarakat Periode
                        <?= e($survey['bulan'] ?? '-') ?>.</p>
                </div>
                <div class="flex gap-8 md:gap-16">
                    <div class="text-center group">
                        <div class="w-24 h-24 md:w-28 md:h-28 rounded-full border-4 border-imipas-gold flex items-center justify-center text-3xl font-bold mb-3 group-hover:bg-imipas-gold group-hover:text-imipas-blue transition-all duration-500 shadow-[0_0_20px_rgba(238,191,99,0.3)] counter"
                            data-target="<?= $skor_ikm ?>">0</div>
                        <p class="text-[10px] uppercase font-bold tracking-widest text-imipas-gold">Indeks IKM</p>
                    </div>
                    <div class="text-center group">
                        <div class="w-24 h-24 md:w-28 md:h-28 rounded-full border-4 border-white/20 flex items-center justify-center text-3xl font-bold mb-3 group-hover:border-white transition-all duration-500 counter"
                            data-target="<?= $skor_ipk ?>">0</div>
                        <p class="text-[10px] uppercase font-bold tracking-widest text-slate-400">Indeks IPK</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 px-4 bg-slate-50" data-aos="fade-up">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-12">
                <h3 class="text-2xl font-bold uppercase text-imipas-blue">Berita <span
                        class="text-imipas-gold">Terkini</span></h3>
                <a href="berita.php"
                    class="text-[10px] font-bold text-imipas-blue uppercase border-b-2 border-imipas-gold pb-1 hover:text-imipas-gold transition">Lihat
                    Semua</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while ($row = $berita_query->fetch_assoc()): ?>
                    <article
                        class="bg-white flex flex-col border border-slate-100 shadow-sm hover:shadow-md transition group">
                        <div class="aspect-video overflow-hidden">
                            <img src="uploads/<?= e($row['gambar']); ?>"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                alt="<?= e($row['judul']); ?>">
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <span
                                class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3"><?= date('d M Y | H:i', strtotime($row['tanggal'])) ?></span>
                            <h4
                                class="font-bold text-sm text-imipas-blue mb-4 line-clamp-2 uppercase leading-snug group-hover:text-imipas-gold transition-colors">
                                <?= e($row['judul']); ?></h4>
                            <div class="mt-auto">
                                <a href="detail_berita.php?id=<?= (int) $row['id_berita']; ?>"
                                    class="text-[10px] font-bold uppercase text-imipas-blue flex items-center gap-2 hover:gap-3 transition-all">Baca
                                    Selengkapnya <span>→</span></a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <section class="py-24 px-4 bg-imipas-blue text-white" data-aos="fade-up">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                <div>
                    <h3 class="text-2xl font-bold uppercase text-center md:text-left">Karya <span
                            class="text-imipas-gold">Warga Binaan</span></h3>
                    <div class="h-1 w-12 bg-imipas-gold mt-2 hidden md:block"></div>
                </div>
                <p class="text-[10px] text-slate-400 uppercase tracking-widest text-center md:text-right italic">Pilih
                    produk untuk informasi lengkap</p>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                <?php while ($p = mysqli_fetch_assoc($produk_query)): ?>
                    <a href="detail_produk.php?id=<?= (int) $p['id_produk'] ?>"
                        class="group flex flex-col items-center text-center">
                        <div
                            class="relative w-full aspect-square overflow-hidden mb-5 border border-white/10 p-1 bg-white/5 rounded-sm">
                            <img src="uploads/<?= e($p['gambar']) ?>"
                                class="w-full h-full object-cover shadow-lg grayscale group-hover:grayscale-0 group-hover:scale-110 transition duration-700"
                                alt="<?= e($p['nama_produk']) ?>">
                            <div
                                class="absolute inset-0 bg-imipas-blue/80 opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-center items-center p-4">
                                <div class="translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                    <svg class="w-8 h-8 text-imipas-gold mb-2 mx-auto" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span class="text-[10px] font-bold uppercase tracking-tighter text-white">Lihat Detail
                                        Produk</span>
                                </div>
                            </div>
                        </div>
                        <div class="px-2">
                            <h5 class="font-bold text-[11px] uppercase text-imipas-gold tracking-wide mb-1">
                                <?= e($p['nama_produk']) ?></h5>
                            <p class="text-[9px] text-slate-400 line-clamp-1 italic"><?= e($p['deskripsi']) ?></p>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <?php include "layout/lokasi.php"; ?>
    <?php include "layout/footer.php"; ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });

        // LOGIKA COUNTER UNTUK ANGKA BULAT & DESIMAL
        const startCounting = () => {
            document.querySelectorAll('.counter').forEach(counter => {
                const target = parseFloat(counter.getAttribute('data-target'));
                const isDecimal = !Number.isInteger(target);
                const speed = 50;
                let current = 0;

                const update = () => {
                    const increment = target / speed;
                    if (current < target) {
                        current += increment;
                        counter.innerText = isDecimal ? current.toFixed(2) : Math.ceil(current);
                        setTimeout(update, 20);
                    } else {
                        counter.innerText = isDecimal ? target.toFixed(2) : target;
                    }
                };
                update();
            });
        };

        // INTERSECTION OBSERVER UNTUK TRIGGER ANIMASI SAAT SCROLL
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    startCounting();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });

        if (document.getElementById('stats-section')) observer.observe(document.getElementById('stats-section'));
        if (document.getElementById('survey-section')) observer.observe(document.getElementById('survey-section'));

        // TANGGAL OTOMATIS DI NAVBAR/TOPBAR
        const dateElement = document.getElementById('currentDate');
        if (dateElement) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateElement.innerText = new Date().toLocaleDateString('id-ID', options);
        }
    </script>
</body>

</html>