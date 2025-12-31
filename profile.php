<?php
// 1. KEAMANAN: Header Proteksi
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");

include "config/koneksi.php"; 

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

/**
 * 2. PERBAIKAN KEAMANAN: Menggunakan Prepared Statements 
 * Untuk mencegah SQL Injection meskipun hanya query SELECT sederhana.
 */
$stmt = $conn->prepare("SELECT * FROM profil_lapas LIMIT 1");
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $profil = $result->fetch_assoc();
} else {
    // Default data jika database kosong
    $profil = [
        'nama_kepala' => 'Nama Kepala Belum Diisi',
        'jabatan_kepala' => 'Kepala Lapas Kelas IIB Lamongan',
        'foto_kepala' => 'default.jpg',
        'sambutan_kepala' => 'Selamat datang di website resmi Lapas Kelas IIB Lamongan.',
        'sejarah' => 'Sejarah belum tersedia.',
        'visi' => 'Terwujudnya kepastian hukum yang berkeadilan.',
        'misi' => 'Membangun insan pemasyarakatan yang berintegritas.'
    ];
}

/**
 * 3. FUNGSI HELPER: Escaping Output (Mencegah XSS)
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Profil - Lapas Kelas IIB Lamongan</title>
    <link rel="icon" type="image/png" href="assets/images/logo_imigrasi.png">
    
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
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
        body { font-family: 'Titillium Web', sans-serif; overflow-x: hidden; width: 100%; }
        .hero-gradient { background: linear-gradient(135deg, #07213D 0%, #0a2d52 100%); }
        /* Perbaikan AOS: Pastikan elemen yang punya data-aos tidak mengganggu layout sebelum load */
        [data-aos] { pointer-events: none; }
        [data-aos].aos-animate { pointer-events: auto; }
        
        .prose p { margin-bottom: 1.5rem; text-align: justify; line-height: 1.8; }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased">

    <div class="bg-imipas-platinum border-b border-slate-200 py-2 px-4 hidden md:block">
        <div class="max-w-7xl mx-auto flex justify-between text-[10px] font-bold uppercase tracking-widest text-imipas-blue">
            <span>Republik Indonesia</span>
            <span id="currentDate"></span>
        </div>
    </div>

    <?php if(file_exists("layout/navbar.php")) include "layout/navbar.php"; ?>

    <section class="pt-24 pb-20 hero-gradient text-white px-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-imipas-gold opacity-5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="max-w-7xl mx-auto relative z-10" data-aos="fade-up">
            <div class="flex items-center gap-2 mb-4">
                <div class="h-1 w-8 bg-imipas-gold"></div>
                <span class="text-imipas-gold text-[10px] font-bold uppercase tracking-[0.3em]">Profil Instansi</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-bold uppercase tracking-tight leading-tight mb-4">Mengenal Lapas <br><span class="text-imipas-gold">Kelas IIB Lamongan</span></h1>
            <p class="text-imipas-platinum/70 text-sm md:text-base max-w-2xl leading-relaxed">Institusi pemasyarakatan yang menjunjung tinggi nilai integritas, transparansi, dan pemenuhan hak asasi manusia.</p>
        </div>
    </section>

    <section class="py-16 px-4 max-w-7xl mx-auto -mt-10 relative z-20">
        <div class="max-w-4xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            <div class="bg-white border border-slate-200 overflow-hidden shadow-2xl rounded-xl">
                <div class="grid md:grid-cols-2 items-stretch">
                    <div class="aspect-[3/4] md:aspect-auto overflow-hidden bg-slate-100 relative">
                        <?php 
                            $foto_name = !empty($profil['foto_kepala']) ? $profil['foto_kepala'] : "default.jpg";
                            $foto_path = "assets/images/" . $foto_name;
                            // Cek fisik file
                            if (!file_exists($foto_path)) $foto_path = "assets/images/default.jpg";
                        ?>
                        <img src="<?= $foto_path ?>" class="w-full h-full object-cover transition duration-700 hover:scale-105" alt="Kepala Lapas">
                    </div>
                    <div class="p-8 md:p-12 flex flex-col justify-center">
                        <div class="mb-6">
                            <h4 class="text-imipas-blue font-bold uppercase text-2xl tracking-tighter leading-none"><?= e($profil['nama_kepala']) ?></h4>
                            <p class="text-imipas-gold text-xs font-bold uppercase tracking-[0.2em] mt-2"><?= e($profil['jabatan_kepala']) ?></p>
                        </div>
                        <div class="relative">
                            <svg class="absolute -top-4 -left-4 w-8 h-8 text-slate-100 z-0" fill="currentColor" viewBox="0 0 32 32"><path d="M10 8v8H6v-8h4zm12 0v8h-4v-8h4z"/></svg>
                            <p class="text-slate-600 text-sm md:text-base italic leading-relaxed relative z-10">
                                "<?= e($profil['sambutan_kepala']) ?>"
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 px-4 max-w-7xl mx-auto overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
            <div class="lg:col-span-5 order-2 lg:order-1" data-aos="fade-right">
                <div class="relative">
                    <div class="absolute -top-4 -left-4 w-full h-full border-4 border-imipas-gold/20 rounded-lg -z-10"></div>
                    <img src="assets/images/hero.jpg" class="w-full h-[450px] object-cover shadow-2xl rounded-lg" alt="Gedung Lapas">
                </div>
            </div>

            <div class="lg:col-span-7 order-1 lg:order-2" data-aos="fade-left">
                <div class="flex items-center gap-2 mb-6">
                    <span class="w-10 h-[2px] bg-imipas-gold"></span>
                    <span class="text-imipas-blue text-xs font-bold uppercase tracking-widest">Tentang Kami</span>
                </div>
                <h3 class="text-3xl md:text-5xl font-bold text-imipas-blue mb-8 uppercase tracking-tighter">Dedikasi Untuk <span class="text-imipas-gold">Negara</span></h3>
                <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed text-base">
                    <?= nl2br(e($profil['sejarah'])) ?>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 px-4 bg-white border-t border-slate-100 overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-imipas-blue text-4xl font-bold uppercase tracking-tighter">Visi & Misi</h2>
                <div class="w-20 h-1.5 bg-imipas-gold mx-auto mt-4"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="group p-10 border border-slate-100 bg-slate-50 hover:bg-imipas-blue transition-all duration-500 rounded-xl shadow-sm" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-12 h-12 bg-imipas-gold flex items-center justify-center rounded-lg mb-6 shadow-lg">
                        <svg class="w-6 h-6 text-imipas-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="2"/></svg>
                    </div>
                    <h5 class="text-imipas-blue group-hover:text-imipas-gold font-bold uppercase tracking-widest mb-4 text-xl">Visi</h5>
                    <p class="text-slate-600 group-hover:text-slate-200 italic leading-relaxed text-lg">"<?= e($profil['visi']) ?>"</p>
                </div>

                <div class="group p-10 border border-slate-100 bg-slate-50 hover:bg-imipas-blue transition-all duration-500 rounded-xl shadow-sm" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-12 h-12 bg-imipas-gold flex items-center justify-center rounded-lg mb-6 shadow-lg">
                        <svg class="w-6 h-6 text-imipas-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-width="2"/></svg>
                    </div>
                    <h5 class="text-imipas-blue group-hover:text-imipas-gold font-bold uppercase tracking-widest mb-4 text-xl">Misi</h5>
                    <div class="text-base text-slate-600 group-hover:text-slate-200 leading-relaxed">
                        <?= nl2br(e($profil['misi'])) ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php 
        if(file_exists("layout/lokasi.php")) include "layout/lokasi.php";
        if(file_exists("layout/footer.php")) include "layout/footer.php"; 
    ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // 1. Inisialisasi AOS dengan Offset agar tidak telat muncul
        AOS.init({ 
            duration: 1000, 
            once: true,
            offset: 100,
            easing: 'ease-out-back'
        });

        // 2. Refresh AOS setelah semua gambar dimuat (Penting untuk mengatasi elemen tidak muncul)
        window.addEventListener('load', function() {
            AOS.refresh();
        });

        // 3. Inisialisasi Tanggal
        const dateElement = document.getElementById('currentDate');
        if(dateElement) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateElement.innerText = new Date().toLocaleDateString('id-ID', options);
        }
    </script>
</body>
</html>