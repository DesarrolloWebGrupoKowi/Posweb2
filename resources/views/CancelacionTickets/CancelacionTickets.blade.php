@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Solicitudes de cancelación')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Solicitudes de cancelación'])
                <div>
                    <a href="/HistorialCancelacionTickets" class="btn btn-sm btn-dark">
                        Historial de solicitudes @include('components.icons.text-file')
                    </a>
                </div>
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex align-items-center justify-content-end gap-2 pb-2" action="/CancelacionTickets"
                method="GET">
                <input type="hidden" class="idPagination" value="&idTienda={{ $idTienda }}">
                <div class="col-auto">
                    <select class="form-select rounded" style="line-height: 18px" name="idTienda" id="idTienda" autofocus>
                        <option value="">Seleccione Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-dark-outline" title="Buscar">
                    @include('components.icons.search')
                </button>
            </form>

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
                    @include('components.table-empty', [
                        'items' => $solicitudesCancelacion,
                        'colspan' => 9,
                    ])
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
                                <td class="text-black">${{ number_format($solicitud->Encabezado->ImporteVenta, 2) }}</td>
                                <td>
                                    <button class="btn-table" data-bs-toggle="modal"
                                        data-bs-target="#ModalDetalleTicket{{ $solicitud->IdEncabezado }}"
                                        title="Ver detalle de venta">
                                        @include('components.icons.list')
                                    </button>
                                    @include('CancelacionTickets.ModalDetalleTicket')
                                </td>
                                <td class="puntitos" title="{{ $solicitud->MotivoCancelacion }}">
                                    {{ $solicitud->MotivoCancelacion }}
                                </td>
                                <td>
                                    <button class="btn-table btn-table-success" data-bs-toggle="modal"
                                        data-bs-target="#ModalConfirmarCancelacion{{ $solicitud->IdEncabezado }}"
                                        title="Aprovar solicitud">
                                        @include('components.icons.check')
                                    </button>
                                </td>
                                <td>
                                    <button class="btn-table btn-table-delete" data-bs-toggle="modal"
                                        data-bs-target="#ModalCancelarCancelacion{{ $solicitud->IdEncabezado }}"
                                        title="Aprovar solicitud">
                                        @include('components.icons.x')
                                    </button>
                                </td>
                                @include('CancelacionTickets.ModalConfirmarCancelacion')
                                @include('CancelacionTickets.ModalCancelarCancelacion')
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $solicitudesCancelacion])
        </div>
    </div>
@endsection
