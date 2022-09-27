@extends('plantillaBase.masterblade')
@section('title', 'Venta Por Ticket Diario')
@section('contenido')
    <div class="container mb-3">
        <div class="row d-flex justify-content-center">
            <div class="col-auto">
                <h2 class="card shadow p-1">Venta Por Ticket Diario - {{ $tienda->NomTienda }}</h2>
            </div>
        </div>
    </div>
    <div class="container">
        <form id="formVentaTicketDiario" action="VentaTicketDiario">
            <div class="row mb-3">
                <div class="col-3">
                    <input type="date" class="form-control shadow" name="txtFecha" id="txtFecha"
                        value="{{ $fecha }}" required>
                </div>
            </div>
        </form>
    </div>
    <div class="container mt-2">
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
                            <i style="color: rgb(255, 145, 0); cursor: pointer; font-size: 20px" class="fa fa-info-circle"
                                data-bs-toggle="modal" data-bs-target="#ModalDetalleTicket{{ $ticket->IdTicket }}"></i>
                            @include('Posweb.ModalDetalleTicket')
                            <i style="color: green; cursor: pointer; font-size: 20px" class="fa fa-usd"
                                data-bs-toggle="modal" data-bs-target="#ModalTipoPago{{ $ticket->IdTicket }}"></i>
                            @include('Posweb.ModalTipoPago')
                        </td>
                        <td>
                            @if ($ticket->SolicitudFE == 0 && $ticket->SolicitudFE != null)
                                <i style="font-size: 18px; cursor: pointer" class="fa fa-check-square"
                                    data-bs-toggle="modal" data-bs-target="#ModalSolicitudFe{{ $ticket->IdTicket }}"></i>
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
    <script>
        const txtFecha = document.getElementById('txtFecha');
        const formVentaTicketDiario = document.getElementById('formVentaTicketDiario');
        txtFecha.addEventListener('change', function() {
            formVentaTicketDiario.submit();
        });
    </script>
@endsection
