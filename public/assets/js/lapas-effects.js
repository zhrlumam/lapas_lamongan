/**
 * ============================================
 * LAPAS LAMONGAN - MODERN JAVASCRIPT LIBRARY
 * ============================================
 * Kumpulan efek JavaScript keren untuk website
 * Author: Lapas Kelas IIB Lamongan Dev Team
 * Version: 1.0.0
 */

// ============================================
// 1. UTILITY FUNCTIONS
// ============================================
const LapasJS = {
    // Debounce function untuk optimasi performance
    debounce: function (func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    // Throttle function
    throttle: function (func, limit) {
        let inThrottle;
        return function () {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    },

    // Check if element is in viewport
    isInViewport: function (element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }
};

// ============================================
// 2. SCROLL PROGRESS INDICATOR
// ============================================
function initScrollProgress() {
    let progressBar = document.getElementById('scrollProgress');

    if (!progressBar) {
        progressBar = document.createElement('div');
        progressBar.id = 'scrollProgress';
        progressBar.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 4px;
            background: linear-gradient(90deg, #EEBF63, #07213D);
            z-index: 9999;
            transition: width 0.1s ease;
            box-shadow: 0 2px 5px rgba(238, 191, 99, 0.3);
        `;
        document.body.appendChild(progressBar);
    }

    const updateProgress = LapasJS.throttle(() => {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        progressBar.style.width = scrolled + '%';
    }, 10);

    window.addEventListener('scroll', updateProgress);
}

// ============================================
// 3. BACK TO TOP BUTTON
// ============================================
function initBackToTop() {
    let backToTop = document.getElementById('backToTop');

    if (!backToTop) {
        backToTop = document.createElement('button');
        backToTop.id = 'backToTop';
        backToTop.innerHTML = '↑';
        backToTop.setAttribute('aria-label', 'Kembali ke atas');
        backToTop.style.cssText = `
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 55px;
            height: 55px;
            background: linear-gradient(135deg, #EEBF63, #07213D);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            font-size: 24px;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        `;

        backToTop.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        backToTop.addEventListener('mouseenter', function () {
            this.style.transform = 'scale(1.1) rotate(360deg)';
        });

        backToTop.addEventListener('mouseleave', function () {
            this.style.transform = 'scale(1) rotate(0deg)';
        });

        document.body.appendChild(backToTop);
    }

    const toggleButton = LapasJS.throttle(() => {
        if (window.pageYOffset > 300) {
            backToTop.style.display = 'flex';
            backToTop.style.opacity = '1';
        } else {
            backToTop.style.opacity = '0';
            setTimeout(() => {
                if (window.pageYOffset <= 300) {
                    backToTop.style.display = 'none';
                }
            }, 300);
        }
    }, 100);

    window.addEventListener('scroll', toggleButton);
}

// ============================================
// 4. ANIMATED NUMBER COUNTER
// ============================================
function initCounters() {
    function animateCounter(element, target, duration = 2000) {
        const start = 0;
        const increment = target / (duration / 16);
        let current = start;

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target;
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current);
            }
        }, 16);
    }

    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px'
    };

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                const target = parseInt(entry.target.textContent);
                if (!isNaN(target)) {
                    animateCounter(entry.target, target);
                    entry.target.classList.add('counted');
                }
            }
        });
    }, observerOptions);

    // Observe elements with data-counter attribute
    document.querySelectorAll('[data-counter]').forEach(el => {
        counterObserver.observe(el);
    });

    // Also observe stat numbers
    document.querySelectorAll('.text-2xl, .text-3xl, .text-4xl').forEach(el => {
        if (el.textContent.match(/^\d+$/)) {
            counterObserver.observe(el);
        }
    });
}

// ============================================
// 5. SMOOTH SCROLL FOR ANCHOR LINKS
// ============================================
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#') return;

            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                const offsetTop = target.offsetTop - 80; // Account for fixed navbar
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// ============================================
// 6. IMAGE LAZY LOADING WITH FADE IN
// ============================================
function initLazyLoading() {
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.style.opacity = '0';
                img.style.transition = 'opacity 0.6s ease-in';

                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                }

                img.onload = () => {
                    img.style.opacity = '1';
                };

                imageObserver.unobserve(img);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

// ============================================
// 7. PARALLAX EFFECT
// ============================================
function initParallax() {
    const parallaxElements = document.querySelectorAll('[data-parallax]');

    if (parallaxElements.length === 0) return;

    const handleParallax = LapasJS.throttle(() => {
        const scrolled = window.pageYOffset;

        parallaxElements.forEach(el => {
            const speed = parseFloat(el.dataset.parallax) || 0.5;
            const yPos = -(scrolled * speed);
            el.style.transform = `translateY(${yPos}px)`;
        });
    }, 10);

    window.addEventListener('scroll', handleParallax);
}

