@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Solicitud de Cancelación de Ticket')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-4">
            @include('components.title', ['titulo' => 'Solicitud de Cancelación de Ticket'])
            <form class="d-flex align-items-end justify-content-end gap-2" action="/SolicitudCancelacionTicket"
                method="GET">
                <div class="form-group">
                    {{-- <div class="form-floating"> --}}
                    <label class="text-secondary fw-bold" for="idTicket">No Ticket</label>
                    <input type="number" class="form-control" id="idTicket" placeholder="No Ticket" name="idTicket"
                        value="{{ $idTicket }}" autofocus required>
                    {{-- </div> --}}
                </div>
                <button class="btn btn-warning">
                    <i class="fa fa-search"></i> Buscar
                </button>
            </form>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>
        @if (!empty($idTicket) && !empty($ticket) && $ticketConSolicitud == 'no')
            <div class="content-table content-table-full card p-4" style="border-radius: 20px">
                <table>
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Ticket</th>
                            <th>Fecha Venta</th>
                            <th>Importe</th>
                            <th>Detalle</th>
                            <th class="rounded-end">Pago(s)</th>
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
            <div class="mt-4 d-flex justify-content-end">
                <div class="col-auto">
                    <button class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#ModalConfirmarSolicitudCancelacion">
                        <i class="fa fa-check"></i> Solicitar Cancelación
                    </button>
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
    </div>
@endsection
