<footer class="bg-midnight-blue text-white border-t border-platinum mt-auto">
    <div class="max-w-6xl mx-auto px-6 py-20 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16">
      <div class="lg:col-span-2">
        <img src="{{ asset('assets/logolap.png') }}" class="h-12 mb-8 grayscale brightness-200">
        <h5 class="text-xs font-bold mb-4 uppercase text-gold-dignity tracking-widest">Lembaga Pemasyarakatan Kelas IIB Lamongan</h5>
        <p class="text-platinum/60 text-[13px] leading-loose max-w-md mb-8">
            Berdedikasi untuk memberikan pelayanan publik yang transparan dan pembinaan kemandirian yang berintegritas bagi warga binaan pemasyarakatan.
        </p>
        
        <!-- Social Media Icons -->
        <div class="flex gap-4">
            <a href="https://www.instagram.com/lapaslamongan_official/" target="_blank" class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center hover:bg-gold-dignity hover:text-midnight-blue transition-all group" title="Follow us on Instagram">
                <i data-lucide="instagram" class="w-4 h-4 text-platinum group-hover:text-midnight-blue"></i>
            </a>
            <a href="https://www.facebook.com/lapaslamonganofficial" target="_blank" class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center hover:bg-gold-dignity hover:text-midnight-blue transition-all group" title="Follow us on Facebook">
                <i data-lucide="facebook" class="w-4 h-4 text-platinum group-hover:text-midnight-blue"></i>
            </a>
            <a href="https://twitter.com/lapas_lamongan" target="_blank" class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center hover:bg-gold-dignity hover:text-midnight-blue transition-all group" title="Follow us on Twitter">
                <i data-lucide="twitter" class="w-4 h-4 text-platinum group-hover:text-midnight-blue"></i>
            </a>
            <a href="https://www.youtube.com/@lapaslamonganofficial" target="_blank" class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center hover:bg-gold-dignity hover:text-midnight-blue transition-all group" title="Subscribe to our YouTube">
                <i data-lucide="youtube" class="w-4 h-4 text-platinum group-hover:text-midnight-blue"></i>
            </a>
        </div>
      </div>
      <div>
        <h6 class="text-[10px] font-black uppercase tracking-[0.2em] mb-8 text-gold-dignity">Navigasi Utama</h6>
        <ul class="text-platinum/50 space-y-4 text-[10px] font-bold uppercase tracking-widest">
            <li><a href="{{ route('profile') }}" class="hover:text-gold-dignity transition">Profil Instansi</a></li>
            <li><a href="{{ route('berita.index') }}" class="hover:text-gold-dignity transition">Pusat Berita</a></li>
            <li><a href="{{ route('layanan') }}" class="hover:text-gold-dignity transition">Maklumat Pelayanan</a></li>
            <li><a href="{{ route('galeri') }}" class="hover:text-gold-dignity transition">Galeri Kegiatan</a></li>
        </ul>
      </div>
      <div>
        <h6 class="text-[10px] font-black uppercase tracking-[0.2em] mb-8 text-gold-dignity">Hubungi Kami</h6>
        <div class="text-platinum/50 text-[10px] space-y-4 font-bold uppercase tracking-widest leading-relaxed">
          <p>Telepon: 0811-3405-959</p>
          <p>Email: lapaslamongan@gmail.com</p>
          <p>JL. Sumargo, No.19, Kec. Lamongan</p>
        </div>
      </div>
    </div>
    <div class="bg-black/10 py-8 text-center px-4 border-t border-white/5">
      <p class="text-[9px] text-platinum/30 tracking-[0.3em] uppercase">
        © {{ date('Y') }} Lembaga Pemasyarakatan Kelas IIB Lamongan. Hak cipta dilindungi undang-undang.
      </p>
    </div>
</footer>
