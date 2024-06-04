@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Cortes Por Tienda')
@section('dashboardWidth', 'width-general')
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
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <!--TITULO-->
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Cortes de Tienda'])
            <div>
                <a href="/GenerarCorteOraclePDF/{{ $fecha1 }}/{{ $idTienda }}/{{ $idCaja }}" target="_blank"
                    type="button" class="btn card">
                    <span class="material-icons">print</span>
                </a>
            </div>
        </div>

        <!--CONTAINER FILTROS-->
        <form class="d-flex align-items-center justify-content-end flex-wrap pb-2 gap-2" action="/VerCortesTienda"
            method="GET">
            <div class="col-auto">
                <select class="form-select" name="idTienda" id="idTienda" required>
                    <option value="">Seleccione Tienda</option>
                    @foreach ($tiendas as $tienda)
                        <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <select class="form-select" name="idCaja" id="idCaja" required>
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
                <select class="form-select" name="idReporte" id="idReporte" required>
                    <option value="">Seleccione Reporte</option>
                    @foreach ($opcionesReporte as $opcionReporte)
                        <option {!! $idReporte == $opcionReporte->IdReporte ? 'selected' : '' !!} value="{{ $opcionReporte->IdReporte }}">
                            {{ $opcionReporte->NomReporte }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <input class="form-control" type="date" name="fecha1" id="fecha1"
                    value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
            </div>
            <div class="col-auto">
                <input {{ empty($fecha2) ? 'hidden disabled' : '' }} class="form-control" type="date" name="fecha2"
                    id="fecha2" value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
            </div>
            <div class="col-auto">
                <button class="btn card">
                    <span class="material-icons">search</span>
                </button>
            </div>
        </form>

        @if ($idReporte == 1)
            <!--CORTE DIARIO DE TIENDA-->
            <h4 class="pb-2 text-center">CORTE DIARIO - {{ $nomTienda }} - CAJA:
                {{ $idCaja == 0 ? 'TODAS' : $numCaja }}</h4>

            <div class="row">
                {{-- Dinero Electrónico --}}
                <div class="col-12 col-md-6 col-lg-3 pb-4">
                    <div class="card p-4" style="border-radius: 20px;">
                        <h6 class="text-dark">Dinero Electrónico</h6>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Crédito Quincenal: </span>
                            <span>${{ number_format($totalMonederoQuincenal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Crédito Semanal: </span>
                            <span>${{ number_format($totalMonederoSemanal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Total: </span>
                            <b class="{{ number_format($totalMonederoQuincenal + $totalMonederoSemanal, 2) == 0 ? 'eliminar' : 'send' }}"
                                style="font-size: 16px">
                                ${{ number_format($totalMonederoQuincenal + $totalMonederoSemanal, 2) }}
                            </b>
                        </div>
                    </div>
                </div>
                {{-- Crédito  --}}
                <div class="col-12 col-md-6 col-lg-3 pb-4">
                    <div class="card p-4" style="border-radius: 20px;">
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
                <div class="col-12 col-md-6 col-lg-3 pb-4">
                    <div class="card p-4" style="border-radius: 20px;">
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
                <div class="col-12 col-md-6 col-lg-3 pb-4">
                    <div class="card p-4" style="border-radius: 20px;">
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
            </div>

            <!--CLIENTES DE TIENDA (SIN SOLICITUD DE FACTURA)-->
            @foreach ($cortesTienda as $corteTienda)
                <div class="content-table content-table-full card p-4 mb-4" style="border-radius: 20px">
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
                                        <h6 class="ps-1 text-white rounded-3">
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
                                    @if (empty($detalleCorte->Source_Transaction_Identifier))
                                        <th style="color: red">SIN PEDIDO</th>
                                    @else
                                        <th style="color: {{ $detalleCorte->STATUS == 'ERROR' ? 'red' : 'blue' }}">
                                            {{ substr_replace($detalleCorte->Source_Transaction_Identifier, '_', 3, 0) }}
                                        </th>
                                    @endif
                                    @if (
                                        (empty($detalleCorte->STATUS) || $detalleCorte->STATUS == 'NULL') &&
                                            empty($detalleCorte->MENSAJE_ERROR) &&
                                            empty($detalleCorte->Batch_Name))
                                        <th style="color: red">SIN PROCESAR</th>
                                    @endif
                                    @if ($detalleCorte->STATUS == 'ERROR')
                                        <th style="cursor: pointer" data-bs-toggle="modal"
                                            data-bs-target="#mensajeError{{ $detalleCorte->IdCortesTienda }}">
                                            <i style="color: red; cursor: pointer; font-size: 18px"
                                                class="fa fa-exclamation-circle">
                                            </i> VER ERROR
                                            @include('CortesTienda.ModalMensajeErrorOracle')
                                        </th>
                                    @endif
                                    @if ($detalleCorte->STATUS == 'PROCESADO' || $detalleCorte->STATUS == 'EN PROCESO')
                                        <th>{{ $detalleCorte->STATUS }}</th>
                                    @endif
                                </tr>
                                @php
                                    $sumCantArticulo = $sumCantArticulo + $detalleCorte->CantArticulo;
                                    $sumImporte = $sumImporte + $detalleCorte->ImporteArticulo;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>

                    <!--INICIA MONEDERO ELECTRONICO PARA EMPLEADOS QUINCENALES-->
                    <table>
                        @if ($corteTienda->IdTipoNomina == 4)
                            <tr>
                                <th style="width: 10vh"></th>
                                <th style="width: 60vh; text-align:center">
                                    <h6 style="color: red">Dinero Electrónico :</h6>
                                </th>
                                <th style="width: 15vh">
                                    <h6></h6>
                                </th>
                                <th style="width: 15vh"></th>
                                <th></th>
                                <th style="width: 15vh; color: red;">
                                    <h6>$ {{ number_format($totalMonederoQuincenal, 2) }}</h6>
                                </th>
                            </tr>
                        @endif
                        <!--TERMINA MONEDERO ELECTRONICO QUINCENALES-->

                        <!--INICIA MONEDERO ELECTRONICO PARA EMPLEADOS SEMANALES-->
                        @if ($corteTienda->IdTipoNomina == 3)
                            <tr>
                                <th style="width: 10vh"></th>
                                <th style="width: 60vh; text-align:center">
                                    <h6 style="color: red">Dinero Electrónico :</h6>
                                </th>
                                <th style="width: 15vh">
                                    <h6></h6>
                                </th>
                                <th style="width: 15vh"></th>
                                <th></th>
                                <th style="width: 15vh; color: red;">
                                    <h6>$ {{ number_format($totalMonederoSemanal, 2) }}</h6>
                                </th>
                            </tr>
                        @endif
                        <!--TERMINA MONEDERO ELECTRONICO SEMANALES-->
                        <tr>
                            <th style="width: 10vh"></th>
                            <th style="width: 60vh; text-align:center">
                                <h6>SubTotales :</h6>
                            </th>
                            <th style="width: 15vh">
                                <h6>{{ number_format($sumCantArticulo, 3) }}</h6>
                            </th>
                            <th style="width: 15vh"></th>
                            <th></th>
                            <th style="width: 15vh">
                                <h6>$ {{ number_format($sumImporte, 2) }}</h6>
                            </th>
                        </tr>
                    </table>
                </div>
            @endforeach
            <!--TERMINA CLIENTES DE TIENDA (SIN SOLICITUD DE FACTURA)-->
            <!--SOLICITUDES DE FACTURA-->
            @foreach ($facturas as $factura)
                @if(count($factura->Factura)!==0)
                <div class="content-table content-table-full card p-4 mb-4" style="border-radius: 20px">
                    <div class="d-flex justify-content-left">
                        @if (empty($factura->Bill_To) && empty($factura->IdClienteCloud))
                            <h6 class="p-1 bg-danger text-white rounded-3"><i class="fa fa-exclamation-triangle"></i>
                                FALTA
                                LIGAR CLIENTE
                                -
                                {{ $factura->NomCliente }} <i class="fa fa-exclamation-triangle"></i></h6>
                        @else
                            <h6 class="ps-4 p-1 rounded-3">{{ $factura->NomCliente }}</h6>
                        @endif
                        @foreach ($factura->PedidoOracle as $pedidoOracle)
                            @if (empty($pedidoOracle->Source_Transaction_Identifier))
                                <h6 class="text-danger p-1">SIN PEDIDO</h6>
                            @else
                                <h6 class="text-{{ $pedidoOracle->STATUS == 'ERROR' ? 'danger' : 'success' }} ps-1">
                                    {{ substr_replace($pedidoOracle->Source_Transaction_Identifier, '_', 3, 0) }}
                                </h6>
                                @if ($pedidoOracle->STATUS == 'ERROR')
                                    <h6 class="ps-1 text-white rounded-3">
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
                                        <th style="color: red">SIN PEDIDO</th>
                                    @else
                                        <th style="color: {{ $detalleFactura->STATUS == 'ERROR' ? 'red' : 'blue' }}">
                                            {{ substr_replace($detalleFactura->Source_Transaction_Identifier, '_', 3, 0) }}
                                        </th>
                                    @endif
                                    @if (
                                        (empty($detalleFactura->STATUS) || $detalleFactura->STATUS == 'NULL') &&
                                            empty($detalleFactura->MENSAJE_ERROR) &&
                                            empty($detalleFactura->Batch_Name))
                                        <th style="color: red">SIN PROCESAR</th>
                                    @endif
                                    @if ($detalleFactura->STATUS == 'ERROR')
                                        <th style="cursor: pointer" data-bs-toggle="modal"
                                            data-bs-target="#mensajeError{{ $detalleFactura->IdCortesTienda }}">
                                            <i style="color: red; cursor: pointer; font-size: 18px"
                                                class="fa fa-exclamation-circle">
                                            </i> VER ERROR
                                            @include('CortesTienda.ModalMensajeErrorOracle')
                                        </th>
                                    @endif
                                    @if ($detalleFactura->STATUS == 'PROCESADO' || $detalleFactura->STATUS == 'EN PROCESO')
                                        <th>{{ $detalleFactura->STATUS }}</th>
                                    @endif
                                </tr>
                                @php
                                    $sumCantArticulo = $sumCantArticulo + $detalleFactura->PivotDetalle->CantArticulo;
                                    $sumImporte = $sumImporte + $detalleFactura->PivotDetalle->ImporteArticulo;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                    <table>
                        <tr>
                            <th style="width: 10vh"></th>
                            <th style="width: 60vh; text-align:center">
                                <h6>SubTotales :</h6>
                            </th>
                            <th style="width: 15vh">
                                <h6>{{ number_format($sumCantArticulo, 3) }}</h6>
                            </th>
                            <th style="width: 15vh"></th>
                            <th></th>
                            <th style="width: 15vh">
                                <h6>$ {{ number_format($sumImporte, 2) }}</h6>
                            </th>
                        </tr>
                    </table>
                </div>
                @endif
            @endforeach
            <!--TERMINA SOLICITUDES DE FACTURA-->
            <!--SUMATORIAS FINALES-->
            {{-- <div class="container mb-3">
                <div class="row d-flex justify-content-end">
                    <div class="col-auto">
                        <table id="sumatorias" style="font-size: 18px" class="table">
                            <thead>
                                <tr>
                                    <td>Dinero Electrónico Crédito Quincenal: </td>
                                    <td>${{ number_format($totalMonederoQuincenal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Dinero Electrónico Crédito Semanal: </td>
                                    <td>${{ number_format($totalMonederoSemanal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Total Dinero Electrónico: </td>
                                    <td style="font-weight: bold; color: red;">
                                        ${{ number_format($totalMonederoQuincenal + $totalMonederoSemanal, 2) }}</td>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <td>Crédito Quincenal: </td>
                                    <td>${{ number_format($creditoQuincenal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Crédito Semanal: </td>
                                    <td>${{ number_format($creditoSemanal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Total Créditos: </td>
                                    <td style="font-weight: bold; color: red;">
                                        ${{ number_format($creditoSemanal + $creditoQuincenal, 2) }}
                                    </td>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <td>Tarjeta Débito: </td>
                                    <td>${{ number_format($totalTarjetaDebito, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Tarjeta Crédito: </td>
                                    <td>${{ number_format($totalTarjetaCredito, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Total Tarjeta: </td>
                                    <td style="font-weight: bold; color: red;">
                                        ${{ number_format($totalTarjetaDebito + $totalTarjetaCredito, 2) }}
                                    </td>
                                </tr>
                            </thead>
                            <thead>
                                <tr>
                                    <td>Total Transferencia: </td>
                                    <td style="font-weight: bold; color: red;">
                                        ${{ number_format($totalTransferencia, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total Factura: </td>
                                    <td style="font-weight: bold; color: red;">${{ number_format($totalFactura, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Total Efectivo: </td>
                                    <td style="font-weight: bold; color: red;">${{ number_format($totalEfectivo, 2) }}
                                    </td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div> --}}
        @elseif($idReporte == 2)
            <!--CONCENTRADO DE VENTAS POR RANGO DE FECHAS-->
            <div class="content-table content-table-full card p-4" style="border-radius: 20px">
                <h4 class="p-1" style="font-size: 20px">CONCENTRADO DE VENTAS - {{ $nomTienda }} - CAJA:
                    {{ $idCaja == 0 ? 'TODAS' : $numCaja }}</h4>
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
                        @if ($concentrado->count() == 0)
                            <tr>
                                <td colspan="6">No Hay Ventas en Rango de Fechas Seleccionadas!</td>
                            </tr>
                        @else
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
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th style="text-align: center">Totales :</th>
                            <th>{{ number_format($totalPeso, 3) }}</th>
                            <th></th>
                            <th>${{ number_format($totalIva, 2) }}</th>
                            <th>${{ number_format($totalImporte, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @elseif($idReporte == 3)
            <!--VENTA POR TICKET DIARIO-->
            <div class="content-table content-table-full card p-4" style="border-radius: 20px">
                <h4 class="p-1" style="font-size: 20px">VENTA POR TICKET DIARIO - {{ $nomTienda }} - CAJA:
                    {{ $idCaja == 0 ? 'TODAS' : $numCaja }}</h4>
                @if ($idCaja == 0)
                    <!--VER TODAS LAS CAJAS-->
                    <div class="row">
                        @foreach ($tickets as $key => $ticket)
                            <div class="col-xxl-6 mb-3">
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
                                        @if ($ticket->count() == 0)
                                            <tr>
                                                <td colspan="8">No hay ventas en esta caja</td>
                                            </tr>
                                        @else
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
                                        @endif
                                    </tbody>
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
                                <th>Detalle</th>
                                <th>Solicitud Factura</th>
                                <th class="rounded-end">Status Venta</th>
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
                                        <i style="color: rgb(255, 145, 0); cursor: pointer; font-size: 20px"
                                            class="fa fa-info-circle" data-bs-toggle="modal"
                                            data-bs-target="#ModalDetalleTicket{{ $ticket->IdTicket }}"></i>
                                        @include('Posweb.ModalDetalleTicket')
                                        <i style="color: green; cursor: pointer; font-size: 20px" class="fa fa-usd"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalTipoPago{{ $ticket->IdTicket }}"></i>
                                        @include('Posweb.ModalTipoPago')
                                    </td>
                                    <td>
                                        @if ($ticket->SolicitudFE == 0 && $ticket->SolicitudFE != null)
                                            <i style="font-size: 18px; cursor: pointer" class="fa fa-check-square"
                                                data-bs-toggle="modal"
                                                data-bs-target="#ModalSolicitudFe{{ $ticket->IdTicket }}"></i>
                                            @include('Posweb.ModalSolicitudFe')
                                        @endif
                                    </td>
                                    <td style="color: red;">
                                        @if ($ticket->StatusVenta == 1)
                                            <i style="font-size: 20px" class="fa fa-ban"></i>
                                        @endif
                                        @if (!empty($ticket->SolicitudCancelacionTicket) and $ticket->StatusVenta == 0)
                                            <i style="font-size: 20px" class="fa fa-hourglass-start"></i>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th style="text-align: center">Totales: </th>
                                <th>${{ number_format($total, 2) }}</th>
                                <th>${{ number_format($totalIva, 2) }}</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                @endif
            </div>
        @elseif($idReporte == 4)
            <!--TICKETS CANCELADOS-->
            <div class="content-table content-table-full card p-4" style="border-radius: 20px">
                <h4 class="p-1" style="font-size: 20px">TICKET'S CANCELADOS - {{ $nomTienda }} - CAJA:
                    {{ $idCaja == 0 ? 'TODAS' : $numCaja }}</h4>
                <table>
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Caja</th>
                            <th>Ticket</th>
                            <th>Fecha</th>
                            <th>Importe</th>
                            <th>Iva</th>
                            <th>Detalle</th>
                            <th>Solicitud Factura</th>
                            <th class="rounded-end">Status Venta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($ticketsCancelados->count() == 0)
                            <tr>
                                <th style="text-align: center; font-size:18px" colspan="8">No hay ventas canceladas en
                                    este
                                    rango de fechas</th>
                            </tr>
                        @endif
                        @foreach ($ticketsCancelados as $ticket)
                            <tr>
                                <th>{{ $ticket->Caja->IdCaja }}</th>
                                <td>{{ $ticket->IdTicket }}</td>
                                <td>{{ strftime('%d %B %Y, %H:%M', strtotime($ticket->FechaVenta)) }}</td>
                                <td>$ {{ number_format($ticket->ImporteVenta, 2) }}</td>
                                <td>{{ number_format($ticket->Iva, 2) }}</td>
                                <td>
                                    <i style="color: rgb(255, 145, 0); cursor: pointer; font-size: 20px"
                                        class="fa fa-info-circle" data-bs-toggle="modal"
                                        data-bs-target="#ModalDetalleTicket{{ $ticket->IdDatEncabezado }}"></i>
                                    @include('CortesTienda.ModalDetalleTicketCancelado')
                                    | <i style="color: green; cursor: pointer; font-size: 20px" class="fa fa-usd"
                                        data-bs-toggle="modal"
                                        data-bs-target="#ModalTipoPago{{ $ticket->IdDatEncabezado }}"></i>
                                    @include('CortesTienda.ModalTipoPago')
                                    | <i style="font-size: 20px; cursor: pointer;" class="fa fa-commenting"
                                        data-bs-toggle="modal"
                                        data-bs-target="#ModalComentarioTicketCancelado{{ $ticket->IdDatEncabezado }}"></i>
                                    @include('CortesTienda.ModalComentarioTicketCancelado')
                                </td>
                                <td>
                                    @if ($ticket->SolicitudFE == 0 && $ticket->SolicitudFE != null)
                                        <i style="font-size: 18px; cursor: pointer" class="fa fa-check-square"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalSolicitudFe{{ $ticket->IdEncabezado }}"></i>
                                        @include('Posweb.ModalSolicitudFe')
                                    @endif
                                </td>
                                <td style="color: red;">
                                    @if ($ticket->StatusVenta == 1)
                                        <i style="font-size: 20px" class="fa fa-ban"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th style="text-align: center">Totales: </th>
                            <th>${{ number_format($total, 2) }}</th>
                            <th>${{ number_format($totalIva, 2) }}</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

    </div>

    <script>
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
    </script>
@endsection
