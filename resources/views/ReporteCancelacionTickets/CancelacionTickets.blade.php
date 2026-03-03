@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Reporte Solicitudes De Cancelación')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <!-- HEADER COMPACTO (Solo título y alertas) -->
        <div class="card border-0 p-3"
            style="border-radius: 10px; background-color: white;">
            <div class="row gap-4">
                <div class="col-12 col-lg-auto d-flex align-items-center gap-3">
                    @include('components.title', [
                        'titulo' => 'Reporte Solicitudes De Cancelación',
                        'options' => [
                            ['name' => 'Venta por ticket diario', 'value' => '/VentaTicketDiario'],
                            [
                                'name' => 'Solicitud de cancelación de ticket',
                                'value' => '/SolicitudCancelacionTicket',
                            ],
                        ],
                    ])
                </div>

                <!-- Filtro de Fechas -->
                <form action="ReporteSolicitudCancelacion"
                    method="GET"
                    class="col-12 col-lg d-lg-flex justify-content-end">
                    <div class="row">
                        <!-- Campo Fecha Inicio -->
                        <div class="col-12 col-md col-lg mb-2">
                            <div class="input-group"
                                style="width: 100%;">
                                <span class="input-group-text bg-gray-100 border-gray-300"
                                    style="width: 100px;">
                                    Fecha Inicio
                                </span>
                                <input type="date"
                                    class="form-control form-control-sm border-gray-300"
                                    name="txtFecha1"
                                    id="fecha1"
                                    value="{{ $fecha1 }}"
                                    autofocus>
                            </div>
                        </div>

                        <!-- Campo Fecha Fin -->
                        <div class="col-12 col-md col-lg mb-2">
                            <div class="input-group"
                                style="width: 100%;">
                                <span class="input-group-text bg-gray-100 border-gray-300"
                                    style="width: 100px;">
                                    Fecha Fin
                                </span>
                                <input type="date"
                                    class="form-control form-control-sm border-gray-300"
                                    name="txtFecha2"
                                    id="fecha2"
                                    value="{{ $fecha2 }}">
                            </div>
                        </div>

                        <!-- Botón Buscar -->
                        <div class="col-12 col-md-auto">
                            <div>
                                <button type="submit"
                                    class="btn btn-outline-dark bg-dark text-white w-100"
                                    title="Buscar">
                                    @include('components.icons.search')
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="mt-2">
                @include('Alertas.Alertas')
            </div>
        </div>

        <!-- CONTENIDO -->
        <div class="card border-0 p-4"
            style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;">
            @if ($solicitudesCancelacion->where('subir', 0)->count() > 0)
                <div class="d-flex justify-content-end align-items-center mb-3">
                    <form action="/SolicitudCancelacionTicket/Subir"
                        method="POST"
                        class="d-inline">
                        @csrf
                        <button type="submit"
                            class="btn btn-sm"
                            style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 6px; padding: 6px 12px; font-size: 0.75rem;">
                            <span class="d-flex align-items-center gap-1">
                                @include('components.icons.upload')
                                Subir solicitudes
                            </span>
                        </button>
                    </form>
                </div>
            @endif

            <!-- TABLA DE SOLICITUDES -->
            <div class="table-responsive content-table-sm">
                <table class="table">
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Folio</th>
                            <th>Folio Ticket</th>
                            <th>Tienda</th>
                            <th>Fecha Solicitud</th>
                            <th>Caja</th>
                            <th>Ticket</th>
                            <th class="text-end">Importe</th>
                            <th>Estatus</th>
                            <th>Motivo</th>
                            <th>Estatus</th>
                            <th class="rounded-end text-center">Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($solicitudesCancelacion as $solicitud)
                            @if (is_null($solicitud->Encabezado))
                                <tr>
                                    <td colspan="9"
                                        class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <div class="mb-2 p-2 rounded-circle"
                                                style="background-color: rgba(245, 158, 11, 0.1); width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                                <div style="color: #f59e0b; width: 24px; height: 24px;">
                                                    @include('components.icons.alert-circle')
                                                </div>
                                            </div>
                                            <span class="fw-500 mb-1">{{ $solicitud->Tienda->NomTienda }}</span>
                                            <span class="small text-muted">
                                                {{ strftime('%d, %B, %Y, %H:%M', strtotime($solicitud->FechaSolicitud)) }}
                                            </span>
                                            <span class="tags-yellow mt-2"
                                                style="padding: 4px 12px;">
                                                @include('components.icons.cloud-slash')
                                                <span class="ms-1">No ha subido la venta</span>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                <tr class="align-middle">
                                    <td>
                                        <span class="fw-500">{{ $solicitud->SolicitudCancelacion }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-500">{{ $solicitud->IdEncabezado }}</span>
                                    </td>
                                    <td><span style="min-width: 150px; display: inline-block;">
                                            {{ $solicitud->Tienda->NomTienda }} </span></td>
                                    <td>
                                        <span style="font-size: 0.85rem;min-width: 150px; display: inline-block;">
                                            {{ \Carbon\Carbon::parse($solicitud->FechaSolicitud)->locale('es')->isoFormat('D MMM YYYY, HH:mm') }}
                                        </span>
                                    </td>
                                    <td>{{ $solicitud->Encabezado->NumCaja }}</td>
                                    <td>
                                        {{ $solicitud->Encabezado->IdTicket }}
                                    </td>
                                    <td class="text-end fw-500">
                                        ${{ number_format($solicitud->Encabezado->ImporteVenta, 2) }}
                                    </td>
                                    <td>
                                        @if ($solicitud->SolicitudAprobada == '0')
                                            <span class="tags-green d-inline-flex align-items-center gap-1"
                                                style="padding: 5px 10px; font-size: 0.75rem;">
                                                @include('components.icons.check')
                                                <span>Aprobada</span>
                                            </span>
                                        @elseif ($solicitud->SolicitudAprobada == 1)
                                            <span class="tags-red d-inline-flex align-items-center gap-1"
                                                style="padding: 5px 10px; font-size: 0.75rem;">
                                                @include('components.icons.block')
                                                <span>Rechazada</span>
                                            </span>
                                        @else
                                            <span class="tags-yellow d-inline-flex align-items-center gap-1"
                                                style="padding: 5px 10px; font-size: 0.75rem;">
                                                @include('components.icons.clock')
                                                <span>Pendiente</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="small text-truncate d-inline-block"
                                            style="max-width: 200px;"
                                            title="{{ $solicitud->MotivoCancelacion }}">
                                            {{ $solicitud->MotivoCancelacion }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($solicitud->subir == 1)
                                            <span class="tags-green d-inline-flex align-items-center gap-1"
                                                style="padding: 4px 8px; font-size: 0.7rem;"
                                                title="En línea">
                                                @include('components.icons.cloud-check')
                                                <span class="d-none d-md-inline">Subido</span>
                                            </span>
                                        @else
                                            <span class="tags-red d-inline-flex align-items-center gap-1"
                                                style="padding: 4px 8px; font-size: 0.7rem;"
                                                title="Fuera de línea">
                                                @include('components.icons.cloud-slash')
                                                <span class="d-none d-md-inline">No subido</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalDetalleTicket{{ $solicitud->IdEncabezado }}"
                                            title="Ver detalle"
                                            style="padding: 0.25rem 0.5rem;">
                                            @include('components.icons.list')
                                            <span class="d-none d-md-inline">DETALLE</span>
                                        </button>
                                        @include('CancelacionTickets.ModalDetalleTicket')
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="11"
                                    class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <!-- Icono -->
                                        <div class="mb-3 p-3 rounded-circle"
                                            style="background-color: rgba(30, 41, 59, 0.05); width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                            @include('components.icons.file-text')
                                        </div>

                                        <!-- Mensaje principal -->
                                        <h6 class="fw-600 mb-2"
                                            style="color: #1e293b;">No hay solicitudes para mostrar</h6>

                                        <!-- Mensaje secundario -->
                                        <p class="text-muted small mb-0">
                                            No se encontraron solicitudes de cancelación en el período
                                            {{ \Carbon\Carbon::parse($fecha1)->format('d/m/Y') }} -
                                            {{ \Carbon\Carbon::parse($fecha2)->format('d/m/Y') }}
                                        </p>

                                        <!-- Sugerencia -->
                                        <div class="mt-3 p-2"
                                            style="background-color: #f8f9fa; border-radius: 6px;">
                                            <span class="small text-muted">
                                                @include('components.icons.calendar')
                                                Prueba con un rango de fechas diferente
                                            </span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if ($solicitudesCancelacion->hasPages())
                <div class="d-flex justify-content-end mt-3 pt-2 border-top"
                    style="border-color: #e5e7eb;">
                    @include('components.paginate', ['items' => $solicitudesCancelacion])
                </div>
            @endif
        </div>
    </div>
@endsection
