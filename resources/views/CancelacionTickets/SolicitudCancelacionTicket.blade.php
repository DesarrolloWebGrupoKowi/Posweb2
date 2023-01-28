@extends('PlantillaBase.masterblade')
@section('title', 'Solicitud de Cancelación de Ticket')
<style>
    i:active {
        transform: scale(1.5);
    }
</style>
@section('contenido')
    <div class="container mb-2">
        <div class="d-flex justify-content-center">
            <div class="col-auto">
                <h3 class="card shadow p-1">
                    Solicitud de Cancelación de Ticket
                </h3>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="d-flex justify-content-center">
            @include('Alertas.Alertas')
        </div>
    </div>
    <div class="container mb-2">
        <form action="/SolicitudCancelacionTicket" method="GET">
            <div class="row">
                <div class="col-auto">
                    <div class="form-floating mb-2">
                        <input type="number" class="form-control" id="idTicket" placeholder="# Ticket" name="idTicket"
                            value="{{ $idTicket }}" required>
                        <label for="idTicket"># Ticket</label>
                    </div>
                </div>
                <div class="col-auto">
                    <button class="btn btn-warning shadow mt-2">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                </div>
            </div>
        </form>
    </div>
    @if (!empty($idTicket) && !empty($ticket) && $ticketConSolicitud == 'no')
        <div class="container">
            <table class="table table-striped table-responsive">
                <thead class="table-dark">
                    <tr>
                        <th>Ticket</th>
                        <th>Fecha Venta</th>
                        <th>Importe</th>
                        <th>Detalle</th>
                        <th>Pago(s)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $ticket->IdTicket }}</td>
                        <td>{{ strftime('%d %B %Y, %H:%M', strtotime($ticket->FechaVenta)) }}</td>
                        <th>$ {{ number_format($ticket->ImporteVenta, 2) }}</th>
                        <td>
                            <i style="font-size: 22px; cursor: pointer; color:rgb(255, 72, 0)" class="fa fa-bar-chart"
                                data-bs-toggle="modal" data-bs-target="#ModalDetalleTicketSolicitud"></i>
                            @include('CancelacionTickets.ModalDetalleTicketSolicitud')
                        </td>
                        <td>
                            <i style="font-size: 22px; cursor: pointer; color:rgb(70, 155, 0)" class="fa fa-usd"
                                data-bs-toggle="modal" data-bs-target="#ModalPagoTicketSolicitud"></i>
                            @include('CancelacionTickets.ModalPagoTicketSolicitud')
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="col-auto">
                    <button class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#ModalConfirmarSolicitudCancelacion">
                        <i class="fa fa-check"></i> Solicitar Cancelación
                    </button>
                </div>
            </div>
        </div>
        @include('CancelacionTickets.ModalConfirmarSolicitudCancelacion')
    @endif
    @if (!empty($idTicket) && $ticketEncontrado == 'no')
        <div class="container">
            <div class="d-flex justify-content-center">
                <h3>
                    <span class="badge rounded-pill bg-danger">
                        <i class="fa fa-exclamation-circle"></i> No se encontro el
                        ticket solicitado <i class="fa fa-exclamation-circle">
                        </i>
                    </span>
                </h3>
            </div>
        </div>
    @endif
    @if (!empty($idTicket) && $ticketConSolicitud == 'si')
        <div class="container">
            <div class="d-flex justify-content-center">
                <h3>
                    <span class="badge rounded-pill bg-danger">
                        <i class="fa fa-exclamation-circle"></i> El ticket ya cuenta con solicitud de cancelación <i
                            class="fa fa-exclamation-circle">
                        </i>
                    </span>
                </h3>
            </div>
        </div>
    @endif
@endsection
