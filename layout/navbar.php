<header class="sticky top-0 left-0 w-full z-[100] bg-white border-b-2 border-imipas-gold shadow-sm">
  <div class="max-w-7xl mx-auto flex justify-between items-center px-4 md:px-8 py-3">
    
    <div class="flex items-center gap-2 md:gap-4">
      <img src="assets/images/logolap.png" class="h-10 md:h-14 w-auto" alt="Logo Instansi">
      <div class="border-l border-slate-300 pl-3 md:pl-4">
        <h1 class="text-[10px] md:text-sm font-bold leading-tight text-imipas-blue uppercase tracking-tight">
          Lembaga Pemasyarakatan <br> Kelas IIB Lamongan
        </h1>
        <p class="hidden md:block text-[9px] text-imipas-gold font-semibold uppercase tracking-[0.1em]">
          Kementerian Imigrasi dan Pemasyarakatan
        </p>
      </div>
    </div>
    
    <nav class="hidden lg:flex items-center space-x-1 text-[11px] font-bold uppercase tracking-wider text-imipas-blue">
      <a href="index.php" class="px-3 py-2 hover:text-imipas-gold transition border-b-2 border-transparent hover:border-imipas-blue">Beranda</a>
      <a href="profile.php" class="px-3 py-2 hover:text-imipas-gold transition border-b-2 border-transparent hover:border-imipas-blue">Profil</a>
      <a href="berita.php" class="px-3 py-2 hover:text-imipas-gold transition border-b-2 border-transparent hover:border-imipas-blue">Berita</a>
      <a href="produk.php" class="px-3 py-2 hover:text-imipas-gold transition border-b-2 border-transparent hover:border-imipas-blue">Produk WBP</a>
      <a href="kunjungan.php" class="px-3 py-2 hover:text-imipas-gold transition border-b-2 border-transparent hover:border-imipas-blue">Kunjungan</a>
      <a href="pengaduan.php" class="px-3 py-2 hover:text-imipas-gold transition border-b-2 border-transparent hover:border-imipas-blue">Pengaduan</a>
    </nav>

    <button id="menuBtn" class="lg:hidden text-imipas-blue p-2 border border-slate-200 rounded focus:outline-none hover:bg-slate-50 transition-colors">
      <svg id="menuIcon" class="w-6 h-6 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path class="line-1" d="M4 6h16" stroke-width="2" stroke-linecap="round"/>
        <path class="line-2" d="M4 12h16" stroke-width="2" stroke-linecap="round"/>
        <path class="line-3" d="M4 18h16" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </button>
  </div>

  <div id="mobileMenu" class="lg:hidden bg-white border-t border-slate-100 font-bold uppercase text-[11px] tracking-widest overflow-hidden max-h-0 transition-all duration-300 ease-in-out">
    <div class="flex flex-col py-2">
      <a href="index.php" class="px-6 py-4 border-b border-slate-50 text-imipas-blue hover:bg-slate-50 transition">Beranda</a>
      <a href="profile.php" class="px-6 py-4 border-b border-slate-50 text-imipas-blue hover:bg-slate-50 transition">Profil</a>
      <a href="berita.php" class="px-6 py-4 border-b border-slate-50 text-imipas-blue hover:bg-slate-50 transition">Berita</a>
      <a href="produk.php" class="px-6 py-4 border-b border-slate-50 text-imipas-blue hover:bg-slate-50 transition">Produk WBP</a>
      <a href="kunjungan.php" class="px-6 py-4 border-b border-slate-50 text-imipas-blue hover:bg-slate-50 transition">Kunjungan</a>
      <a href="pengaduan.php" class="px-6 py-4 text-imipas-blue hover:bg-slate-50 transition">Pengaduan</a>
    </div>
  </div>
</header>

<script>
  const menuBtn = document.getElementById('menuBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  const menuIcon = document.getElementById('menuIcon');

  menuBtn.addEventListener('click', () => {
    // Toggle Menu Open/Close
    if (mobileMenu.style.maxHeight === '0px' || mobileMenu.style.maxHeight === '') {
      mobileMenu.style.maxHeight = mobileMenu.scrollHeight + 'px';
      // Optional: Animasi icon menjadi silang (X)
      menuIcon.innerHTML = '<path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
    } else {
      mobileMenu.style.maxHeight = '0px';
      // Kembali ke icon hamburger
      menuIcon.innerHTML = '<path d="M4 6h16M4 12h16M4 18h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
    }
  });
</script>