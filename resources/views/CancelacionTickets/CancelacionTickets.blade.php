@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Módulo de solicitud de cancelación')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Módulo de solicitud de cancelación'])
            <div>
                <a href="/HistorialCancelacionTickets" class="btn btn-sm btn-dark">
                    <i class="fa fa-history"></i> Historial de solicitudes
                </a>
            </div>
        </div>

        <form class="d-flex align-items-center justify-content-end flex-wrap pb-2 gap-2" action="/CancelacionTickets"
            method="GET">
            <input type="hidden" class="idPagination" value="&idTienda={{ $idTienda }}">
            <div class="col-auto">
                <select class="form-select" name="idTienda" id="idTienda">
                    <option value="">Seleccione Tienda</option>
                    @foreach ($tiendas as $tienda)
                        <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button class="btn card">
                    <span class="material-icons">search</span>
                </button>
            </div>
        </form>

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
                        <th>Motivo</th>
                        <th>Aprobar</th>
                        <th class="rounded-end">Cancelar</th>
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
                                    <td class="puntitos">{{ $solicitud->MotivoCancelacion }}</td>
                                    <td>
                                        <i style="font-size: 22px; color:rgb(18,167,18); cursor: pointer;"
                                            class="fa fa-check-square-o" data-bs-toggle="modal"
                                            data-bs-target="#ModalConfirmarCancelacion{{ $solicitud->IdEncabezado }}"></i>
                                    </td>
                                    <td>
                                        <i style="font-size: 22px; color:rgb(255, 81, 0); cursor: pointer;"
                                            class="fa fa-times-circle-o" data-bs-toggle="modal"
                                            data-bs-target="#ModalCancelarCancelacion{{ $solicitud->IdEncabezado }}"></i>

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
@endsection
