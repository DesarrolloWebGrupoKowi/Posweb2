@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Solicitud de Cancelación de Ticket')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <!-- HEADER COMPACTO -->
        <div class="card border-0 p-3"
            style="border-radius: 10px; background-color: white;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    @include('components.title', [
                        'titulo' => 'Solicitud de Cancelación de Ticket',
                        'options' => [['name' => 'Venta por ticket diario', 'value' => '/VentaTicketDiario']],
                    ])
                </div>

                <div class="d-flex gap-2">
                    @isset($ticket)
                        @if (
                            \Carbon\Carbon::today()->toDateString() == \Carbon\Carbon::parse($ticket->FechaVenta)->toDateString() &&
                                empty($ticket->SolicitudCancelacionTicket))
                            <button data-bs-toggle="modal"
                                data-bs-target="#ModalCancelarTicket"
                                class="btn btn-sm"
                                style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; border: none; border-radius: 6px; padding: 8px 16px;">
                                <span class="d-flex align-items-center gap-2">
                                    @include('components.icons.delete')
                                    Cancelar
                                </span>
                            </button>
                            @include('CancelacionTickets.ModalCancelarTicket')
                        @endif
                    @endisset

                    <a href="/ReporteSolicitudCancelacion"
                        class="btn btn-sm"
                        style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 6px; padding: 8px 16px;">
                        <span class="d-flex align-items-center gap-2">
                            @include('components.icons.text-file')
                            Historial
                        </span>
                    </a>
                </div>
            </div>

            <div class="mt-2">
                @include('Alertas.Alertas')
            </div>
        </div>

        <!-- BUSCADOR INICIAL COMPACTO -->
        @if (empty($ticket->TipoPago))
            <div class="card border-0 p-5"
                style="border-radius: 10px; background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%); min-height: 500px; display: flex; align-items: center; justify-content: center;">

                <div class="text-center"
                    style="max-width: 500px;">
                    <!-- Icono animado sutil -->
                    <div class="mb-4 position-relative">
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center"
                            style="background-color: rgba(30, 41, 59, 0.03); width: 160px; height: 160px;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); width: 100px; height: 100px; box-shadow: 0 20px 30px -10px rgba(0,0,0,0.15);">
                                <div style="color: white; width: 50px; height: 50px;">
                                    {{-- @include('components.icons.search') --}}
                                    <svg aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="50"
                                        height="50"
                                        fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor"
                                            stroke-linecap="round"
                                            stroke-width="2"
                                            d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                                    </svg>

                                </div>
                            </div>
                        </div>

                        <!-- Círculo decorativo -->
                        <div class="position-absolute"
                            style="width: 20px; height: 20px; background-color: #10b981; border-radius: 50%; bottom: 20px; right: calc(50% - 70px); opacity: 0.5;">
                        </div>
                        <div class="position-absolute"
                            style="width: 12px; height: 12px; background-color: #f59e0b; border-radius: 50%; top: 20px; left: calc(50% - 70px); opacity: 0.5;">
                        </div>
                    </div>

                    <h2 class="fw-600 mb-3"
                        style="color: #0f172a; font-size: 2rem;">
                        ¡Busca un ticket!
                    </h2>

                    <p class="text-muted mb-4"
                        style="font-size: 1.1rem;">
                        Ingresa el número de ticket o folio para ver toda la información disponible y gestionar
                        solicitudes de cancelación.
                    </p>

                    <!-- Formulario grande y visible -->
                    <div class="p-4 rounded-3"
                        style="background-color: white; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                        <form class="d-flex flex-column flex-sm-row gap-3"
                            action="/SolicitudCancelacionTicket"
                            method="GET">
                            <div class="flex-grow-1">
                                <div class="input-group"
                                    style="width: 100%;">
                                    <input type="number"
                                        class="form-control form-control-lg py-2 {{ $idTicket && empty($ticket) ? 'is-invalid' : 'border-gray-300' }}"
                                        id="idTicket"
                                        name="idTicket"
                                        value="{{ $idTicket }}"
                                        placeholder="Ej: 12345"
                                        autofocus>
                                </div>
                            </div>
                            <button type="submit"
                                class="btn btn-outline-dark bg-dark text-white ">
                                <span class="d-flex align-items-center justify-content-center gap-2">
                                    @include('components.icons.search')
                                    Buscar
                                </span>
                            </button>
                        </form>
                        @if ($idTicket && empty($ticket))
                            <div class="text-danger text-start pt-2">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                No se encontró el ticket #{{ $idTicket }}
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 d-flex flex-wrap gap-3 justify-content-center">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width: 8px; height: 8px; background-color: #10b981; border-radius: 50%;"></div>
                            <span class="small text-muted">Tickets del día</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width: 8px; height: 8px; background-color: #f59e0b; border-radius: 50%;"></div>
                            <span class="small text-muted">Solicitudes pendientes</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width: 8px; height: 8px; background-color: #ef4444; border-radius: 50%;"></div>
                            <span class="small text-muted">Cancelaciones</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- INFORMACIÓN DEL TICKET - LAYOUT DE DOS COLUMNAS -->
        @isset($ticket->TipoPago)
            <!-- HEADER DE INFORMACIÓN DEL TICKET (ARRIBA DE LAS COLUMNAS) -->
            <div class="card border-0 p-4"
                style="border-radius: 10px; background-color: white;">

                <!-- BARRA SUPERIOR CON INFORMACIÓN CLAVE Y BUSCADOR RÁPIDO -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    <div class="d-flex align-items-center gap-4 flex-wrap">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center p-1"
                                style="background-color: rgba(30, 41, 59, 0.1); width: 32px; height: 32px;">
                                <div class="d-flex align-items-center justify-content-center"
                                    style="color: #1e293b; width: 16px; height: 16px;">
                                    @include('components.icons.credit-card')
                                </div>
                            </div>
                            <div>
                                <div class="small text-muted">Ticket</div>
                                <div class="fw-600">{{ $ticket->IdTicket }}</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center p-1"
                                style="background-color: rgba(30, 41, 59, 0.1); width: 32px; height: 32px;">
                                <div class="d-flex align-items-center justify-content-center"
                                    style="color: #1e293b; width: 16px; height: 16px;">
                                    @include('components.icons.hash')
                                </div>
                            </div>
                            <div>
                                <div class="small text-muted">Folio</div>
                                <div class="fw-600">{{ $ticket->IdEncabezado }}</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center p-1"
                                style="background-color: rgba(30, 41, 59, 0.1); width: 32px; height: 32px;">
                                <div class="d-flex align-items-center justify-content-center"
                                    style="color: #1e293b; width: 16px; height: 16px;">
                                    @include('components.icons.calendar')
                                </div>
                            </div>
                            <div>
                                <div class="small text-muted">Fecha</div>
                                <div class="fw-600">
                                    {{ \Carbon\Carbon::parse($ticket->FechaVenta)->locale('es')->isoFormat('D MMM YYYY, HH:mm') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BUSCADOR RÁPIDO -->
                    <form class="d-flex align-items-center gap-1"
                        action="/SolicitudCancelacionTicket"
                        method="GET">
                        <div class="flex-grow-1">
                            <div class="input-group"
                                style="width: 100%;">
                                <input type="number"
                                    class="form-control form-control-sm py-2"
                                    id="idTicket"
                                    name="idTicket"
                                    value="{{ $idTicket }}"
                                    placeholder="Ej: 12345"
                                    autofocus>
                            </div>
                        </div>

                        <button type="submit"
                            class="btn btn-outline-dark bg-dark text-white ">
                            <span class="d-flex align-items-center justify-content-center gap-2">
                                @include('components.icons.search')
                                Buscar
                            </span>
                        </button>
                    </form>
                </div>

                <!-- INFORMACIÓN ADICIONAL (EMPLEADO/SOCIO) EN UNA SOLA LÍNEA -->
                @if (!empty($ticket->NumNomina) && (!empty($empleado) || !empty($frecuenteSocio)))
                    <div class="d-flex align-items-center gap-4 mb-4 pb-3 border-bottom"
                        style="border-color: #e5e7eb !important;">
                        @if (!empty($empleado))
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center p-1"
                                    style="background-color: rgba(124, 58, 237, 0.1); width: 28px; height: 28px;">
                                    <div style="color: #7c3aed; width: 14px; height: 14px;">
                                        @include('components.icons.user')
                                    </div>
                                </div>
                                <span class="small">
                                    <span class="text-muted">Empleado:</span>
                                    <span class="fw-500 ms-1">{{ $empleado->Nombre }} {{ $empleado->Apellidos }}</span>
                                </span>
                            </div>
                        @endif

                        @if (!empty($frecuenteSocio))
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center p-1"
                                    style="background-color: rgba(245, 158, 11, 0.1); width: 28px; height: 28px;">
                                    <div style="color: #d97706; width: 14px; height: 14px;">
                                        @include('components.icons.user')
                                    </div>
                                </div>
                                <span class="small">
                                    <span class="text-muted">Socio:</span>
                                    <span class="fw-500 ms-1">{{ $frecuenteSocio->Nombre }}</span>
                                </span>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- ESTADO DE SOLICITUD (SI EXISTE) -->
                @isset($ticket->SolicitudCancelacionTicket)
                    <div class="card mb-4 border-0"
                        style="border-radius: 10px; background-color: #f8f9fa; border-left: 4px solid
                        @if ($ticket->SolicitudCancelacionTicket->SolicitudAprobada === null) #f59e0b
                        @elseif($ticket->SolicitudCancelacionTicket->SolicitudAprobada === '0') #ef4444
                        @elseif($ticket->SolicitudCancelacionTicket->SolicitudAprobada === '1') #ef4444 @endif !important;">

                        <div class="card-body p-3">
                            <div class="d-flex flex-wrap align-items-center gap-3">
                                <!-- Primera línea: Estado y motivo -->
                                <div class="d-flex align-items-center gap-2 flex-grow-1">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                        style="background-color:
                                        @if ($ticket->SolicitudCancelacionTicket->SolicitudAprobada === null) rgba(245, 158, 11, 0.15)
                                        @elseif($ticket->SolicitudCancelacionTicket->SolicitudAprobada === '0') rgba(239, 68, 68, 0.15)
                                        @elseif($ticket->SolicitudCancelacionTicket->SolicitudAprobada === '1') rgba(239, 68, 68, 0.15) @endif;
                                        width: 32px; height: 32px;">
                                        <div class="d-flex align-items-center justify-content-center"
                                            style="color:
                                        @if ($ticket->SolicitudCancelacionTicket->SolicitudAprobada === null) #f59e0b
                                        @elseif($ticket->SolicitudCancelacionTicket->SolicitudAprobada === '0') #ef4444
                                        @elseif($ticket->SolicitudCancelacionTicket->SolicitudAprobada === '1') #ef4444 @endif;
                                        width: 16px; height: 16px;">
                                            @include('components.icons.text-file')
                                        </div>
                                    </div>

                                    <span class="badge"
                                        style="background-color: #1e293b; color: white; padding: 4px 8px; font-size: 0.7rem;">
                                        #{{ $ticket->SolicitudCancelacionTicket->IdSolicitud ?? $ticket->IdEncabezado }}
                                    </span>

                                    <span class="badge"
                                        style="
                                    background-color:
                                        @if ($ticket->SolicitudCancelacionTicket->SolicitudAprobada === null) rgba(245, 158, 11, 0.15)
                                        @elseif($ticket->SolicitudCancelacionTicket->SolicitudAprobada === '0') rgba(239, 68, 68, 0.15)
                                        @elseif($ticket->SolicitudCancelacionTicket->SolicitudAprobada === '1') rgba(239, 68, 68, 0.15) @endif;
                                    color:
                                        @if ($ticket->SolicitudCancelacionTicket->SolicitudAprobada === null) #f59e0b
                                        @elseif($ticket->SolicitudCancelacionTicket->SolicitudAprobada === '0') #ef4444
                                        @elseif($ticket->SolicitudCancelacionTicket->SolicitudAprobada === '1') #ef4444 @endif;
                                    padding: 4px 8px;
                                    font-size: 0.7rem;">
                                        @if ($ticket->SolicitudCancelacionTicket->SolicitudAprobada === null)
                                            Pendiente de revisión
                                        @elseif($ticket->SolicitudCancelacionTicket->SolicitudAprobada === '0')
                                            Ticket Cancelado
                                        @elseif($ticket->SolicitudCancelacionTicket->SolicitudAprobada === '1')
                                            Solicitud Rechazada
                                        @endif
                                    </span>

                                    <span class="small">
                                        <span class="text-muted fw-500">Motivo:</span>
                                        <span class="fw-500 ms-1">
                                            {{ $ticket->SolicitudCancelacionTicket->MotivoCancelacion }}
                                        </span>
                                    </span>
                                </div>

                                <!-- Segunda línea: Fecha y estatus de subida -->
                                <div class="d-flex align-items-center gap-3">
                                    <span class="small text-muted">
                                        <span class="fw-500">Fecha:</span>
                                        {{ \Carbon\Carbon::parse($ticket->SolicitudCancelacionTicket->created_at ?? $ticket->FechaVenta)->format('d/m/Y H:i') }}
                                    </span>

                                    <!-- Status de Subida -->
                                    <span
                                        class="badge {{ ($ticket->SolicitudCancelacionTicket->subir ?? $ticket->Subir) == 1 ? 'tags-green' : 'tags-red' }}"
                                        style="font-size: 0.7rem;">
                                        @if (($ticket->SolicitudCancelacionTicket->subir ?? $ticket->Subir) == 1)
                                            @include('components.icons.cloud-check')
                                            <span class="ps-2 d-none d-md-inline">Subido</span>
                                        @else
                                            @include('components.icons.cloud-slash')
                                            <span class="ps-2 d-none d-md-inline">No subido</span>
                                        @endif
                                    </span>
                                </div>

                                <!-- Botón de acción -->
                                @if ($ticket->SolicitudCancelacionTicket->subir == 0)
                                    <form action="/SolicitudCancelacionTicket/Subir"
                                        method="POST"
                                        class="d-inline">
                                        @csrf
                                        <input type="hidden"
                                            name="idEncabezado"
                                            value="{{ $ticket->IdEncabezado }}">
                                        <input type="hidden"
                                            name="idTicket"
                                            value="{{ $idTicket ?? $ticket->IdTicket }}">
                                        <button type="submit"
                                            class="btn btn-sm"
                                            style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 6px; padding: 6px 12px; font-size: 0.75rem;">
                                            <span class="d-flex align-items-center gap-1">
                                                @include('components.icons.upload')
                                                Subir solicitud
                                            </span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endisset

                <!-- SOLICITUD DE FACTURA (SI EXISTE) -->
                @if (isset($ticket->SolicitudFactura) && $ticket->SolicitudFactura->count() > 0)
                    <div class="card mb-4 border-0"
                        style="border-radius: 10px; background-color: #f8f9fa; border-left: 4px solid #10b981 !important;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                    style="background-color: rgba(16, 185, 129, 0.15); width: 32px; height: 32px;">
                                    <div class="d-flex align-items-center justify-content-center"
                                        style="color: #10b981; width: 16px; height: 16px;">
                                        @include('components.icons.file-text')
                                    </div>
                                </div>
                                <span class="badge"
                                    style="background-color: #10b981; color: white;">SOLICITUD DE FACTURA</span>
                            </div>

                            @foreach ($ticket->SolicitudFactura as $factura)
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="d-flex flex-column gap-2">
                                            <div>
                                                <span class="text-muted small d-block">Cliente</span>
                                                <span class="fw-500">{{ $factura->NomCliente }}</span>
                                            </div>
                                            <div>
                                                <span class="text-muted small d-block">RFC</span>
                                                <span class="fw-500">{{ $factura->RFC }}</span>
                                            </div>
                                            <div>
                                                <span class="text-muted small d-block">Email</span>
                                                <span class="fw-500">{{ $factura->Email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex flex-column gap-2">
                                            <div>
                                                <span class="text-muted small d-block">Fecha Solicitud</span>
                                                <span class="fw-500">
                                                    {{ \Carbon\Carbon::parse($factura->FechaSolicitud)->format('d/m/Y H:i') }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-muted small d-block">Uso CFDI</span>
                                                <span class="fw-500">{{ $factura->UsoCFDI }}</span>
                                            </div>
                                            <div>
                                                <span class="text-muted small d-block">Método de Pago</span>
                                                <span class="fw-500">{{ $factura->MetodoPago }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dirección (si existe) -->
                                @if ($factura->Calle)
                                    <div class="mt-2 pt-2 border-top"
                                        style="border-color: #e5e7eb;">
                                        <span class="text-muted small d-block mb-1">Dirección</span>
                                        <span class="small">
                                            {{ $factura->Calle }} {{ $factura->NumExt }} {{ $factura->NumInt }},
                                            {{ $factura->Colonia }}, {{ $factura->Ciudad }}, {{ $factura->Estado }}, CP
                                            {{ $factura->CodigoPostal }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Status de la factura -->
                                <div class="d-flex justify-content-end align-items-center gap-2 mt-2 pt-2 border-top"
                                    style="border-color: #e5e7eb;">
                                    {{-- <span class="small text-muted">Status:</span> --}}
                                    <span class="badge {{ $factura->Subir == 1 ? 'tags-green' : 'tags-red' }}"
                                        style="font-size: 0.7rem;">
                                        @if ($factura->Subir == 1)
                                            @include('components.icons.cloud-check')
                                            <span class="ps-1">Subido</span>
                                        @else
                                            @include('components.icons.cloud-slash')
                                            <span class="ps-1">No subido</span>
                                        @endif
                                    </span>
                                    @if ($ticket->Subir == 1 && $factura->Subir == 0)
                                        <form action="/SolicitudesFactura/Subir"
                                            method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-sm"
                                                style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 6px; padding: 6px 12px; font-size: 0.75rem;">
                                                <span class="d-flex align-items-center gap-1">
                                                    @include('components.icons.upload')
                                                    Subir solicitud
                                                </span>
                                            </button>
                                        </form>
                                    @endif
                                    {{-- @if ($factura->Status == 0)
                                        <span class="badge tags-blue" style="font-size: 0.7rem;">Activo</span>
                                    @endif --}}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- CONTENIDO PRINCIPAL EN DOS COLUMNAS -->
                <div class="row g-4">
                    <!-- COLUMNA IZQUIERDA: DETALLE DEL TICKET -->
                    <div class="col-lg-8">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-600 text-gray-800">Artículos</h6>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge {{ $ticket->Subir == 1 ? 'tags-green' : 'tags-red' }}"
                                    style="font-size: 0.7rem;">
                                    @if ($ticket->Subir == 1)
                                        @include('components.icons.cloud-check')
                                        <span class="ps-2 d-none d-md-inline">Subido</span>
                                    @else
                                        @include('components.icons.cloud-slash')
                                        <span class="ps-2 d-none d-md-inline">No subido</span>
                                    @endif
                                </span>
                                @if ($ticket->Subir == 0)
                                    <form action="/VentaTicketDiario/Subir"
                                        method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            type="button"
                                            class="btn btn-sm"
                                            style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 6px; padding: 6px 12px; font-size: 0.75rem;"
                                            id="rotateButton">
                                            <span id="buttonIcon">
                                                @include('components.icons.upload')
                                            </span>
                                            Subir ventas
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <!-- TABLA DE ARTÍCULOS COMPACTA -->
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-head">
                                    <tr>
                                        <th class="rounded-start">Código</th>
                                        <th>Artículo</th>
                                        <th class="text-center">Cant</th>
                                        <th class="text-end">Precio</th>
                                        <th class="text-end">IVA</th>
                                        <th class="text-end">Importe</th>
                                        <th>Paq</th>
                                        <th>Pedido</th>
                                        <th class="rounded-end">Rost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalCant = 0;
                                        $totalIva = 0;
                                        $total = 0;
                                    @endphp
                                    @foreach ($ticket->detalle as $detalle)
                                        <tr class="@if (isset($ticket->SolicitudCancelacionTicket) && $ticket->SolicitudCancelacionTicket->SolicitudAprobada === '0') text-decoration-line-through @endif">
                                            <td><span class="fw-500 small">{{ $detalle->CodArticulo }}</span></td>
                                            <td>
                                                <span class="small text-truncate"
                                                    style="max-width: 180px;"
                                                    title="{{ $detalle->NomArticulo }}">
                                                    {{ $detalle->NomArticulo }}
                                                </span>
                                            </td>
                                            <td class="text-center small">{{ number_format($detalle->CantArticulo, 4) }}</td>
                                            <td class="text-end small">${{ number_format($detalle->PrecioArticulo, 2) }}</td>
                                            <td class="text-end small">${{ number_format($detalle->IvaArticulo, 2) }}</td>
                                            <td class="text-end small fw-500">
                                                ${{ number_format($detalle->ImporteArticulo, 2) }}</td>
                                            <td class="small">{{ $detalle->NomPaquete }}</td>
                                            <td class="small">{{ $detalle->Cliente }}</td>
                                            <td class="small">{{ $detalle->IdRosticero }}</td>
                                        </tr>
                                        @php
                                            $totalCant += $detalle->CantArticulo;
                                            $totalIva += $detalle->IvaArticulo;
                                            $total += $detalle->ImporteArticulo;
                                        @endphp
                                    @endforeach

                                    <tr
                                        class="table-light @if (isset($ticket->SolicitudCancelacionTicket) && $ticket->SolicitudCancelacionTicket->SolicitudAprobada === '0') text-decoration-line-through @endif">
                                        <td colspan="2"
                                            class="fw-bold text-end">TOTALES:</td>
                                        <td class="fw-bold text-end">{{ number_format($totalCant, 2) }}</td>
                                        <td></td>
                                        <td class="fw-bold text-end">${{ number_format($totalIva, 2) }}</td>
                                        <td class="fw-bold text-end">${{ number_format($total, 2) }}</td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- COLUMNA DERECHA: PAGOS Y TOTALES EN UNA SOLA CARD -->
                    <div class="col-lg-4">
                        <div class="card border-0 h-100"
                            style="background-color: #f8f9fa; border-radius: 10px;">
                            <div class="card-body p-3">
                                <h6 class="fw-600 text-gray-800 mb-3">Pagos y Totales</h6>

                                <!-- Tabla de Pagos -->
                                <div class="table-responsive mb-3">
                                    <table class="table table-sm">
                                        <thead class="table-head"
                                            style="background-color: transparent; color: #1e293b;">
                                            <tr>
                                                <th class="ps-0">Tipo de Pago</th>
                                                <th class="text-end">Pago</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalPagos = 0;
                                            @endphp
                                            @isset($ticket->TipoPago)
                                                @foreach ($ticket->TipoPago as $tipoPago)
                                                    <tr
                                                        class="@if (isset($ticket->SolicitudCancelacionTicket) && $ticket->SolicitudCancelacionTicket->SolicitudAprobada === '0') text-decoration-line-through @endif">
                                                        <td class="ps-0 small">{{ $tipoPago->NomTipoPago }}</td>
                                                        <td class="text-end small fw-500">
                                                            ${{ number_format($tipoPago->PivotPago->Pago, 2) }}
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $totalPagos += $tipoPago->PivotPago->Pago;
                                                    @endphp
                                                @endforeach
                                            @endisset
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Información adicional de pagos -->
                                @isset($cambio)
                                    <div class="mb-3 pb-2 border-bottom @if (isset($ticket->SolicitudCancelacionTicket) && $ticket->SolicitudCancelacionTicket->SolicitudAprobada === '0') text-decoration-line-through @endif"
                                        style="border-color: #e5e7eb !important;">
                                        <div class="d-flex justify-content-between small">
                                            <span class="text-muted">SubTotal:</span>
                                            <span class="fw-500">${{ number_format($ticket->SubTotal, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between small mb-2">
                                            <span class="text-muted">IVA:</span>
                                            <span class="fw-500">${{ number_format($ticket->Iva, 2) }}</span>
                                        </div>
                                    </div>
                                @endisset

                                <!-- Totales -->
                                <div class="mt-2 @if (isset($ticket->SolicitudCancelacionTicket) && $ticket->SolicitudCancelacionTicket->SolicitudAprobada === '0') text-decoration-line-through @endif">
                                    <div class="d-flex justify-content-between small">
                                        <span class="text-muted">Pago recibido:</span>
                                        <span class="fw-500">${{ number_format($totalPagos, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between small">
                                        <span class="text-muted">Cambio:</span>
                                        <span class="fw-500">${{ number_format($cambio->Restante, 2) }}</span>
                                    </div>
                                    @if ($ticket->Promocion)
                                        <div class="d-flex justify-content-between small mb-2">
                                            <span class="text-muted">Promoción:</span>
                                            <span
                                                class="fw-500 text-success">-${{ number_format($ticket->Promocion, 2) }}</span>
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between pt-2 mt-2 border-top"
                                        style="border-color: #e5e7eb !important;">
                                        <span class="fw-600">Total:</span>
                                        <span class="fw-600"
                                            style="color: #1e293b;">${{ number_format($ticket->ImporteVenta, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endisset
    </div>
@endsection

@section('styles')
    <style>
        .table thead th {
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.75rem 0.5rem;
        }

        .table tbody td {
            padding: 0.5rem;
            font-size: 0.85rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .table tbody tr:hover {
            background-color: rgba(30, 41, 59, 0.02);
        }

        /* Estilo para la tabla de pagos en la columna derecha */
        .col-lg-4 .table thead th {
            background-color: transparent;
            font-size: 0.7rem;
            padding: 0.5rem 0;
        }

        .col-lg-4 .table tbody td {
            background-color: transparent;
            padding: 0.35rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .badge {
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.7rem;
        }


        .fw-600 {
            font-weight: 600;
        }

        .card {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        /* Asegurar que la columna derecha ocupe toda la altura */
        .col-lg-4 .card {
            height: calc(100% - 1.5rem);
            margin-bottom: 1.5rem;
        }

        .text-decoration-line-through {
            text-decoration: line-through;
            opacity: 0.7;
        }
    </style>
@endsection
