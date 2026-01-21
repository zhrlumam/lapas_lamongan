<header id="mainHeader" class="sticky top-0 left-0 w-full z-[100] bg-white shadow-md transition-all duration-300">
  <div id="scrollProgress" class="absolute top-0 left-0 h-1 bg-gold-dignity w-0 transition-all duration-100 z-[110]"></div>
  
  <div class="max-w-6xl mx-auto flex justify-between items-center px-4 md:px-8 py-3">
    <a href="{{ route('home') }}" class="flex items-center gap-2 md:gap-4 transition hover:opacity-90">
      <img src="{{ asset('assets/logolap.png') }}" class="h-10 md:h-14 w-auto" alt="Logo">
      <div class="border-l border-platinum pl-3 md:pl-4">
        <h1 class="text-[10px] md:text-sm font-bold leading-tight text-midnight-blue uppercase">
          Lembaga Pemasyarakatan <br> Kelas IIB Lamongan
        </h1>
        <p class="hidden md:block text-[9px] text-gold-dignity font-semibold uppercase tracking-[0.1em]">
          Kementerian Imigrasi dan Pemasyarakatan
        </p>
      </div>
    </a>
    
    <nav class="hidden lg:flex items-center space-x-1 text-[11px] font-bold uppercase tracking-wider text-midnight-blue">
      <a href="{{ route('home') }}" class="px-5 py-3 transition border-b-2 {{ Request::is('/') ? 'text-gold-dignity border-midnight-blue' : 'border-transparent hover:text-gold-dignity hover:border-midnight-blue' }}">Beranda</a>
      
      <div class="relative group">
        <button class="px-5 py-3 transition border-b-2 border-transparent hover:text-gold-dignity hover:border-midnight-blue flex items-center gap-1 uppercase">
          Profil & Info <i data-lucide="chevron-down" class="w-3 h-3"></i>
        </button>
        <div class="absolute left-0 mt-0 w-60 bg-white shadow-[0_20px_50px_rgba(0,33,71,0.1)] border-t-2 border-gold-dignity opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 z-[110]">
          <a href="{{ route('profile') }}" class="block px-6 py-4 hover:bg-soft-grey hover:text-gold-dignity transition-colors border-b border-platinum font-bold text-midnight-blue">PROFIL INSTANSI</a>
          <a href="{{ route('berita.index') }}" class="block px-6 py-4 hover:bg-soft-grey hover:text-gold-dignity transition-colors border-b border-platinum font-bold text-midnight-blue">BERITA TERKINI</a>
          <a href="{{ route('layanan') }}" class="block px-6 py-4 hover:bg-soft-grey hover:text-gold-dignity transition-colors border-b border-platinum font-bold text-midnight-blue">STANDAR PELAYANAN</a>
          <a href="{{ route('galeri') }}" class="block px-6 py-4 hover:bg-soft-grey hover:text-gold-dignity transition-colors font-bold text-midnight-blue">GALERI KEGIATAN</a>
        </div>
      </div>

      <div class="relative group">
        <button class="px-5 py-3 transition border-b-2 border-transparent hover:text-gold-dignity hover:border-midnight-blue flex items-center gap-1 uppercase">
          Layanan Publik <i data-lucide="chevron-down" class="w-3 h-3"></i>
        </button>
        <div class="absolute left-0 mt-0 w-60 bg-white shadow-[0_20px_50px_rgba(0,33,71,0.1)] border-t-2 border-gold-dignity opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 z-[110]">
          <a href="{{ route('kunjungan') }}" class="block px-6 py-4 hover:bg-soft-grey hover:text-gold-dignity transition-colors border-b border-platinum font-bold text-midnight-blue">PENDAFTARAN KUNJUNGAN</a>
          <a href="{{ route('integrasi.login') }}" class="block px-6 py-4 hover:bg-soft-grey hover:text-gold-dignity transition-colors border-b border-platinum font-bold text-midnight-blue">LAYANAN INTEGRASI</a>
          <a href="{{ route('pengaduan') }}" class="block px-6 py-4 hover:bg-soft-grey hover:text-gold-dignity transition-colors border-b border-platinum font-bold text-midnight-blue">PORTAL PENGADUAN</a>
          <a href="{{ route('produk') }}" class="block px-6 py-4 hover:bg-soft-grey hover:text-gold-dignity transition-colors font-bold text-midnight-blue">PRODUK UNGGULAN WBP</a>
        </div>
      </div>
    </nav>

    <button id="menuBtn" class="lg:hidden text-midnight-blue p-2 border border-platinum rounded focus:outline-none hover:bg-platinum transition-colors">
      <i data-lucide="menu"></i>
    </button>
  </div>

  <!-- Mobile Menu -->
  <div id="mobileMenu" class="lg:hidden hidden bg-white border-t border-platinum shadow-lg">
    <nav class="flex flex-col">
      <a href="{{ route('home') }}" class="px-6 py-3 border-b border-platinum hover:bg-platinum transition font-bold text-midnight-blue uppercase text-sm">Beranda</a>
      
      <div class="border-b border-platinum">
        <button onclick="toggleMobileSubmenu('info')" class="w-full px-6 py-4 hover:bg-soft-grey transition font-bold text-midnight-blue uppercase text-xs flex justify-between items-center">
          Profil & Info <i data-lucide="chevron-down" class="w-4 h-4"></i>
        </button>
        <div id="info-submenu" class="hidden bg-soft-grey/50">
          <a href="{{ route('profile') }}" class="block px-10 py-3 hover:bg-soft-grey transition text-midnight-blue font-semibold text-xs border-b border-platinum/30">PROFIL INSTANSI</a>
          <a href="{{ route('berita.index') }}" class="block px-10 py-3 hover:bg-soft-grey transition text-midnight-blue font-semibold text-xs border-b border-platinum/30">BERITA TERKINI</a>
          <a href="{{ route('layanan') }}" class="block px-10 py-3 hover:bg-soft-grey transition text-midnight-blue font-semibold text-xs border-b border-platinum/30">STANDAR PELAYANAN</a>
          <a href="{{ route('galeri') }}" class="block px-10 py-3 hover:bg-soft-grey transition text-midnight-blue font-semibold text-xs">GALERI KEGIATAN</a>
        </div>
      </div>

      <div class="border-b border-platinum">
        <button onclick="toggleMobileSubmenu('layanan')" class="w-full px-6 py-4 hover:bg-soft-grey transition font-bold text-midnight-blue uppercase text-xs flex justify-between items-center">
          Layanan Publik <i data-lucide="chevron-down" class="w-4 h-4"></i>
        </button>
        <div id="layanan-submenu" class="hidden bg-soft-grey/50">
          <a href="{{ route('kunjungan') }}" class="block px-10 py-3 hover:bg-soft-grey transition text-midnight-blue font-semibold text-xs border-b border-platinum/30">PENDAFTARAN KUNJUNGAN</a>
          <a href="{{ route('integrasi.login') }}" class="block px-10 py-3 hover:bg-soft-grey transition text-midnight-blue font-semibold text-xs border-b border-platinum/30">LAYANAN INTEGRASI</a>
          <a href="{{ route('pengaduan') }}" class="block px-10 py-3 hover:bg-soft-grey transition text-midnight-blue font-semibold text-xs border-b border-platinum/30">PORTAL PENGADUAN</a>
          <a href="{{ route('produk') }}" class="block px-10 py-3 hover:bg-soft-grey transition text-midnight-blue font-semibold text-xs">PRODUK UNGGULAN WBP</a>
        </div>
      </div>
    </nav>
  </div>
</header>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.getElementById('menuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (menuBtn && mobileMenu) {
      menuBtn.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
      });
    }
  });

  function toggleMobileSubmenu(id) {
    const submenu = document.getElementById(id + '-submenu');
    if (submenu) {
      submenu.classList.toggle('hidden');
    }
  }
</script>
