@extends('layouts.app')

@section('title', 'Produk Unggulan WBP')

@section('content')
    <!-- Header Section -->
    <!-- Header Section -->
    <section class="relative flex flex-col items-center justify-center text-center px-4 pt-40 pb-20 bg-white border-b border-platinum">
        <div class="relative z-10 max-w-4xl mx-auto w-full">
            <span class="text-gold-dignity font-bold uppercase text-[10px] tracking-[0.3em] mb-4 block">Karya & Kemandirian</span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-midnight-blue mb-4 uppercase tracking-tight leading-tight">Produk Unggulan WBP</h1>
            <p class="text-sm md:text-base text-dark-grey max-w-2xl mx-auto leading-relaxed font-normal">
                Koleksi karya terbaik hasil pembinaan kemandirian Warga Binaan Pemasyarakatan pada Lapas Kelas IIB Lamongan. Mendukung rehabilitasi melalui kreativitas produktif.
            </p>
        </div>
    </section>

    <!-- Content Sections -->
    <div class="bg-soft-grey py-16 px-6 min-h-screen">
        <div class="max-w-6xl mx-auto">
            
            <!-- Category Filter Scripts -->
            <div class="flex flex-wrap justify-center gap-4 mb-16" id="categoryFilters">
                <button onclick="filterProducts('all')" class="filter-btn active px-8 py-3 rounded-full text-xs font-black uppercase tracking-widest bg-midnight-blue text-white shadow-lg shadow-midnight-blue/20 transition-all hover:-translate-y-1">
                    Semua
                </button>
                @php
                    $categories = \App\Models\Produk::distinct('kategori')->pluck('kategori');
                @endphp
                @foreach($categories as $cat)
                    <button onclick="filterProducts('{{ $cat }}')" class="filter-btn px-8 py-3 rounded-full text-xs font-black uppercase tracking-widest bg-white text-midnight-blue border border-platinum hover:border-gold-dignity hover:text-gold-dignity transition-all hover:-translate-y-1">
                        {{ $cat }}
                    </button>
                @endforeach
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" id="productGrid">
                @forelse($produk as $p)
                <div class="product-item group bg-white p-4 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-500 cursor-pointer border border-transparent hover:border-gold-dignity/30 flex flex-col h-full" data-category="{{ $p->kategori }}" onclick='openProductModal(@json($p))'>
                    <!-- Image Wrapper -->
                    <div class="aspect-[4/5] rounded-2xl overflow-hidden relative mb-5 bg-platinum">
                        <img src="{{ $p->gambar_url }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        
                        <!-- Overlay Gradient -->
                        <div class="absolute inset-0 bg-gradient-to-t from-midnight-blue/80 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>
                        
                        <!-- Floating Category -->
                        <div class="absolute top-3 left-3">
                            <span class="bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-wider text-midnight-blue shadow-sm">
                                {{ $p->kategori }}
                            </span>
                        </div>

                        <!-- Action Icon -->
                        <div class="absolute bottom-4 right-4 translate-y-10 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 delay-100">
                            <div class="w-10 h-10 bg-gold-dignity rounded-full flex items-center justify-center text-midnight-blue shadow-lg shadow-gold-dignity/40">
                                <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 flex flex-col px-2 pb-2">
                        <h3 class="text-base font-bold text-midnight-blue leading-tight mb-2 group-hover:text-gold-dignity transition-colors line-clamp-2">
                            {{ $p->nama_produk }}
                        </h3>
                        <div class="w-10 h-0.5 bg-platinum group-hover:bg-gold-dignity transition-colors mb-3"></div>
                        <p class="text-[11px] text-dark-grey/60 line-clamp-2 leading-relaxed mb-4">
                            {{ $p->deskripsi }}
                        </p>
                        <div class="mt-auto flex flex-col gap-2">
                           <a href="https://wa.me/6282142565696?text={{ urlencode('Halo Admin, saya tertarik dengan produk *'.$p->nama_produk.'*. Apakah masih tersedia?') }}" target="_blank" onclick="event.stopPropagation()" class="w-full bg-midnight-blue text-white py-3 rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-gold-dignity hover:text-midnight-blue transition-all shadow-lg">
                                <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i>
                                Pesan Sekarang
                           </a>
                           <div class="flex items-center justify-between mt-1">
                                <span class="text-[9px] font-bold text-dark-grey/30 uppercase tracking-widest">Lihat Detail</span>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-1.5 h-1.5 bg-gold-dignity rounded-full animate-pulse"></div>
                                    <span class="text-[8px] font-black text-gold-dignity uppercase tracking-tighter">Ready Stock</span>
                                </div>
                           </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-32 text-center">
                    <div class="inline-block p-6 rounded-full bg-platinum/50 mb-6">
                        <i data-lucide="package-open" class="w-10 h-10 text-dark-grey/30"></i>
                    </div>
                    <div class="text-sm font-bold text-dark-grey/40 uppercase tracking-[0.2em]">Belum Ada Produk Tersedia</div>
                </div>
                @endforelse
            </div>

            <!-- Pre-Order Banner -->
            <div class="mt-24 bg-midnight-blue rounded-[2rem] p-10 md:p-16 text-center relative overflow-hidden shadow-2xl shadow-midnight-blue/20">
                <!-- Decorative Elements -->
                <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-10">
                    <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-gold-dignity blur-3xl"></div>
                    <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-blue-500 blur-3xl"></div>
                </div>
                
                <div class="relative z-10 max-w-2xl mx-auto">
                    <span class="inline-block px-4 py-1.5 rounded-full border border-white/20 text-gold-dignity text-[9px] font-black uppercase tracking-[0.2em] mb-6">
                        Dukungan Pembinaan
                    </span>
                    <h2 class="text-2xl md:text-4xl font-bold text-white mb-6 leading-tight">
                        Bangga Menggunakan Produk<br>Karya Warga Binaan
                    </h2>
                    <p class="text-sm text-platinum/60 leading-loose mb-10 font-normal">
                        Setiap produk yang Anda miliki adalah bukti nyata dukungan terhadap proses rehabilitasi dan kemandirian saudara-saudara kita di Lapas Kelas IIB Lamongan.
                    </p>
                    <a href="https://wa.me/6281234567890" target="_blank" class="inline-flex items-center gap-3 bg-gold-dignity hover:bg-white text-midnight-blue text-xs font-black uppercase tracking-[0.2em] px-8 py-4 rounded-xl transition-all shadow-lg hover:shadow-gold-dignity/20 transform hover:-translate-y-1">
                        <span>Pemesanan Khusus</span>
                        <i data-lucide="message-square" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- MODERN PRODUCT MODAL -->
    <div id="productModal" class="fixed inset-0 z-[999] hidden" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-midnight-blue/95 backdrop-blur-sm transition-opacity duration-300 opacity-0" id="modalBackdrop" onclick="closeProductModal()"></div>
        
        <!-- Modal Content -->
        <div class="relative z-10 flex items-center justify-center min-h-screen p-4 pointer-events-none">
            <div class="bg-white w-full max-w-4xl rounded-[2rem] overflow-hidden shadow-2xl transform transition-all duration-500 translate-y-20 opacity-0 pointer-events-auto" id="modalContent">
                <button onclick="closeProductModal()" class="absolute top-4 right-4 z-20 w-10 h-10 rounded-full bg-white/10 hover:bg-black/5 flex items-center justify-center text-midnight-blue transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>

                <div class="grid grid-cols-1 md:grid-cols-2 min-h-[500px]">
                    <!-- Image Section -->
                    <div class="bg-platinum relative h-64 md:h-full overflow-hidden group">
                        <img id="modalImage" src="" alt="Product Image" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                        <!-- Gradient Overlay -->
                        <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-black/50 to-transparent"></div>
                        <div class="absolute bottom-6 left-6 text-white">
                             <div class="inline-block px-3 py-1 rounded-md bg-gold-dignity text-midnight-blue text-[9px] font-black uppercase tracking-widest mb-2" id="modalCategory">
                                KATEGORI
                            </div>
                        </div>
                    </div>

                    <!-- Details Section -->
                    <div class="p-8 md:p-12 flex flex-col bg-white">
                        <div class="mb-auto">
                            <h2 id="modalTitle" class="text-2xl md:text-3xl font-bold text-midnight-blue mb-6 leading-tight">Nama Produk</h2>
                            <div class="w-16 h-1.5 bg-gold-dignity mb-8 rounded-full"></div>
                            
                            <h4 class="text-xs font-black text-dark-grey/40 uppercase tracking-widest mb-3">Deskripsi Produk</h4>
                            <p id="modalDescription" class="text-sm text-dark-grey leading-relaxed font-normal overflow-y-auto max-h-[200px] pr-4 scrollbar-thin scrollbar-thumb-platinum scrollbar-track-transparent">
                                Deskripsi...
                            </p>
                        </div>

                        <div class="mt-8 pt-8 border-t border-platinum">
                            <a id="modalWhatsapp" href="#" target="_blank" class="flex items-center justify-center gap-3 w-full bg-midnight-blue hover:bg-gold-dignity text-white hover:text-midnight-blue py-4 rounded-xl shadow-lg transition-all transform hover:-translate-y-1 group">
                                <i data-lucide="phone" class="w-5 h-5 fill-current"></i>
                                <span class="text-xs font-black uppercase tracking-[0.2em]">Hubungi via WhatsApp</span>
                            </a>
                            <p class="text-center text-[10px] text-dark-grey/40 mt-3 font-medium">
                                Tanya ketersediaan & detail pesanan langsung ke Admin
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Filter Scripts
        function filterProducts(category) {
            // Update Buttons
            document.querySelectorAll('.filter-btn').forEach(btn => {
                if(btn.innerText.toLowerCase() === category.toLowerCase() || (category === 'all' && btn.innerText.toLowerCase() === 'semua')) {
                    btn.classList.remove('bg-white', 'text-midnight-blue', 'border-platinum');
                    btn.classList.add('bg-midnight-blue', 'text-white', 'shadow-lg');
                } else {
                    btn.classList.add('bg-white', 'text-midnight-blue', 'border-platinum');
                    btn.classList.remove('bg-midnight-blue', 'text-white', 'shadow-lg');
                }
            });

            // Filter Items
            const items = document.querySelectorAll('.product-item');
            items.forEach(item => {
                if(category === 'all' || item.dataset.category === category) {
                    item.style.display = 'flex';
                    setTimeout(() => {
                         item.classList.remove('opacity-0', 'scale-95');
                         item.classList.add('opacity-100', 'scale-100');
                    }, 50);
                } else {
                    item.classList.add('opacity-0', 'scale-95');
                    item.classList.remove('opacity-100', 'scale-100');
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        }

        // Modal Scripts
        function openProductModal(product) {
            // Populate Data
            document.getElementById('modalTitle').innerText = product.nama_produk;
            document.getElementById('modalCategory').innerText = product.kategori;
            document.getElementById('modalDescription').innerText = product.deskripsi;
            
            // Image
            const imgPath = product.gambar.startsWith('http') ? product.gambar : "{{ asset('uploads') }}/" + product.gambar;
            document.getElementById('modalImage').src = imgPath;

            // Whatsapp Link - Without Price
            const message = `Halo Admin Lapas Lamongan, saya tertarik dengan produk *${product.nama_produk}* (Kategori: ${product.kategori}). Mohon info detailnya.`;
            const waLink = `https://wa.me/6282142565696?text=${encodeURIComponent(message)}`; 
            document.getElementById('modalWhatsapp').href = waLink;

            // Show Modal
            const modal = document.getElementById('productModal');
            const backdrop = document.getElementById('modalBackdrop');
            const content = document.getElementById('modalContent');
            
            modal.classList.remove('hidden');
            
            // Animation
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                content.classList.remove('translate-y-20', 'opacity-0');
            }, 10);
            
            document.body.style.overflow = 'hidden'; 
        }

        function closeProductModal() {
            const modal = document.getElementById('productModal');
            const backdrop = document.getElementById('modalBackdrop');
            const content = document.getElementById('modalContent');

            backdrop.classList.add('opacity-0');
            content.classList.add('translate-y-20', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto'; 
            }, 300);
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") closeProductModal();
        });
    </script>
@endsection
