@props(['active' => false, 'id' => 'filtrosAvanzadosCollapse', 'showBadge' => true])

<div
    class="{{ $active ? 'show' : '' }} collapse mt-3"
    id="{{ $id }}"
>
    <div class="w-100 d-flex flex-column gap-3">
        {{ $slot }}
    </div>
</div>

<style>
    /* .filter-has-value {
        border-left: 3px solid #0d6efd !important;
        background-color: rgba(13, 110, 253, 0.05) !important;
        transition: all 0.2s ease-in-out;
    } */
    .filter-has-value:not([type="checkbox"]) {
        border-left: 3px solid #0d6efd !important;
        background-color: rgba(13, 110, 253, 0.05) !important;
        /* transition: all 0.2s ease-in-out; */
    }

    .filter-has-value-select {
        border-left: 3px solid #0d6efd !important;
        background-color: rgba(13, 110, 253, 0.05) !important;
        transition: all 0.2s ease-in-out;
    }

    .filter-badge {
        font-size: 10px;
        /* animation: fadeIn 0.3s ease-in; */
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes filterPulse {
        0% {
            border-left-color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.02);
        }

        50% {
            border-left-color: #0a58ca;
            background-color: rgba(13, 110, 253, 0.15);
        }

        100% {
            border-left-color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.05);
        }
    }

    .filter-has-value,
    .filter-has-value-select {
        /* animation: filterPulse 0.5s ease-in-out; */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const collapseElement = document.getElementById('{{ $id }}');
        if (!collapseElement) return;

        function tieneValor(elemento) {
            if (elemento.tagName === 'SELECT') {
                return elemento.value && elemento.value !== '';
            }
            if (elemento.type === 'checkbox') {
                return elemento.checked;
            }
            return elemento.value && elemento.value.trim() !== '';
        }

        function mostrarBadge(elemento, valor) {
            if (!{{ $showBadge ? 'true' : 'false' }}) return;

            const container = elemento.closest('.mb-3');
            if (!container) return;

            let badge = container.querySelector('.filter-badge');
            if (!badge) {
                badge = document.createElement('small');
                badge.className = 'text-primary ms-1 filter-badge';
                badge.style.fontSize = '10px';
                container.appendChild(badge);
            }

            let displayValue = valor;
            if (elemento.type === 'date' && valor) {
                displayValue = new Date(valor).toLocaleDateString('es-MX');
            } else if (elemento.tagName === 'SELECT') {
                const option = elemento.options[elemento.selectedIndex];
                displayValue = option.text;
            }

            badge.innerHTML = `✓ Activo: ${displayValue}`;
            badge.style.display = 'block';
        }

        function ocultarBadge(elemento) {
            const container = elemento.closest('.mb-3');
            const badge = container?.querySelector('.filter-badge');
            if (badge) badge.style.display = 'none';
        }

        function resaltarCampo(elemento) {
            const tieneDato = tieneValor(elemento);

            if (tieneDato) {
                if (elemento.tagName === 'SELECT') {
                    elemento.classList.add('filter-has-value-select');
                } else {
                    elemento.classList.add('filter-has-value');
                }
                mostrarBadge(elemento, elemento.value);
            } else {
                if (elemento.tagName === 'SELECT') {
                    elemento.classList.remove('filter-has-value-select');
                } else {
                    elemento.classList.remove('filter-has-value');
                }
                ocultarBadge(elemento);
            }
        }

        function resaltarTodos() {
            const inputs = collapseElement.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.type !== 'hidden') {
                    resaltarCampo(input);
                }
            });
        }

        resaltarTodos();

        const inputs = collapseElement.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (input.type !== 'hidden') {
                input.addEventListener('change', () => resaltarCampo(input));
                input.addEventListener('keyup', () => resaltarCampo(input));
            }
        });
    });
</script>
