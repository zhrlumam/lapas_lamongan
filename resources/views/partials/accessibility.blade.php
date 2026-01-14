<div x-data="accessibilityHelper()" class="fixed left-6 bottom-24 z-[9998]" x-cloak>
    <!-- Toggle Button -->
    <button @click="isOpen = !isOpen" 
            class="w-12 h-12 bg-white border-2 border-midnight-blue text-midnight-blue rounded-full shadow-lg flex items-center justify-center hover:bg-midnight-blue hover:text-white transition-all duration-300 group"
            title="Fitur Aksesibilitas">
        <i data-lucide="accessibility" class="w-6 h-6"></i>
    </button>

    <!-- Toolbar Menu -->
    <div x-show="isOpen" 
         @click.away="isOpen = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-x-4 scale-95"
         x-transition:enter-end="opacity-100 translate-x-0 scale-100"
         class="absolute bottom-16 left-0 w-64 bg-white rounded-2xl shadow-2xl border border-platinum p-5 space-y-4">
        
        <div class="flex items-center justify-between border-b border-platinum pb-3 mb-2">
            <h5 class="text-[10px] font-black uppercase tracking-widest text-midnight-blue">Aksesibilitas</h5>
            <button @click="resetAll()" class="text-[9px] font-bold text-red-500 hover:underline uppercase">Reset</button>
        </div>

        <!-- Font Size Control -->
        <div class="space-y-2">
            <label class="text-[9px] font-black text-dark-grey/40 uppercase tracking-wider">Ukuran Teks</label>
            <div class="flex gap-2">
                <button @click="setFontSize('small')" :class="fontSize === 'small' ? 'bg-midnight-blue text-white' : 'bg-soft-grey text-midnight-blue'" class="flex-1 py-2 text-xs font-bold rounded-lg transition-all">A-</button>
                <button @click="setFontSize('normal')" :class="fontSize === 'normal' ? 'bg-midnight-blue text-white' : 'bg-soft-grey text-midnight-blue'" class="flex-1 py-2 text-xs font-bold rounded-lg transition-all">A</button>
                <button @click="setFontSize('large')" :class="fontSize === 'large' ? 'bg-midnight-blue text-white' : 'bg-soft-grey text-midnight-blue'" class="flex-1 py-2 text-xs font-bold rounded-lg transition-all">A+</button>
            </div>
        </div>

        <!-- Visual Filters -->
        <div class="space-y-3 pt-2">
            <label class="text-[9px] font-black text-dark-grey/40 uppercase tracking-wider">Filter Visual</label>
            
            <button @click="toggleHighContrast()" 
                    :class="highContrast ? 'bg-gold-dignity text-midnight-blue' : 'bg-soft-grey text-midnight-blue'"
                    class="w-full flex items-center justify-between p-3 rounded-xl transition-all group">
                <div class="flex items-center gap-3">
                    <i data-lucide="contrast" class="w-4 h-4"></i>
                    <span class="text-[10px] font-bold uppercase tracking-tighter">Kontras Tinggi</span>
                </div>
                <div :class="highContrast ? 'bg-midnight-blue' : 'bg-platinum'" class="w-8 h-4 rounded-full relative transition-colors">
                    <div :class="highContrast ? 'translate-x-4' : 'translate-x-0'" class="absolute left-1 top-1 w-2 h-2 bg-white rounded-full transition-transform"></div>
                </div>
            </button>

            <button @click="toggleGrayscale()" 
                    :class="grayscale ? 'bg-gold-dignity text-midnight-blue' : 'bg-soft-grey text-midnight-blue'"
                    class="w-full flex items-center justify-between p-3 rounded-xl transition-all group">
                <div class="flex items-center gap-3">
                    <i data-lucide="palette" class="w-4 h-4"></i>
                    <span class="text-[10px] font-bold uppercase tracking-tighter">Hitam Putih</span>
                </div>
                <div :class="grayscale ? 'bg-midnight-blue' : 'bg-platinum'" class="w-8 h-4 rounded-full relative transition-colors">
                    <div :class="grayscale ? 'translate-x-4' : 'translate-x-0'" class="absolute left-1 top-1 w-2 h-2 bg-white rounded-full transition-transform"></div>
                </div>
            </button>

            <button @click="toggleHighlightLinks()" 
                    :class="highlightLinks ? 'bg-gold-dignity text-midnight-blue' : 'bg-soft-grey text-midnight-blue'"
                    class="w-full flex items-center justify-between p-3 rounded-xl transition-all group">
                <div class="flex items-center gap-3">
                    <i data-lucide="link" class="w-4 h-4"></i>
                    <span class="text-[10px] font-bold uppercase tracking-tighter">Tandai Link</span>
                </div>
                <div :class="highlightLinks ? 'bg-midnight-blue' : 'bg-platinum'" class="w-8 h-4 rounded-full relative transition-colors">
                    <div :class="highlightLinks ? 'translate-x-4' : 'translate-x-0'" class="absolute left-1 top-1 w-2 h-2 bg-white rounded-full transition-transform"></div>
                </div>
            </button>
        </div>
    </div>
