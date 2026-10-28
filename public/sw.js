// Service worker de Posweb.
// Las páginas y peticiones al servidor (ventas, cortes, CSRF) SIEMPRE van a la red;
// solo se cachean archivos estáticos para que la app cargue más rápido.
// Al cambiar archivos estáticos, subir CACHE_VERSION para limpiar la caché vieja.
const CACHE_VERSION = 'posweb-v1';
const OFFLINE_URL = '/offline.html';

const PRECACHE = [
    OFFLINE_URL,
    '/img/pwa/icon-192.png',
    '/img/logokowi-v2.png',
];

const STATIC_PATHS = [
    '/bootstrap/', '/css/', '/js/', '/JQuery/', '/Icons/',
    '/img/', '/material-icon/', '/DataTables/',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_VERSION)
            .then((cache) => cache.addAll(PRECACHE))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(keys.filter((k) => k !== CACHE_VERSION).map((k) => caches.delete(k))))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    const request = event.request;
    if (request.method !== 'GET') return;

    const url = new URL(request.url);
    if (url.origin !== self.location.origin) return;

    // Navegación: red primero, página offline si no hay conexión
    if (request.mode === 'navigate') {
        event.respondWith(fetch(request).catch(() => caches.match(OFFLINE_URL)));
        return;
    }

    // Estáticos: responde de caché y actualiza en segundo plano
    if (STATIC_PATHS.some((p) => url.pathname.startsWith(p))) {
        event.respondWith(
            caches.open(CACHE_VERSION).then((cache) =>
                cache.match(request).then((cached) => {
                    const network = fetch(request)
                        .then((response) => {
                            if (response.ok) cache.put(request, response.clone());
                            return response;
                        })
                        .catch(() => cached);
                    return cached || network;
                })
            )
        );
    }
});
