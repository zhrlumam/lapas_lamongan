<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Lapas Kelas IIB Lamongan</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_imigrasi.png') }}">
    
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
                            '0%': { transform: 'translateX(100%)' },
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
            from { transform: translateX(100%); }
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

        @media (max-width: 640px) {
            #backToTop { bottom: 1.5rem; right: 1.5rem; width: 3rem; height: 3rem; }
            section { padding-top: 4rem; padding-bottom: 4rem; }
        }
    </style>
</head>
<body class="bg-white text-dark-grey antialiased font-sans flex flex-col min-h-screen overflow-x-hidden">

    @include('partials.navbar')

    <main class="flex-grow">
        @yield('content')
    </main>

    <button id="backToTop" aria-label="Kembali ke atas">
        <i data-lucide="arrow-up" class="w-5 h-5"></i>
    </button>

    @include('partials.footer')

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
    </script>
</body>
</html>
