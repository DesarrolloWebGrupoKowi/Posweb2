@extends('plantillaBase.masterblade')
@section('title', 'Módulo de Cancelación de Tickets')
<style>
    i:active {
        transform: scale(1.5);
    }
</style>
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
    <div class="container">
        <table class="table table-striped table-responsive shadow">
            <thead class="table-dark">
                <tr>
                    <th>Tienda</th>
                    <th>FechaSolicitud</th>
                    <th>Caja</th>
                    <th>Ticket</th>
                    <th>Importe</th>
                    <th>Detalle</th>
                    <th>Motivo</th>
                    <th>Aprobar</th>
                </tr>
            </thead>
            <tbody>
                @if ($solicitudesCancelacion->count() == 0)
                    <tr>
                        <th style="text-align: center; font-size: 22px" colspan="8">No hay solicitudes de cancelación de
                            tickets</th>
                    </tr>
                @else
                    @foreach ($solicitudesCancelacion as $solicitud)
                        <tr>
                            <td>{{ $solicitud->Tienda->NomTienda }}</td>
                            <td>{{ strftime('%d, %B, %Y, %H:%M', strtotime($solicitud->FechaSolicitud)) }}</td>
                            <td>{{ $solicitud->Encabezado->NumCaja }}</td>
                            <td>{{ $solicitud->Encabezado->IdTicket }}</td>
                            <th>${{ number_format($solicitud->Encabezado->ImporteVenta, 2) }}</th>
                            <td>
                                <i style="font-size: 22px; cursor: pointer; color:rgb(18, 167, 18)" class="fa fa-bar-chart"
                                    data-bs-toggle="modal"
                                    data-bs-target="#ModalDetalleTicket{{ $solicitud->IdEncabezado }}"></i>
                                @include('CancelacionTickets.ModalDetalleTicket')
                            </td>
                            <td>{{ $solicitud->MotivoCancelacion }}</td>
                            <td>
                                <i style="font-size: 22px; color:rgb(255, 81, 0); cursor: pointer;"
                                    class="fa fa-check-square-o" data-bs-toggle="modal"
                                    data-bs-target="#ModalConfirmarCancelacion{{ $solicitud->IdEncabezado }}"></i>
                            </td>
                            @include('CancelacionTickets.ModalConfirmarCancelacion')
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endsection
