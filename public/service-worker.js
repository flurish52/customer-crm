const CACHE_NAME = 'entroly-cache-v1';
const urlsToCache = [
    '/',
    '/build/assets/app.js',    // adjust to your Vite output
    '/build/assets/app.css',   // adjust to your Vite output
    '/favicon.ico',
];

self.addEventListener('install', event => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll(urlsToCache);
        })
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request).then(response => {
            return response || fetch(event.request);
        })
    );
});
