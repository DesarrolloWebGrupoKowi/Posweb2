{{-- Configuración PWA: permite instalar Posweb como app desde Chrome/Edge --}}
<link rel="manifest" href="{{ asset('manifest.json') }}">
<meta name="theme-color" content="#1e2839">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="Posweb">
<link rel="apple-touch-icon" href="{{ asset('img/pwa/apple-touch-icon.png') }}">
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('{{ asset('sw.js') }}').catch(function (err) {
                console.warn('No se pudo registrar el service worker', err);
            });
        });
    }
</script>
