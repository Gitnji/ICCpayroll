self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open('teachtrack-v1').then((cache) => {
            return cache.addAll([
                '/',
                '/index.php',
                '/manifest.json'
            ]);
        })
    );
});

self.addEventListener('fetch', (event) => {
    event.respondWith(
        caches.match(event.request).then((response) => {
            return response || fetch(event.request);
        })
    );
});