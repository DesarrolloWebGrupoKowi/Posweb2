@extends('plantillaBase.masterblade')
@section('title', 'Módulo de Cancelación de Tickets')
@section('contenido')
    <div class="container mb-3">
        <div class="d-flex justify-content-center">
            <div class="col-auto">
                <h2 class="card shadow p-1">Módulo de Cancelación de Tickets</h2>
            </div>
        </div>
    </div>
    <div class="container">
        @include('Alertas.Alertas')
    </div>
    <div class="container mb-3">
        <form action="/CancelacionTickets">
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
                    <input class="form-control shadow" type="date" name="fechaVenta" id="fechaVenta"
                        value="{{ empty($fechaVenta) ? date('Y-m-d') : $fechaVenta }}" required>
                </div>
                <div class="col-auto">
                    <input class="form-control shadow" type="number" name="numTicket" id="numTicket" placeholder="# Ticket"
                        value="{{ $numTicket }}" required>
                </div>
                <div class="col-auto">
                    <button class="btn card shadow">
                        <span class="material-icons">search</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    @if ($tickets->count() > 0)
        <div class="container">
            <div class="card text-center shadow-lg">
                @foreach ($tickets as $ticket)
                    <div class="card-header">
                        <h5 class="card-tittle">{{ $ticket->Tienda->NomTienda }}</h5>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">
                            Ticket #{{ $ticket->IdTicket }}
                        </h5>
                        <button class="btn" data-bs-toggle="modal"
                            data-bs-target="#ModalDetalleTicket{{ $ticket->IdTicket }}">
                            <span style="font-size:30px" class="material-icons">list</span>
                        </button>
                        @include('CancelacionTickets.ModalDetalleTicket')
                        <h5 class="card-title"></h5>
                        <h5 class="card-tittle">${{ number_format($ticket->ImporteVenta, 2) }}</h5>
                        @if ($ticket->StatusVenta == 0)
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="{!! $ticket->SolicitudFE == '0'
                                    ? '#ModalConfirmarCancelacionSolicitudFE' . $ticket->IdTicket
                                    : '#ModalConfirmarCancelacion' . $ticket->IdTicket !!}">
                                <i class="fa fa-ban"></i> Cancelar ticket
                            </button>
                        @else
                            <h5 style="color: red"><i class="fa fa-exclamation-circle"></i> Ticket Cancelado <i class="fa fa-exclamation-circle"></i></h5>
                        @endif
                        @include('CancelacionTickets.ModalConfirmarCancelacion')
                        @include('CancelacionTickets.ModalConfirmarCancelacionSolicitudFE')
                    </div>
                    <div class="card-footer">
                        {{ strftime('%d %B %Y, %H:%M', strtotime($ticket->FechaVenta)) }}
                    </div>
                @endforeach
            </div>
        </div>
    @elseif($tickets->count() == 0 && !empty($numTicket))
        <div class="container">
            <div class="card text-center">
                <div class="card-header">

                </div>
                <div class="card-body">
                    <h5 class="card-title"><i class="fa fa-exclamation-circle"></i> No se encontró el ticket <i
                            class="fa fa-exclamation-circle"></i></h5>
                </div>
                <div class="card-footer text-muted">

                </div>
            </div>
        </div>
    @endif

@endsection
