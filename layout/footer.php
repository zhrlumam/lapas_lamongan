

  <style>
    .safe-area-bottom { padding-bottom: env(safe-area-inset-bottom); }
  </style>

  <!-- Floating Support Button -->
  <a href="https://wa.me/628113405959" target="_blank" class="fixed bottom-32 md:bottom-24 right-6 md:right-8 bg-emerald-500 text-white w-12 h-12 rounded-full shadow-2xl flex items-center justify-center transition-all duration-300 z-[99] hover:scale-110 active:scale-95 group">
    <i data-lucide="message-circle" class="w-6 h-6"></i>
    <span class="absolute right-full mr-3 bg-white text-slate-800 text-[10px] font-bold px-3 py-1.5 rounded-lg shadow-xl opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap uppercase tracking-widest border border-slate-100">Hubungi Kami</span>
  </a>

  <footer class="bg-imipas-blue text-white border-t-8 border-imipas-gold">
    <div class="max-w-7xl mx-auto px-6 py-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
      <div class="lg:col-span-2">
        <img src="assets/images/logolap.png" class="h-14 mb-6 grayscale brightness-200">
        <h5 class="text-lg font-bold mb-2 uppercase text-imipas-gold">Lapas Kelas IIB Lamongan</h5>
        <p class="text-imipas-platinum text-sm leading-relaxed max-w-md mb-6">
          Memberikan pelayanan prima dan pembinaan berkesinambungan bagi warga binaan demi tercapainya tujuan pemasyarakatan yang berintegritas sesuai standar Kementerian Imigrasi dan Pemasyarakatan.
        </p>
        <!-- Social Media Icons (Footer) -->
        <div class="flex gap-4">
            <a href="https://facebook.com" class="w-10 h-10 bg-white/10 hover:bg-imipas-gold hover:text-imipas-blue text-white rounded-lg flex items-center justify-center transition-all">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://instagram.com" class="w-10 h-10 bg-white/10 hover:bg-imipas-gold hover:text-imipas-blue text-white rounded-lg flex items-center justify-center transition-all">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://twitter.com" class="w-10 h-10 bg-white/10 hover:bg-imipas-gold hover:text-imipas-blue text-white rounded-lg flex items-center justify-center transition-all">
                <i class="fab fa-twitter"></i>
            </a>
            <a href="https://youtube.com" class="w-10 h-10 bg-white/10 hover:bg-imipas-gold hover:text-imipas-blue text-white rounded-lg flex items-center justify-center transition-all">
                <i class="fab fa-youtube"></i>
            </a>
        </div>
      </div>
      <div>
        <img src="assets/images/logo_imigrasi.png" class="h-12 mb-4 drop-shadow-lg" alt="Logo Imigrasi">
        <h6 class="text-[10px] font-bold uppercase tracking-widest mb-6 text-imipas-gold">Tautan Resmi</h6>
        <ul class="text-imipas-platinum space-y-3 text-[11px] font-semibold uppercase">
          <li><a href="https://kemenimipas.go.id/" class="hover:text-imipas-gold transition">Kemenimipas RI</a></li>
          <li><a href="https://www.ditjenpas.go.id/" class="hover:text-imipas-gold transition">Ditjen Pemasyarakatan</a></li>
          <li><a href="https://jatim.kemenkum.go.id/" class="hover:text-imipas-gold transition">Kanwil Jatim</a></li>
        </ul>
      </div>
      <div>
        <h6 class="text-[10px] font-bold uppercase tracking-widest mb-6 text-imipas-gold">Kontak Instansi</h6>
        <div class="text-imipas-platinum text-[11px] space-y-3 font-semibold uppercase">
          <p class="flex items-center gap-2">Telp: 0811-3405-959</p>
          <p class="flex items-center gap-2">Email: lapaslamongan@gmail.com</p>
          <p class="flex items-center gap-2">Alamat: Jl. Veteran No.01, Lamongan</p>
        </div>
      </div>
    </div>
    <div class="bg-[#041629] py-6 text-center px-4">
      <p class="text-[8px] md:text-[9px] text-slate-500 tracking-[0.4em] uppercase leading-loose">
        Hak Cipta © 2025 Lembaga Pemasyarakatan Kelas IIB Lamongan - Kanwil Kemenimipas Jatim
      </p>
    </div>
  </footer>
  <!-- Back to Top Button -->
  <button id="backToTop" class="fixed bottom-8 right-8 bg-imipas-gold text-imipas-blue w-12 h-12 rounded-full shadow-2xl flex items-center justify-center transition-all duration-300 translate-y-20 opacity-0 z-[99] hover:scale-110 active:scale-95">
    <i data-lucide="arrow-up" class="w-6 h-6"></i>
  </button>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    const btt = document.getElementById('backToTop');
    window.addEventListener('scroll', () => {
      if (window.scrollY > 300) {
        btt.classList.remove('translate-y-20', 'opacity-0');
      } else {
        btt.classList.add('translate-y-20', 'opacity-0');
      }
    });
    btt.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    
    // Inisialisasi Lucide Icons jika tersedia
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
  </script>
  <script src="assets/js/feedback.js"></script>