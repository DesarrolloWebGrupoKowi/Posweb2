@props([
    'articulos' => [],
    'costoEnvio' => 0,
    'importePago' => 0,
])

<table class="table-hover table-custom table">
    <thead>
        <tr>
            <th><i class="bi bi-upc-scan me-1"></i>Código</th>
            <th><i class="bi bi-box me-1"></i>Artículo</th>
            <th class="text-end"><i class="bi bi-box me-1"></i>Cantidad</th>
            <th class="text-end"><i class="bi bi-cash me-1"></i>Precio</th>
            <th class="text-end"><i class="bi bi-cash-stack me-1"></i>Importe</th>
        </tr>
    </thead>
    <tbody>
        @php
            $sumKilos = 0;
            $sumImporte = 0;
        @endphp
        @foreach ($articulos as $articulo)
            <tr>
                <td style="font-weight: 500;">{{ $articulo->Codigo }}</td>
                <td
                    class="text-truncate"
                    style="max-width: 250px;"
                    title="{{ $articulo->Descripcion }}"
                >{{ $articulo->Descripcion }}</td>
                <td
                    class="text-end"
                    style="font-weight: 500;"
                >{{ number_format($articulo->Kilos, 3) }}</td>
                <td
                    class="text-end"
                    style="font-weight: 500;"
                >${{ number_format($articulo->Precio, 2) }}</td>
                <td
                    class="text-end"
                    style="font-weight: 500;"
                >${{ number_format($articulo->Importe, 2) }}</td>
            </tr>
            @php
                $sumKilos += $articulo->Kilos;
                $sumImporte += $articulo->Importe;
            @endphp
        @endforeach

        {{-- Suma de los productos --}}
        <tr style="background: var(--bg-subtle);">
            <td
                colspan="2"
                class="fw-bold text-end"
                style="color: var(--text-secondary);"
            >Subtotal:</td>
            <td
                class="fw-bold text-end"
                style="color: var(--text-secondary);"
            >{{ number_format($sumKilos, 3) }}</td>
            <td></td>
            <td
                class="fw-bold text-end"
                style="color: var(--text-secondary);"
            >${{ number_format($sumImporte, 2) }}</td>
        </tr>

        {{-- Costo de envío --}}
        <tr style="background: var(--bg-subtle);">
            <td
                colspan="4"
                class="fw-bold text-end"
                style="color: var(--text-secondary);"
            >Envío:</td>
            <td
                class="fw-bold text-end"
                style="color: var(--text-secondary);"
            >${{ number_format($costoEnvio, 2) }}</td>
        </tr>

        {{-- Totales --}}
        <tr class="bg-table-totals">
            <td
                colspan="4"
                class="fw-bold text-end"
                style="color: var(--text-primary);"
            >TOTAL:</td>
            <td
                class="fw-bold text-end"
                style="color: var(--text-primary);"
            >${{ number_format($sumImporte + $costoEnvio, 2) }}</td>
        </tr>

        {{-- Aviso cuando lo cobrado no cuadra con los artículos más el envío --}}
        @if (abs($sumImporte + $costoEnvio - $importePago) >= 0.01)
            <tr>
                <td
                    colspan="5"
                    class="text-danger py-1 text-end"
                >
                    <small>Cobrado en el pago: ${{ number_format($importePago, 2) }}
                        (diferencia ${{ number_format($importePago - $sumImporte - $costoEnvio, 2) }})</small>
                </td>
            </tr>
        @endif
    </tbody>
</table>
