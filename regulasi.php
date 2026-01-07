<?php
include "config/koneksi.php";
function e($string) { return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hak & Kewajiban WBP - Lapas Lamongan</title>
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
    <?php include "layout/navbar.php"; ?>

    <section class="relative py-20 bg-imipas-blue overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('assets/images/pattern.png')] bg-repeat"></div>
        <div class="max-w-7xl mx-auto px-4 relative z-10 text-center">
            <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 uppercase tracking-tight" data-aos="fade-up">Hak & Kewajiban <br><span class="text-imipas-gold italic">Warga Binaan</span></h1>
            <p class="text-imipas-platinum/80 text-sm md:text-lg max-w-2xl mx-auto font-light" data-aos="fade-up" data-aos-delay="100">
                Transparansi pemenuhan hak-hak dasar dan administratif bagi Warga Binaan Pemasyarakatan secara gratis tanpa pungutan liar.
            </p>
        </div>
    </section>

    <section class="py-10 md:py-16 px-4 md:px-8 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-12 items-start">
            
            <!-- HAK WBP -->
            <div class="space-y-6" data-aos="fade-up">
                <div class="flex items-center gap-4 mb-6 md:mb-8">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-emerald-100 text-emerald-600 flex items-center justify-center rounded-xl md:rounded-2xl shadow-sm">
                        <i data-lucide="shield-check" class="w-5 h-5 md:w-6 md:h-6"></i>
                    </div>
                    <h2 class="text-xl md:text-2xl font-bold text-imipas-blue uppercase tracking-tight">Hak Warga Binaan</h2>
                </div>

                <div class="p-5 md:p-6 bg-slate-50 border-l-4 border-emerald-500 rounded-r-2xl shadow-sm">
                    <h3 class="font-bold text-imipas-blue text-sm md:text-base mb-2">Pemberian Makan & Minum</h3>
                    <p class="text-[11px] md:text-xs text-slate-500 leading-relaxed">WBP berhak mendapatkan makanan dan minuman yang layak, bergizi, dan higienis secara GRATIS 3 kali sehari sesuai standar Kementerian.</p>
                </div>

                <div class="p-5 md:p-6 bg-slate-50 border-l-4 border-emerald-500 rounded-r-2xl shadow-sm">
                    <h3 class="font-bold text-imipas-blue text-sm md:text-base mb-2">Layanan Kesehatan</h3>
                    <p class="text-[11px] md:text-xs text-slate-500 leading-relaxed">Setiap WBP berhak atas pemeriksaan kesehatan rutin di Klinik Lapas dan rujukan ke RSUD jika diperlukan tanpa biaya.</p>
                </div>

                <div class="p-5 md:p-6 bg-slate-50 border-l-4 border-emerald-500 rounded-r-2xl shadow-sm">
                    <h3 class="font-bold text-imipas-blue text-sm md:text-base mb-2">Hak Remisi & Integrasi</h3>
                    <p class="text-[11px] md:text-xs text-slate-500 leading-relaxed">Pengusulan Remisi, PB, CB, CMB, dan Asimilasi dilakukan secara sistemik (SDP) bagi yang memenuhi syarat perilaku baik tanpa Pungutan Liar.</p>
                </div>

                <div class="p-5 md:p-6 bg-slate-50 border-l-4 border-emerald-500 rounded-r-2xl shadow-sm">
                    <h3 class="font-bold text-imipas-blue text-sm md:text-base mb-2">Bantuan Hukum</h3>
                    <p class="text-[11px] md:text-xs text-slate-500 leading-relaxed">Mendapatkan informasi hukum dan bantuan hukum sesuai dengan ketentuan perundang-undangan yang berlaku.</p>
                </div>
            </div>

            <!-- KEWAJIBAN WBP -->
            <div class="space-y-6" data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center gap-4 mb-6 md:mb-8">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-amber-100 text-amber-600 flex items-center justify-center rounded-xl md:rounded-2xl shadow-sm">
                        <i data-lucide="alert-circle" class="w-5 h-5 md:w-6 md:h-6"></i>
                    </div>
                    <h2 class="text-xl md:text-2xl font-bold text-imipas-blue uppercase tracking-tight">Kewajiban WBP</h2>
                </div>

                <div class="p-5 md:p-6 bg-slate-50 border-l-4 border-amber-500 rounded-r-2xl shadow-sm">
                    <h3 class="font-bold text-imipas-blue text-sm md:text-base mb-2">Taat Tata Tertib</h3>
                    <p class="text-[11px] md:text-xs text-slate-500 leading-relaxed">Mematuhi tata tertib Lapas, menghormati petugas, dan sesama warga binaan demi terciptanya kondusifitas.</p>
                </div>

                <div class="p-5 md:p-6 bg-slate-50 border-l-4 border-amber-500 rounded-r-2xl shadow-sm">
                    <h3 class="font-bold text-imipas-blue text-sm md:text-base mb-2">Mengikuti Pembinaan</h3>
                    <p class="text-[11px] md:text-xs text-slate-500 leading-relaxed">Wajib mengikuti program pembinaan kepribadian (ibadah/mental) dan kemandirian (pelatihan kerja) secara aktif.</p>
                </div>

                <div class="p-5 md:p-6 bg-slate-50 border-l-4 border-amber-500 rounded-r-2xl shadow-sm">
                    <h3 class="font-bold text-imipas-blue text-sm md:text-base mb-2">Kebersihan Lingkungan</h3>
                    <p class="text-[11px] md:text-xs text-slate-500 leading-relaxed">Menjaga kebersihan diri, kamar hunian, dan lingkungan blok hunian secara berkala.</p>
                </div>

                <div class="p-5 md:p-6 bg-slate-50 border-l-4 border-amber-500 rounded-r-2xl shadow-sm">
                    <h3 class="font-bold text-imipas-blue text-sm md:text-base mb-2">Larangan Barang Terlarang</h3>
                    <p class="text-[11px] md:text-xs text-slate-500 leading-relaxed">Dilarang keras memiliki, membawa, atau menggunakan Handphone, Narkoba, dan Senjata Tajam (HALINAR).</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ANTI PUNGLI BANNER -->
    <section class="py-12 px-4 max-w-7xl mx-auto mb-16" data-aos="zoom-in">
        <div class="bg-red-50 border-2 border-red-100 p-6 md:p-10 rounded-[2rem] md:rounded-[3rem] flex flex-col md:flex-row items-center text-center md:text-left gap-6 md:gap-10 shadow-sm transition hover:shadow-md">
            <div class="w-16 h-16 md:w-24 md:h-24 bg-red-600 text-white flex-shrink-0 flex items-center justify-center rounded-full animate-pulse shadow-lg">
                <i data-lucide="shield-alert" class="w-8 h-8 md:w-12 md:h-12"></i>
            </div>
            <div class="flex-grow">
                <h2 class="text-xl md:text-3xl font-black text-red-700 uppercase mb-3 leading-tight">Layanan Kami 100% Gratis!</h2>
                <p class="text-xs md:text-sm text-red-600 leading-relaxed max-w-2xl">Apabila Anda menemukan petugas yang meminta imbalan dalam bentuk apapun untuk pengurusan hak-hak WBP, segera laporkan melalui kanal pengaduan resmi kami. Kami tidak menoleransi segala bentuk Pungutan Liar (PUNGLI).</p>
            </div>
            <a href="pengaduan.php" class="w-full md:w-auto bg-red-600 text-white px-10 py-5 rounded-2xl font-bold uppercase text-xs shadow-lg hover:bg-red-700 transition active:scale-95">Laporkan Pungli</a>
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
