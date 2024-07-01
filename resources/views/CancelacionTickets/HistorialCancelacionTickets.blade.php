@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Historial de solicitud de cancelación')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', [
                    'titulo' => 'Historial de solicitud de cancelación',
                    'options' => [['name' => 'SOLICITUDES DE CANCELACIÓN', 'value' => '/CancelacionTickets']],
                ])
                {{-- <div>
                    <a href="/CancelacionTickets" class="btn btn-sm btn-dark">
                        Solicitudes De Cancelación @include('components.icons.check')
                    </a>
                </div> --}}
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex align-items-center justify-content-end flex-wrap pb-2 gap-2"
                action="/HistorialCancelacionTickets" method="GET">
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
                        <th>Status</th>
                        <th class="rounded-end">Motivo</th>
                        {{-- <th>Status</th> --}}
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
                                <td>
                                    @if ($solicitud->SolicitudAprobada == '0')
                                        <span class="tags-green"> Aprovada </span>
                                    @endif
                                    @if ($solicitud->SolicitudAprobada == 1)
                                        <span class="tags-red"> Rechazada </span>
                                    @endif
                                </td>
                                <td class="puntitos">
                                    {{ $solicitud->MotivoCancelacion }}
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
