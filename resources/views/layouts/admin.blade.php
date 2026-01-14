<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Admin Lapas Lamongan</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo_imigrasi.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/logo_imigrasi.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/logo_imigrasi.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'midnight-blue': '#002147',
                        'navy-accent': '#003366',
                        'dark-grey': '#333333',
                        'soft-grey': '#F5F7F9',
                        'gold-dignity': '#C5A059',
                        'platinum': '#E1E4E8'
                    },
                    fontFamily: { 
                        sans: ['"Inter"', 'sans-serif'] 
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --font-main: 'Inter', sans-serif;
            --touch-target: 44px;
        }

        [x-cloak] { display: none !important; }

        html {
            font-size: 14px; /* Base font size */
        }

        @media (min-width: 1024px) {
            html { font-size: 15px; } /* Slightly larger on desktop */
        }

        body { 
            font-family: var(--font-main);
            color: #333333;
            background-color: #F5F7F9;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
        }

        /* Typography Scaling with Clamp */
        h1 { font-size: clamp(1.25rem, 4vw, 1.75rem); }
        h3 { font-size: clamp(1rem, 2.5vw, 1.1rem); }

        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #C5A059; border-radius: 10px; }
        
        /* Universal Responsive Card */
        .admin-card {
            background: white;
            border: 1px solid #E1E4E8;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        /* Responsive & Scrollable Table */
        .table-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 4px;
        }
        
        table thead th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 800;
            color: #002147;
            padding: 1.25rem 1rem;
            border-bottom: 2px solid #F0F2F5;
            white-space: nowrap;
        }
        
        table tbody td {
            font-size: 0.875rem;
            padding: 1rem;
            border-bottom: 1px solid #F0F2F5;
            vertical-align: middle;
        }

        /* Touch-Friendly Buttons (Min 44px) */
        .btn-compact {
            min-height: var(--touch-target);
            padding: 0 1.25rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-radius: 4px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        /* Scroll Reveal Styles */
        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.8s cubic-bezier(0.25, 1, 0.5, 1), transform 0.8s cubic-bezier(0.25, 1, 0.5, 1);
            will-change: opacity, transform;
        }
        .reveal-on-scroll.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        /* Mobile Adjustments */
        @media (max-width: 640px) {
            .admin-card { padding: 1rem !important; }
            .mobile-hide { display: none; }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
</head>
<body x-data="{ 
    sidebarOpen: false, 
    sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true'
}" 
x-init="$watch('sidebarCollapsed', value => localStorage.setItem('sidebarCollapsed', value))"
class="antialiased">
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#002147',
                confirmButtonText: 'Mantap!'
            });
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Gagal!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Coba Lagi'
            });
        });
    </script>
    @endif

    @if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Periksa Inputan!',
                html: '<ul class="text-left">@foreach($errors->all() as $error)<li>• {{ $error }}</li>@endforeach</ul>',
                icon: 'warning',
                confirmButtonColor: '#002147'
            });
        });
    </script>
    @endif
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Overlay (Mobile) -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
            class="fixed inset-0 bg-midnight-blue/40 backdrop-blur-sm z-40 lg:hidden"></div>

        <!-- Sidebar -->
        <aside 
            :class="{
                'translate-x-0': sidebarOpen,
                '-translate-x-full': !sidebarOpen,
                'lg:w-64': !sidebarCollapsed,
                'lg:w-20': sidebarCollapsed
            }"
            class="fixed inset-y-0 left-0 bg-midnight-blue text-white z-50 lg:static lg:translate-x-0 transition-all duration-300 flex flex-col flex-shrink-0 shadow-2xl lg:shadow-none overflow-hidden">
            
            <div class="h-16 flex items-center justify-between px-6 border-b border-white/5 overflow-hidden">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gold-dignity rounded flex items-center justify-center flex-shrink-0">
                        <i data-lucide="shield" class="w-5 h-5 text-midnight-blue"></i>
                    </div>
                    <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="font-black text-sm uppercase tracking-tighter whitespace-nowrap">Lapas Lamongan</span>
                </div>
                <!-- Desktop Toggle Button -->
                <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:flex text-white/50 hover:text-white transition-colors">
                    <i :data-lucide="sidebarCollapsed ? 'chevron-right' : 'chevron-left'" class="w-4 h-4"></i>
                </button>
                <button @click="sidebarOpen = false" class="lg:hidden text-white/50 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 custom-scrollbar overflow-x-hidden">
                <div x-show="!sidebarCollapsed" class="px-3 py-2 text-[10px] font-bold text-white/30 uppercase tracking-widest">Main Menu</div>
                
                <a href="{{ route('admin.dashboard') }}" 
                    :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'"
                    class="flex items-center gap-3 py-2.5 rounded {{ Request::is('admin/dashboard') ? 'bg-gold-dignity text-midnight-blue' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} font-bold text-[13px] transition-all"
                    title="Dashboard">
                    <i data-lucide="layout-grid" class="w-4 h-4 flex-shrink-0"></i> 
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Dashboard</span>
                </a>

                @canAny(['super', 'humas'])
                <div x-show="!sidebarCollapsed" class="pt-4 px-3 py-2 text-[10px] font-bold text-white/30 uppercase tracking-widest">Konten Media</div>
                <a href="{{ route('admin.berita.index') }}" 
                    :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'"
                    class="flex items-center gap-3 py-2.5 rounded {{ Request::is('admin/berita*') ? 'bg-gold-dignity text-midnight-blue' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} font-bold text-[13px] transition-all"
                    title="Berita Terkini">
                    <i data-lucide="newspaper" class="w-4 h-4 flex-shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Berita Terkini</span>
                </a>
                <a href="{{ route('admin.galeri.index') }}" 
                    :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'"
                    class="flex items-center gap-3 py-2.5 rounded {{ Request::is('admin/galeri*') ? 'bg-gold-dignity text-midnight-blue' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} font-bold text-[13px] transition-all"
                    title="Galeri Foto">
                    <i data-lucide="image" class="w-4 h-4 flex-shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Galeri Foto</span>
                </a>
                <a href="{{ route('admin.informasi.index') }}" 
                    :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'"
                    class="flex items-center gap-3 py-2.5 rounded {{ Request::is('admin/informasi*') ? 'bg-gold-dignity text-midnight-blue' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} font-bold text-[13px] transition-all"
                    title="Running Text">
                    <i data-lucide="info" class="w-4 h-4 flex-shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Running Text</span>
                </a>
                <a href="{{ route('admin.produk.index') }}" 
                    :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'"
                    class="flex items-center gap-3 py-2.5 rounded {{ Request::is('admin/produk*') ? 'bg-gold-dignity text-midnight-blue' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} font-bold text-[13px] transition-all"
                    title="Produk Unggulan">
                    <i data-lucide="package" class="w-4 h-4 flex-shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Produk Unggulan</span>
                </a>
                @endcan

                @canAny(['super', 'layanan'])
                <div x-show="!sidebarCollapsed" class="pt-4 px-3 py-2 text-[10px] font-bold text-white/30 uppercase tracking-widest">Layanan Utama</div>
                <a href="{{ route('admin.kunjungan.index') }}" 
                    :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'"
                    class="flex items-center gap-3 py-2.5 rounded {{ Request::is('admin/kunjungan*') ? 'bg-gold-dignity text-midnight-blue' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} font-bold text-[13px] transition-all"
                    title="Antrean Kunjungan">
                    <i data-lucide="users" class="w-4 h-4 flex-shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Antrean Kunjungan</span>
                </a>
                <a href="{{ route('admin.integrasi.index') }}" 
                    :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'"
                    class="flex items-center gap-3 py-2.5 rounded {{ Request::is('admin/integrasi*') ? 'bg-gold-dignity text-midnight-blue' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} font-bold text-[13px] transition-all"
                    title="Data Integrasi">
                    <i data-lucide="file-check" class="w-4 h-4 flex-shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Data Integrasi</span>
                </a>
                <a href="{{ route('admin.hunian.index') }}" 
                    :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'"
                    class="flex items-center gap-3 py-2.5 rounded {{ Request::is('admin/hunian*') ? 'bg-gold-dignity text-midnight-blue' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} font-bold text-[13px] transition-all"
                    title="Statistik Hunian">
                    <i data-lucide="database" class="w-4 h-4 flex-shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Statistik Hunian</span>
                </a>
                <a href="{{ route('admin.laporan.index') }}" 
                    :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'"
                    class="flex items-center gap-3 py-2.5 rounded {{ Request::is('admin/laporan') ? 'bg-gold-dignity text-midnight-blue' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} font-bold text-[13px] transition-all"
                    title="Eksport Laporan">
                    <i data-lucide="printer" class="w-4 h-4 flex-shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Eksport Laporan</span>
                </a>
                <a href="{{ route('admin.laporan.traffic') }}" 
                    :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'"
                    class="flex items-center gap-3 py-2.5 rounded {{ Request::is('admin/laporan/traffic') ? 'bg-gold-dignity text-midnight-blue' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} font-bold text-[13px] transition-all"
                    title="Statistik Traffic">
                    <i data-lucide="bar-chart-3" class="w-4 h-4 flex-shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Statistik Traffic</span>
                </a>
                @endcan

                @canAny(['super', 'pengaduan'])
                <div x-show="!sidebarCollapsed" class="pt-4 px-3 py-2 text-[10px] font-bold text-white/30 uppercase tracking-widest">Komunikasi</div>
                <a href="{{ route('admin.pengaduan.index') }}" 
                    :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'"
                    class="flex items-center gap-3 py-2.5 rounded {{ Request::is('admin/pengaduan*') ? 'bg-gold-dignity text-midnight-blue' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} font-bold text-[13px] transition-all"
                    title="Pengaduan WBS">
                    <i data-lucide="message-square" class="w-4 h-4 flex-shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Pengaduan WBS</span>
                </a>
                <a href="{{ route('admin.rating.index') }}" 
                    :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'"
                    class="flex items-center gap-3 py-2.5 rounded {{ Request::is('admin/rating*') ? 'bg-gold-dignity text-midnight-blue' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} font-bold text-[13px] transition-all"
                    title="Respon Layanan">
                    <i data-lucide="heart" class="w-4 h-4 flex-shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Respon Layanan</span>
                </a>
                @endcan

                <div x-show="!sidebarCollapsed" class="pt-4 px-3 py-2 text-[10px] font-bold text-white/30 uppercase tracking-widest">Pengaturan</div>
                @can('super')
                <a href="{{ route('admin.manage-admin.index') }}" 
                    :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'"
                    class="flex items-center gap-3 py-2.5 rounded {{ Request::is('admin/manage-admin*') ? 'bg-gold-dignity text-midnight-blue' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} font-bold text-[13px] transition-all"
                    title="Kelola Admin">
                    <i data-lucide="user-cog" class="w-4 h-4 flex-shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Kelola Admin</span>
                </a>
                <a href="{{ route('admin.manage-users.index') }}" 
                    :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'"
                    class="flex items-center gap-3 py-2.5 rounded {{ Request::is('admin/manage-users*') ? 'bg-gold-dignity text-midnight-blue' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} font-bold text-[13px] transition-all"
                    title="Kelola User Login">
                    <i data-lucide="user-check" class="w-4 h-4 flex-shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Kelola User Login</span>
                </a>
                @endcan
                <a href="{{ route('admin.profil.index') }}" 
                    :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'"
                    class="flex items-center gap-3 py-2.5 rounded {{ Request::is('admin/profil*') ? 'bg-gold-dignity text-midnight-blue' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} font-bold text-[13px] transition-all"
                    title="Profil Instansi">
                    <i data-lucide="settings" class="w-4 h-4 flex-shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Profil Instansi</span>
                </a>
                <a href="{{ route('admin.survey.index') }}" 
                    :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'"
                    class="flex items-center gap-3 py-2.5 rounded {{ Request::is('admin/survey*') ? 'bg-gold-dignity text-midnight-blue' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} font-bold text-[13px] transition-all"
                    title="Indeks Kepuasan">
                    <i data-lucide="star" class="w-4 h-4 flex-shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Indeks Kepuasan</span>
                </a>
            </nav>

            <div class="p-4 border-t border-white/5 overflow-hidden">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                        :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'"
                        class="w-full flex items-center gap-3 py-2.5 text-red-400 hover:bg-red-500/10 rounded font-bold text-[13px] transition-all">
                        <i data-lucide="log-out" class="w-4 h-4 flex-shrink-0"></i> 
                        <span x-show="!sidebarCollapsed" class="whitespace-nowrap">Logout Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Navbar -->
            <header class="h-16 bg-white border-b border-platinum flex items-center justify-between px-6 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-midnight-blue hover:bg-soft-grey rounded-lg">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <div class="hidden sm:flex relative" x-data="{ open: false, search: '', results: [] }">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                        <input type="text" x-model="search" @focus="open = true" @click.away="open = false"
                            @input.debounce.300ms="
                                if(search.length > 2) {
                                    fetch('{{ route('admin.search') }}?q=' + search)
                                    .then(res => res.json())
                                    .then(data => results = data)
                                } else { results = [] }
                            "
                            class="bg-soft-grey border-none w-64 lg:w-80 pl-10 pr-4 py-2 rounded text-[13px] placeholder-slate-400 focus:ring-1 focus:ring-gold-dignity transition-all"
                            placeholder="Cari data cepat...">
                        
                        <div x-show="open && results.length > 0" x-cloak
                            class="absolute top-full mt-2 left-0 right-0 bg-white rounded shadow-xl border border-platinum p-2 z-50">
                            <template x-for="res in results">
                                <a :href="res.url" class="flex items-center gap-3 p-2 hover:bg-soft-grey rounded transition-all">
                                    <div class="w-8 h-8 bg-midnight-blue text-white rounded flex items-center justify-center flex-shrink-0">
                                        <i :data-lucide="res.icon" class="w-4 h-4"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="text-[12px] font-extrabold text-midnight-blue truncate" x-text="res.title"></p>
                                        <p class="text-[10px] text-slate-400 font-medium truncate" x-text="res.type"></p>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-[12px] font-extrabold text-midnight-blue leading-none">{{ Auth::user()->nama }}</p>
                        <p class="text-[10px] text-gold-dignity font-bold uppercase tracking-widest mt-1">{{ Auth::user()->role }}</p>
                    </div>
                    <div class="w-10 h-10 rounded bg-midnight-blue flex items-center justify-center font-black text-white text-sm">
                        {{ substr(Auth::user()->nama, 0, 1) }}
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6 custom-scrollbar">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-xl font-black text-midnight-blue uppercase tracking-tight">@yield('page_title', 'Dashboard Overview')</h1>
                    <div class="flex gap-2">
                        @yield('header_actions')
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-6 p-3 bg-emerald-50 border border-emerald-100 text-emerald-700 text-[12px] font-bold rounded flex items-center gap-3">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();

            // Global Delete Confirmation
            document.querySelectorAll('form').forEach(form => {
                const deleteMethod = form.querySelector('input[name="_method"][value="DELETE"]');
                const isDeleteForm = form.classList.contains('delete-form');
                
                if (deleteMethod || isDeleteForm) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const form = this;
                        
                        Swal.fire({
                            title: 'Apakah Anda Yakin?',
                            text: "Data yang dihapus tidak dapat dikembalikan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal',
                            background: '#fff',
                            color: '#002147',
                            customClass: {
                                popup: 'rounded-xl border border-slate-200 shadow-2xl',
                                confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded',
                                cancelButton: 'bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2 px-4 rounded ml-2'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                }
            });

            // Intersection Observer for Scroll Reveal
            const observerOptions = {
                threshold: 0.1,
                rootMargin: "0px 0px -30px 0px"
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.reveal-on-scroll').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>
