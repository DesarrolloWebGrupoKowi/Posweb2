@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Historial de solicitud de cancelación')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Historial de solicitud de cancelación'])
            <div>
                <a href="/CancelacionTickets" class="btn btn-sm btn-dark">
                    <i class="fa fa-history"></i> Solicitud de tickets
                </a>
            </div>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>
        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Tienda</th>
                        <th>FechaSolicitud</th>
                        <th>Caja</th>
                        <th>Ticket</th>
                        <th>Importe</th>
                        <th>Detalle</th>
                        <th>Status</th>
                        <th class="rounded-end">Motivo</th>
                        {{-- <th>Status</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @if ($solicitudesCancelacion->count() == 0)
                        <tr>
                            <th style="text-align: center; font-size: 22px" colspan="9">No hay solicitudes de cancelación
                                de
                                tickets
                            </th>
                        </tr>
                    @else
                        @foreach ($solicitudesCancelacion as $solicitud)
                            @if (is_null($solicitud->Encabezado))
                                <tr>
                                    <td>{{ $solicitud->Tienda->NomTienda }}</td>
                                    <td>{{ strftime('%d, %B, %Y, %H:%M', strtotime($solicitud->FechaSolicitud)) }}</td>
                                    <th colspan="7">
                                        No ha subido la venta <i class="fa fa-exclamation-circle"></i>
                                    </th>
                                </tr>
                            @else
                                <tr>
                                    <td>{{ $solicitud->Tienda->NomTienda }}</td>
                                    <td>{{ strftime('%d, %B, %Y, %H:%M', strtotime($solicitud->FechaSolicitud)) }}</td>
                                    <td>{{ $solicitud->Encabezado->NumCaja }}</td>
                                    <td>{{ $solicitud->Encabezado->IdTicket }}</td>
                                    <th>${{ number_format($solicitud->Encabezado->ImporteVenta, 2) }}</th>
                                    <td>
                                        <i style="font-size: 22px; cursor: pointer; color:rgb(18, 167, 18)"
                                            class="fa fa-bar-chart" data-bs-toggle="modal"
                                            data-bs-target="#ModalDetalleTicket{{ $solicitud->IdEncabezado }}"></i>
                                        @include('CancelacionTickets.ModalDetalleTicket')
                                    </td>
                                    <td>
                                        @if ($solicitud->SolicitudAprobada == '0')
                                            <i style="font-size: 22px; color:rgb(18,167,18); cursor: pointer;"
                                                class="fa fa-check-square-o"></i> Aprovada
                                        @endif
                                        @if ($solicitud->SolicitudAprobada == 1)
                                            <i style="font-size: 22px; color:rgb(255, 81, 0);"
                                                class="fa fa-times-circle-o"></i>
                                            Rechazada
                                        @endif
                                    </td>
                                    <td class="puntitos">
                                        {{-- <p style="overflow: hidden;"> --}}
                                        {{ $solicitud->MotivoCancelacion }}
                                        {{-- </p> --}}
                                    </td>
                                    @include('CancelacionTickets.ModalConfirmarCancelacion')
                                    @include('CancelacionTickets.ModalCancelarCancelacion')
                                </tr>
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-5 d-flex justify-content-center">
        {!! $solicitudesCancelacion->links() !!}
    </div>
@endsection
