@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Venta Por Ticket Diario')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <!-- HEADER COMPACTO (Solo título y alertas) -->
        <div class="card border-0 p-3"
            style="border-radius: 10px; background-color: white;">
            <div class="row gap-4">
                <div class="col-12 col-lg-auto d-flex align-items-center gap-3">
                    @include('components.title', ['titulo' => 'Venta Por Ticket Diario'])
                </div>

                <!-- Filtro de Fecha -->
                <form action="VentaTicketDiario"
                    method="GET"
                    class="col-12 col-lg d-lg-flex justify-content-end">

                    <div class="row">

                        <!-- Campo de Fecha -->
                        <div class="col-12 col-md col-lg mb-2">
                            <div class="input-group"
                                style="width: 100%;">
                                <span class="input-group-text bg-gray-100 border-gray-300"
                                    style="width: 100px;"> Fecha </span>
                                <input type="date"
                                    class="form-control form-control-sm border-gray-300"
                                    name="txtFecha"
                                    id="txtFecha"
                                    value="{{ $fecha }}"
                                    autofocus>
                            </div>
                        </div>

                        <!-- Campo de Folio -->
                        <div class="col-12 col-md col-lg mb-2">
                            <div class="input-group"
                                style="width: 100%;">
                                <span class="input-group-text bg-gray-100 border-gray-300"
                                    style="width: 100px;">
                                    Folio
                                </span>
                                <input type="text"
                                    class="form-control form-control-sm border-gray-300"
                                    name="txtFolio"
                                    id="txtFolio"
                                    value="{{ $txtFolio }}"
                                    placeholder="Número de folio...">
                            </div>
                        </div>

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

            @if ($tickets->where('Subir', 0)->count() > 0)
                <div class="d-flex justify-content-end align-items-center mb-3">
                    <form action="/VentaTicketDiario/Subir"
                        method="POST"
                        class="d-inline">
                        @csrf
                        <button type="submit"
                            class="btn-loading btn btn-sm"
                            style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 6px; padding: 6px 12px; font-size: 0.75rem;">
                            <span id="buttonIcon">
                                @include('components.icons.upload')
                            </span>
                            Subir ventas
                        </button>
                    </form>
                </div>
            @endif

            <!-- TABLA DE TICKETS -->
            <div class="table-responsive content-table-sm">
                <table class="table">
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Ticket</th>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th class="text-end">IVA</th>
                            <th class="text-end">Importe</th>
                            <th>Estatus</th>
                            <th class="rounded-end text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalVentas = 0;
                            $totalKilos = 0;
                        @endphp

                        @forelse ($tickets as $ticket)
                            @php
                                $rowId = 'row-' . ($ticket->IdEncabezado ?? 'temp-' . $loop->index);
                            @endphp
                            <tr id="{{ $rowId }}"
                                class="align-middle">
                                <td>
                                    {{ $ticket->IdTicket }}
                                </td>
                                <td class="fw-500">
                                    {{ $ticket->IdEncabezado }}
                                </td>
                                <td>
                                    <span style="font-size: 0.85rem; min-width: 150px; display: inline-block;">
                                        {{ \Carbon\Carbon::parse($ticket->FechaVenta)->locale('es')->isoFormat('D MMM YYYY, HH:mm') }}
                                    </span>
                                </td>
                                <td class="fw-500 text-end"
                                    style="font-size: 0.85rem;">
                                    ${{ number_format($ticket->Iva, 2) }}
                                </td>
                                <td class="fw-500 text-end"
                                    style="font-size: 0.9rem;">
                                    ${{ number_format($ticket->ImporteVenta, 2) }}
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <!-- Status de subida -->
                                        <span id="status-subida-{{ $ticket->IdEncabezado }}"
                                            class="{{ $ticket->Subir == 1 ? 'tags-green' : 'tags-red' }} d-inline-flex align-items-center gap-1"
                                            style="padding: 5px 10px; font-size: 0.75rem;">
                                            @if ($ticket->Subir == 1)
                                                @include('components.icons.cloud-check')
                                                <span class="d-none d-md-inline">Subido</span>
                                            @else
                                                @include('components.icons.cloud-slash')
                                                <span class="d-none d-md-inline">No subido</span>
                                            @endif
                                        </span>

                                        <!-- Status de cancelación -->
                                        @if ($ticket->StatusVenta == 1)
                                            <span class="tags-red d-inline-flex align-items-center gap-1"
                                                style="padding: 5px 10px; font-size: 0.75rem;"
                                                title="Ticket cancelado">
                                                @include('components.icons.block')
                                                <span class="d-none d-md-inline">Cancelado</span>
                                            </span>
                                        @elseif ($ticket->SolicitudCancelacionTicket)
                                            @if ($ticket->SolicitudCancelacionTicket->SolicitudAprobada != 1)
                                                <span class="tags-blue d-inline-flex align-items-center gap-1"
                                                    style="padding: 5px 10px; font-size: 0.75rem;"
                                                    title="Ticket en proceso de cancelación">
                                                    @include('components.icons.clock')
                                                    <span class="d-none d-md-inline">Solicitud</span>
                                                </span>
                                            @else
                                                <span class="tags-yellow d-inline-flex align-items-center gap-1"
                                                    style="padding: 5px 10px; font-size: 0.75rem;"
                                                    title="La solicitud de cancelación fue rechazada">
                                                    @include('components.icons.alert-circle')
                                                    <span class="d-none d-md-inline">Rechazada</span>
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <!-- Botón Detalle ticket -->
                                        <form class="d-inline-flex"
                                            action="/SolicitudCancelacionTicket"
                                            method="GET">
                                            <input type="hidden"
                                                name="idTicket"
                                                value="{{ $ticket->IdEncabezado }}">
                                            <button class="btn btn-sm btn-outline-primary"
                                                title="Ver detalle del ticket"
                                                style="padding: 0.25rem 0.5rem;">
                                                @include('components.icons.list')
                                                <span class="d-none d-md-inline">DETALLE</span>
                                            </button>
                                        </form>

                                        <!-- Botón Reimprimir (solo hoy) -->
                                        @if (\Carbon\Carbon::parse($ticket->FechaVenta)->format('d/m/Y') == \Carbon\Carbon::now()->format('d/m/Y'))
                                            <form class="d-inline-flex"
                                                action="/ImprimirTicket"
                                                method="GET">
                                                <input type="hidden"
                                                    name="txtFecha"
                                                    value="{{ $ticket->FechaVenta }}">
                                                <input type="hidden"
                                                    name="txtIdTicket"
                                                    value="{{ $ticket->IdTicket }}">
                                                <button class="btn btn-sm btn-outline-primary"
                                                    title="Reimprimir ticket"
                                                    style="padding: 0.25rem 0.5rem;">
                                                    @include('components.icons.print')
                                                    <span class="d-none d-md-inline">REIMPRIMIR</span>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Botón Solicitud Factura -->
                                        @if ($ticket->SolicitudFE == 0 && $ticket->SolicitudFE != null)
                                            <button class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#ModalSolicitudFe{{ $ticket->IdTicket }}"
                                                title="Solicitar factura"
                                                style="padding: 0.25rem 0.5rem;">
                                                @include('components.icons.file-text')
                                                <span class="d-none d-md-inline">FACTURA</span>
                                            </button>
                                            @include('Posweb.ModalSolicitudFe')
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <!-- Icono -->
                                        <div class="mb-3 p-3 rounded-circle"
                                            style="background-color: rgba(30, 41, 59, 0.05); width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                            @include('components.icons.box')
                                        </div>

                                        <!-- Mensaje principal -->
                                        <h6 class="fw-600 mb-2"
                                            style="color: #1e293b;">No hay tickets para mostrar</h6>

                                        <!-- Mensaje secundario -->
                                        <p class="text-muted small mb-0">
                                            @if ($txtFolio)
                                                No se encontraron tickets con el folio <span
                                                    class="fw-500">"{{ $txtFolio }}"</span> para la fecha
                                                {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
                                            @else
                                                No hay tickets registrados para la fecha
                                                {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
                                            @endif
                                        </p>

                                        <!-- Sugerencia -->
                                        <div class="mt-3 p-2"
                                            style="background-color: #f8f9fa; border-radius: 6px;">
                                            <span class="small text-muted">
                                                @if ($txtFolio)
                                                    @include('components.icons.search') Prueba con otro folio o cambia la fecha de
                                                    búsqueda
                                                @else
                                                    @include('components.icons.calendar') Selecciona otra fecha para ver más
                                                    resultados
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                        @if ($tickets->count() > 0)
                            <tr class="table-light">
                                <td colspan="3"
                                    class="fw-bold text-end">TOTALES:</td>
                                <td class="fw-bold text-end">${{ number_format($totalIva, 2) }}</td>
                                <td class="fw-bold text-end">${{ number_format($total, 2) }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* Ajuste para el mensaje vacío */
        .table tbody tr td.py-5 {
            background-color: white;
        }

        /* Hacer el ícono más grande en el mensaje vacío */
        .rounded-circle svg {
            width: 40px;
            height: 40px;
        }
    </style>
@endsection
