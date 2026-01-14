const CACHE_NAME = 'sipas-lamongan-v1.0.0';
const urlsToCache = [
    '/',
    '/css/app.css',
    '/js/app.js',
    '/assets/hero.jpg',
    '/assets/logolap.png',
    '/assets/logo_pas.png',
    '/assets/logo_kanwil.png',
    '/assets/logo_membangun.png',
    'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js',
    'https://unpkg.com/lucide@latest/dist/umd/lucide.min.js'
];

// Install Service Worker
self.addEventListener('install', (event) => {
    console.log('[Service Worker] Installing...');
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('[Service Worker] Caching app shell');
                return cache.addAll(urlsToCache);
            })
            .catch((error) => {
                console.error('[Service Worker] Cache failed:', error);
            })
    );
    self.skipWaiting();
});

// Activate Service Worker
self.addEventListener('activate', (event) => {
    console.log('[Service Worker] Activating...');
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('[Service Worker] Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    return self.clients.claim();
});

// Fetch Strategy: Network First, fallback to Cache
self.addEventListener('fetch', (event) => {
    event.respondWith(
        fetch(event.request)
            .then((response) => {
                // Clone the response
                const responseToCache = response.clone();

                // Cache the fetched response
                caches.open(CACHE_NAME).then((cache) => {
                    cache.put(event.request, responseToCache);
                });

                return response;
            })
            .catch(() => {
                // If network fails, try cache
                return caches.match(event.request).then((response) => {
                    if (response) {
                        return response;
                    }

                    // If not in cache, return offline page
                    if (event.request.mode === 'navigate') {
                        return caches.match('/offline.html');
                    }
                });
            })
    );
});

// Push Notification (Optional - for future use)
self.addEventListener('push', (event) => {
    const options = {
        body: event.data ? event.data.text() : 'Notifikasi baru dari SIPAS Lamongan',
        icon: '/assets/icon-192x192.png',
        badge: '/assets/icon-72x72.png',
        vibrate: [200, 100, 200],
        tag: 'sipas-notification',
        requireInteraction: false,
        actions: [
            {
                action: 'open',
                title: 'Buka'
            },
            {
                action: 'close',
                title: 'Tutup'
            }
        ]
    };

    event.waitUntil(
        self.registration.showNotification('SIPAS Lamongan', options)
    );
});

// Notification Click Handler
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    if (event.action === 'open') {
        event.waitUntil(
            clients.openWindow('/')
        );
    }
});
