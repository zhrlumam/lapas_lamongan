<?php
// SET TIMEZONE
date_default_timezone_set('Asia/Jakarta');

include "config/koneksi.php";

// KEAMANAN
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Layanan & Informasi - Lapas Kelas IIB Lamongan</title>
    <meta name="description" content="Informasi lengkap syarat dan alur pengusulan Cuti Bersyarat, Pembebasan Bersyarat, Cuti Menjelang Bebas, dan Layanan Lainnya.">
    
    <link rel="icon" type="image/png" href="assets/images/logo_imigrasi.png">
    
    <!-- Fonts & CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Font Awesome -->
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
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        .flow-arrow {
            position: relative;
        }
        .flow-arrow::after {
            content: '';
            position: absolute;
            right: -10px;
            top: 50%;
            transform: translateY(-50%);
            border-left: 10px solid #07213D;
            border-top: 8px solid transparent;
            border-bottom: 8px solid transparent;
        }
        @media (max-width: 768px) {
            .flow-arrow::after {
                right: 50%;
                top: auto;
                bottom: -10px;
                transform: translateX(50%) rotate(90deg);
            }
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased font-sans">

    <!-- Topbar -->
    <div class="bg-imipas-platinum border-b border-slate-200 py-2 px-4 block">
        <div class="max-w-7xl mx-auto flex justify-between text-[10px] font-bold uppercase tracking-widest text-imipas-blue">
            <span>Republik Indonesia</span>
            <span><?= date('l, d F Y') ?></span>
        </div>
    </div>

    <?php include "layout/navbar.php"; ?>

    <!-- Header / Hero -->
    <section class="relative bg-imipas-blue py-20 px-4 overflow-hidden">
        <div class="absolute top-0 right-0 opacity-10 w-full h-full">
            <div class="absolute right-0 top-0 w-96 h-96 bg-imipas-gold blur-3xl rounded-full translate-x-1/2 -translate-y-1/2"></div>
        </div>
        <div class="max-w-7xl mx-auto relative z-10 text-center">
            <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 uppercase" data-aos="fade-up">Layanan & Integrasi</h1>
            <div class="h-1 w-20 bg-imipas-gold mx-auto mb-6" data-aos="fade-up" data-aos-delay="100"></div>
            <p class="text-imipas-platinum max-w-2xl mx-auto text-sm md:text-lg font-light leading-relaxed" data-aos="fade-up" data-aos-delay="200">
                Informasi standar pelayanan Cuti Bersyarat, Pembebasan Bersyarat, dan layanan lainnya di Lembaga Pemasyarakatan Kelas IIB Lamongan. Pungutan liar & gratifikasi adalah pelanggaran!
            </p>
        </div>
    </section>

    <!-- CONTENT START -->
    <div class="max-w-7xl mx-auto px-4 py-16 space-y-24">

        <!-- 1. CUTI BERSYARAT (CB) & PEMBEBASAN BERSYARAT (PB) & CMB -->
        <!-- We will group them by tabs or just list them nicely. Let's list them as distinct sections for clarity given the request "sesuaikan". -->

        <!-- CUTI BERSYARAT -->
        <section id="cuti-bersyarat" class="scroll-mt-24" data-aos="fade-up">
            <div class="border-l-4 border-imipas-gold pl-6 mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-imipas-blue uppercase tracking-wide">Cuti Bersyarat (CB)</h2>
                <p class="text-slate-500 text-sm mt-1">Alur Proses Pengusulan Layanan Integrasi</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-xl border border-slate-200 overflow-hidden">
                <!-- Banner Title -->
                <div class="bg-imipas-gold p-4 text-center">
                    <span class="text-imipas-blue font-bold text-xl uppercase tracking-widest">Syarat & Ketentuan</span>
                </div>

                <div class="p-8 grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Syarat Substantif -->
                    <div>
                        <h3 class="flex items-center gap-3 text-lg font-bold text-imipas-blue mb-4 uppercase border-b border-slate-200 pb-2">
                            <i data-lucide="scale" class="w-5 h-5 text-imipas-gold"></i> Syarat Substantif
                        </h3>
                        <ul class="space-y-3 text-sm text-slate-700">
                            <li class="flex gap-3">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5"></i>
                                <span>Telah menjalani masa pidana lebih dari 2/3 masa pidana dengan ketentuan masa pidana paling sedikit 6 bulan.</span>
                            </li>
                            <li class="flex gap-3">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5"></i>
                                <span>Maksimal perolehan 6 bulan.</span>
                            </li>
                            <li class="flex gap-3">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5"></i>
                                <span>Berkelakuan baik selama menjalani masa pidana paling singkat 6 bulan terakhir.</span>
                            </li>
                            <li class="flex gap-3">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5"></i>
                                <span>Telah mengikuti program pembinaan dengan baik.</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Syarat Admnistratif -->
                    <div>
                        <h3 class="flex items-center gap-3 text-lg font-bold text-imipas-blue mb-4 uppercase border-b border-slate-200 pb-2">
                            <i data-lucide="file-text" class="w-5 h-5 text-imipas-gold"></i> Syarat Administratif
                        </h3>
                        <ol class="list-decimal list-outside ml-4 space-y-2 text-sm text-slate-700 font-medium">
                            <li>Kartu Narapidana</li>
                            <li>Fotokopi Kutipan Putusan Hakim</li>
                            <li>Berita Acara Pelaksanaan Putusan Pengadilan</li>
                            <li>Surat Keterangan tidak ada perkara lain dari Kejari</li>
                            <li>Surat kesanggupan dan kemampuan dari yang menerima (Penjamin)</li>
                            <li>Laporan Hasil Penelitian Kemasyarakatan dari Bapas</li>
                            <li>Laporan Perkembangan Pembinaan dari Lapas</li>
                            <li>Salinan Registratif F</li>
                            <li>Salinan Daftar Perubahan</li>
                        </ol>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="bg-imipas-blue p-6 text-white text-sm md:flex justify-between items-center">
                    <div class="space-y-1 mb-4 md:mb-0">
                        <p class="font-bold flex items-center gap-2"><i data-lucide="info" class="w-4 h-4 text-imipas-gold"></i> KETERANGAN:</p>
                        <ul class="list-disc list-inside text-imipas-platinum text-xs ml-2">
                            <li>Penjamin harus membawa berkas Fotocopy KK dan KTP</li>
                            <li>Materai Rp. 10.000 (4 Pcs)</li>
                            <li>Pengusulan CB ketika sudah melewati 1/2 masa pidana dan sudah menjalani paling tidak 6 bulan.</li>
                        </ul>
                    </div>
                    <div class="text-center md:text-right">
                         <div class="inline-block border-2 border-white px-6 py-2 transform -rotate-3 hover:rotate-0 transition duration-300 bg-white text-imipas-blue font-black text-2xl uppercase tracking-tighter shadow-lg">
                            GRATIS!
                            <span class="block text-[10px] font-normal tracking-normal text-slate-500">Tanpa Dipungut Biaya</span>
                         </div>
                    </div>
                </div>
            </div>

            <!-- Flowchart CB -->
            <div class="mt-8 bg-slate-100 p-6 rounded-lg border border-slate-200">
                <h3 class="text-center font-bold text-imipas-blue uppercase mb-8">Alur dan Proses Pengusulan Cuti Bersyarat</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative">
                    <!-- Step 1 -->
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        Terpenuhinya Syarat Administratif & Subtantif
                    </div>
                    <!-- Step 2 -->
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        Sidang TPP Lapas
                    </div>
                    <!-- Step 3 -->
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        Pembuatan Usulan Cuti Bersyarat
                    </div>
                    <!-- Step 4 -->
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        Usulan Cuti Bersyarat Secara Online
                    </div>
                </div>
                <!-- Row 2 -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative mt-6 lg:w-3/4 mx-auto">
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        Verifikasi Kanwil
                    </div>
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        Verifikasi Ditjenpas
                    </div>
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        SK di TTD Elektronik Oleh Dirjen/Menteri
                    </div>
                </div>
                 <!-- Row 3 -->
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative mt-6 lg:w-1/2 mx-auto">
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        SK Dikirim Dari Ditjen ke Lapas Melalui SDP
                    </div>
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        Cetak SK Cuti Bersyarat Secara Online
                    </div>
                 </div>
                 <p class="text-center text-xs mt-6 text-slate-400 italic">Ikuti tanda panah sesuai alur di atas.</p>
            </div>
        </section>


        <!-- PEMBEBASAN BERSYARAT -->
        <section id="pembebasan-bersyarat" class="scroll-mt-24" data-aos="fade-up">
            <div class="border-l-4 border-imipas-gold pl-6 mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-imipas-blue uppercase tracking-wide">Pembebasan Bersyarat (PB)</h2>
                <p class="text-slate-500 text-sm mt-1">Layanan Re-Integrasi Warga Binaan</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-xl border border-slate-200 overflow-hidden">
                <div class="bg-imipas-blue p-4 text-center">
                    <span class="text-imipas-gold font-bold text-xl uppercase tracking-widest">Syarat & Ketentuan</span>
                </div>

                <div class="p-8 grid grid-cols-1 lg:grid-cols-2 gap-12">
                     <!-- Syarat Substantif -->
                     <div>
                        <h3 class="flex items-center gap-3 text-lg font-bold text-imipas-blue mb-4 uppercase border-b border-slate-200 pb-2">
                             <i data-lucide="scale" class="w-5 h-5 text-imipas-gold"></i> Syarat Substantif
                        </h3>
                        <ul class="space-y-3 text-sm text-slate-700">
                            <li class="flex gap-3">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5"></i>
                                <span>Telah menjalani masa pidana lebih dari 2/3 masa pidana dengan ketentuan masa pidana paling sedikit 9 bulan.</span>
                            </li>
                            <li class="flex gap-3">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5"></i>
                                <span>Menjalani pidana diatas 1 Tahun 6 Bulan (sesuai aturan internal untuk prioritas/seleksi).</span>
                            </li>
                            <li class="flex gap-3">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5"></i>
                                <span>Berkelakuan baik selama menjalani masa pidana paling singkat 9 bulan terakhir.</span>
                            </li>
                            <li class="flex gap-3">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5"></i>
                                <span>Telah mengikuti program pembinaan dengan baik.</span>
                            </li>
                        </ul>
                    </div>
                     <!-- Syarat Administratif -->
                     <div>
                        <h3 class="flex items-center gap-3 text-lg font-bold text-imipas-blue mb-4 uppercase border-b border-slate-200 pb-2">
                            <i data-lucide="file-text" class="w-5 h-5 text-imipas-gold"></i> Syarat Administratif
                        </h3>
                        <ol class="list-decimal list-outside ml-4 space-y-2 text-sm text-slate-700 font-medium">
                            <li>Kartu Narapidana</li>
                            <li>Fotokopi Kutipan Putusan Hakim</li>
                            <li>Berita Acara Pelaksanaan Putusan Pengadilan</li>
                            <li>Surat Keterangan tidak ada perkara lain dari Kejari</li>
                            <li>Surat kesanggupan dan kemampuan dari yang menerima (Penjamin)</li>
                            <li>Laporan Hasil Penelitian Kemasyarakatan dari Bapas</li>
                            <li>Laporan Perkembangan Pembinaan dari Lapas</li>
                            <li>Salinan Registratif F</li>
                            <li>Salinan Daftar Perubahan</li>
                        </ol>
                    </div>
                </div>
                 <!-- Footer Info -->
                 <div class="bg-imipas-platinum p-6 text-imipas-blue text-sm md:flex justify-between items-center border-t border-slate-200">
                    <div class="space-y-1 mb-4 md:mb-0">
                        <p class="font-bold flex items-center gap-2">KETERANGAN:</p>
                        <ul class="list-disc list-inside text-xs ml-2">
                            <li>Penjamin harus membawa Fotocopy KK & KTP</li>
                            <li>Materai Rp. 10.000 (4 Pcs)</li>
                            <li>Pengusulan PB ketika sudah melewati 1/2 masa pidana & menjalani paling tidak 9 bulan.</li>
                        </ul>
                    </div>
                     <div class="text-center md:text-right">
                         <span class="text-3xl font-black text-imipas-blue tracking-tighter opacity-20">PB ONLINE</span>
                    </div>
                </div>
            </div>
             <!-- Flowchart PB is essentially same structure -->
              <!-- We can reuse similar layout or skip for brevity if user didn't ask exact duplicate but 'sesuaikan'. -->
             <!-- But let's add it for completeness -->
                <!-- Flowchart PB -->
            <div class="mt-8 bg-slate-100 p-6 rounded-lg border border-slate-200">
                <h3 class="text-center font-bold text-imipas-blue uppercase mb-8">Alur dan Proses Pengusulan Pembebasan Bersyarat</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative">
                    <!-- Step 1 -->
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        Terpenuhinya Syarat Administratif & Subtantif
                    </div>
                    <!-- Step 2 -->
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        Sidang TPP Lapas
                    </div>
                    <!-- Step 3 -->
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        Pembuatan Usulan Pembebasan Bersyarat
                    </div>
                    <!-- Step 4 -->
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        Usulan Pembebasan Bersyarat Online
                    </div>
                </div>
                <!-- Row 2 -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative mt-6 lg:w-3/4 mx-auto">
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        Verifikasi Kanwil
                    </div>
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        Verifikasi Ditjenpas
                    </div>
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        SK di TTD Elektronik Oleh Dirjen/Menteri
                    </div>
                </div>
                 <!-- Row 3 -->
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative mt-6 lg:w-1/2 mx-auto">
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        SK Dikirim Dari Ditjen ke Lapas Melalui SDP
                    </div>
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        Cetak SK Pembebasan Bersyarat Secara Online
                    </div>
                 </div>
                 <p class="text-center text-xs mt-6 text-slate-400 italic">Ikuti tanda panah sesuai alur di atas.</p>
            </div>
        </section>


        <!-- CUTI MENJELANG BEBAS (CMB) -->
        <section id="cmb" class="scroll-mt-24" data-aos="fade-up">
            <div class="border-l-4 border-imipas-gold pl-6 mb-8">
                <h2 class="text-2xl md:text-3xl font-bold text-imipas-blue uppercase tracking-wide">Cuti Menjelang Bebas (CMB)</h2>
            </div>
            
             <div class="bg-white rounded-lg shadow-xl border border-slate-200 overflow-hidden">
                <div class="p-8 grid grid-cols-1 lg:grid-cols-2 gap-12">
                     <!-- Syarat Substantif -->
                     <div>
                        <h3 class="flex items-center gap-3 text-lg font-bold text-imipas-blue mb-4 uppercase border-b border-slate-200 pb-2">Syarat Substantif</h3>
                        <ul class="space-y-3 text-sm text-slate-700">
                             <li class="flex gap-3">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5"></i>
                                <span>Masa pidana lebih dari 2/3, dengan ketentuan 2/3 masa pidana paling sedikit 9 bulan.</span>
                            </li>
                             <li class="flex gap-3">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5"></i>
                                <span>Remisi Terakhir sebesar lama CMB (Maksimal 6 Bulan).</span>
                            </li>
                             <li class="flex gap-3">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5"></i>
                                <span>Berkelakuan baik selama menjalani masa pidana.</span>
                            </li>
                        </ul>
                    </div>
                    <!-- Syarat Administratif -->
                    <div>
                         <h3 class="flex items-center gap-3 text-lg font-bold text-imipas-blue mb-4 uppercase border-b border-slate-200 pb-2">Syarat Administratif</h3>
                         <p class="text-sm text-slate-500 mb-2 italic">Sama dengan persyaratan PB/CB:</p>
                         <ol class="list-decimal list-outside ml-4 space-y-1 text-sm text-slate-700">
                             <li>Kartu Narapidana, FC Putusan, BA Putusan</li>
                             <li>Surat Ket. tidak ada perkara lain</li>
                             <li>Jaminan Keluarga (KK/KTP + Materai)</li>
                             <li>Litmas Bapas</li>
                             <li>Laporan Perkembangan Pembinaan</li>
                         </ol>
                    </div>
                </div>
                 <div class="bg-yellow-50 p-4 text-center border-t border-yellow-200">
                     <p class="text-yellow-800 text-xs font-bold uppercase">Lama CMB sebesar remisi terakhir (Maksimal 6 bulan)</p>
                 </div>
             </div>
             
             <!-- Flowchart CMB -->
            <div class="mt-8 bg-slate-100 p-6 rounded-lg border border-slate-200">
                <h3 class="text-center font-bold text-imipas-blue uppercase mb-8">Alur dan Proses Pengusulan Cuti Menjelang Bebas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative">
                    <!-- Step 1 -->
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        Terpenuhinya Syarat Administratif & Subtantif
                    </div>
                    <!-- Step 2 -->
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        Sidang TPP Lapas
                    </div>
                    <!-- Step 3 -->
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        Pembuatan Usulan CMB
                    </div>
                    <!-- Step 4 -->
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        Usulan CMB Secara Online
                    </div>
                </div>
                <!-- Row 2 -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative mt-6 lg:w-3/4 mx-auto">
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        Verifikasi Kanwil
                    </div>
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        Verifikasi Ditjenpas
                    </div>
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        SK di TTD Elektronik Oleh Dirjen/Menteri
                    </div>
                </div>
                 <!-- Row 3 -->
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative mt-6 lg:w-1/2 mx-auto">
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        SK Dikirim Dari Ditjen ke Lapas Melalui SDP
                    </div>
                    <div class="bg-imipas-blue text-white p-4 rounded text-center text-[10px] font-bold uppercase flex items-center justify-center min-h-[80px] hover:bg-imipas-gold hover:text-imipas-blue transition shadow-md">
                        Cetak SK CMB Secara Online
                    </div>
                 </div>
                 <p class="text-center text-xs mt-6 text-slate-400 italic">Ikuti tanda panah sesuai alur di atas.</p>
            </div>
        </section>


        <!-- LAYANAN REGBIMKEMAS -->
        <section id="regbimkemas" class="scroll-mt-24" data-aos="fade-up">
             <div class="flex items-center justify-between mb-8 border-b-2 border-slate-200 pb-4">
                <h2 class="text-2xl md:text-3xl font-bold text-imipas-blue uppercase tracking-wide">Layanan Regbimkemas</h2>
                <span class="bg-imipas-gold text-white px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest animate-pulse">Gratis</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php
                $layanan = [
                    "Penerimaan Tahanan",
                    "Penerimaan Narapidana",
                    "Bantuan Hukum Gratis (Bagi Tahanan Tidak Mampu)",
                    "Konsultasi Hukum Tahanan/Narapidana",
                    "Sidang Pengadilan Negeri",
                    "Assesmen Risiko Tahanan/Narapidana",
                    "Penilaian Perkembangan Pembinaan",
                    "Usulan Remisi",
                    "Usulan Program Integrasi (PB, CB, CMB)",
                    "Pembebasan Tahanan/Narapidana"
                ];
                foreach($layanan as $idx => $item):
                ?>
                <div class="flex items-start gap-4 p-4 bg-white border border-slate-100 shadow-sm hover:shadow-md transition rounded-lg group">
                    <span class="flex-shrink-0 w-10 h-10 bg-imipas-platinum text-imipas-blue font-bold rounded-full flex items-center justify-center group-hover:bg-imipas-blue group-hover:text-imipas-gold transition">
                        <?= $idx + 1 ?>
                    </span>
                    <h3 class="font-bold text-slate-700 text-sm md:text-base pt-2"><?= $item ?></h3>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="mt-8 text-center bg-white p-6 rounded-lg shadow-inner border border-slate-200">
                <h3 class="text-imipas-blue font-bold uppercase mb-2">Semua Layanan Diatas</h3>
                 <div class="inline-block border-2 border-imipas-blue text-imipas-blue px-8 py-3 text-3xl font-black uppercase tracking-widest transform rotate-2 hover:rotate-0 transition duration-300">
                    GRATIS
                 </div>
                 <p class="text-slate-400 text-xs mt-3 uppercase tracking-widest">Tanpa Dipungut Biaya Sepeserpun</p>
            </div>
        </section>

    </div>
    <!-- CONTENT END -->

    <?php include "layout/footer.php"; ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
        lucide.createIcons();
    </script>
</body>
</html>