// ============================================
// 8. TYPING EFFECT
// ============================================
function typeWriter(element, text, speed = 50, callback) {
    if (!element) return;

    let i = 0;
    element.textContent = '';
    element.style.borderRight = '2px solid #EEBF63';
    element.style.paddingRight = '5px';

    function type() {
        if (i < text.length) {
            element.textContent += text.charAt(i);
            i++;
            setTimeout(type, speed);
        } else {
            element.style.borderRight = 'none';
            if (callback) callback();
        }
    }
    type();
}

// ============================================
// 9. CARD HOVER EFFECTS
// ============================================
function initCardHovers() {
    document.querySelectorAll('.floating-card, .group').forEach(card => {
        card.addEventListener('mouseenter', function () {
            this.style.transform = 'translateY(-10px) scale(1.02)';
            this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            this.style.boxShadow = '0 20px 40px rgba(0,0,0,0.15)';
        });

        card.addEventListener('mouseleave', function () {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '';
        });
    });
}

// ============================================
// 10. RIPPLE EFFECT ON BUTTONS
// ============================================
function initRippleEffect() {
    document.querySelectorAll('button, .btn').forEach(button => {
        button.addEventListener('click', function (e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                left: ${x}px;
                top: ${y}px;
                transform: scale(0);
                animation: ripple 0.6s ease-out;
                pointer-events: none;
            `;

            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);

            setTimeout(() => ripple.remove(), 600);
        });
    });

    // Add ripple animation to stylesheet
    if (!document.getElementById('ripple-style')) {
        const style = document.createElement('style');
        style.id = 'ripple-style';
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
}

// ============================================
// 11. LOADING ANIMATION
// ============================================
function initPageLoader() {
    const loader = document.createElement('div');
    loader.id = 'pageLoader';
    loader.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #07213D, #0a2d52);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.5s ease;
    `;

    loader.innerHTML = `
        <div style="text-align: center;">
            <div style="width: 60px; height: 60px; border: 4px solid rgba(238, 191, 99, 0.3); border-top-color: #EEBF63; border-radius: 50%; animation: spin 1s linear infinite;"></div>
            <p style="color: #EEBF63; margin-top: 20px; font-weight: bold;">Loading...</p>
        </div>
    `;

    document.body.appendChild(loader);

    // Add spin animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);

    window.addEventListener('load', () => {
        setTimeout(() => {
            loader.style.opacity = '0';
            setTimeout(() => loader.remove(), 500);
        }, 500);
    });
}

// ============================================
// 12. CONSOLE EASTER EGG
// ============================================
function initConsoleEasterEgg() {
    console.log('%c🏛️ Lapas Kelas IIB Lamongan', 'font-size: 24px; font-weight: bold; color: #07213D; text-shadow: 2px 2px 4px rgba(0,0,0,0.1);');
    console.log('%c━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━', 'color: #EEBF63;');
    console.log('%cWebsite ini dikembangkan dengan ❤️ untuk melayani masyarakat', 'font-size: 12px; color: #EEBF63; font-style: italic;');
    console.log('%c⚠️ PERINGATAN KEAMANAN', 'font-size: 16px; color: red; font-weight: bold; background: yellow; padding: 5px;');
    console.log('%cJangan paste kode dari sumber tidak terpercaya di console ini!', 'font-size: 12px; color: red; font-weight: bold;');
    console.log('%cIni bisa membahayakan akun dan data Anda.', 'font-size: 12px; color: red;');
    console.log('%c━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━', 'color: #EEBF63;');
}

// ============================================
// 13. INITIALIZE ALL EFFECTS
// ============================================
function initAllEffects() {
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            runInitializations();
        });
    } else {
        runInitializations();
    }
}

function runInitializations() {
    initScrollProgress();
    initBackToTop();
    initCounters();
    initSmoothScroll();
    initLazyLoading();
    initParallax();
    initCardHovers();
    initRippleEffect();
    initConsoleEasterEgg();

    // Initialize AOS if available
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            once: true,
            offset: 50,
            easing: 'ease-out-cubic'
        });
    }

    // Initialize Lucide icons if available
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

// Auto-initialize when script loads
initAllEffects();

// Export for use in other scripts
window.LapasJS = LapasJS;
window.typeWriter = typeWriter;
