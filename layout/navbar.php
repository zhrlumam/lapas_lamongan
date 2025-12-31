<header class="sticky top-0 left-0 w-full z-[100] bg-white border-b-2 border-imipas-gold shadow-sm">
  <div class="max-w-7xl mx-auto flex justify-between items-center px-4 md:px-8 py-3">
    
    <a href="index.php" class="flex items-center gap-2 md:gap-4 transition hover:opacity-90">
      <img src="assets/images/logolap.png" class="h-10 md:h-14 w-auto" alt="Logo Instansi">
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
      <?php
        $menus = [
          'index.php' => 'Beranda',
          'profile.php' => 'Profil',
          'berita.php' => 'Berita',
          'produk.php' => 'Produk WBP',
          'kunjungan.php' => 'Kunjungan',
          'pengaduan.php' => 'Pengaduan'
        ];

        foreach ($menus as $link => $name): 
          $isActive = ($current_page == $link) ? 'text-imipas-gold border-imipas-blue' : 'border-transparent hover:text-imipas-gold hover:border-imipas-blue';
      ?>
        <a href="<?= $link ?>" class="px-3 py-2 transition border-b-2 <?= $isActive ?>"><?= $name ?></a>
      <?php endforeach; ?>
    </nav>

    <button id="menuBtn" class="lg:hidden text-imipas-blue p-2 border border-slate-200 rounded focus:outline-none hover:bg-slate-50 transition-colors">
      <svg id="menuIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path id="path1" d="M4 6h16" stroke-width="2" stroke-linecap="round" class="transition-all duration-300 origin-center"/>
        <path id="path2" d="M4 12h16" stroke-width="2" stroke-linecap="round" class="transition-all duration-300"/>
        <path id="path3" d="M4 18h16" stroke-width="2" stroke-linecap="round" class="transition-all duration-300 origin-center"/>
      </svg>
    </button>
  </div>

  <div id="mobileMenu" class="lg:hidden bg-white border-t border-slate-100 font-bold uppercase text-[11px] tracking-widest overflow-hidden max-h-0 transition-all duration-500 ease-in-out">
    <div class="flex flex-col py-2">
      <?php foreach ($menus as $link => $name): 
        $isActiveMobile = ($current_page == $link) ? 'bg-slate-50 text-imipas-gold border-l-4 border-imipas-gold' : 'text-imipas-blue';
      ?>
        <a href="<?= $link ?>" class="px-6 py-4 border-b border-slate-50 transition <?= $isActiveMobile ?>"><?= $name ?></a>
      <?php endforeach; ?>
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
    const isOpen = mobileMenu.style.maxHeight !== '0px' && mobileMenu.style.maxHeight !== '';
    
    if (!isOpen) {
      // Buka Menu
      mobileMenu.style.maxHeight = mobileMenu.scrollHeight + 'px';
      // Animasi Icon ke X
      path1.setAttribute('d', 'M6 18L18 6');
      path2.style.opacity = '0';
      path3.setAttribute('d', 'M6 6l12 12');
    } else {
      // Tutup Menu
      mobileMenu.style.maxHeight = '0px';
      // Kembali ke Hamburger
      path1.setAttribute('d', 'M4 6h16');
      path2.style.opacity = '1';
      path3.setAttribute('d', 'M4 18h16');
    }
  });

  // Menutup menu otomatis jika window di-resize ke desktop
  window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024) {
      mobileMenu.style.maxHeight = '0px';
      path1.setAttribute('d', 'M4 6h16');
      path2.style.opacity = '1';
      path3.setAttribute('d', 'M4 18h16');
    }
  });
</script>