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
                    <input class="form-control" type="number" name="numTicket" id="numTicket" placeholder="# Ticket"
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
            <table class="table table-responsive table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Tienda</th>
                        <th>Fecha Venta</th>
                        <th>Ticket</th>
                        <th>Detalle</th>
                        <th>Importe</th>
                        <th>Cancelar Ticket</th>
                    </tr>
                </thead>
                <tbody style="vertical-align: middle; font-size:16px">
                    @foreach ($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->Tienda->NomTienda }}</td>
                            <td>{{ strftime('%d %B %Y, %H:%M', strtotime($ticket->FechaVenta)) }}</td>
                            </td>
                            <td>{{ $ticket->IdTicket }}</td>
                            <td>
                                <button class="btn" data-bs-toggle="modal"
                                    data-bs-target="#ModalDetalleTicket{{ $ticket->IdTicket }}">
                                    <span class="material-icons">list</span>
                                </button>
                                @include('CancelacionTickets.ModalDetalleTicket')
                            </td>
                            <td>${{ number_format($ticket->ImporteVenta, 2) }}</td>
                            <td>
                                @if ($ticket->StatusVenta == 0)
                                    <button class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="{{ $ticket->StatusVenta == 0 ? '#ModalConfirmarCancelacion' . $ticket->IdTicket : '' }}">
                                        <i class="fa fa-ban"></i> Cancelar
                                    </button>
                                @else
                                    <strong style="color: red">Ticket Cancelado</strong>
                                @endif
                                @if ($ticket->StatusVenta == 0)
                                    @include('CancelacionTickets.ModalConfirmarCancelacion')
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @elseif($tickets->count() == 0 && !empty($numTicket))
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-auto">
                    <h4 class="bg-danger text-white p-1 rounded-3"><i class="fa fa-exclamation-circle"></i> No se encontro
                        el ticket <i class="fa fa-exclamation-circle"></i></h4>
                </div>
            </div>
        </div>
    @endif

@endsection
