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
                    <select class="form-select" name="idCaja" id="idCaja" required>
                        <option value="">Seleccione Caja</option>
                        @foreach ($cajasTienda as $caja)
                            <option {!! $idCaja == $caja->IdDatCajas ? 'selected' : '' !!} value="{{ $caja->IdDatCajas }}">Caja {{ $caja->IdCaja }}</option>
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
                            <div class="row d-flex justify-content-center">
                                <div class="col-auto card shadow-lg p-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="{{ $ticket->MotivoCancel }}">
                                    <h5>Ticket Cancelado</h5>
                                    <h6>Cancelación:
                                        {{ strftime('%d %B %Y, %H:%M', strtotime($ticket->FechaCancelacion)) }}
                                    </h6>
                                    <h6>
                                        Cancelado Por: @if (!empty($ticket->UsuarioCancelacion))
                                            {{ Str::upper($ticket->UsuarioCancelacion->NomUsuario) }}
                                        @endif
                                    </h6>
                                </div>
                            </div>
                        @endif
                        @include('CancelacionTickets.ModalConfirmarCancelacion')
                        @include('CancelacionTickets.ModalConfirmarCancelacionSolicitudFE')
                    </div>
                    <div class="card-footer">
                        Venta: {{ strftime('%d %B %Y, %H:%M', strtotime($ticket->FechaVenta)) }}
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

    <script>
        const idTienda = document.getElementById('idTienda');
        const idCaja = document.getElementById('idCaja');
        idTienda.addEventListener('change', (e) => {
            var options = document.querySelectorAll('#idCaja option');
            options.forEach(o => o.remove());
            fetch('/BuscarCajasTienda?idTienda=' + idTienda.value)
                .then(res => res.json())
                .then(respuesta => {
                    if (respuesta != '') {
                        /*if (respuesta.length >= 2) {
                            const optionCaja = document.createElement('option');
                            optionCaja.value = 0;
                            optionCaja.text = 'Todas las Cajas';
                            idCaja.add(optionCaja);
                        }*/
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
