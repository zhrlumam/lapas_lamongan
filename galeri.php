<?php
include "config/koneksi.php";
function e($string) { return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Kegiatan - Lapas Lamongan</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { imipas: { blue: '#07213D', gold: '#EEBF63', platinum: '#E0E2E3' } },
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] }
                }
            }
        }
    </script>
</head>
<body class="bg-white text-slate-900 antialiased font-sans">
    
    <div class="bg-imipas-platinum border-b border-slate-200 py-2 px-4 block">
        <div class="max-w-7xl mx-auto flex justify-between text-[10px] font-bold uppercase tracking-widest text-imipas-blue">
            <span>Republik Indonesia</span>
            <span><?= date('l, d F Y') ?></span>
        </div>
    </div>

    <?php include "layout/navbar.php"; ?>

    <section class="relative py-20 bg-imipas-blue overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('assets/images/pattern.png')] bg-repeat"></div>
        <div class="max-w-7xl mx-auto px-4 relative z-10 text-center">
            <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 uppercase tracking-tight" data-aos="fade-up">Galeri <br><span class="text-imipas-gold italic">Kegiatan Pembinaan</span></h1>
            <p class="text-imipas-platinum/80 text-sm md:text-lg max-w-2xl mx-auto font-light" data-aos="fade-up" data-aos-delay="100">
                Dokumentasi program rehabilitasi, pemberdayaan kemandirian, dan pembinaan kepribadian di Lapas Kelas IIB Lamongan.
            </p>
        </div>
    </section>

    <section class="py-12 md:py-16 px-4 md:px-8 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            
            <!-- Item 1: Workshop -->
            <div class="group relative overflow-hidden rounded-[2rem] shadow-lg bg-white" data-aos="fade-up">
                <div class="aspect-square overflow-hidden">
                    <img src="assets/images/galeri/workshop.png" 
                         alt="Pembinaan Kemandirian" 
                         class="w-full h-full object-cover transition duration-700 md:group-hover:scale-110">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-imipas-blue via-transparent to-transparent opacity-80 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="absolute bottom-0 left-0 p-6 md:p-8 transform translate-y-0 md:translate-y-4 md:group-hover:translate-y-0 transition-transform duration-500">
                    <span class="text-imipas-gold text-[9px] md:text-[10px] font-bold uppercase tracking-widest mb-1 md:mb-2 block">Kemandirian</span>
                    <h3 class="text-white font-bold text-base md:text-lg leading-tight uppercase">Workshop Kerajinan <br class="hidden md:block"> Kayu & Batik</h3>
                </div>
            </div>

            <!-- Item 2: Religious -->
            <div class="group relative overflow-hidden rounded-[2rem] shadow-lg bg-white" data-aos="fade-up" data-aos-delay="100">
                <div class="aspect-square overflow-hidden">
                    <img src="assets/images/galeri/worship.png" 
                         alt="Pembinaan Kepribadian" 
                         class="w-full h-full object-cover transition duration-700 md:group-hover:scale-110">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-imipas-blue via-transparent to-transparent opacity-80 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="absolute bottom-0 left-0 p-6 md:p-8 transform translate-y-0 md:translate-y-4 md:group-hover:translate-y-0 transition-transform duration-500">
                    <span class="text-imipas-gold text-[9px] md:text-[10px] font-bold uppercase tracking-widest mb-1 md:mb-2 block">Kepribadian</span>
                    <h3 class="text-white font-bold text-base md:text-lg leading-tight uppercase">Kegiatan Kerohanian <br class="hidden md:block"> & Tadarus Quran</h3>
                </div>
            </div>

            <!-- Item 3: Sports -->
            <div class="group relative overflow-hidden rounded-[2rem] shadow-lg bg-white" data-aos="fade-up" data-aos-delay="200">
                <div class="aspect-square overflow-hidden">
                    <img src="assets/images/galeri/sports.png" 
                         alt="Pembinaan Jasmani" 
                         class="w-full h-full object-cover transition duration-700 md:group-hover:scale-110">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-imipas-blue via-transparent to-transparent opacity-80 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="absolute bottom-0 left-0 p-6 md:p-8 transform translate-y-0 md:translate-y-4 md:group-hover:translate-y-0 transition-transform duration-500">
                    <span class="text-imipas-gold text-[9px] md:text-[10px] font-bold uppercase tracking-widest mb-1 md:mb-2 block">Kesehatan</span>
                    <h3 class="text-white font-bold text-base md:text-lg leading-tight uppercase">Olahraga Bersama <br class="hidden md:block"> & Senam Pagi</h3>
                </div>
            </div>

        </div>

        <!-- CALL TO ACTION -->
        <div class="mt-20 text-center" data-aos="fade-up">
            <p class="text-slate-400 text-[11px] uppercase tracking-widest mb-6 italic">Lihat aktivitas lebih lengkap di Media Sosial kami</p>
            <div class="flex justify-center gap-4">
                <a href="#" class="w-12 h-12 bg-slate-100 text-imipas-blue rounded-2xl flex items-center justify-center hover:bg-imipas-blue hover:text-white transition shadow-sm">
                    <i data-lucide="instagram" class="w-5 h-5"></i>
                </a>
                <a href="#" class="w-12 h-12 bg-slate-100 text-imipas-blue rounded-2xl flex items-center justify-center hover:bg-imipas-blue hover:text-white transition shadow-sm">
                    <i data-lucide="facebook" class="w-5 h-5"></i>
                </a>
                <a href="#" class="w-12 h-12 bg-slate-100 text-imipas-blue rounded-2xl flex items-center justify-center hover:bg-imipas-blue hover:text-white transition shadow-sm">
                    <i data-lucide="youtube" class="w-5 h-5"></i>
                </a>
            </div>
        </div>
    </section>

    <?php include "layout/footer.php"; ?>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        lucide.createIcons();
        AOS.init({ duration: 800, once: true });
    </script>
</body>
</html>
