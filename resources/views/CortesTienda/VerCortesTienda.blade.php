@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Cortes Por Tienda')
@section('dashboardWidth', 'width-general')
@section('styles')
    @if ($idReporte == 1)
        <style>
            table {
                font-size: 13px;
            }

            #totales {
                color: red;
            }

            #sumatorias {
                text-align: right
            }
        </style>
    @endif
    <style>
        /* Define la animación del spinner */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Estilo para el spinner */
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #f59e0b;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            margin: 0 auto;
            animation: spin 1s linear infinite;
        }

        /* Estilo para el botón deshabilitado */
        #rotateButton,
        #buttonIcon,
        #rotateButtonFac,
        #buttonIconFac {
            color: #1e429f;
            font-weight: bold;
        }

        /* Estilo cuando el modal está abierto (activo) */
        .link-style.opened {
            color: #44aef5;
        }
    </style>

@endsection
@section('contenido')
    <div class="gap-4 pt-4 container-fluid width-general d-flex flex-column">

        <div class="p-4 border-0 card" style="border-radius: 10px">
            <div class="gap-2 d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Cortes de Tienda'])
                <div class="d-flex gap-4">
                    <a href="/GenerarCorteOraclePDF/{{ $fecha1 }}/{{ $idTienda }}/{{ $idCaja }}"
                        target="_blank" type="button" class="btn card">
                        @include('components.icons.print')
                    </a>
                </div>
            </div>
            {{-- </div> --}}

            <!--CONTAINER FILTROS-->
            {{-- <div class="p-4 border-0 card" style="border-radius: 10px"> --}}
            <form class="flex-wrap gap-2 pb-0 m-0 mt-2 d-flex align-items-center justify-content-end"
                action="/VerCortesTienda" method="GET">
                <div class="col-auto">
                    <select class="rounded form-select" style="line-height: 18px" name="idTienda" id="idTienda" required>
                        <option value="">Seleccione Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <select class="rounded form-select" style="line-height: 18px" name="idCaja" id="idCaja" required>
                        <option value="">Seleccione Caja</option>
                        @if ($cajasTienda->count() >= 2)
                            <option {!! $idCaja == 0 ? 'selected' : '' !!} value="0">Todas las cajas</option>
                        @endif
                        @foreach ($cajasTienda as $caja)
                            <option {!! $idCaja == $caja->IdDatCajas ? 'selected' : '' !!} value="{{ $caja->IdDatCajas }}">Caja {{ $caja->IdCaja }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <select class="rounded form-select" style="line-height: 18px" name="idReporte" id="idReporte" required>
                        <option value="">Seleccione Reporte</option>
                        @foreach ($opcionesReporte as $opcionReporte)
                            <option {!! $idReporte == $opcionReporte->IdReporte ? 'selected' : '' !!} value="{{ $opcionReporte->IdReporte }}">
                                {{ $opcionReporte->NomReporte }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <input class="rounded form-control" style="line-height: 18px" type="date" name="fecha1"
                        id="fecha1" value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}" autofocus>
                </div>
                <div class="col-auto">
                    <input {{ empty($fecha2) ? 'hidden disabled' : '' }} class="rounded form-control"
                        style="line-height: 18px" type="date" name="fecha2" id="fecha2"
                        value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-dark-outline">
                        @include('components.icons.search')
                    </button>
                </div>
            </form>
        </div>

        @if ($idReporte == 1)
            <!--CORTE DIARIO DE TIENDA-->
            <span class="mb-0 text-sm text-center fs-5" style="font-weight: 500; font-family: sans-serif; color: #334155">
                Corte diario - {{ $nomTienda }} - Caja {{ $idCaja == 0 ? 'TODAS' : $numCaja }}
            </span>

            <div class="row" style="font-size: small">
                @if ($fecha1 != date('Y-m-d') && Auth::id() == 11)
                    <div class="mb-4 col-12 col-md-6">
                        <a href="/procesarclientescontado/{{ $fecha1 }}/{{ $idTienda }}/{{ $idCaja }}"
                            type="button" class="btn card" style="border-radius: 10px;" id="rotateButton">
                            <span id="buttonIcon">
                                @include('components.icons.cloud-up')
                            </span>
                            Procesar contado
                        </a>
                    </div>
                    <div class="mb-4 col-12 col-md-6">
                        <a href="/procesarclientesfacturas/{{ $fecha1 }}/{{ $idTienda }}/{{ $idCaja }}"
                            type="button" class="btn card" style="border-radius: 10px;" id="rotateButtonFac">
                            <span id="buttonIconFac">
                                @include('components.icons.cloud-up')
                            </span>
                            Procesar facturas
                        </a>
                    </div>
                @endif
                <div class="mb-4 col-12 col-md-6 col-lg-4 mb-lg-0">
                    <div class="p-4 border-0 card" style="border-radius: 10px;">
                        <h6 class="text-dark">Dinero Electrónico</h6>
                        @php
                            $totalImporte = 0;
                        @endphp
                        @foreach ($totalMonedero as $monedero)
                            <div class="d-flex justify-content-between">
                                <span class="text-secondary">{{ $monedero->NomClienteCloud }}: </span>
                                <span>${{ number_format($monedero->importe, 2) }}</span>
                            </div>
                            @php
                                $totalImporte += $monedero->importe;
                            @endphp
                        @endforeach
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Total: </span>
                            <b class="{{ number_format($totalImporte, 2) == 0 ? 'eliminar' : 'send' }}"
                                style="font-size: 16px">
                                ${{ number_format($totalImporte, 2) }}
                            </b>
                        </div>
                    </div>
                </div>
                {{-- Crédito  --}}
                <div class="pb-4 col-12 col-md-6 col-lg-2 pb-lg-0">
                    <div class="p-4 border-0 card" style="border-radius: 10px">
                        <h6 class="text-dark">Crédito</h6>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Crédito Quincenal: </span>
                            <span>${{ number_format($creditoQuincenal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Crédito Semanal: </span>
                            <span>${{ number_format($creditoSemanal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Total Créditos: </span>
                            <b class="{{ number_format($creditoSemanal + $creditoQuincenal, 2) == 0 ? 'eliminar' : 'send' }}"
                                style="font-size: 16px">
                                ${{ number_format($creditoSemanal + $creditoQuincenal, 2) }}
                            </b>
                        </div>
                    </div>
                </div>
                {{-- Tarjeta  --}}
                <div class="pb-4 col-12 col-md-6 col-lg-2 pb-md-0">
                    <div class="p-4 border-0 card" style="border-radius: 10px">
                        <h6 class="text-dark">Tarjeta</h6>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Tarjeta Débito: </span>
                            <span>${{ number_format($totalTarjetaDebito, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Tarjeta Crédito: </span>
                            <span>${{ number_format($totalTarjetaCredito, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Total Tarjeta: </span>
                            <b class="{{ number_format($totalTarjetaDebito + $totalTarjetaCredito, 2) == 0 ? 'eliminar' : 'send' }}"
                                style="font-size: 16px">
                                ${{ number_format($totalTarjetaDebito + $totalTarjetaCredito, 2) }}
                            </b>
                        </div>
                    </div>
                </div>
                {{-- Totales --}}
                <div class="pb-4 col-12 col-md-6 col-lg-2">
                    <div class="p-4 border-0 card" style="border-radius: 10px">
                        <h6 class="text-dark">Totales</h6>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Total Transferencia: </span>
                            <span>${{ number_format($totalTransferencia, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Total Factura: </span>
                            <span>${{ number_format($totalFactura, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Total Efectivo: </span>
                            <b class="{{ number_format($totalEfectivo, 2) == 0 ? 'eliminar' : 'send' }}"
                                style="font-size: 16px">
                                ${{ number_format($totalEfectivo, 2) }}
                            </b>
                        </div>
                    </div>
                </div>
                {{-- Total general --}}
                <div class="pb-0 col-12 col-md-12 col-lg-2">
                    <div class="p-4 border-0 card text-center" style="border-radius: 10px">
                        <h6 class="text-dark">Total General</h6>
                        <b class="{{ number_format($totalEfectivo, 2) == 0 ? 'eliminar' : 'send' }}"
                            style="font-size: 16px">
                            ${{ number_format($totalEfectivo + $totalTarjetaDebito + $totalTarjetaCredito + $creditoSemanal + $creditoQuincenal + $totalImporte, 2) }}
                        </b>
                        {{-- <span>${{ number_format($totalEfectivo + $totalTarjetaDebito + $totalTarjetaCredito + $creditoSemanal + $creditoQuincenal + $totalImporte, 2) }}</span> --}}
                    </div>
                </div>
            </div>

            <!--CLIENTES DE TIENDA (SIN SOLICITUD DE FACTURA)-->
            @foreach ($cortesTienda as $corteTienda)
                <div class="p-4 border-0 content-table content-table-flex-none content-table-full card"
                    style="border-radius: 10px">
                    @foreach ($corteTienda->Customer as $customer)
                        <div class="d-flex justify-content-left">
                            <h6 class="">{{ $customer->NomClienteCloud }}</h6>
                            &nbsp;&nbsp;
                            @foreach ($corteTienda->PedidoOracle as $pedidoOracle)
                                @if (empty($pedidoOracle->Source_Transaction_Identifier))
                                    <h6 class="text-danger ps-1">SIN PEDIDO</h6>
                                @else
                                    <h6 class="text-{{ $pedidoOracle->STATUS == 'ERROR' ? 'danger' : 'success' }} ps-1">
                                        {{ substr_replace($pedidoOracle->Source_Transaction_Identifier, '_', 3, 0) }}
                                    </h6>
                                    @if ($pedidoOracle->STATUS == 'ERROR')
                                        <h6 class="text-white ps-1 rounded-3">
                                            <i style="color: red" class="fa fa-exclamation-circle ps-1"></i>
                                        </h6>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                    <table>
                        <thead class="table-head">
                            <tr>
                                <th class="rounded-start">Código</th>
                                <th>Articulo</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Iva</th>
                                <th>Importe</th>
                                <th>Pedido</th>
                                <th class="rounded-end">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $sumCantArticulo = 0;
                                $sumImporte = 0;
                            @endphp
                            @foreach ($corteTienda->CorteTiendaOracle as $detalleCorte)
                                <tr>
                                    <td style="width: 10vh">{{ $detalleCorte->CodArticulo }}</td>
                                    <td style="width: 60vh">{{ $detalleCorte->NomArticulo }}</td>
                                    <td style="width: 15vh">{{ number_format($detalleCorte->CantArticulo, 4) }}</td>
                                    <td style="width: 15vh">{{ number_format($detalleCorte->PrecioArticulo, 2) }}</td>
                                    <td>{{ number_format($detalleCorte->IvaArticulo, 2) }}</td>
                                    <td style="width: 10vh">{{ number_format($detalleCorte->ImporteArticulo, 2) }}</td>
                                    {{-- SolicitudCancelacion --}}
                                    @if (empty($detalleCorte->Source_Transaction_Identifier) && $detalleCorte->SolicitudCancelacion != null)
                                        <th>
                                            <span class="tags-red">Solicitud Cancelacion</span>
                                        </th>
                                    @elseif (empty($detalleCorte->Source_Transaction_Identifier))
                                        <th>
                                            <span class="tags-red">SIN PEDIDO</span>
                                        </th>
                                    @else
                                        <th>
                                            <span
                                                class="{{ $detalleCorte->STATUS == 'ERROR' ? 'tags-red' : 'tags-blue' }}">
                                                {{ substr_replace($detalleCorte->Source_Transaction_Identifier, '_', 3, 0) }}
                                            </span>
                                        </th>
                                    @endif
                                    @if (
                                        (empty($detalleCorte->STATUS) || $detalleCorte->STATUS == 'NULL') &&
                                            empty($detalleCorte->MENSAJE_ERROR) &&
                                            empty($detalleCorte->Batch_Name))
                                        <th>
                                            <span class="tags-red">SIN PROCESAR</span>
                                        </th>
                                    @endif
                                    @if ($detalleCorte->STATUS == 'ERROR')
                                        <th>
                                            <button class="btn-table btn-table-delete" data-bs-toggle="modal"
                                                data-bs-target="#mensajeError{{ $detalleCorte->IdCortesTienda }}"
                                                title="Ver error">
                                                @include('components.icons.info') VER ERROR
                                            </button>
                                            @include('CortesTienda.ModalMensajeErrorOracle')
                                        </th>
                                    @endif
                                    @if ($detalleCorte->STATUS == 'PROCESADO' || $detalleCorte->STATUS == 'EN PROCESO')
                                        <th>
                                            <span class="tags-green">
                                                {{ $detalleCorte->STATUS }}
                                            </span>
                                        </th>
                                    @endif
                                </tr>
                                @php
                                    $sumCantArticulo = $sumCantArticulo + $detalleCorte->CantArticulo;
                                    $sumImporte = $sumImporte + $detalleCorte->ImporteArticulo;
                                @endphp
                            @endforeach
                        </tbody>

                        <tfoot>
                            <!--INICIA MONEDERO ELECTRONICO-->
                            @foreach ($totalMonedero as $monedero)
                                @if ($corteTienda->Bill_To == $monedero->Bill_To)
                                    <tr>
                                        <th style="width: 10vh"></th>
                                        <th style="width: 60vh; text-align:center">
                                            <h6 style="color: red; font-size: small;">Dinero Electrónico :</h6>
                                        </th>
                                        <th style="width: 15vh">
                                            <h6></h6>
                                        </th>
                                        <th style="width: 15vh"></th>
                                        <th></th>
                                        <th style="width: 15vh; color: red;">
                                            <h6 style="font-size: small;">$ {{ number_format($monedero->importe, 2) }}
                                            </h6>
                                        </th>
                                    </tr>
                                @endif
                            @endforeach
                            <!--TERMINA MONEDERO ELECTRONICO--
                                                                                                                                                                                                                                                                                    <!--INICIA MONEDERO ELECTRONICO PARA EMPLEADOS QUINCENALES-->
                            {{-- @if ($corteTienda->IdTipoNomina == 4)
                                <tr style="font-size: .9rem">
                                    <td></td>
                                    <td class="text-danger" style="text-align: right; font-weight: 500">
                                        Dinero Electrónico:
                                    </td>
                                    <td> </td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-danger" style="font-weight: 500">
                                        ${{ number_format($totalMonederoQuincenal, 2) }}
                                    </td>
                                </tr>
                            @endif --}}
                            <!--TERMINA MONEDERO ELECTRONICO QUINCENALES-->

                            <!--INICIA MONEDERO ELECTRONICO PARA EMPLEADOS SEMANALES-->
                            {{-- @if ($corteTienda->IdTipoNomina == 3)
                                <tr style="font-size: .9rem">
                                    <td></td>
                                    <td class="text-danger" style="text-align: right; font-weight: 500">
                                        Dinero Electrónico:
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-danger" style="font-weight: 500">
                                        ${{ number_format($totalMonederoSemanal, 2) }}
                                    </td>
                                </tr>
                            @endif --}}
                            <!--TERMINA MONEDERO ELECTRONICO SEMANALES-->
                            <tr style="font-size: .9rem">
                                <td></td>
                                <td style="text-align: right; font-weight: 500"> SubTotales: </td>
                                <td style="font-weight: 500">{{ number_format($sumCantArticulo, 3) }}</td>
                                <td></td>
                                <td></td>
                                <td style="font-weight: 500">${{ number_format($sumImporte, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endforeach
            <!--TERMINA CLIENTES DE TIENDA (SIN SOLICITUD DE FACTURA)-->
            <!--SOLICITUDES DE FACTURA-->
            @foreach ($facturas as $factura)
                @if (count($factura->Factura) !== 0)
                    <div class="p-4 border-0 content-table content-table-flex-none content-table-full card"
                        style="border-radius: 10px">
                        <div class="d-flex justify-content-left">
                            @if (empty($factura->Bill_To) && empty($factura->IdClienteCloud))
                                <h6 class="p-1 text-white bg-danger rounded-3"><i class="fa fa-exclamation-triangle"></i>
                                    FALTA
                                    LIGAR CLIENTE
                                    -
                                    {{ $factura->NomCliente }} <i class="fa fa-exclamation-triangle"></i></h6>
                            @else
                                <h6 class="p-1 ps-4 rounded-3">{{ $factura->NomCliente }}</h6>
                            @endif
                            @foreach ($factura->PedidoOracle as $pedidoOracle)
                                @if (empty($pedidoOracle->Source_Transaction_Identifier))
                                    <h6 class="p-1 text-danger">SIN PEDIDO</h6>
                                @else
                                    <h6 class="text-{{ $pedidoOracle->STATUS == 'ERROR' ? 'danger' : 'success' }} ps-1">
                                        {{ substr_replace($pedidoOracle->Source_Transaction_Identifier, '_', 3, 0) }}
                                    </h6>
                                    @if ($pedidoOracle->STATUS == 'ERROR')
                                        <h6 class="text-white ps-1 rounded-3">
                                            <i style="color: red" class="fa fa-exclamation-circle ps-1"></i>
                                        </h6>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                        <table>
                            <thead class="table-head">
                                <tr>
                                    <th class="rounded-start">Código</th>
                                    <th>Articulo</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Iva</th>
                                    <th>Importe</th>
                                    <th>Pedido</th>
                                    <th class="rounded-end">Status</th>
                                </tr>
                            </thead>
                            <tbody class="cuchi">
                                @php
                                    $sumCantArticulo = 0;
                                    $sumImporte = 0;
                                @endphp
                                @foreach ($factura->Factura as $detalleFactura)
                                    <tr>
                                        <td style="width: 10vh">{{ $detalleFactura->CodArticulo }}</td>
                                        <td style="width: 60vh">{{ $detalleFactura->NomArticulo }}</td>
                                        <td style="width: 15vh">
                                            {{ number_format($detalleFactura->PivotDetalle->CantArticulo, 4) }}
                                        </td>
                                        <td style="width: 15vh">
                                            {{ number_format($detalleFactura->PivotDetalle->PrecioArticulo, 2) }}
                                        </td>
                                        <td>{{ number_format($detalleFactura->PivotDetalle->IvaArticulo, 2) }}</td>
                                        <td style="width: 15vh">
                                            {{ number_format($detalleFactura->PivotDetalle->ImporteArticulo, 2) }}
                                        </td>
                                        @if (empty($detalleFactura->Source_Transaction_Identifier))
                                            <th>
                                                <span class="tags-red">SIN PEDIDO</span>
                                            </th>
                                        @else
                                            <th>
                                                <span
                                                    class="{{ $detalleFactura->STATUS == 'ERROR' ? 'tags-red' : 'tags-blue' }}">
                                                    {{ substr_replace($detalleFactura->Source_Transaction_Identifier, '_', 3, 0) }}
                                                </span>
                                            </th>
                                        @endif
                                        @if (
                                            (empty($detalleFactura->STATUS) || $detalleFactura->STATUS == 'NULL') &&
                                                empty($detalleFactura->MENSAJE_ERROR) &&
                                                empty($detalleFactura->Batch_Name))
                                            <th>
                                                <span class="tags-red">SIN PROCESAR</span>
                                            </th>
                                        @endif
                                        @if ($detalleFactura->STATUS == 'ERROR')
                                            <th>
                                                <button class="btn-table btn-table-delete" data-bs-toggle="modal"
                                                    data-bs-target="#mensajeError{{ $detalleFactura->IdCortesTienda }}"
                                                    title="Ver error">
                                                    @include('components.icons.info') VER ERROR
                                                </button>
                                                @include('CortesTienda.ModalMensajeErrorOracle')
                                            </th>
                                        @endif
                                        @if ($detalleFactura->STATUS == 'PROCESADO' || $detalleFactura->STATUS == 'EN PROCESO')
                                            <th>
                                                <span class="tags-green">
                                                    {{ $detalleFactura->STATUS }}
                                                </span>
                                            </th>
                                        @endif
                                    </tr>
                                    @php
                                        $sumCantArticulo =
                                            $sumCantArticulo + $detalleFactura->PivotDetalle->CantArticulo;
                                        $sumImporte = $sumImporte + $detalleFactura->PivotDetalle->ImporteArticulo;
                                    @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="font-size: .9rem">
                                    <td></td>
                                    <td style="text-align: right; font-weight: 500"> SubTotales: </td>
                                    <td style="font-weight: 500"> {{ number_format($sumCantArticulo, 3) }} </td>
                                    <td></td>
                                    <td></td>
                                    <td style="font-weight: 500"> ${{ number_format($sumImporte, 2) }} </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            @endforeach
        @elseif($idReporte == 2)
            <!--CONCENTRADO DE VENTAS POR RANGO DE FECHAS-->
            <div class="p-4 border-0 content-table content-table-full card" style="border-radius: 10px">
                <span class="mb-2 text-sm fs-5" style="font-weight: 500; font-family: sans-serif; color: #334155">
                    Concentrado de ventas - {{ $nomTienda }} - Caja {{ $idCaja == 0 ? 'TODAS' : $numCaja }}
                </span>
                <table>
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Código</th>
                            <th>Nombre</th>
                            <th>Familia</th>
                            <th>Grupo</th>
                            <th>Peso</th>
                            <th>Precio</th>
                            <th>Iva</th>
                            <th class="rounded-end">Importe</th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('components.table-empty', [
                            'items' => $concentrado,
                            'colspan' => 8,
                        ])
                        @foreach ($concentrado as $tConcentrado)
                            <tr>
                                <td>{{ $tConcentrado->CodArticulo }}</td>
                                <td>{{ $tConcentrado->NomArticulo }}</td>
                                <td>{{ $tConcentrado->NomFamilia }}</td>
                                <td>{{ $tConcentrado->NomGrupo }}</td>
                                <td>{{ number_format($tConcentrado->Peso, 3) }}</td>
                                <td>{{ number_format($tConcentrado->PrecioArticulo, 2) }}</td>
                                <td>{{ number_format($tConcentrado->Iva, 2) }}</td>
                                <td>{{ number_format($tConcentrado->Importe, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="text-align: right; font-weight: 500">Totales :</td>
                            <td style="font-weight: 500">{{ number_format($totalPeso, 3) }}</td>
                            <td></td>
                            <td style="font-weight: 500">${{ number_format($totalIva, 2) }}</td>
                            <td style="font-weight: 500">${{ number_format($totalImporte, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @elseif($idReporte == 3)
            <!--VENTA POR TICKET DIARIO-->
            <div class="p-4 border-0 content-table content-table-full card" style="border-radius: 10px">
                <span class="mb-2 text-sm fs-5" style="font-weight: 500; font-family: sans-serif; color: #334155">
                    Venta por ticket diario - {{ $nomTienda }} - Caja {{ $idCaja == 0 ? 'TODAS' : $numCaja }}
                </span>
                @if ($idCaja === 0)
                    <!--VER TODAS LAS CAJAS-->
                    <div class="row">
                        @foreach ($tickets as $key => $ticket)
                            <div class="mb-3 col-xxl-6">
                                <table class="w-100">
                                    <thead class="table-head">
                                        <tr>
                                            <th class="rounded-start">Caja</th>
                                            <th>Ticket</th>
                                            <th>Fecha</th>
                                            <th>Importe</th>
                                            <th>Iva</th>
                                            <th>Detalle</th>
                                            <th>Factura</th>
                                            <th class="rounded-end">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalImporte = 0;
                                            $totalIva = 0;
                                        @endphp
                                        @include('components.table-empty', [
                                            'items' => $ticket,
                                            'colspan' => 8,
                                        ])
                                        @foreach ($ticket as $tVenta)
                                            <tr>
                                                <th>{{ $tVenta->Caja->IdCaja }}</th>
                                                <td>{{ $tVenta->IdTicket }}</td>
                                                <td>{{ strftime('%d %B %Y, %H:%M', strtotime($tVenta->FechaVenta)) }}
                                                </td>
                                                <td>$ {{ number_format($tVenta->ImporteVenta, 2) }}</td>
                                                <td>{{ number_format($tVenta->Iva, 2) }}</td>
                                                <td>
                                                    <i style="color: rgb(255, 145, 0); cursor: pointer; font-size: 20px"
                                                        class="fa fa-info-circle" data-bs-toggle="modal"
                                                        data-bs-target="#ModalDetalleTicket{{ $tVenta->IdDatEncabezado }}"></i>
                                                    @include('CortesTienda.ModalDetalleTicket')
                                                    <i style="color: green; cursor: pointer; font-size: 20px"
                                                        class="fa fa-usd" data-bs-toggle="modal"
                                                        data-bs-target="#ModalTicketTipoPago{{ $tVenta->IdDatEncabezado }}"></i>
                                                    @include('CortesTienda.ModalTicketTipoPago')
                                                </td>
                                                <td>
                                                    @if ($tVenta->SolicitudFE == 0 && $tVenta->SolicitudFE != null)
                                                        <i style="font-size: 18px; cursor: pointer"
                                                            class="fa fa-check-square" data-bs-toggle="modal"
                                                            data-bs-target="#ModalSolicitudFe{{ $tVenta->IdDatEncabezado }}"></i>
                                                        @include('CortesTienda.ModalSolicitudFe')
                                                    @endif
                                                </td>
                                                <td style="color: red;">
                                                    @if ($tVenta->StatusVenta == 1)
                                                        <i style="font-size: 20px" class="fa fa-ban"></i>
                                                    @endif
                                                    @if (!empty($tVenta->SolicitudCancelacionTicket) and $tVenta->StatusVenta == 0)
                                                        <i style="font-size: 20px" class="fa fa-hourglass-start"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                            @if ($tVenta->StatusVenta == 0 && empty($tVenta->SolicitudCancelacionTicket))
                                                @php
                                                    $totalImporte = $totalImporte + $tVenta->ImporteVenta;
                                                    $totalIva = $totalIva + $tVenta->Iva;
                                                @endphp
                                            @endif
                                        @endforeach
                                    </tbody>
                                    @if (count($ticket) > 0)
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align: center">Totales: </th>
                                                <th>${{ number_format($totalImporte, 2) }}</th>
                                                <th>${{ number_format($totalIva, 2) }}</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        @endforeach
                    </div>
                @else
                    <table>
                        <thead class="table-head">
                            <tr>
                                <th class="rounded-start">Ticket</th>
                                <th>Fecha</th>
                                <th>Importe</th>
                                <th>Iva</th>
                                <th>Solicitud Factura</th>
                                <th>Status Venta</th>
                                <th class="rounded-end">Detalle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($tickets->count() == 0)
                                <tr>
                                    <td colspan="7">No Hay Ventas</td>
                                </tr>
                            @endif
                            @foreach ($tickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->IdTicket }}</td>
                                    <td>{{ strftime('%d %B %Y, %H:%M', strtotime($ticket->FechaVenta)) }}</td>
                                    <td>$ {{ number_format($ticket->ImporteVenta, 2) }}</td>
                                    <td>{{ number_format($ticket->Iva, 2) }}</td>
                                    <td>
                                        @if ($ticket->SolicitudFE == 0 && $ticket->SolicitudFE != null)
                                            <button class="btn-table" data-bs-toggle="modal"
                                                data-bs-target="#ModalSolicitudFe{{ $ticket->IdTicket }}"
                                                title="Ver solicitud de factura">
                                                @include('components.icons.check')
                                            </button>
                                            @include('Posweb.ModalSolicitudFe')
                                        @endif
                                    </td>
                                    <td style="color: red;">
                                        @if ($ticket->StatusVenta == 1)
                                            <span class="tags-red" title="Ticket cancelado">
                                                @include('components.icons.x')
                                            </span>
                                        @endif
                                        @isset($ticket->SolicitudCancelacionTicket)
                                            @if ($ticket->SolicitudCancelacionTicket->SolicitudAprobada == 0 and $ticket->StatusVenta == 0)
                                                <span class="tags-red" title="En proceso de cancelación">
                                                    @include('components.icons.loading') En proceso de cancelación
                                                </span>
                                            @endif
                                            @if ($ticket->SolicitudCancelacionTicket->SolicitudAprobada == 1 and $ticket->StatusVenta == 0)
                                                <span class="tags-red" title="Ticket con solicitud de cancelación, cancelada">
                                                    {{-- @include('components.icons.loading') --}}
                                                    Solicitud cancelada
                                                </span>
                                            @endif
                                        @endisset
                                    </td>
                                    <td>
                                        <button class="btn-table btn-table-show link-style" data-bs-toggle="modal"
                                            data-bs-target="#ModalDetalleTicket{{ $ticket->IdTicket }}"
                                            title="Detalle de ticket" id="btnTicket{{ $ticket->IdTicket }}">
                                            @include('components.icons.list')
                                        </button>
                                        <button class="btn-table btn-table-success" data-bs-toggle="modal"
                                            data-bs-target="#ModalTipoPago{{ $ticket->IdTicket }}"
                                            title="Detalle de pago">
                                            @include('components.icons.dolar')
                                        </button>
                                        @include('Posweb.ModalDetalleTicket')
                                        @include('Posweb.ModalTipoPago')
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td style="text-align: right; font-weight: 500">Totales: </td>
                                <td style="font-weight: 500">${{ number_format($total, 2) }}</td>
                                <td style="font-weight: 500">${{ number_format($totalIva, 2) }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                @endif
            </div>
        @elseif($idReporte == 4)
            <!--TICKETS CANCELADOS-->
            <div class="p-4 border-0 content-table content-table-full card" style="border-radius: 10px">
                <span class="mb-2 text-sm fs-5" style="font-weight: 500; font-family: sans-serif; color: #334155">
                    Tickets Cancelados - {{ $nomTienda }} - Caja {{ $idCaja == 0 ? 'Todas' : $numCaja }}
                </span>
                <table>
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Caja</th>
                            <th>Ticket</th>
                            <th>Fecha</th>
                            <th>Importe</th>
                            <th>Iva</th>
                            <th>Solicitud Factura</th>
                            <th>Status Venta</th>
                            <th class="rounded-end">Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('components.table-empty', [
                            'items' => $ticketsCancelados,
                            'colspan' => 8,
                        ])
                        @foreach ($ticketsCancelados as $ticket)
                            <tr>
                                <th>{{ $ticket->Caja->IdCaja }}</th>
                                <td>{{ $ticket->IdTicket }}</td>
                                <td>{{ strftime('%d %B %Y, %H:%M', strtotime($ticket->FechaVenta)) }}</td>
                                <td>$ {{ number_format($ticket->ImporteVenta, 2) }}</td>
                                <td>{{ number_format($ticket->Iva, 2) }}</td>
                                <td>
                                    @if ($ticket->SolicitudFE == 0 && $ticket->SolicitudFE != null)
                                        <button class="btn-table" data-bs-toggle="modal"
                                            data-bs-target="#ModalSolicitudFe{{ $ticket->IdTicket }}"
                                            title="Ver solicitud de factura">
                                            @include('components.icons.check')
                                        </button>
                                        @include('Posweb.ModalSolicitudFe')
                                    @endif
                                </td>
                                <td>
                                    @if ($ticket->StatusVenta == 1)
                                        <span class="tags-red">
                                            @include('components.icons.x')
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn-table btn-table-show" data-bs-toggle="modal"
                                        data-bs-target="#ModalDetalleTicket{{ $ticket->IdDatEncabezado }}"
                                        title="Detalle de ticket">
                                        @include('components.icons.list')
                                    </button>
                                    <button class="btn-table btn-table-success" data-bs-toggle="modal"
                                        data-bs-target="#ModalTipoPago{{ $ticket->IdDatEncabezado }}"
                                        title="Detalle de pago">
                                        @include('components.icons.dolar')
                                    </button>
                                    <button class="btn-table btn-table-show" data-bs-toggle="modal"
                                        data-bs-target="#ModalComentarioTicketCancelado{{ $ticket->IdDatEncabezado }}"
                                        title="Motivo de cancelación">
                                        @include('components.icons.message')
                                    </button>
                                    @include('CortesTienda.ModalDetalleTicketCancelado')
                                    @include('CortesTienda.ModalTipoPago')
                                    @include('CortesTienda.ModalComentarioTicketCancelado')
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    @if (count($ticketsCancelados) > 0)
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td style="text-align: right; font-weight: 500">Totales: </td>
                                <td style="font-weight: 500">${{ number_format($total, 2) }}</td>
                                <td style="font-weight: 500">${{ number_format($totalIva, 2) }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        @endif

    </div>

    <script>
        // Agregar un listener para el evento 'shown.bs.modal' de Bootstrap
        document.addEventListener('DOMContentLoaded', function() {
            // Obtén todos los modales
            const modals = document.querySelectorAll('.modal');

            modals.forEach(modal => {
                modal.addEventListener('shown.bs.modal', function() {
                    // Obtén el ID del modal
                    const modalId = modal.id.replace('ModalDetalleTicket', '');
                    // Encuentra el botón correspondiente
                    const button = document.querySelector('#btnTicket' + modalId);
                    if (button) {
                        // Agrega la clase 'opened' para marcar el botón como clicado
                        button.classList.add('opened');
                    }
                });
            });
        });

        const fecha2 = document.getElementById('fecha2');
        document.getElementById('idReporte').addEventListener('change', (e) => {
            const reporte = document.getElementById('idReporte').value
            if (reporte == 1 || reporte == 3 || reporte == '') {
                fecha2.hidden = true;
                fecha2.disabled = true;
            } else {
                fecha2.disabled = false;
                fecha2.hidden = false;
            }
        });

        const idTienda = document.getElementById('idTienda');
        const idCaja = document.getElementById('idCaja');
        idTienda.addEventListener('change', (e) => {
            var options = document.querySelectorAll('#idCaja option');
            options.forEach(o => o.remove());
            fetch('/BuscarCajasTienda?idTienda=' + idTienda.value)
                .then(res => res.json())
                .then(respuesta => {
                    if (respuesta != '') {
                        if (respuesta.length >= 2) {
                            const optionCaja = document.createElement('option');
                            optionCaja.value = 0;
                            optionCaja.text = 'Todas las Cajas';
                            idCaja.add(optionCaja);
                        }
                        for (const key in respuesta) {
                            if (Object.hasOwnProperty.call(respuesta, key)) {
                                const caja = respuesta[key];
                                const optionCaja = document.createElement('option');
                                optionCaja.value = caja.IdDatCajas;
                                optionCaja.text = 'Caja ' + caja.IdCaja;
                                idCaja.add(optionCaja);
                            }
                        }
                    } else {
                        const SinCaja = document.createElement('option');
                        SinCaja.value = '';
                        SinCaja.text = 'No hay cajas para esta tienda';
                        idCaja.add(SinCaja);
                    }
                });
        });

        document.addEventListener('click', e => {
            if (e.target.matches('#rotateButton') || e.target.matches('#rotateButton *')) {
                let button = document.getElementById('rotateButton');
                let buttonIcon = document.getElementById('buttonIcon'); // El contenedor del icono
                if (button.getAttribute('disabled')) {
                    e.preventDefault()
                } else {
                    // e.preventDefault()
                    button.setAttribute('disabled', 'true'); // Deshabilitar el botón
                    button.style += 'border-radius: 10px; opacity: 0.5; cursor: wait;';
                    buttonIcon.setAttribute('disabled', 'true'); // Deshabilitar el botón

                    // Mostrar el spinner en lugar del icono
                    buttonIcon.innerHTML = '<div class="spinner"></div>'; // Reemplazar el icono por el spinner
                }
            }

            if (e.target.matches('#rotateButtonFac') || e.target.matches('#rotateButtonFac *')) {
                let button = document.getElementById('rotateButtonFac');
                let buttonIcon = document.getElementById('buttonIconFac'); // El contenedor del icono
                if (button.getAttribute('disabled')) {
                    e.preventDefault()
                } else {
                    // e.preventDefault()
                    button.setAttribute('disabled', 'true'); // Deshabilitar el botón
                    button.style += 'border-radius: 10px; opacity: 0.5; cursor: wait;';
                    buttonIcon.setAttribute('disabled', 'true'); // Deshabilitar el botón

                    // Mostrar el spinner en lugar del icono
                    buttonIcon.innerHTML = '<div class="spinner"></div>'; // Reemplazar el icono por el spinner
                }
            }
        })
    </script>
@endsection