</div>

<script>
    function accessibilityHelper() {
        return {
            isOpen: false,
            fontSize: localStorage.getItem('acc-fontSize') || 'normal',
            highContrast: localStorage.getItem('acc-highContrast') === 'true',
            grayscale: localStorage.getItem('acc-grayscale') === 'true',
            highlightLinks: localStorage.getItem('acc-highlightLinks') === 'true',

            init() {
                this.applyStyles();
                // Ensure lucide icons are created if they exist
                setTimeout(() => typeof lucide !== 'undefined' && lucide.createIcons(), 100);
            },

            setFontSize(size) {
                this.fontSize = size;
                localStorage.setItem('acc-fontSize', size);
                this.applyStyles();
            },

            toggleHighContrast() {
                this.highContrast = !this.highContrast;
                localStorage.setItem('acc-highContrast', this.highContrast);
                this.applyStyles();
            },

            toggleGrayscale() {
                this.grayscale = !this.grayscale;
                localStorage.setItem('acc-grayscale', this.grayscale);
                this.applyStyles();
            },

            toggleHighlightLinks() {
                this.highlightLinks = !this.highlightLinks;
                localStorage.setItem('acc-highlightLinks', this.highlightLinks);
                this.applyStyles();
            },

            resetAll() {
                this.fontSize = 'normal';
                this.highContrast = false;
                this.grayscale = false;
                this.highlightLinks = false;
                
                localStorage.removeItem('acc-fontSize');
                localStorage.removeItem('acc-highContrast');
                localStorage.removeItem('acc-grayscale');
                localStorage.removeItem('acc-highlightLinks');
                
                this.applyStyles();
            },

            applyStyles() {
                const html = document.documentElement;
                
                // Font Size
                html.classList.remove('acc-text-small', 'acc-text-normal', 'acc-text-large');
                html.classList.add(`acc-text-${this.fontSize}`);

                // High Contrast
                if (this.highContrast) html.classList.add('acc-high-contrast');
                else html.classList.remove('acc-high-contrast');

                // Grayscale
                if (this.grayscale) html.classList.add('acc-grayscale');
                else html.classList.remove('acc-grayscale');

                // Highlight Links
                if (this.highlightLinks) html.classList.add('acc-highlight-links');
                else html.classList.remove('acc-highlight-links');
            }
        }
    }
</script>

<style>
    /* CSS for Accessibility Features */
    .acc-text-small { font-size: 0.875rem !important; }
    .acc-text-normal { font-size: 1rem !important; }
    .acc-text-large { font-size: 1.125rem !important; }
    
    .acc-text-large p { font-size: 1.1rem !important; }
    .acc-text-large h1 { font-size: 3rem !important; }
    .acc-text-large h2 { font-size: 2.5rem !important; }

    .acc-grayscale {
        filter: grayscale(100%) !important;
    }

    .acc-high-contrast {
        filter: contrast(150%) brightness(110%) !important;
    }

    .acc-high-contrast body {
        background: #000 !important;
        color: #fff !important;
    }

    .acc-highlight-links a {
        outline: 2px solid #C5A059 !important;
        outline-offset: 2px !important;
        background-color: rgba(197, 160, 89, 0.1) !important;
        text-decoration: underline !important;
    }
</style>
