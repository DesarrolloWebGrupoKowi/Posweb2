@props([
    'diasPorProcesar' => ['contado' => collect(), 'facturas' => collect()],
])

@php
    $botones = [
        'contado' => ['texto' => 'Procesar contado', 'ruta' => 'DashEcommerce.procesar-contado', 'clase' => 'btn-process-left'],
        'facturas' => ['texto' => 'Procesar facturas', 'ruta' => 'DashEcommerce.procesar-facturas', 'clase' => 'btn-process-right'],
    ];
@endphp

<div
    class="btn-group"
    style="gap: 0;"
>
    @foreach ($botones as $tipo => $boton)
        @php
            $dias = $diasPorProcesar[$tipo];
            // Un renglon por centro y dia, el procedimiento se ejecuta una sola vez por cada uno
            $listaDias = $dias->map(fn($dia) => \Carbon\Carbon::parse($dia->Fecha)->format('d/m/Y') . ' - ' . $dia->CentroVenta . ' (' . $dia->Ventas . ' venta(s))')->implode("\n");
        @endphp
        <form
            method="POST"
            action="{{ route($boton['ruta']) }}"
            class="form-procesar-ecommerce"
            data-dias="{{ $listaDias }}"
            data-texto="{{ $boton['texto'] }}"
        >
            @csrf
            <input type="hidden" name="mes" value="{{ request('mes') }}">
            <input type="hidden" name="centro_venta" value="{{ request('centro_venta') }}">
            <button
                type="submit"
                class="btn btn-sm d-flex align-items-center {{ $boton['clase'] }} h-100 gap-1"
                title="{{ $dias->isEmpty() ? 'No hay ventas sin pedido por procesar' : $listaDias }}"
                @disabled($dias->isEmpty())
            >
                <i class="bi bi-cloud-upload"></i>
                {{ $boton['texto'] }}
                @if ($dias->isNotEmpty())
                    ({{ $dias->count() }})
                @endif
            </button>
        </form>
    @endforeach
</div>

<script>
    document.querySelectorAll('.form-procesar-ecommerce').forEach(form => {
        form.addEventListener('submit', event => {
            const mensaje = `${form.dataset.texto} ejecuta el procedimiento una vez por cada día:\n\n${form.dataset.dias}\n\n¿Continuar?`;
            if (!confirm(mensaje)) {
                event.preventDefault();
                return;
            }
            const boton = form.querySelector('button');
            boton.disabled = true;
            boton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Procesando...';
        });
    });
</script>

<style>
    /* Mismos estilos que los botones de procesar de DashTienda */
    .btn-process-left {
        background: var(--btn-blue-bg);
        color: var(--btn-blue-text);
        border: 1px solid var(--btn-blue-hover);
        border-radius: 8px 0 0 8px;
        padding: 8px 12px;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-process-left:hover {
        background: var(--btn-blue-hover);
        color: var(--btn-blue-text);
        transform: translateY(-1px);
    }

    .btn-process-right {
        background: var(--btn-green-bg);
        color: var(--btn-green-text);
        border: 1px solid var(--btn-green-hover);
        border-left: none;
        border-radius: 0 8px 8px 0;
        padding: 8px 12px;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-process-right:hover {
        background: var(--btn-green-hover);
        color: var(--btn-green-text);
        transform: translateY(-1px);
    }

    .btn-process-left:disabled,
    .btn-process-right:disabled {
        opacity: 0.5;
        transform: none;
    }
</style>
