<header id="mainHeader" class="sticky top-0 left-0 w-full z-[100] bg-white/0 border-b-2 border-transparent shadow-none transition-all duration-500 py-4">
  <!-- Scroll Progress Bar -->
  <div id="scrollProgress" class="absolute top-0 left-0 h-1 bg-imipas-gold w-0 transition-all duration-100 z-[110]"></div>
  
  <div class="max-w-7xl mx-auto flex justify-between items-center px-4 md:px-8 py-3">
    
    <a href="index.php" class="flex items-center gap-2 md:gap-4 transition hover:opacity-90">
      <img src="assets/images/logolap.png" class="h-10 md:h-14 w-auto" alt="Logo Instansi" loading="lazy">
      <div class="border-l border-slate-300 pl-3 md:pl-4">
        <h1 class="text-[10px] md:text-sm font-bold leading-tight text-imipas-blue uppercase tracking-tight">
          Lembaga Pemasyarakatan <br> Kelas IIB Lamongan
        </h1>
        <p class="hidden md:block text-[9px] text-imipas-gold font-semibold uppercase tracking-[0.1em]">
          Kementerian Imigrasi dan Pemasyarakatan
        </p>
      </div>
    </a>
    
    <?php 
      // Mendapatkan nama file saat ini untuk class 'active'
      $current_page = basename($_SERVER['PHP_SELF']); 
    ?>
    <nav class="hidden lg:flex items-center space-x-1 text-[11px] font-bold uppercase tracking-wider text-imipas-blue">
      <a href="index.php" class="px-3 py-2 transition border-b-2 <?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'text-imipas-gold border-imipas-blue' : 'border-transparent hover:text-imipas-gold hover:border-imipas-blue' ?>">Beranda</a>
      
      <!-- Dropdown Informasi -->
      <div class="relative group">
        <button class="px-3 py-2 transition border-b-2 border-transparent hover:text-imipas-gold hover:border-imipas-blue flex items-center gap-1 uppercase">
          Informasi <i data-lucide="chevron-down" class="w-3 h-3"></i>
        </button>
        <div class="absolute left-0 mt-0 w-48 bg-white shadow-xl border-t-2 border-imipas-gold opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 z-[110]">
          <a href="profile.php" class="block px-4 py-3 hover:bg-slate-50 hover:text-imipas-gold transition-colors border-b border-slate-50">Profil Instansi</a>
          <a href="berita.php" class="block px-4 py-3 hover:bg-slate-50 hover:text-imipas-gold transition-colors border-b border-slate-50">Berita Terkini</a>
          <a href="regulasi.php" class="block px-4 py-3 hover:bg-slate-50 hover:text-imipas-gold transition-colors border-b border-slate-50">Hak & Kewajiban</a>
          <a href="galeri.php" class="block px-4 py-3 hover:bg-slate-50 hover:text-imipas-gold transition-colors">Galeri Kegiatan</a>
        </div>
      </div>

      <!-- Dropdown Layanan -->
      <div class="relative group">
        <button class="px-3 py-2 transition border-b-2 border-transparent hover:text-imipas-gold hover:border-imipas-blue flex items-center gap-1 uppercase">
          Layanan <i data-lucide="chevron-down" class="w-3 h-3"></i>
        </button>
        <div class="absolute left-0 mt-0 w-48 bg-white shadow-xl border-t-2 border-imipas-gold opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 z-[110]">
          <a href="layanan.php" class="block px-4 py-3 hover:bg-slate-50 hover:text-imipas-gold transition-colors border-b border-slate-50">Standar Pelayanan</a>
          <a href="kunjungan.php" class="block px-4 py-3 hover:bg-slate-50 hover:text-imipas-gold transition-colors border-b border-slate-50">Pendaftaran Kunjungan</a>
          <a href="pengaduan.php" class="block px-4 py-3 hover:bg-slate-50 hover:text-imipas-gold transition-colors border-b border-slate-50">Portal Pengaduan</a>
          <a href="login_integrasi.php" class="block px-4 py-3 hover:bg-slate-50 hover:text-imipas-gold transition-colors border-b border-slate-50">Hak Integrasi</a>
          <a href="produk.php" class="block px-4 py-3 hover:bg-slate-50 hover:text-imipas-gold transition-colors">Produk WBP</a>
        </div>
      </div>

      <a href="panduan.php" class="px-3 py-2 transition border-b-2 <?= (basename($_SERVER['PHP_SELF']) == 'panduan.php') ? 'text-imipas-gold border-imipas-blue' : 'border-transparent hover:text-imipas-gold hover:border-imipas-blue' ?>">Panduan</a>
    </nav>

    <button id="menuBtn" class="lg:hidden text-imipas-blue p-2 border border-slate-200 rounded focus:outline-none hover:bg-slate-50 transition-colors">
      <svg id="menuIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path id="path1" d="M4 6h16" stroke-width="2" stroke-linecap="round" class="transition-all duration-300 origin-center"/>
        <path id="path2" d="M4 12h16" stroke-width="2" stroke-linecap="round" class="transition-all duration-300"/>
        <path id="path3" d="M4 18h16" stroke-width="2" stroke-linecap="round" class="transition-all duration-300 origin-center"/>
      </svg>
    </button>
  </div>

  <div id="mobileMenu" class="lg:hidden bg-white border-t border-slate-100 font-bold uppercase text-[11px] tracking-widest hidden">
    <div class="flex flex-col py-2 px-6">
        <a href="index.php" class="py-4 border-b border-slate-50 text-imipas-blue">Beranda</a>
        
        <div class="border-b border-slate-50">
            <button onclick="toggleSub('infoSub')" class="w-full py-4 flex justify-between items-center text-imipas-blue uppercase">
                Informasi <i data-lucide="chevron-down" id="infoChevron" class="w-4 h-4 transition-transform duration-300"></i>
            </button>
            <div id="infoSub" class="hidden pb-4 pl-4 space-y-4 text-[10px] text-slate-500">
                <a href="profile.php" class="block">Profil Instansi</a>
                <a href="berita.php" class="block">Berita Terkini</a>
                <a href="regulasi.php" class="block">Hak & Kewajiban</a>
                <a href="galeri.php" class="block">Galeri Kegiatan</a>
            </div>
        </div>

        <div class="border-b border-slate-50">
            <button onclick="toggleSub('layananSub')" class="w-full py-4 flex justify-between items-center text-imipas-blue uppercase">
                Layanan <i data-lucide="chevron-down" id="layananChevron" class="w-4 h-4 transition-transform duration-300"></i>
            </button>
            <div id="layananSub" class="hidden pb-4 pl-4 space-y-4 text-[10px] text-slate-500">
                <a href="layanan.php" class="block">Standar Pelayanan</a>
                <a href="kunjungan.php" class="block">Pendaftaran Kunjungan</a>
                <a href="pengaduan.php" class="block">Portal Pengaduan</a>
                <a href="login_integrasi.php" class="block">Hak Integrasi</a>
                <a href="produk.php" class="block">Produk WBP</a>
            </div>
        </div>

        <a href="panduan.php" class="py-4 text-imipas-blue">Panduan</a>
    </div>
  </div>
