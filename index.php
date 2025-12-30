<?php
include "config/koneksi.php";

// Ambil Berita
$berita = mysqli_query($conn, "SELECT * FROM berita ORDER BY tanggal DESC LIMIT 6");

// Ambil Profil (untuk sambutan/foto kepala)
$profil_query = mysqli_query($conn, "SELECT * FROM profil_lapas WHERE id = 1");
$profil = mysqli_fetch_assoc($profil_query);

// Ambil Produk (Limit 4 untuk ditampilkan di home)
$produk_query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk DESC LIMIT 4");
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lapas Kelas IIB Lamongan - Situs Resmi</title>
  <link rel="icon" type="image/png" href="assets/images/logo_imigrasi.png">
  
  <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300;400;600;700&display=swap" rel="stylesheet">
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
          fontFamily: {
            sans: ['"Titillium Web"', 'sans-serif'],
          }
        }
      }
    }
  </script>

  <style>
    body { font-family: 'Titillium Web', sans-serif; }
    .hero-overlay {
      background: linear-gradient(to bottom, rgba(7, 33, 61, 0.95), rgba(7, 33, 61, 0.70));
    }
  </style>
</head>

<body class="bg-white text-slate-900 antialiased overflow-x-hidden">

  <div class="bg-imipas-platinum border-b border-slate-200 py-2 px-4 hidden md:block">
    <div class="max-w-7xl mx-auto flex justify-between text-[10px] font-bold uppercase tracking-widest text-imipas-blue">
      <span>Republik Indonesia</span>
      <span id="currentDate"></span>
    </div>
  </div>

  <?php include "layout/navbar.php"; ?>

  <section class="relative min-h-[85vh] flex items-center justify-center py-20 px-6 bg-[url('assets/images/hero.jpg')] bg-cover bg-center text-center">
    <div class="absolute inset-0 hero-overlay"></div>
    <div class="relative z-10 max-w-4xl mx-auto" data-aos="fade-up" data-aos-duration="1500">
      <span class="bg-imipas-gold text-imipas-blue text-[10px] md:text-[11px] px-4 py-1 font-bold uppercase tracking-[0.2em] mb-6 inline-block">
        Portal Informasi Resmi
      </span>
      <h2 class="text-4xl md:text-7xl font-bold text-white mb-6 leading-tight uppercase">
        Transformasi Pemasyarakatan <br> <span class="text-imipas-gold">Berintegritas</span>
      </h2>
      <div class="h-1 w-20 bg-imipas-gold mx-auto mb-8"></div>
      <p class="text-imipas-platinum text-sm md:text-lg mb-10 leading-relaxed max-w-2xl mx-auto font-light">
        Mewujudkan sistem pemasyarakatan yang transparan, humanis, dan akuntabel di Lembaga Pemasyarakatan Kelas IIB Lamongan.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
        <a href="kunjungan.php" class="bg-white text-imipas-blue px-10 py-4 text-[11px] font-bold uppercase transition hover:bg-imipas-platinum border-b-4 border-imipas-gold w-full sm:w-64">
          Daftar Kunjungan
        </a>
        <a href="pengaduan.php" class="bg-imipas-gold text-imipas-blue px-10 py-4 text-[11px] font-bold uppercase transition hover:opacity-90 border-b-4 border-[#C9A052] w-full sm:w-64">
          Portal Pengaduan
        </a>
      </div>
    </div>
  </section>

  <section class="max-w-7xl mx-auto px-4 -mt-16 relative z-20" data-aos="zoom-in" data-aos-delay="300">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-0 shadow-2xl bg-white border-t-4 border-imipas-gold">
      <a href="kunjungan.php" class="p-10 flex flex-col items-center text-center border-b sm:border-r border-slate-100 hover:bg-slate-50 transition group">
        <div class="w-14 h-14 flex items-center justify-center bg-slate-100 text-imipas-blue mb-5 rounded-full group-hover:bg-imipas-blue group-hover:text-white transition-all duration-500">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <h4 class="font-bold text-imipas-blue uppercase text-xs mb-2 tracking-widest">Pendaftaran Kunjungan</h4>
        <p class="text-slate-500 text-[11px] leading-relaxed">Sistem pendaftaran online kunjungan tatap muka & titipan barang.</p>
      </a>
      <a href="integritas.php" class="p-10 flex flex-col items-center text-center border-b lg:border-r border-slate-100 hover:bg-slate-50 transition group">
        <div class="w-14 h-14 flex items-center justify-center bg-slate-100 text-imipas-blue mb-5 rounded-full group-hover:bg-imipas-blue group-hover:text-white transition-all duration-500">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <h4 class="font-bold text-imipas-blue uppercase text-xs mb-2 tracking-widest">Hak Integrasi</h4>
        <p class="text-slate-500 text-[11px] leading-relaxed">Pantau progres usulan PB, CB, CMB, dan Asimilasi secara transparan.</p>
      </a>
      <a href="pengaduan.php" class="p-10 flex flex-col items-center text-center col-span-1 sm:col-span-2 lg:col-span-1 hover:bg-slate-50 transition group">
        <div class="w-14 h-14 flex items-center justify-center bg-slate-100 text-imipas-blue mb-5 rounded-full group-hover:bg-imipas-blue group-hover:text-white transition-all duration-500">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <h4 class="font-bold text-imipas-blue uppercase text-xs mb-2 tracking-widest">Kontak & Pengaduan</h4>
        <p class="text-slate-500 text-[11px] leading-relaxed">Layanan pengaduan masyarakat atas ketidakpuasan pelayanan publik.</p>
      </a>
    </div>
  </section>

  <section class="py-24 px-4 bg-white overflow-hidden">
    <div class="max-w-6xl mx-auto">
      <div class="flex flex-col lg:flex-row items-center justify-center gap-12 lg:gap-20" data-aos="fade-up" data-aos-duration="1000">
        <div class="relative w-full max-w-[320px]">
          <div class="absolute -bottom-4 -right-4 w-full h-full bg-imipas-gold/20 -z-10"></div>
          <img src="assets/images/<?= $profil['foto_kepala'] ?>" class="w-full h-auto shadow-xl border-b-8 border-imipas-blue" alt="Kepala Lapas">
        </div>
        <div class="max-w-xl text-center lg:text-left">
          <span class="text-imipas-gold font-bold text-[11px] uppercase tracking-widest mb-2 block">Sambutan Pimpinan</span>
          <h3 class="text-3xl font-bold text-imipas-blue mb-6 uppercase"><?= $profil['nama_kepala'] ?></h3>
          <p class="text-slate-600 italic leading-relaxed mb-8 font-light">"<?= $profil['sambutan_kepala'] ?>"</p>
          <a href="profile.php" class="inline-block bg-imipas-blue text-white px-8 py-3 text-[10px] font-bold uppercase hover:bg-imipas-gold hover:text-imipas-blue transition">Selengkapnya</a>
        </div>
      </div>
    </div>
  </section>

  <section class="py-24 px-4 bg-slate-50">
    <div class="max-w-7xl mx-auto">
      <div class="flex items-center justify-between mb-12" data-aos="fade-up">
        <h3 class="text-2xl font-bold uppercase text-imipas-blue">Berita <span class="text-imipas-gold">Terkini</span></h3>
        <a href="berita.php" class="text-[10px] font-bold text-imipas-blue uppercase border-b-2 border-imipas-gold pb-1">Lihat Semua</a>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8" data-aos="fade-up" data-aos-duration="1000">
        <?php while ($row = mysqli_fetch_assoc($berita)): ?>
          <article class="group bg-white flex flex-col border border-slate-100 shadow-sm hover:shadow-md transition">
            <div class="aspect-video overflow-hidden">
              <img src="assets/images/<?= $row['gambar']; ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
            </div>
            <div class="p-6">
              <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest"><?= date('d M Y', strtotime($row['tanggal'])) ?></span>
              <h4 class="font-bold text-sm text-imipas-blue group-hover:text-imipas-gold transition mt-2 mb-4 line-clamp-2 uppercase"><?= $row['judul']; ?></h4>
              <a href="detail_berita.php?id=<?= $row['id_berita']; ?>" class="text-[10px] font-bold uppercase text-imipas-blue flex items-center gap-2">Baca Selengkapnya <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="3" stroke-linecap="round"/></svg></a>
            </div>
          </article>
        <?php endwhile; ?>
      </div>
    </div>
  </section>

  <section class="py-24 px-4 bg-imipas-blue text-white overflow-hidden">
    <div class="max-w-7xl mx-auto">
      <div class="flex items-center justify-between mb-12" data-aos="fade-up">
        <h3 class="text-2xl font-bold uppercase text-white">Produk <span class="text-imipas-gold">Unggulan</span></h3>
        <a href="produk.php" class="text-[10px] font-bold text-imipas-gold uppercase border-b-2 border-white/30 pb-1 hover:border-imipas-gold transition-all">Lihat Semua</a>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-4 gap-8" data-aos="fade-up" data-aos-duration="1000">
        <?php while($p = mysqli_fetch_assoc($produk_query)): ?>
        <div class="group flex flex-col items-center">
          <div class="w-full aspect-square overflow-hidden mb-6 border border-white/10 p-2">
            <img src="uploads/<?= $p['gambar'] ?>" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-700 shadow-lg">
          </div>
          <h5 class="font-bold text-[11px] uppercase text-imipas-gold mb-2 text-center"><?= $p['nama_produk'] ?></h5>
          <p class="text-[10px] text-slate-300 text-center line-clamp-2 font-light px-2"><?= $p['deskripsi'] ?></p>
        </div>
        <?php endwhile; ?>
      </div>
    </div>
  </section>

  <?php include "layout/lokasi.php"; ?>
  <?php include "layout/footer.php"; ?>

  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    // Initialize AOS
    AOS.init({
      once: true,
      offset: 120,
    });

    // Tanggal Indonesia
    const dateElement = document.getElementById('currentDate');
    if(dateElement) {
      const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
      dateElement.innerText = new Date().toLocaleDateString('id-ID', options);
    }
  </script>
</body>
</html>