@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Solicitud de Cancelación de Ticket')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Solicitud de Cancelación de Ticket'])

            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex align-items-center justify-content-end gap-2 pb-2" action="/SolicitudCancelacionTicket"
                method="GET">
                <div class="d-flex align-items-center gap-2">
                    <label class="text-secondary d-inline" style="font-weight: 500; white-space: nowrap;" for="idTicket">No
                        Ticket:</label>
                    <input type="number" class="form-control rounded" style="line-height: 18px" id="idTicket"
                        placeholder="No Ticket" name="idTicket" value="{{ $idTicket }}" autofocus required>
                </div>
            </form>

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Ticket</th>
                        <th>Fecha Venta</th>
                        <th>Importe</th>
                        <th>Estatus</th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($idTicket) && !empty($ticket))
                        <tr>
                            <td>{{ $ticket->IdTicket }}</td>
                            <td>{{ strftime('%d %B %Y, %H:%M', strtotime($ticket->FechaVenta)) }}</td>
                            <th>$ {{ number_format($ticket->ImporteVenta, 2) }}</th>
                            <th>
                                @if (!empty($idTicket) && $ticketConSolicitud == 'si')
                                    <span class="tags-red">
                                        El ticket ya cuenta con solicitud de cancelación
                                    </span>
                                @else
                                    <span class="tags-green">
                                        Disponible para cancelación
                                    </span>
                                @endif
                            </th>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn-table" data-bs-toggle="modal"
                                        data-bs-target="#ModalDetalleTicketSolicitud" title="Ver detalle del ticket">
                                        @include('components.icons.list')
                                    </button>
                                    <button class="btn-table" data-bs-toggle="modal"
                                        data-bs-target="#ModalPagoTicketSolicitud" title="Detalle de pagos">
                                        @include('components.icons.dolar')
                                    </button>
                                    <button class="btn-table" data-bs-toggle="modal"
                                        data-bs-target="#ModalConfirmarSolicitudCancelacion"
                                        {{ !empty($idTicket) && $ticketConSolicitud == 'si' ? 'disabled' : '' }}
                                        title="Solicitar cancelación">
                                        @include('components.icons.check')
                                    </button>
                                </div>
                                @include('CancelacionTickets.ModalDetalleTicketSolicitud')
                                @include('CancelacionTickets.ModalPagoTicketSolicitud')
                                @include('CancelacionTickets.ModalConfirmarSolicitudCancelacion')
                            </td>
                        </tr>
                    @else
                        @include('components.table-empty', ['items' => [], 'colspan' => 5])
                    @endif
                </tbody>
            </table>
            {{-- @if (!empty($idTicket) && !empty($ticket) && $ticketConSolicitud == 'no')
                <div class="d-flex justify-content-end">
                    <div class="col-auto">
                        <button class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#ModalConfirmarSolicitudCancelacion">
                            Solicitar Cancelación
                        </button>
                    </div>
                </div>
            @endif --}}
        </div>

        {{-- @if (!empty($idTicket) && $ticketConSolicitud == 'si')
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
        @endif --}}
    </div>
@endsection
