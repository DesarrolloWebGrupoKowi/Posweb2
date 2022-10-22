@extends('plantillaBase.masterblade')
@section('title', 'Cortes Por Tienda')
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
    <div class="container card rounded-3 shadow p-1 mb-3">
        <div class="d-flex justify-content-center">
            <div class="col-auto">
                <h3>Cortes de Tienda</h3>
            </div>
        </div>
        <form class="mb-3" action="/VerCortesTienda" method="GET">
            <div class="row d-flex justify-content-center">
                <div class="col-auto">
                    <select class="form-select shadow" name="idTienda" id="idTienda" required>
                        <option value="">Seleccione Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <select class="form-select shadow" name="idCaja" id="idCaja" required>
                        <option value="">Seleccione Caja</option>
                        @foreach ($cajasTienda as $caja)
                            <option {!! $idCaja == $caja->IdDatCajas ? 'selected' : '' !!} value="{{ $caja->IdDatCajas }}">Caja {{ $caja->IdCaja }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <select class="form-select shadow" name="idReporte" id="idReporte" required>
                        <option value="">Seleccione Reporte</option>
                        @foreach ($opcionesReporte as $opcionReporte)
                            <option {!! $idReporte == $opcionReporte->IdReporte ? 'selected' : '' !!} value="{{ $opcionReporte->IdReporte }}">
                                {{ $opcionReporte->NomReporte }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <input class="form-control shadow" type="date" name="fecha1" id="fecha1"
                        value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
                </div>
                <div class="col-auto">
                    <input {{ empty($fecha2) ? 'hidden disabled' : '' }} class="form-control shadow" type="date"
                        name="fecha2" id="fecha2" value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                </div>
                <div class="col-auto">
                    <button class="btn card shadow">
                        <span class="material-icons">search</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    @if ($idReporte == 1)
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-auto">
                    <h4 class="rounded-3 p-1">CORTE DIARIO - {{ $nomTienda }}</h4>
                </div>
                <hr>
            </div>
            <div class="row d-flex justify-content-end">
                <div class="col-auto">
                    <a href="/GenerarCortePDF/{{ $fecha1 }}/{{ $idTienda }}" target="_blank" type="button"
                        class="btn card">
                        <span class="material-icons">print</span>
                    </a>
                </div>
            </div>
        </div>
        <!--CLIENTES DE TIENDA (SIN SOLICITUD DE FACTURA)-->
        <div class="container mb-3">
            @foreach ($cortesTienda as $corteTienda)
                @foreach ($corteTienda->Customer as $customer)
                    <div class="d-flex justify-content-left">
                        <h6 class="p-1 bg-dark text-white rounded-3">{{ $customer->NomClienteCloud }}</h6>
                    </div>
                @endforeach
                <table class="table table-responsive table-striped table-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th>Articulo</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Iva</th>
                            <th>Importe</th>
                        </tr>
                    </thead>
                    <tbody class="cuchi">
                        @php
                            $sumCantArticulo = 0;
                            $sumImporte = 0;
                        @endphp
                        @foreach ($corteTienda->CorteTienda as $detalleCorte)
                            <tr>
                                <td style="width: 10vh">{{ $detalleCorte->CodArticulo }}</td>
                                <td style="width: 60vh">{{ $detalleCorte->NomArticulo }}</td>
                                <td style="width: 15vh">{{ number_format($detalleCorte->CantArticulo, 4) }}</td>
                                <td style="width: 15vh">{{ number_format($detalleCorte->PrecioArticulo, 2) }}</td>
                                <td>{{ number_format($detalleCorte->IvaArticulo, 2) }}</td>
                                <td style="width: 15vh">{{ number_format($detalleCorte->ImporteArticulo, 2) }}</td>
                            </tr>
                            @php
                                $sumCantArticulo = $sumCantArticulo + $detalleCorte->CantArticulo;
                                $sumImporte = $sumImporte + $detalleCorte->ImporteArticulo;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
                <table class="table">
                    <!--INICIA MONEDERO ELECTRONICO PARA EMPLEADOS QUINCENALES-->
                    @if ($corteTienda->IdListaPrecio == 4 && $corteTienda->IdTipoNomina == 4)
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
                    @if ($corteTienda->IdListaPrecio == 4 && $corteTienda->IdTipoNomina == 3)
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
            @endforeach
        </div>
        <!--TERMINA CLIENTES DE TIENDA (SIN SOLICITUD DE FACTURA)-->
        <!--SOLICITUDES DE FACTURA-->
        <div class="container mb-3">
            @foreach ($facturas as $factura)
                <div class="d-flex justify-content-left">
                    @if (empty($factura->Bill_To) && empty($factura->IdClienteCloud))
                        <h6 class="p-1 bg-danger text-white rounded-3"><i class="fa fa-exclamation-triangle"></i> FALTA
                            LIGAR CLIENTE
                            -
                            {{ $factura->NomCliente }} <i class="fa fa-exclamation-triangle"></i></h6>
                    @else
                        <h6 class="p-1 bg-dark text-white rounded-3">{{ $factura->NomCliente }}</h6>
                    @endif
                </div>
                <table class="table table-responsive table-striped table-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th>Articulo</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Iva</th>
                            <th>Importe</th>
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
                                <td style="width: 15vh">{{ number_format($detalleFactura->PivotDetalle->CantArticulo, 4) }}
                                </td>
                                <td style="width: 15vh">
                                    {{ number_format($detalleFactura->PivotDetalle->PrecioArticulo, 2) }}
                                </td>
                                <td>{{ number_format($detalleFactura->PivotDetalle->IvaArticulo, 2) }}</td>
                                <td style="width: 15vh">
                                    {{ number_format($detalleFactura->PivotDetalle->ImporteArticulo, 2) }}
                                </td>
                            </tr>
                            @php
                                $sumCantArticulo = $sumCantArticulo + $detalleFactura->PivotDetalle->CantArticulo;
                                $sumImporte = $sumImporte + $detalleFactura->PivotDetalle->ImporteArticulo;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
                <table class="table">
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
            @endforeach
        </div>
        <!--TERMINA SOLICITUDES DE FACTURA-->
        <!--SUMATORIAS FINALES-->
        <div class="container mb-3">
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
                                <td style="font-weight: bold; color: red;">${{ number_format($totalTransferencia, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td>Total Factura: </td>
                                <td style="font-weight: bold; color: red;">${{ number_format($totalFactura, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Total Efectivo: </td>
                                <td style="font-weight: bold; color: red;">${{ number_format($totalEfectivo, 2) }}</td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    @elseif($idReporte == 2)
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-auto">
                    <h4 class="rounded-3 p-1">CONCENTRADO DE VENTAS - {{ $nomTienda }}</h4>
                </div>
                <hr>
            </div>
            <table class="table table-sm table-responsive table-striped shadow">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Peso</th>
                        <th>Precio</th>
                        <th>Iva</th>
                        <th>Importe</th>
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
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-auto">
                    <h4 class="rounded-3 p-1">VENTA POR TICKET DIARIO - {{ $nomTienda }}</h4>
                </div>
                <hr>
            </div>
            <table class="table table-responsive table-striped table-sm shadow">
                <thead class="table-dark">
                    <tr>
                        <th>Ticket</th>
                        <th>Fecha</th>
                        <th>Importe</th>
                        <th>Iva</th>
                        <th>Detalle</th>
                        <th>Solicitud Factura</th>
                        <th>Status Venta</th>
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
                                    data-bs-toggle="modal" data-bs-target="#ModalTipoPago{{ $ticket->IdTicket }}"></i>
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
        </div>
    @elseif($idReporte == 4)
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-auto">
                    <h4 class="rounded-3 p-1">TICKET'S CANCELADOS - {{ $nomTienda }}</h4>
                </div>
                <hr>
            </div>
            <table class="table table-responsive table-striped table-sm shadow">
                <thead class="table-dark">
                    <tr>
                        <th>Ticket</th>
                        <th>Fecha</th>
                        <th>Importe</th>
                        <th>Iva</th>
                        <th>Detalle</th>
                        <th>Solicitud Factura</th>
                        <th>Status Venta</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($ticketsCancelados->count() == 0)
                        <tr>
                            <td colspan="7">No Hay Ventas</td>
                        </tr>
                    @endif
                    @foreach ($ticketsCancelados as $ticket)
                        <tr>
                            <td>{{ $ticket->IdTicket }}</td>
                            <td>{{ strftime('%d %B %Y, %H:%M', strtotime($ticket->FechaVenta)) }}</td>
                            <td>$ {{ number_format($ticket->ImporteVenta, 2) }}</td>
                            <td>{{ number_format($ticket->Iva, 2) }}</td>
                            <td>
                                <i style="color: rgb(255, 145, 0); cursor: pointer; font-size: 20px"
                                    class="fa fa-info-circle" data-bs-toggle="modal"
                                    data-bs-target="#ModalDetalleTicket{{ $ticket->IdEncabezado }}"></i>
                                @include('CortesTienda.ModalDetalleTicketCancelado')
                                | <i style="color: green; cursor: pointer; font-size: 20px" class="fa fa-usd"
                                    data-bs-toggle="modal"
                                    data-bs-target="#ModalTipoPago{{ $ticket->IdEncabezado }}"></i>
                                @include('CortesTienda.ModalTipoPago')
                                | <i style="font-size: 20px; cursor: pointer;" class="fa fa-commenting"
                                    data-bs-toggle="modal"
                                    data-bs-target="#ModalComentarioTicketCancelado{{ $ticket->IdEncabezado }}"></i>
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
