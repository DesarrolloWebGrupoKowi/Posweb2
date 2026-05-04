<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        * {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        .titulo,
        .tienda {
            text-align: center;
        }

        #tblCustomerTienda,
        #tblFactura {
            font-size: 10px;
            /*border-collapse: collapse;*/
            width: 100%;
        }

        #sumatorias {
            font-size: 11px;
            /*border-collapse: collapse;*/
            width: 100%;
        }

        table,
        td {
            border-bottom: 1px solid rgb(95, 95, 95);
            border-collapse: collapse;
        }

        th {
            text-align: left;
            border-bottom: 1px solid rgb(0, 0, 0);
            background-color: #000000;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        #DivSumatorias {
            float: right;
            ;
        }
    </style>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <meta
        http-equiv="X-UA-Compatible"
        content="ie=edge"
    >
    <title>Corte Diario de Tienda</title>
</head>

<body>
    <div class="container">
        <caption class="titulo">{{ $titulo }}</caption>
        <caption class="tienda">{{ $nomTienda }}</caption>
        <caption class="titulo">CAJA: {{ $numCaja == 0 ? 'TODAS' : $numCaja }}</caption>
        <caption style="text-align: right">{{ $fecha }}</caption>
        <br>
        @foreach ($cortesTienda as $corteTienda)
            @foreach ($corteTienda->Customer as $customer)
            @endforeach
            <table id="tblCustomerTienda">
                <caption style="text-align: left; padding-bottom: 5px;">
                    {{ $customer->NomClienteCloud }}
                    @foreach ($corteTienda->PedidoOracle as $pedidoOracle)
                        @if (empty($pedidoOracle->Source_Transaction_Identifier))
                            SIN PEDIDO
                        @else
                            - {{ substr_replace($pedidoOracle->Source_Transaction_Identifier, '_', 3, 0) }}
                        @endif
                    @endforeach
                </caption>
                <thead>
                    <tr class="cab">
                        <th>Código</th>
                        <th>Articulo</th>
                        <th style="text-align: center;">Cantidad</th>
                        <th style="text-align: center;">Precio</th>
                        <th style="text-align: center;">Iva</th>
                        <th style="text-align: center;">Importe</th>
                        <th>Pedido</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sumCantArticulo = 0;
                        $sumImporte = 0;
                    @endphp
                    @foreach ($corteTienda->CorteTiendaOracle as $detalleCorte)
                        <tr class="striped">
                            <td>{{ $detalleCorte->CodArticulo }}</td>
                            <td>{{ $detalleCorte->NomArticulo }}</td>
                            <td style="text-align: right; padding-right: 5px;">
                                {{ number_format($detalleCorte->CantArticulo, 4) }}</td>
                            <td style="text-align: right; padding-right: 5px;">
                                {{ number_format($detalleCorte->PrecioArticulo, 2) }}</td>
                            <td style="text-align: right; padding-right: 5px;">
                                {{ number_format($detalleCorte->IvaArticulo, 2) }}</td>
                            <td style="text-align: right; padding-right: 10px;">
                                {{ number_format($detalleCorte->ImporteArticulo, 2) }}</td>
                            @if (empty($detalleCorte->Source_Transaction_Identifier))
                                <td>SIN PEDIDO</td>
                            @else
                                <td>
                                    {{ substr_replace($detalleCorte->Source_Transaction_Identifier, '_', 3, 0) }}
                                </td>
                            @endif
                            @if (
                                (empty($detalleCorte->STATUS) || $detalleCorte->STATUS == 'NULL') &&
                                    empty($detalleCorte->MENSAJE_ERROR) &&
                                    empty($detalleCorte->Batch_Name))
                                <td>SIN PROCESAR</td>
                            @endif
                            @if ($detalleCorte->STATUS == 'ERROR')
                                <td>ERROR</td>
                            @endif
                            @if ($detalleCorte->STATUS == 'PROCESADO')
                                <td>{{ $detalleCorte->STATUS }}</td>
                            @endif
                        </tr>
                        @php
                            $sumCantArticulo = $sumCantArticulo + $detalleCorte->CantArticulo;
                            $sumImporte = $sumImporte + $detalleCorte->ImporteArticulo;
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    @foreach ($totalMonedero as $monedero)
                        @if ($corteTienda->Bill_To == $monedero->Bill_To)
                            <tr>
                                <td></td>
                                <td style="text-align:center; font-weight: bold;">Dinero Electrónico: </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="color: red; font-weight: bold; text-align:  right; padding-right: 10px;">
                                    $ {{ number_format($monedero->importe, 2) }}
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endif
                    @endforeach
                    <!--MONEDERO ELECTRONICO QUINCENAL-->
                    {{-- @if ($corteTienda->IdTipoNomina == 4)
                        <tr>
                            <td></td>
                            <td style="text-align:center; font-weight: bold;">Dinero Electrónico: </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="color: red; font-weight: bold; text-align:  right; padding-right: 10px;">
                                ${{ number_format($totalMonederoQuincenal, 2) }}
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endif --}}
                    <!--TERMINA MONEDERO ELECTRONICO QUINCENAL-->

                    <!--MONEDERO ELECTRONICO SEMANAL-->
                    {{-- @if ($corteTienda->IdTipoNomina == 3)
                        <tr>
                            <td></td>
                            <td style="text-align:center; font-weight: bold;">Dinero Electrónico: </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="color: red; font-weight: bold; text-align:  right; padding-right: 10px;">
                                ${{ number_format($totalMonederoSemanal, 2) }}
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endif --}}
                    <!--TERMINA MONEDERO ELECTRONICO SEMANAL-->
                    <tr>
                        <td></td>
                        <td style="text-align:center; font-weight: bold;">SubTotal: </td>
                        <td style="font-weight: bold; text-align:  right; padding-right: 5px;">
                            {{ number_format($sumCantArticulo, 3) }}</td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: bold; text-align:  right; padding-right: 10px;">
                            ${{ number_format($sumImporte, 2) }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <br>
        @endforeach
    </div>
    <div class="container">
        {{-- @foreach ($facturas as $factura)
            <table id="tblFactura">
                @if (empty($factura->Bill_To) && empty($factura->IdClienteCloud))
                    <caption style="text-align: left; padding-bottom: 5px;">FALTA LIGAR CLIENTE -
                        {{ $factura->NomCliente }}</caption>
                @else
                    <caption style="text-align: left; padding-bottom: 5px;">{{ $factura->NomCliente }}
                        @foreach ($factura->PedidoOracle as $pedidoOracle)
                            @if (empty($pedidoOracle->Source_Transaction_Identifier))
                                SIN PEDIDO
                            @else
                                - {{ substr_replace($pedidoOracle->Source_Transaction_Identifier, '_', 3, 0) }}
                            @endif
                            <!-- @break; -->
                        @endforeach
                    </caption>
                @endif
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Articulo</th>
                        <th style="text-align: center;">Cantidad</th>
                        <th style="text-align: center;">Precio</th>
                        <th style="text-align: center;">Iva</th>
                        <th style="text-align: center;">Importe</th>
                        <th>Pedido</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sumCantArticulo = 0;
                        $sumImporte = 0;
                    @endphp
                    @foreach ($factura->Factura as $detalleFactura)
                        <tr class="striped">
                            <td>{{ $detalleFactura->CodArticulo }}</td>
                            <td>{{ $detalleFactura->NomArticulo }}</td>
                            <td style="text-align: right; padding-right: 5px;">
                                {{ number_format($detalleFactura->PivotDetalle->CantArticulo, 4) }}
                            </td>
                            <td style="text-align: right; padding-right: 5px;">
                                {{ number_format($detalleFactura->PivotDetalle->PrecioArticulo, 2) }}
                            </td>
                            <td style="text-align: right; padding-right: 5px;">
                                {{ number_format($detalleFactura->PivotDetalle->IvaArticulo, 2) }}
                            </td>
                            <td style="text-align: right; padding-right: 10px;">
                                {{ number_format($detalleFactura->PivotDetalle->ImporteArticulo, 2) }}
                            </td>
                            @if (empty($detalleFactura->Source_Transaction_Identifier))
                                <td>SIN PEDIDO</td>
                            @else
                                <td>
                                    {{ substr_replace($detalleFactura->Source_Transaction_Identifier, '_', 3, 0) }}
                                </td>
                            @endif
                            @if ((empty($detalleFactura->STATUS) || $detalleFactura->STATUS == 'NULL') && empty($detalleFactura->MENSAJE_ERROR) && empty($detalleFactura->Batch_Name))
                                <td>SIN PROCESAR</td>
                            @endif
                            @if ($detalleFactura->STATUS == 'ERROR')
                                <td>ERROR</td>
                            @endif
                            @if ($detalleFactura->STATUS == 'PROCESADO')
                                <td>{{ $detalleFactura->STATUS }}</td>
                            @endif
                        </tr>
                        @php
                            $sumCantArticulo = $sumCantArticulo + $detalleFactura->PivotDetalle->CantArticulo;
                            $sumImporte = $sumImporte + $detalleFactura->PivotDetalle->ImporteArticulo;
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td style="text-align: center; font-weight: bold;">SubTotal: </td>
                        <td style="font-weight: bold; text-align: right; padding-right: 5px;">
                            {{ number_format($sumCantArticulo, 3) }}</td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: bold; text-align: right; padding-right: 10px;">
                            ${{ number_format($sumImporte, 2) }}
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <br>
        @endforeach --}}
        {{-- @foreach ($facturas as $corteTienda)
            <div
                class="content-table content-table-flex-none content-table-full card border-0 p-4"
                style="border-radius: 10px"
            >
                <!-- Encabezdo del cliente -->
                <div class="d-flex align-items-center gap-3">

                    <!-- Icono -->
                    <div
                        class="rounded p-2"
                        style="background-color: rgba(30, 66, 159, 0.1); display: flex; justify-content: center; align-items: center"
                    >
                        <div
                            style="color: #1e429f; width: 20px; height: 20px; display: flex; justify-content: center; align-items: center">
                            @include('components.icons.user')
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="flex-grow-1">
                        <!-- Línea 1: Nombre y Status -->
                        <div class="d-flex align-items-center flex-wrap gap-3">
                            <h6 class="fw-semibold @if (!is_null($corteTienda->Customer->Editar)) text-danger @endif mb-0">
                                {{ $corteTienda->Customer->NomCliente ?? ($corteTienda->Customer->NomClienteCloud ?? 'Cliente') }}
                                @if (!is_null($corteTienda->Customer->Editar))
                                    <span class="tags-red ms-2">SIN LIGAR</span>
                                @endif
                            </h6>

                            @php
                                $oracleData = $corteTienda->OracleData ?? [];
                                $totalPedidos = count($oracleData);
                            @endphp

                            @if ($totalPedidos > 0)
                                @foreach ($oracleData as $sourceId => $oracleInfo)
                                    @php
                                        $statusClass = 'tags-green';
                                        $statusText = $oracleInfo->Source_Transaction_Number ?? null;

                                        if (empty($statusText)) {
                                            $statusClass = 'tags-red';
                                            $statusText = 'SIN PEDIDO';
                                        } elseif ($oracleInfo->STATUS == 'ERROR') {
                                            $statusClass = 'tags-red';
                                        }
                                    @endphp

                                    <span class="{{ $statusClass }} d-inline-flex align-items-center">
                                        {{ $statusText }}
                                    </span>
                                @endforeach
                            @else
                                <span class="tags-red d-inline-flex align-items-center">
                                    SIN PEDIDO
                                </span>
                            @endif
                        </div>

                        @foreach ($corteTienda->OracleData as $sourceId => $oracleInfo)
                            @if (!empty($oracleInfo->MENSAJE_ERROR))
                                <div
                                    class="{{ ($oracleInfo->STATUS ?? '') == 'ERROR' ? 'text-danger' : 'text-success' }}"
                                    style="font-size: 0.75rem; font-weight: 400;"
                                >
                                    <div class="d-flex align-items-start gap-2">
                                        <div>
                                            <span style="font-weight: 500;">
                                                {{ $oracleInfo->Transaction_On }}
                                                @if (!empty($oracleInfo->Transaction_On))
                                                    -
                                                @endif
                                                Mensaje:
                                            </span>
                                            {{ $oracleInfo->Source_Transaction_Number }}
                                            {{ $oracleInfo->MENSAJE_ERROR }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Tabla de productos -->
                <table>
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Código</th>
                            <th>Artículo</th>
                            <th class="text-end">Cantidad</th>
                            <th class="text-end">Precio</th>
                            <th class="text-end">IVA</th>
                            <th class="text-end">Importe</th>
                            <th>Pedido</th>
                            <th class="rounded-end text-center">Estatus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sumCantArticulo = 0;
                            $sumImporte = 0;
                        @endphp

                        @foreach ($corteTienda->cortes as $detalleCorte)
                            <tr>
                                <td>{{ $detalleCorte->CodArticulo }}</td>
                                <td
                                    class="text-truncate"
                                    style="max-width: 200px;"
                                    title="{{ $detalleCorte->NomArticulo }}"
                                >
                                    {{ $detalleCorte->NomArticulo }}
                                </td>
                                <td class="text-end">
                                    {{ number_format($detalleCorte->CantArticulo, 4) }}
                                </td>
                                <td class="text-end">
                                    ${{ number_format($detalleCorte->PrecioArticulo, 2) }}
                                </td>
                                <td class="text-end">
                                    ${{ number_format($detalleCorte->IvaArticulo, 2) }}
                                </td>
                                <td class="text-end">
                                    ${{ number_format($detalleCorte->ImporteArticulo, 2) }}
                                </td>

                                <!-- Columna Pedido -->
                                <td>
                                    @php
                                        $sourceId = $detalleCorte->Source_Transaction_Identifier ?? null;
                                    @endphp

                                    @if (empty($sourceId) && $detalleCorte->SolicitudCancelacion != null)
                                        <span class="tags-red">Solicitud Cancelación</span>
                                    @elseif(empty($sourceId))
                                        <span class="tags-red">SIN PEDIDO</span>
                                    @else
                                        <span class="{{ $oracleInfo->STATUS == 'ERROR' ? 'tags-red' : 'tags-blue' }}">
                                            {{ substr_replace($sourceId, '_', 3, 0) }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Columna Status -->
                                <td class="text-center">
                                    @php
                                        // Obtener el Source_Transaction_Identifier del detalle
                                        $sourceId = $detalleCorte->Source_Transaction_Identifier ?? null;

                                        // Buscar el status en OracleData usando el sourceId
                                        $oracleInfo = $corteTienda->OracleData[$sourceId] ?? null;
                                        $status = $oracleInfo->STATUS ?? null;
                                        $mensajeError = $oracleInfo->MENSAJE_ERROR ?? null;
                                        $solicitudCancelacion = $detalleCorte->SolicitudCancelacion ?? null;

                                        // Determinar el estado a mostrar
                                        if (!empty($solicitudCancelacion)) {
                                            $statusClass = 'tags-red';
                                            $statusText = 'CANCELACIÓN SOLICITADA';
                                        } elseif (empty($sourceId)) {
                                            $statusClass = 'tags-red';
                                            $statusText = 'SIN PROCESAR';
                                        } elseif ($status == 'ERROR') {
                                            $statusClass = 'tags-red';
                                            $statusText = 'ERROR';
                                        } elseif ($status == 'PROCESADO') {
                                            $statusClass = 'tags-green';
                                            $statusText = 'PROCESADO';
                                        } elseif ($status == 'EN PROCESO') {
                                            $statusClass = 'tags-yellow';
                                            $statusText = 'EN PROCESO';
                                        } elseif (empty($status)) {
                                            $statusClass = 'tags-red';
                                            $statusText = 'SIN PROCESAR';
                                        } else {
                                            $statusClass = 'tags-red';
                                            $statusText = 'SIN PROCESAR';
                                        }
                                    @endphp

                                    <span class="{{ $statusClass }} d-inline-flex align-items-center gap-1">
                                        {{ $statusText }}
                                    </span>
                                </td>
                            </tr>

                            @php
                                $sumCantArticulo = $sumCantArticulo + $detalleCorte->CantArticulo;
                                $sumImporte = $sumImporte + $detalleCorte->ImporteArticulo;
                            @endphp
                        @endforeach

                        <!-- Totales -->
                        <tr class="table-light">
                            <td
                                colspan="2"
                                class="fw-bold p-1 text-end"
                            >SubTotales:</td>
                            <td class="fw-bold p-1 text-end">{{ number_format($sumCantArticulo, 3) }}
                            </td>
                            <td
                                colspan="2"
                                class="p-1"
                            ></td>
                            <td class="fw-bold p-1 text-end">${{ number_format($sumImporte, 2) }}</td>
                            <td
                                colspan="2"
                                class="p-1"
                            ></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endforeach --}}
        @foreach ($facturas as $corteTienda)
            <table id="tblFactura">
                {{-- Caption con cliente y pedidos Oracle --}}
                @php
                    $showUnlinkedWarning =
                        empty($corteTienda->Bill_To) && empty($corteTienda->Customer->IdSolicitudFactura);
                @endphp
                <caption style="text-align: left; padding-bottom: 5px;">
                    @if ($showUnlinkedWarning)
                        FALTA LIGAR CLIENTE - {{ $corteTienda->Customer->NomCliente ?? 'Cliente' }}
                    @else
                        {{ $corteTienda->Customer->NomCliente ?? ($corteTienda->Customer->NomClienteCloud ?? 'Cliente') }}
                        @php
                            $oracleData = $corteTienda->OracleData ?? [];
                        @endphp
                        @forelse ($oracleData as $sourceId => $oracleInfo)
                            @php
                                $pedidoDisplay = $oracleInfo->Source_Transaction_Number ?? null;
                                if (empty($pedidoDisplay)) {
                                    $pedidoDisplay = 'SIN PEDIDO';
                                }
                            @endphp
                            - {{ $pedidoDisplay }}
                        @empty
                            - SIN PEDIDO
                        @endforelse
                    @endif
                </caption>

                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Artículo</th>
                        <th style="text-align: center;">Cantidad</th>
                        <th style="text-align: center;">Precio</th>
                        <th style="text-align: center;">IVA</th>
                        <th style="text-align: center;">Importe</th>
                        <th>Pedido</th>
                        <th>Status</th>
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
                            $mensajeError = $oracleInfo->MENSAJE_ERROR ?? null;
                            $solicitudCancelacion = $detalleCorte->SolicitudCancelacion ?? null;
                        @endphp
                        <tr class="striped">
                            <td>{{ $detalleCorte->CodArticulo }}</td>
                            <td>{{ $detalleCorte->NomArticulo }}</td>
                            <td style="text-align: right; padding-right: 5px;">
                                {{ number_format($detalleCorte->CantArticulo, 4) }}
                            </td>
                            <td style="text-align: right; padding-right: 5px;">
                                ${{ number_format($detalleCorte->PrecioArticulo, 2) }}
                            </td>
                            <td style="text-align: right; padding-right: 5px;">
                                ${{ number_format($detalleCorte->IvaArticulo, 2) }}
                            </td>
                            <td style="text-align: right; padding-right: 10px;">
                                ${{ number_format($detalleCorte->ImporteArticulo, 2) }}
                            </td>

                            {{-- Columna Pedido --}}
                            <td>
                                @if (!empty($solicitudCancelacion))
                                    <span class="tags-red">Solicitud Cancelación</span>
                                @elseif(empty($sourceId))
                                    <span class="tags-red">SIN PEDIDO</span>
                                @else
                                    <span class="{{ $status == 'ERROR' ? 'tags-red' : 'tags-blue' }}">
                                        {{ substr_replace($sourceId, '_', 3, 0) }}
                                    </span>
                                @endif
                            </td>

                            {{-- Columna Status --}}
                            <td>
                                @php
                                    if (!empty($solicitudCancelacion)) {
                                        $statusClass = 'tags-red';
                                        $statusText = 'CANCELACIÓN SOLICITADA';
                                    } elseif (empty($sourceId)) {
                                        $statusClass = 'tags-red';
                                        $statusText = 'SIN PROCESAR';
                                    } elseif ($status == 'ERROR') {
                                        $statusClass = 'tags-red';
                                        $statusText = 'ERROR';
                                    } elseif ($status == 'PROCESADO') {
                                        $statusClass = 'tags-green';
                                        $statusText = 'PROCESADO';
                                    } elseif ($status == 'EN PROCESO') {
                                        $statusClass = 'tags-yellow';
                                        $statusText = 'EN PROCESO';
                                    } else {
                                        $statusClass = 'tags-red';
                                        $statusText = 'SIN PROCESAR';
                                    }
                                @endphp
                                <span class="{{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                        </tr>

                        @php
                            $sumCantArticulo += $detalleCorte->CantArticulo;
                            $sumImporte += $detalleCorte->ImporteArticulo;
                        @endphp
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td></td>
                        <td style="text-align: center; font-weight: bold;">SubTotal:</td>
                        <td style="font-weight: bold; text-align: right; padding-right: 5px;">
                            {{ number_format($sumCantArticulo, 3) }}
                        </td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: bold; text-align: right; padding-right: 10px;">
                            ${{ number_format($sumImporte, 2) }}
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <br>
        @endforeach
    </div>
    <br>
    <!--SUMATORIAS FINALES-->
    <div
        id="DivSumatorias"
        class="container"
    >
        <table id="sumatorias">
            <tbody>
                @php
                    $totalImporte = 0;
                @endphp
                @foreach ($totalMonedero as $monedero)
                    <tr>
                        <td style="text-align: right">{{ $monedero->NomClienteCloud }}: </td>
                        <td style="text-align: right">${{ number_format($monedero->importe, 2) }}</td>
                    </tr>
                    @php
                        $totalImporte += $monedero->importe;
                    @endphp
                @endforeach
                <tr>
                    <td style="text-align: right">Total Dinero Electrónico: </td>
                    <td style="font-weight: bold; color: red; text-align: right;">
                        ${{ number_format($totalImporte, 2) }}
                    </td>
                </tr>
            </tbody>
            <br>
            <tbody>
                <tr>
                    <td style="text-align: right">Crédito Quincenal: </td>
                    <td style="text-align: right">${{ number_format($creditoQuincenal, 2) }}</td>
                </tr>
                <tr>
                    <td style="text-align: right">Crédito Semanal: </td>
                    <td style="text-align: right">${{ number_format($creditoSemanal, 2) }}</td>
                </tr>
                <tr>
                    <td style="text-align: right">Total Créditos: </td>
                    <td style="font-weight: bold; color: red; text-align: right;">
                        ${{ number_format($creditoSemanal + $creditoQuincenal, 2) }}</td>
                </tr>
            </tbody>
            <br>
            <tbody>
                <tr>
                    <td style="text-align: right">Tarjeta Débito: </td>
                    <td style="text-align: right">${{ number_format($totalTarjetaDebito, 2) }}</td>
                </tr>
                <tr>
                    <td style="text-align: right">Tarjeta Crédito: </td>
                    <td style="text-align: right">${{ number_format($totalTarjetaCredito, 2) }}</td>
                </tr>
                <tr>
                    <td style="text-align: right">Total Tarjeta: </td>
                    <td style="font-weight: bold; color: red; text-align: right">
                        ${{ number_format($totalTarjetaDebito + $totalTarjetaCredito, 2) }}
                    </td>
                </tr>
            </tbody>
            <br>
            <tbody>
                <tr>
                    <td style="text-align: right">Total Transferencia: </td>
                    <td style="font-weight: bold; color: red; text-align: right">
                        ${{ number_format($totalTransferencia, 2) }}</td>
                </tr>
                {{-- <tr>
                <td style="text-align: right">Total Factura: </td>
                <td style="font-weight: bold; color: red; text-align: right">${{ number_format($totalFactura, 2) }}
                </td>
            </tr> --}}
                <tr>
                    <td style="text-align: right">Total Efectivo: </td>
                    <td style="font-weight: bold; color: red; text-align:right">${{ number_format($totalEfectivo, 2) }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right">Total General: </td>
                    <td style="font-weight: bold; color: red; text-align:right">
                        ${{ number_format($totalEfectivo + $totalTransferencia + $totalTarjetaDebito + $totalTarjetaCredito + $creditoSemanal + $creditoQuincenal + $totalImporte, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
