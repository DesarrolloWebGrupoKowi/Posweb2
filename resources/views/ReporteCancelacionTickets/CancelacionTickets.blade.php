@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Reporte Tickets Cancelados')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4 flex-1" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', [
                    'titulo' => 'Reporte Tickets Cancelados',
                    'options' => [
                        ['name' => 'Venta por ticket diario', 'value' => '/VentaTicketDiario'],
                        ['name' => 'Solicitud de cancelación de ticket', 'value' => '/SolicitudCancelacionTicket'],
                    ],
                ])
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex align-items-center justify-content-end gap-2 pb-2" action="ReporteSolicitudCancelacion">
                <div class="col-auto">
                    <input class="form-control rounded" style="line-height: 18px" type="date" name="txtFecha1"
                        id="fecha1" value="{{ $fecha1 }}" autofocus>
                </div>
                <div class="col-auto">
                    <input class="form-control rounded" style="line-height: 18px" type="date" name="txtFecha2"
                        id="fecha2" value="{{ $fecha2 }}">
                </div>
                {{-- <button type="submit" class="d-none">Buscar</button> --}}
                <button class="btn btn-dark-outline" title="Buscar">
                    @include('components.icons.search')
                </button>
            </form>

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Folio</th>
                        <th>Tienda</th>
                        <th>FechaSolicitud</th>
                        <th>Caja</th>
                        <th>Ticket</th>
                        <th>Importe</th>
                        <th>Status</th>
                        <th>Motivo</th>
                        <th class="rounded-end">Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', [
                        'items' => $solicitudesCancelacion,
                        'colspan' => 8,
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
                                <td>{{ $solicitud->SolicitudCancelacion }}</td>
                                <td>{{ $solicitud->Tienda->NomTienda }}</td>
                                <td>{{ strftime('%d, %B, %Y, %H:%M', strtotime($solicitud->FechaSolicitud)) }}</td>
                                <td>{{ $solicitud->Encabezado->NumCaja }}</td>
                                <td>{{ $solicitud->Encabezado->IdTicket }}</td>
                                <td class="text-black">${{ number_format($solicitud->Encabezado->ImporteVenta, 2) }}
                                </td>
                                <td>
                                    @if ($solicitud->SolicitudAprobada == '0')
                                        <span class="tags-green">Aprobada</span>
                                    @elseif ($solicitud->SolicitudAprobada == 1)
                                        <span class="tags-red">Rechazada</span>
                                    @else
                                        <span class="tags-yellow">Pendiente</span>
                                    @endif
                                </td>
                                <td class="puntitos" title="{{ $solicitud->MotivoCancelacion }}">
                                    {{ $solicitud->MotivoCancelacion }}
                                </td>
                                <td>
                                    <button class="btn-table" data-bs-toggle="modal"
                                        data-bs-target="#ModalDetalleTicket{{ $solicitud->IdEncabezado }}">
                                        @include('components.icons.list')
                                    </button>
                                    @include('CancelacionTickets.ModalDetalleTicket')
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $solicitudesCancelacion])
        </div>
    </div>
@endsection
