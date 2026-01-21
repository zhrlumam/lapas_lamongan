<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Lapas Kelas IIB Lamongan</title>
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#C5A059">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="SIPAS Lamongan">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    
    <!-- Favicons & Icons -->
    <link rel="icon" type="image/png" href="{{ asset('assets/logo_imigrasi.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/logo_imigrasi.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/logo_imigrasi.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="96x96" href="{{ asset('assets/icon-96x96.png') }}">
    <link rel="apple-touch-icon" sizes="128x128" href="{{ asset('assets/icon-128x128.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('assets/icon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="384x384" href="{{ asset('assets/icon-384x384.png') }}">
    <link rel="apple-touch-icon" sizes="512x512" href="{{ asset('assets/icon-512x512.png') }}">
    
    <!-- SEO Meta Tags -->
    @include('partials.seo', [
        'page' => $seoPage ?? 'home',
        'seoData' => $seoData ?? [],
        'structuredData' => $structuredData ?? null
    ])
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.tailwindcss.com">
    <link rel="dns-prefetch" href="https://unpkg.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"></noscript>

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
                    },
                    fontSize: {
                        'base': '16px',
                        'headline': ['34px', { lineHeight: '1.2', fontWeight: '800' }],
                        'sub-headline': ['24px', { lineHeight: '1.3', fontWeight: '700' }],
                        'compact': ['14px', { lineHeight: '1.5' }]
                    },
                    animation: {
                        'marquee': 'marquee 40s linear infinite',
                    },
                    keyframes: {
                        marquee: {
                            '0%': { transform: 'translateX(100vw)' },
                            '100%': { transform: 'translateX(-100%)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --midnight-blue: #002147;
            --gold-dignity: #C5A059;
            --touch-target: 44px;
        }

        html {
            font-size: 14px; /* Default for mobile */
            scroll-behavior: smooth;
        }

        @media (min-width: 768px) {
            html { font-size: 15px; }
        }

        @media (min-width: 1280px) {
            html { font-size: 16px; }
        }

        body {
            color: #333333;
            line-height: 1.6;
            letter-spacing: -0.01em;
            -webkit-font-smoothing: antialiased;
        }

        /* Fluid Typography Scaling */
        h1, .text-headline { font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 900; line-height: 1.1; }
        h2, .text-sub-headline { font-size: clamp(1.5rem, 4vw, 2.5rem); font-weight: 800; line-height: 1.2; }
        
        .animate-marquee {
            display: inline-block;
            white-space: nowrap;
            animation: marquee 40s linear infinite;
        }
        @keyframes marquee {
            from { transform: translateX(100vw); }
            to { transform: translateX(-100%); }
        }

        .glass-header {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 33, 71, 0.08);
        }

        /* Touch-Friendly Navigation & Buttons */
        .nav-link, .btn-action {
            min-height: var(--touch-target);
            display: inline-flex;
            align-items: center;
        }

        #backToTop {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 3.5rem;
            height: 3.5rem;
            background-color: var(--midnight-blue);
            color: #ffffff;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 999;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        #backToTop.show { opacity: 1; visibility: visible; }
        #backToTop:hover { background-color: var(--gold-dignity); transform: translateY(-5px); }

        /* Responsive Table Wrapper */
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
        }

        /* Scroll Reveal Styles */
        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 1s cubic-bezier(0.2, 0.8, 0.2, 1), transform 1s cubic-bezier(0.2, 0.8, 0.2, 1);
            will-change: opacity, transform;
        }
        .reveal-on-scroll.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        /* Alpine.js Cloak */
        [x-cloak] { display: none !important; }

        /* Skeleton Loading */
        @keyframes pulse-grey {
            0%, 100% { background-color: #f1f5f9; }
            50% { background-color: #e2e8f0; }
        }
        .skeleton {
            animation: pulse-grey 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @media (max-width: 640px) {
            #backToTop { bottom: 1.5rem; right: 1.5rem; width: 3rem; height: 3rem; }
            section { padding-top: 4rem; padding-bottom: 4rem; }
        }

        /* Global Page Loader */
        #pageLoader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 33, 71, 0.95);
            backdrop-filter: blur(8px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        #pageLoader.hidden {
            opacity: 0;
            visibility: hidden;
        }
        .loader-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid rgba(197, 160, 89, 0.2);
            border-top-color: #C5A059;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .loader-text {
            margin-top: 1rem;
            color: #C5A059;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
    </style>
</head>
<body class="bg-white text-dark-grey antialiased font-sans flex flex-col min-h-screen overflow-x-hidden">

    <!-- Global Page Loader -->
    <div id="pageLoader">
        <div class="loader-spinner"></div>
        <p class="loader-text">Loading...</p>
    </div>

    @include('partials.navbar')

    <main class="flex-grow">
        @yield('content')
    </main>

    <button id="backToTop" aria-label="Kembali ke atas">
        <i data-lucide="arrow-up" class="w-5 h-5"></i>
    </button>

    @include('partials.footer')
    
    <!-- AI Chat Assistant -->
    @include('partials.chatbot')

    <!-- Accessibility Helper -->
    @include('partials.accessibility')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            
            const header = document.getElementById('mainHeader');
            const backToTop = document.getElementById('backToTop');

            window.addEventListener('scroll', () => {
                // Navbar Scroll Logic
                const isHomePage = window.location.pathname === '/' || window.location.pathname === '/public/' || window.location.pathname.endsWith('index.php');
                
                if (window.scrollY > 50 || !isHomePage) {
                    if (header) {
                        header.classList.remove('bg-white/0', 'backdrop-blur-none', 'shadow-none', 'py-4');
                        header.classList.add('bg-white/95', 'backdrop-blur-md', 'shadow-md', 'py-3');
                    }
                } else {
                    if (header) {
                        header.classList.add('bg-white/0', 'backdrop-blur-none', 'shadow-none', 'py-4');
                        header.classList.remove('bg-white/95', 'backdrop-blur-md', 'shadow-md', 'py-3');
                    }
                }

                // Back to Top Logic
                if (window.scrollY > 300) {
                    backToTop.classList.add('show');
                } else {
                    backToTop.classList.remove('show');
                }
            });

            // Trigger once on load
            window.dispatchEvent(new Event('scroll'));

            backToTop.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            // Intersection Observer for Scroll Reveal
            const observerOptions = {
                threshold: 0.1,
                rootMargin: "0px 0px -50px 0px"
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

        // Smart Page Loader Control (Only show if loading takes > 200ms)
        const pageLoader = document.getElementById('pageLoader');
        let loaderTimeout;
        
        // Hide loader immediately when page is loaded
        window.addEventListener('load', () => {
            clearTimeout(loaderTimeout);
            pageLoader.classList.add('hidden');
        });

        // Show loader only if navigation takes longer than 200ms
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (link && !link.target && !link.href.includes('#') && !link.href.startsWith('mailto:') && !link.href.startsWith('tel:')) {
                const href = link.getAttribute('href');
                if (href && !href.startsWith('http') && !href.startsWith('//')) {
                    // Delay showing loader by 200ms - only show if truly slow
                    loaderTimeout = setTimeout(() => {
                        pageLoader.classList.remove('hidden');
                    }, 200);
                }
            }
        });
    </script>

    <!-- PWA Service Worker Registration -->
    <script>
        // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .then((registration) => {
                        console.log('✅ Service Worker registered successfully:', registration.scope);
                        
                        // Check for updates
                        registration.addEventListener('updatefound', () => {
                            const newWorker = registration.installing;
                            newWorker.addEventListener('statechange', () => {
                                if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                    // New service worker available
                                    if (confirm('Update tersedia! Refresh halaman untuk mendapatkan versi terbaru?')) {
                                        window.location.reload();
                                    }
                                }
                            });
                        });
                    })
                    .catch((error) => {
                        console.log('❌ Service Worker registration failed:', error);
                    });
            });
        }

        // PWA Install Prompt
        let deferredPrompt;
        const installButton = document.createElement('button');
        installButton.id = 'pwaInstallBtn';
        installButton.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            <span>Install App</span>
        `;
        installButton.style.cssText = `
            position: fixed;
            bottom: 5rem;
            right: 2rem;
            background: linear-gradient(135deg, #C5A059 0%, #D4AF6A 100%);
            color: #1A2332;
            border: none;
            padding: 12px 24px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(197, 160, 89, 0.4);
            z-index: 998;
            display: none;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        `;
        installButton.onmouseover = () => {
            installButton.style.transform = 'translateY(-3px)';
            installButton.style.boxShadow = '0 6px 25px rgba(197, 160, 89, 0.5)';
        };
        installButton.onmouseout = () => {
            installButton.style.transform = 'translateY(0)';
            installButton.style.boxShadow = '0 4px 20px rgba(197, 160, 89, 0.4)';
        };

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            
            // Show install button
            document.body.appendChild(installButton);
            installButton.style.display = 'flex';
            
            installButton.addEventListener('click', async () => {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    const { outcome } = await deferredPrompt.userChoice;
                    console.log(`User response to install prompt: ${outcome}`);
                    deferredPrompt = null;
                    installButton.style.display = 'none';
                }
            });
        });

        // Hide install button if already installed
        window.addEventListener('appinstalled', () => {
            console.log('✅ PWA installed successfully!');
            deferredPrompt = null;
            if (installButton.parentNode) {
                installButton.remove();
            }
        });

        // Detect if running as PWA
        if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true) {
            console.log('🚀 Running as PWA!');
            // Add PWA-specific styling or features here
            document.body.classList.add('pwa-mode');
        }
    </script>
</body>
</html>
