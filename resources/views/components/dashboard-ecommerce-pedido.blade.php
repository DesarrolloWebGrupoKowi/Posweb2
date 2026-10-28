@props([
    'oracleData' => null,
    'fechaPago' => null,
])

@php
    $mensajeClass = ($oracleData->STATUS ?? null) === 'ERROR' ? 'text-danger' : 'text-success';
@endphp

{{-- Pedido, estatus, fechas y mensaje de la interfaz en un solo renglon --}}
<div
    class="d-flex align-items-center mb-2 flex-wrap gap-3 rounded px-3 py-2"
    style="background: var(--bg-subtle); font-size: 0.8rem;"
>
    <div class="d-flex align-items-center gap-2">
        <x-dashboard-ecommerce-oracle :oracleData="$oracleData" />
    </div>
    <span
        style="color: var(--text-secondary); text-wrap: nowrap;"
        title="Fecha de pago"
    >
        <i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($fechaPago)->format('d/m/Y') }}
    </span>
    @if (!empty($oracleData->Transaction_On))
        <span
            style="color: var(--text-secondary); text-wrap: nowrap;"
            title="Fecha del pedido en la interfaz"
        >
            <i class="bi bi-cloud-arrow-up me-1"></i>{{ $oracleData->Transaction_On }}
        </span>
    @endif
    @if (!empty($oracleData->MENSAJE_ERROR))
        <span
            class="{{ $mensajeClass }} flex-grow-1 text-truncate"
            style="min-width: 200px; max-width: 100%;"
            title="{{ $oracleData->MENSAJE_ERROR }}"
        >
            <i class="bi bi-chat-left-text me-1"></i>{{ $oracleData->MENSAJE_ERROR }}
        </span>
    @endif
</div>
