{{-- Aviso de novedades: se muestra una vez por navegador cada que cambia config('app.version') --}}
@auth
    <div
        id="avisoNovedades"
        class="aviso-novedades"
        style="display: none;"
    >
        <i class="bi bi-stars aviso-novedades-icono"></i>
        <div class="flex-grow-1">
            <div class="fw-semibold">Posweb se actualizó a la versión {{ config('app.version') }}</div>
            <a
                href="{{ route('ayuda.versiones') }}"
                class="aviso-novedades-link"
            >Ver novedades</a>
        </div>
        <button
            type="button"
            class="btn-close btn-close-white"
            aria-label="Cerrar"
        ></button>
    </div>

    <style>
        .aviso-novedades {
            position: fixed;
            right: 16px;
            bottom: 16px;
            z-index: 1080;
            max-width: 340px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 12px;
            color: #fff;
            font-size: 0.9rem;
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.3);
        }

        .aviso-novedades-icono {
            font-size: 1.5rem;
            color: #fbbf24;
        }

        .aviso-novedades-link {
            color: #fbbf24;
            font-size: 0.85rem;
        }

        @media (max-width: 767.98px) {
            .aviso-novedades {
                left: 12px;
                right: 12px;
                max-width: none;
            }
        }
    </style>

    <script>
        (function() {
            var version = @json(config('app.version'));
            var clave = 'posweb_version_vista';
            var aviso = document.getElementById('avisoNovedades');
            var vista = null;

            try {
                vista = localStorage.getItem(clave);
            } catch (e) {}

            if (vista === version) return;
            aviso.style.display = '';

            var marcarVista = function() {
                try {
                    localStorage.setItem(clave, version);
                } catch (e) {}
            };

            aviso.querySelector('.btn-close').addEventListener('click', function() {
                marcarVista();
                aviso.style.display = 'none';
            });
            aviso.querySelector('.aviso-novedades-link').addEventListener('click', marcarVista);
        })();
    </script>
@endauth
