@props([
    'active' => false,
    'collapseId' => 'filtrosAvanzadosCollapse',
    'hasBadge' => false,
])

<div>
    <a
        class="btn btn-outline-secondary position-relative {{ $active ? 'bg-secondary text-white' : '' }}"
        data-bs-toggle="collapse"
        href="#{{ $collapseId }}"
        role="button"
        aria-expanded="{{ $active ? 'true' : 'false' }}"
        aria-controls="{{ $collapseId }}"
        id="btnFiltrosAvanzados"
        title="Filtros avanzados"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="14"
            height="14"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
        >
            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
        </svg>
        @if ($hasBadge)
            <span
                class="badge bg-danger rounded-pill position-absolute"
                id="filtrosBadge"
                style="top: -5px; right: -5px; font-size: 10px; display: none;"
            >!</span>
        @endif
    </a>
    {{-- Lógica JS importada desde VentaEmpleados.blade.php (1181-1188) para actualizar visual según filtros activos --}}
    <script>
        function verificarFiltrosActivos() {

            let filtros = [];

            // Obtén todos los elementos input dentro del contenedor de filtros avanzados
            const inputs = document
                .getElementById('filtrosAvanzadosCollapse')
                ?.querySelectorAll('input, select, textarea') || [];

            // Agrega a filtros cada nombre
            inputs.forEach(f => {
                if (f.name) {
                    filtros.push(f.name);
                }
            });

            const hayActivos = filtros.some(id => {
                const el = document.getElementById(id);
                if (!el) return false;

                if (el.type === 'checkbox' || el.type === 'radio') {
                    return el.checked;
                }
                return el.value && el.value !== '';
            });

            const badge = document.getElementById('filtrosBadge');
            if (badge) badge.style.display = hayActivos ? 'inline-block' : 'none';
        }


        document.addEventListener('DOMContentLoaded', function() {
            const btnFiltros = document.getElementById('btnFiltrosAvanzados');
            if (btnFiltros) {
                btnFiltros.addEventListener('click', function(e) {
                    e.preventDefault();
                    btnFiltros.classList.toggle('bg-secondary');
                    btnFiltros.classList.toggle('text-white');
                });
            }

            verificarFiltrosActivos();

            // Event listeners para actualizar el badge cuando cambien los filtros
            const filtrosInputs = ['idTienda', 'codArticulo', 'fechaInterfaz', 'codigoInterfaz'];
            filtrosInputs.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('change', verificarFiltrosActivos);
                    el.addEventListener('keyup', verificarFiltrosActivos);
                }
            });

        });
    </script>
</div>
