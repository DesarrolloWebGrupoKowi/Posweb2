<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Corte Diario de Tienda</title>
    <style>
        * {
            font-family: 'Helvetica', 'Arial', sans-serif;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 15px;
            font-size: 10px;
            color: #1a1a1a;
        }

        /* ========== ENCABEZADO ========== */
        .header {
            text-align: center;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 2px solid #1e293b;
        }

        .header h1 {
            font-size: 16px;
            font-weight: 700;
            margin: 0 0 4px 0;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header h2 {
            font-size: 12px;
            font-weight: 600;
            margin: 0 0 2px 0;
            color: #475569;
        }

        .header .info-line {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            color: #64748b;
            margin-top: 6px;
        }

        /* ========== CLIENTE SECTION ========== */
        .cliente-header {
            background: #f1f5f9;
            padding: 6px 10px;
            margin: 12px 0 4px 0;
            border-left: 3px solid #1e293b;
            font-size: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            border-radius: 2px;
        }

        .cliente-header .cliente-nombre {
            color: #0f172a;
        }

        .cliente-header .pedido-badge {
            font-size: 8px;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: 500;
        }

        .pedido-blue {
            background: #dbeafe;
            color: #1e40af;
        }

        .pedido-red {
            background: #fee2e2;
            color: #991b1b;
        }

        /* ========== TABLAS ========== */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            margin-bottom: 8px;
        }

        thead th {
            background: #1e293b !important;
            color: white !important;
            padding: 6px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: none !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        thead th:first-child {
            border-radius: 4px 0 0 0;
        }

        thead th:last-child {
            border-radius: 0 4px 0 0;
        }

        thead th.text-end {
            text-align: right;
        }

        thead th.text-center {
            text-align: center;
        }

        tbody td {
            padding: 4px 8px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        /* Filas alternadas - DOS COLORES */
        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        tbody td.text-end {
            text-align: right;
            padding-right: 10px;
        }

        tbody td.text-center {
            text-align: center;
        }

        /* ========== TAGS DE ESTATUS ========== */
        .tag {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 600;
            white-space: nowrap;
        }

        .tag-green {
            background: #dcfce7;
            color: #166534;
        }

        .tag-red {
            background: #fee2e2;
            color: #991b1b;
        }

        .tag-blue {
            background: #dbeafe;
            color: #1e40af;
        }

        .tag-yellow {
            background: #fef3c7;
            color: #92400e;
        }

        /* ========== FILAS DE TOTALES ========== */
        .subtotal-row td {
            font-weight: 700;
            background: #f1f5f9 !important;
            border-top: 2px solid #cbd5e1;
            font-size: 9px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .monedero-row td {
            color: #dc2626;
            font-weight: 600;
            background: #fef2f2 !important;
            font-size: 9px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ========== SUMATORIAS FINALES ========== */
        .sumatorias-container {
            margin-top: 16px;
            padding-top: 12px;
            border-top: 2px solid #1e293b;
        }

        .sumatorias-title {
            font-size: 12px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .sumatorias-table {
            width: 320px;
            float: right;
            font-size: 10px;
        }

        .sumatorias-table td {
            padding: 3px 8px;
            border: none;
            background: transparent !important;
        }

        .sumatorias-table .section-title {
            font-weight: 700;
            color: #475569;
            padding-top: 6px;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .sumatorias-table .total-row td {
            font-weight: 700;
            color: #dc2626;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
        }

        .sumatorias-table .grand-total td {
            font-weight: 700;
            color: #dc2626;
            font-size: 11px;
            border-top: 2px solid #1e293b;
            padding-top: 6px;
        }

        /* ========== UTILIDADES ========== */
        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: 700;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        /* ========== PAGE BREAK ========== */
        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    {{-- ENCABEZADO PRINCIPAL --}}
    <div class="header">
        <h1>{{ $titulo }}</h1>
        <h2>{{ $nomTienda }}</h2>
        <div class="info-line">
            <span>CAJA: {{ $numCaja == 0 ? 'TODAS' : $numCaja }}</span>
            <span>{{ $fecha }}</span>
        </div>
    </div>

    {{-- CORTES DE CONTADO --}}
    @foreach ($cortesTienda as $corteTienda)
        {{-- @dump($corteTienda)
        @dump($corteTienda->Customer)
        @dump($corteTienda->Customer->NomClienteCloud) --}}
        @php
            $customer = $corteTienda->Customer[0] ?? null;
            $oracleData = $corteTienda->PedidoOracle ?? [];
        @endphp
        {{-- @dump($customer[0]->NomClienteCloud) --}}
        {{-- Encabezado del cliente --}}
        <div class="cliente-header">
            <span class="cliente-nombre">
                {{-- <i style="font-style: normal; margin-right: 4px;">📦</i> --}}
                {{ $customer->NomClienteCloud ?? 'Cliente' }}
            </span>
            @forelse ($oracleData as $pedidoOracle)
                <span
                    class="pedido-badge {{ empty($pedidoOracle->Source_Transaction_Identifier) ? 'pedido-red' : 'pedido-blue' }}"
                >
                    {{ empty($pedidoOracle->Source_Transaction_Identifier) ? 'SIN PEDIDO' : substr_replace($pedidoOracle->Source_Transaction_Identifier, '_', 3, 0) }}
                </span>
            @empty
                <span class="pedido-badge pedido-red">SIN PEDIDO</span>
            @endforelse
        </div>

        {{-- Tabla de productos --}}
        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">Código</th>
                    <th style="width: 28%;">Artículo</th>
                    <th
                        class="text-end"
                        style="width: 10%;"
                    >Cantidad</th>
                    <th
                        class="text-end"
                        style="width: 10%;"
                    >Precio</th>
                    <th
                        class="text-end"
                        style="width: 10%;"
                    >IVA</th>
                    <th
                        class="text-end"
                        style="width: 12%;"
                    >Importe</th>
                    <th style="width: 10%;">Pedido</th>
                    <th
                        class="text-center"
                        style="width: 8%;"
                    >Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $sumCantArticulo = 0;
                    $sumImporte = 0;
                @endphp
                @foreach ($corteTienda->CorteTiendaOracle as $detalleCorte)
                    <tr>
                        <td>{{ $detalleCorte->CodArticulo }}</td>
                        <td
                            style="max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                            title="{{ $detalleCorte->NomArticulo }}"
                        >
                            {{ $detalleCorte->NomArticulo }}
                        </td>
                        <td class="text-end">{{ number_format($detalleCorte->CantArticulo, 4) }}</td>
                        <td class="text-end">{{ number_format($detalleCorte->PrecioArticulo, 2) }}</td>
                        <td class="text-end">{{ number_format($detalleCorte->IvaArticulo, 2) }}</td>
                        <td class="text-end">{{ number_format($detalleCorte->ImporteArticulo, 2) }}</td>
                        <td>
                            @if (empty($detalleCorte->Source_Transaction_Identifier))
                                <span class="tag tag-red">SIN PEDIDO</span>
                            @else
                                <span
                                    class="tag tag-blue">{{ substr_replace($detalleCorte->Source_Transaction_Identifier, '_', 3, 0) }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php
                                $status = $detalleCorte->STATUS ?? null;
                                if (empty($status) || $status == 'NULL') {
                                    $tagClass = 'tag-red';
                                    $tagText = 'SIN PROCESAR';
                                } elseif ($status == 'ERROR') {
                                    $tagClass = 'tag-red';
                                    $tagText = 'ERROR';
                                } elseif ($status == 'PROCESADO') {
                                    $tagClass = 'tag-green';
                                    $tagText = 'PROCESADO';
                                } elseif ($status == 'EN PROCESO') {
                                    $tagClass = 'tag-yellow';
                                    $tagText = 'EN PROCESO';
                                } else {
                                    $tagClass = 'tag-red';
                                    $tagText = $status;
                                }
                            @endphp
                            <span class="tag {{ $tagClass }}">{{ $tagText }}</span>
                        </td>
                    </tr>
                    @php
                        $sumCantArticulo += $detalleCorte->CantArticulo;
                        $sumImporte += $detalleCorte->ImporteArticulo;
                    @endphp
                @endforeach

                {{-- Monedero Electrónico --}}
                @foreach ($totalMonedero as $monedero)
                    @if ($corteTienda->Bill_To == $monedero->Bill_To)
                        <tr class="monedero-row">
                            <td colspan="3"></td>
                            <td
                                class="fw-bold text-end"
                                colspan="2"
                            >Dinero Electrónico:</td>
                            <td class="text-end">${{ number_format($monedero->importe, 2) }}</td>
                            <td colspan="2"></td>
                        </tr>
                    @endif
                @endforeach

                {{-- Subtotales --}}
                <tr class="subtotal-row">
                    <td colspan="3"></td>
                    <td
                        class="text-end"
                        colspan="2"
                    >SubTotal:</td>
                    <td class="text-end">{{ number_format($sumCantArticulo, 3) }}</td>
                    <td class="text-end">${{ number_format($sumImporte, 2) }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    @endforeach

    {{-- FACTURAS --}}
    @foreach ($facturas as $corteTienda)
        @php
            $oracleData = $corteTienda->OracleData ?? [];
            $showUnlinkedWarning = empty($corteTienda->Bill_To) && empty($corteTienda->Customer->IdSolicitudFactura);
        @endphp

        <div class="cliente-header">
            <span class="cliente-nombre">
                {{-- <i style="font-style: normal; margin-right: 4px;">👤</i> --}}
                @if ($showUnlinkedWarning)
                    <span style="color: #dc2626;">FALTA LIGAR CLIENTE -
                        {{ $corteTienda->Customer->NomCliente ?? 'Cliente' }}</span>
                @else
                    {{ $corteTienda->Customer->NomCliente ?? ($corteTienda->Customer->NomClienteCloud ?? 'Cliente') }}
                @endif
            </span>
            @forelse ($oracleData as $sourceId => $oracleInfo)
                @php $pedidoDisplay = $oracleInfo->Source_Transaction_Number ?? null; @endphp
                <span
                    class="pedido-badge {{ empty($pedidoDisplay) ? 'pedido-red' : ($oracleInfo->STATUS == 'ERROR' ? 'pedido-red' : 'pedido-blue') }}"
                >
                    {{ empty($pedidoDisplay) ? 'SIN PEDIDO' : $pedidoDisplay }}
                </span>
            @empty
                <span class="pedido-badge pedido-red">SIN PEDIDO</span>
            @endforelse
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">Código</th>
                    <th style="width: 28%;">Artículo</th>
                    <th
                        class="text-end"
                        style="width: 10%;"
                    >Cantidad</th>
                    <th
                        class="text-end"
                        style="width: 10%;"
                    >Precio</th>
                    <th
                        class="text-end"
                        style="width: 10%;"
                    >IVA</th>
                    <th
                        class="text-end"
                        style="width: 12%;"
                    >Importe</th>
                    <th style="width: 10%;">Pedido</th>
                    <th
                        class="text-center"
                        style="width: 8%;"
                    >Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $sumCantArticulo = 0;
                    $sumImporte = 0;
                @endphp
                @foreach ($corteTienda->cortes as $detalleCorte)
                    @php
                        $sourceId = $detalleCorte->Source_Transaction_Identifier ?? null;
                        $oracleInfo =
                            $sourceId && isset($corteTienda->OracleData[$sourceId])
                                ? $corteTienda->OracleData[$sourceId]
                                : null;
                        $status = $oracleInfo->STATUS ?? null;
                        $solicitudCancelacion = $detalleCorte->SolicitudCancelacion ?? null;
                    @endphp
                    <tr>
                        <td>{{ $detalleCorte->CodArticulo }}</td>
                        <td style="max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $detalleCorte->NomArticulo }}
                        </td>
                        <td class="text-end">{{ number_format($detalleCorte->CantArticulo, 4) }}</td>
                        <td class="text-end">{{ number_format($detalleCorte->PrecioArticulo, 2) }}</td>
                        <td class="text-end">{{ number_format($detalleCorte->IvaArticulo, 2) }}</td>
                        <td class="text-end">{{ number_format($detalleCorte->ImporteArticulo, 2) }}</td>
                        <td>
                            @if (!empty($solicitudCancelacion))
                                <span class="tag tag-red">SOL. CANCELACIÓN</span>
                            @elseif(empty($sourceId))
                                <span class="tag tag-red">SIN PEDIDO</span>
                            @else
                                <span class="tag {{ $status == 'ERROR' ? 'tag-red' : 'tag-blue' }}">
                                    {{ substr_replace($sourceId, '_', 3, 0) }}
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php
                                if (!empty($solicitudCancelacion)) {
                                    $tagClass = 'tag-red';
                                    $tagText = 'CANCEL. SOLICITADA';
                                } elseif (empty($sourceId)) {
                                    $tagClass = 'tag-red';
                                    $tagText = 'SIN PROCESAR';
                                } elseif ($status == 'ERROR') {
                                    $tagClass = 'tag-red';
                                    $tagText = 'ERROR';
                                } elseif ($status == 'PROCESADO') {
                                    $tagClass = 'tag-green';
                                    $tagText = 'PROCESADO';
                                } elseif ($status == 'EN PROCESO') {
                                    $tagClass = 'tag-yellow';
                                    $tagText = 'EN PROCESO';
                                } else {
                                    $tagClass = 'tag-red';
                                    $tagText = 'SIN PROCESAR';
                                }
                            @endphp
                            <span class="tag {{ $tagClass }}">{{ $tagText }}</span>
                        </td>
                    </tr>
                    @php
                        $sumCantArticulo += $detalleCorte->CantArticulo;
                        $sumImporte += $detalleCorte->ImporteArticulo;
                    @endphp
                @endforeach

                <tr class="subtotal-row">
                    <td colspan="3"></td>
                    <td
                        class="text-end"
                        colspan="2"
                    >SubTotal:</td>
                    <td class="text-end">{{ number_format($sumCantArticulo, 3) }}</td>
                    <td class="text-end">${{ number_format($sumImporte, 2) }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    @endforeach

    {{-- SUMATORIAS FINALES --}}
    @php $totalMonederoImporte = $totalMonedero->sum('importe'); @endphp
    <div class="sumatorias-container clearfix">
        <div class="sumatorias-title">Resumen de Totales</div>
        <table class="sumatorias-table">
            {{-- Monedero Electrónico --}}
            <tr>
                <td
                    class="section-title"
                    colspan="2"
                >Dinero Electrónico</td>
            </tr>
            @foreach ($totalMonedero as $monedero)
                <tr>
                    <td>{{ $monedero->NomClienteCloud }}:</td>
                    <td class="text-end">${{ number_format($monedero->importe, 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td>Total Monedero:</td>
                <td class="text-end">${{ number_format($totalMonederoImporte, 2) }}</td>
            </tr>

            {{-- Créditos --}}
            <tr>
                <td
                    class="section-title"
                    colspan="2"
                >Créditos</td>
            </tr>
            <tr>
                <td>Crédito Quincenal:</td>
                <td class="text-end">${{ number_format($creditoQuincenal, 2) }}</td>
            </tr>
            <tr>
                <td>Crédito Semanal:</td>
                <td class="text-end">${{ number_format($creditoSemanal, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Total Créditos:</td>
                <td class="text-end">${{ number_format($creditoSemanal + $creditoQuincenal, 2) }}</td>
            </tr>

            {{-- Tarjetas --}}
            <tr>
                <td
                    class="section-title"
                    colspan="2"
                >Tarjetas</td>
            </tr>
            <tr>
                <td>Tarjeta Débito:</td>
                <td class="text-end">${{ number_format($totalTarjetaDebito, 2) }}</td>
            </tr>
            <tr>
                <td>Tarjeta Crédito:</td>
                <td class="text-end">${{ number_format($totalTarjetaCredito, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Total Tarjeta:</td>
                <td class="text-end">${{ number_format($totalTarjetaDebito + $totalTarjetaCredito, 2) }}</td>
            </tr>

            {{-- Transferencia y Efectivo --}}
            <tr>
                <td
                    class="section-title"
                    colspan="2"
                >Transferencia / Efectivo</td>
            </tr>
            <tr>
                <td>Total Transferencia:</td>
                <td class="text-end">${{ number_format($totalTransferencia, 2) }}</td>
            </tr>
            <tr>
                <td>Total Efectivo:</td>
                <td class="text-end">${{ number_format($totalEfectivo, 2) }}</td>
            </tr>

            {{-- Gran Total --}}
            <tr class="grand-total">
                <td>TOTAL GENERAL:</td>
                <td class="text-end">
                    ${{ number_format($totalEfectivo + $totalTransferencia + $totalTarjetaDebito + $totalTarjetaCredito + $creditoSemanal + $creditoQuincenal + $totalMonederoImporte, 2) }}
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
