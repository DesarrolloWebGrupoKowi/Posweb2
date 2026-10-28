@props([
    'oracleData' => null,
    // Id del estatus para que el boton ENVIAR lo actualice
    'statusId' => null,
    // todo, pedido o estatus, para mostrarlos juntos o en columnas separadas
    'mostrar' => 'todo',
])

@php
    $status = $oracleData->STATUS ?? null;
    $statusClass = $status === 'PROCESADO' ? 'tags-green' : (in_array($status, ['ERROR', 'Cancelado']) ? 'tags-red' : 'tags-yellow');
@endphp

@if ($oracleData)
    @if ($mostrar != 'estatus')
        <span class="tags-blue" style="text-wrap: nowrap">
            {{ $oracleData->Source_Transaction_Number ?? substr_replace($oracleData->Source_Transaction_Identifier, '_', 3, 0) }}
        </span>
    @endif
    @if ($mostrar != 'pedido')
        <span
            @if ($statusId) id="{{ $statusId }}" @endif
            class="{{ $statusClass }}"
            style="text-wrap: nowrap"
        >{{ $status && $status !== 'NULL' ? $status : 'SIN PROCESAR' }}</span>
    @endif
@elseif ($mostrar != 'estatus')
    <span class="tags-red" style="text-wrap: nowrap">SIN PEDIDO</span>
@else
    <span class="text-muted">-</span>
@endif
