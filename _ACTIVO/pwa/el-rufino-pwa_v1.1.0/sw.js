// El Rufino — Service Worker de la candidata PWA v1.1.0.
// Limita el caché al shell aprobado y a solicitudes GET del mismo origen.

const CACHE_NAME = "el-rufino-shell-v1.1.0-r2";
const SHELL_FILES = [
  "/app/",
  "/app/index.html",
  "/app/manifest.json",
  "/app/privacy.html",
  "/app/icons/icon-192.png",
  "/app/icons/icon-512.png",
  "/app/icons/icon-maskable-512.png",
  "/app/fonts/PlayfairDisplay-Variable.ttf",
  "/app/fonts/SourceSerif4-Variable.ttf",
  "/app/fonts/SourceSerif4-Italic-Variable.ttf"
];

self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(SHELL_FILES))
  );
});

self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(
        keys
          .filter((key) => key.startsWith("el-rufino-shell-") && key !== CACHE_NAME)
          .map((key) => caches.delete(key))
      )
    ).then(() => self.clients.claim())
  );
});

self.addEventListener("fetch", (event) => {
  if (event.request.method !== "GET") return;

  const url = new URL(event.request.url);
  if (url.origin !== self.location.origin) return;

  event.respondWith(
    caches.match(event.request).then((cached) => {
      if (cached) return cached;

      return fetch(event.request).then((response) => {
        if (!response || !response.ok || response.type !== "basic") {
          return response;
        }

        const copy = response.clone();
        caches.open(CACHE_NAME).then((cache) => cache.put(event.request, copy));
        return response;
      });
    })
  );
});