</header>

<script>
  const menuBtn = document.getElementById('menuBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  const path1 = document.getElementById('path1');
  const path2 = document.getElementById('path2');
  const path3 = document.getElementById('path3');

  menuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
    
    if (!mobileMenu.classList.contains('hidden')) {
      // Menu Terbuka
      path1.setAttribute('d', 'M6 18L18 6');
      path2.style.opacity = '0';
      path3.setAttribute('d', 'M6 6l12 12');
    } else {
      // Menu Tertutup
      path1.setAttribute('d', 'M4 6h16');
      path2.style.opacity = '1';
      path3.setAttribute('d', 'M4 18h16');
    }
  });

  // Togle Sub Menu Mobile
  // Togle Sub Menu Mobile (Simple Hidden Toggle)
  function toggleSub(id) {
    const sub = document.getElementById(id);
    const chevron = document.getElementById(id === 'infoSub' ? 'infoChevron' : 'layananChevron');
    
    sub.classList.toggle('hidden');
    
    // Rotate Chevron
    if (!sub.classList.contains('hidden')) {
        chevron.style.transform = 'rotate(180deg)';
    } else {
        chevron.style.transform = 'rotate(0deg)';
    }
  }

  // Menutup menu otomatis jika window di-resize ke desktop
  // Menutup menu otomatis jika window di-resize ke desktop
  window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024) {
      mobileMenu.classList.add('hidden');
      path1.setAttribute('d', 'M4 6h16');
      path2.style.opacity = '1';f
      path3.setAttribute('d', 'M4 18h16');
      // Tutup semua submenu
      document.getElementById('infoSub').classList.add('hidden');
      document.getElementById('layananSub').classList.add('hidden');
      document.getElementById('infoChevron').style.transform = 'rotate(0deg)';
      document.getElementById('layananChevron').style.transform = 'rotate(0deg)';
    }
  });

  // --- PREMIUM SCROLL EXPERIENCE ---
  const header = document.getElementById('mainHeader');
  const progressBar = document.getElementById('scrollProgress');

  window.addEventListener('scroll', () => {
    // 1. Progress Bar
    const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
    const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    const scrolled = (winScroll / height) * 100;
    progressBar.style.width = scrolled + "%";

    // 2. Glassmorphism Effect for Jabarprov Style
    // Default state should be transparent if at top? No, user wanted "Versi Lapas", so maybe sticky white is safer. 
    // But Jabarprov is transparent at top. Let's make it transparent at top.
    if (window.scrollY > 50) {
      header.classList.remove('bg-white/0', 'backdrop-blur-none', 'shadow-none', 'py-4');
      header.classList.add('bg-white/90', 'backdrop-blur-md', 'shadow-sm', 'py-2');
    } else {
      header.classList.add('bg-white/0', 'backdrop-blur-none', 'shadow-none', 'py-4');
      header.classList.remove('bg-white/90', 'backdrop-blur-md', 'shadow-sm', 'py-2');
    }
  });
</script>