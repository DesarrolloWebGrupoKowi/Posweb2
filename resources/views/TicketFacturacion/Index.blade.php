<x-page-container title="Habilitar Tickets para Facturación">
    <x-card-gradient-header
        icon="receipt"
        title="Habilitar Tickets para Facturación"
        subtitle="Gestione las excepciones de facturación de tickets"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <x-form.form
            action="/TicketFacturacion"
            method="GET"
            id="formBuscar"
        >
            <x-form.group>
                <x-form.text
                    name="idEncabezado"
                    label="Id Encabezado / Folio"
                    icon="ticket"
                    placeholder="Ingrese el Id Encabezado..."
                    col="col-md-4"
                    :autofocus="true"
                    :value="$idEncabezado ?? ''"
                />
            </x-form.group>
            <div class="col-md-2 d-flex gap-2">
                <x-form.submit
                    text="Buscar"
                    icon="search"
                />
                <x-form.clear />
            </div>
        </x-form.form>

        @if ($ticket)
            @php
                $ticketHabilitado = \App\Models\DatTicketHabilitadoFacturacion::where(
                    'IdEncabezado',
                    $ticket->IdEncabezado,
                )->first();

                $estaActivoHoy =
                    $ticketHabilitado &&
                    $ticketHabilitado->Activo == 1 &&
                    $ticketHabilitado->Fecha->format('Y-m-d') == now()->format('Y-m-d');
                $estaExpirado =
                    $ticketHabilitado &&
                    $ticketHabilitado->Activo == 1 &&
                    $ticketHabilitado->Fecha->format('Y-m-d') != now()->format('Y-m-d');
                $estaInactivo = $ticketHabilitado && $ticketHabilitado->Activo == 0;
            @endphp

            <!-- Datos del Ticket -->
            <div class="p-4">
                <div class="card-modern mb-4">
                    <div
                        class="card-header border-bottom p-3"
                        style="background: #f8fafc;"
                    >
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i
                                    class="bi bi-receipt"
                                    style="color: #64748b;"
                                ></i>
                                <h6
                                    class="fw-bold mb-0"
                                    style="color: #0f172a;"
                                >Ticket Encontrado</h6>
                            </div>
                            <div>
                                @if ($estaActivoHoy)
                                    <span class="badge-status badge-active">
                                        <i class="bi bi-check-circle me-1"></i>Habilitado hoy
                                    </span>
                                @elseif ($estaExpirado)
                                    <span class="badge-status badge-pending">
                                        <i class="bi bi-clock-history me-1"></i>Expirado
                                    </span>
                                @elseif ($estaInactivo)
                                    <span class="badge-status badge-inactive">
                                        <i class="bi bi-x-circle me-1"></i>Desactivado
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <!-- Banner de estado -->
                        @if ($ticket->SolicitudFE == '0')
                            <div
                                class="rounded-3 mb-4 p-4"
                                style="background: #eff6ff; border-left: 4px solid #3b82f6;"
                            >
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="rounded-circle d-flex align-items-center justify-content-center"
                                        style="background: #dbeafe; width: 40px; height: 40px;"
                                    >
                                        <i
                                            class="bi bi-check-circle"
                                            style="color: #3b82f6; font-size: 1.2rem;"
                                        ></i>
                                    </div>
                                    <div>
                                        <h6
                                            class="fw-bold mb-1"
                                            style="color: #1e40af;"
                                        >Ticket ya facturado</h6>
                                        <p
                                            class="mb-0"
                                            style="color: #3b82f6; font-size: 0.85rem;"
                                        >Este ticket ya ha sido facturado. No es posible habilitarlo nuevamente.</p>
                                    </div>
                                </div>
                            </div>
                        @elseif ($ticket->StatusVenta == 1)
                            <div
                                class="rounded-3 mb-4 p-4"
                                style="background: #fef2f2; border-left: 4px solid #ef4444;"
                            >
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="rounded-circle d-flex align-items-center justify-content-center"
                                        style="background: #fee2e2; width: 40px; height: 40px;"
                                    >
                                        <i
                                            class="bi bi-x-circle"
                                            style="color: #ef4444; font-size: 1.2rem;"
                                        ></i>
                                    </div>
                                    <div>
                                        <h6
                                            class="fw-bold mb-1"
                                            style="color: #991b1b;"
                                        >Ticket cancelado</h6>
                                        <p
                                            class="mb-0"
                                            style="color: #ef4444; font-size: 0.85rem;"
                                        >Este ticket ha sido cancelado. No puede ser habilitado para facturación.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Información General -->
                        <h5
                            class="mb-4"
                            style="font-weight: 600; color: #0f172a; font-size: 1rem;"
                        >
                            <i
                                class="bi bi-info-circle me-2"
                                style="color: #3b82f6;"
                            ></i>Información General
                        </h5>
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6 col-md-3">
                                <label
                                    style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                                >Id Encabezado</label>
                                <p
                                    class="mb-0"
                                    style="font-weight: 600; color: #0f172a;"
                                >{{ $ticket->IdEncabezado }}</p>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label
                                    style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                                >Ticket</label>
                                <p
                                    class="mb-0"
                                    style="font-weight: 600; color: #0f172a;"
                                >{{ $ticket->IdTicket }}</p>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label
                                    style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                                >Tienda</label>
                                <p
                                    class="mb-0"
                                    style="color: #475569; font-size: 0.9rem;"
                                ><span style="font-weight: 500">{{ $ticket->Tienda->NomTienda ?? '-' }}</span></p>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label
                                    style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                                >Fecha Venta</label>
                                <p
                                    class="mb-0"
                                    style="color: #475569; font-size: 0.9rem;"
                                >
                                    <i
                                        class="bi bi-clock me-1"
                                        style="color: #94a3b8; font-size: 0.8rem;"
                                    ></i>
                                    <span
                                        style="font-weight: 500">{{ $ticket->FechaVenta ? \Carbon\Carbon::parse($ticket->FechaVenta)->format('d/m/Y H:i') : '-' }}</span>
                                </p>
                            </div>
                            @if ($ticketHabilitado)
                                <div class="col-sm-6 col-md-3">
                                    <label
                                        style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                                    >Habilitado por</label>
                                    <p
                                        class="mb-0"
                                        style="color: #475569; font-size: 0.9rem;"
                                    ><span style="font-weight: 500">{{ $ticketHabilitado->usuario->Nombre ?? '' }}
                                            {{ $ticketHabilitado->usuario->Apellidos ?? ($ticketHabilitado->usuario->NomUsuario ?? 'N/A') }}</span>
                                    </p>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <label
                                        style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; display: block; margin-bottom: 4px;"
                                    >Fecha Habilitación</label>
                                    <p
                                        class="mb-0"
                                        style="color: #475569; font-size: 0.9rem;"
                                    >
                                        <i
                                            class="bi bi-clock me-1"
                                            style="color: #94a3b8; font-size: 0.8rem;"
                                        ></i>
                                        <span
                                            style="font-weight: 500">{{ $ticketHabilitado->Fecha->format('d/m/Y H:i') }}</span>
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Detalle del Ticket -->
                        @if ($ticket->Detalle && count($ticket->Detalle) > 0)
                            <h5
                                class="mb-3"
                                style="font-weight: 600; color: #0f172a; font-size: 1rem;"
                            >
                                <i
                                    class="bi bi-list-ul me-2"
                                    style="color: #3b82f6;"
                                ></i>Artículos del Ticket
                            </h5>
                            <div
                                class="table-responsive mb-4"
                                style="overflow-y: auto;"
                            >
                                <table class="table-hover table-custom mb-0 table">
                                    <thead style="position: sticky; top: 0; z-index: 2; background: #f8fafc;">
                                        <tr>
                                            <th><i class="bi bi-upc me-1"></i>Código</th>
                                            <th><i class="bi bi-box me-1"></i>Artículo</th>
                                            <th class="text-center"><i class="bi bi-123 me-1"></i>Cantidad</th>
                                            <th class="text-end"><i class="bi bi-currency-dollar me-1"></i>Precio</th>
                                            <th class="text-end"><i class="bi bi-calculator me-1"></i>Importe</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $totalArticulos = 0; @endphp
                                        @foreach ($ticket->Detalle as $detalle)
                                            <tr>
                                                <td style="font-weight: 500; color: #0f172a;">
                                                    {{ $detalle->CodArticulo }}</td>
                                                <td>{{ $detalle->NomArticulo }}</td>
                                                <td class="text-center">{{ number_format($detalle->CantArticulo, 2) }}
                                                </td>
                                                <td class="text-end">${{ number_format($detalle->PrecioArticulo, 2) }}
                                                </td>
                                                <td
                                                    class="text-end"
                                                    style="font-weight: 500;"
                                                >${{ number_format($detalle->ImporteArticulo, 2) }}</td>
                                            </tr>
                                            @php $totalArticulos += $detalle->ImporteArticulo; @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot style="border: 2px solid #f8fafc;">
                                        <tr
                                            style="background: #f8fafc; font-weight: 700; border-top: 2px solid #f8fafc;">
                                            <td
                                                colspan="4"
                                                class="py-1 text-end"
                                            >Total</td>
                                            <td
                                                class="py-1 text-end"
                                                style="color: #059669;"
                                            >${{ number_format($totalArticulos, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endif

                        <!-- Botones de acción -->
                        @if ($ticket->SolicitudFE != '0' && $ticket->StatusVenta != 1)
                            <div>
                                <div class="d-flex justify-content-end gap-2">
                                    @if (!$ticketHabilitado || $estaInactivo || $estaExpirado)
                                        <button
                                            type="button"
                                            class="btn-modern {{ $estaExpirado ? 'btn-warning-modern' : 'btn-agregar' }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalHabilitarTicket"
                                        >
                                            <i class="bi bi-check-circle me-2"></i>
                                            {{ $estaExpirado ? 'Rehabilitar Ticket' : 'Habilitar Ticket' }}
                                        </button>
                                    @endif
                                    @if ($estaActivoHoy)
                                        <button
                                            type="button"
                                            class="btn-modern btn-remover"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalDesactivarTicket"
                                        >
                                            <i class="bi bi-x-circle me-2"></i>Desactivar Ticket
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @include('TicketFacturacion.ModalHabilitarTicket')
            @if ($estaActivoHoy)
                @include('TicketFacturacion.ModalDeshabilitarTicket')
            @endif
        @elseif ($idEncabezado)
            <div class="p-4">
                <div class="card-modern">
                    <div class="card-body p-4 text-center">
                        <div class="empty-state-icon mx-auto mb-3">
                            <i
                                class="bi bi-search fs-3"
                                style="color: #94a3b8;"
                            ></i>
                        </div>
                        <h6 class="text-muted">Ticket no encontrado</h6>
                        <small class="text-muted">No se encontró un ticket con el Id Encabezado ingresado</small>
                    </div>
                </div>
            </div>
        @else
            <div class="p-4">
                <div class="card-modern">
                    <div class="card-body p-4 text-center">
                        <div class="empty-state-icon mx-auto mb-3">
                            <i
                                class="bi bi-ticket-detailed fs-3"
                                style="color: #94a3b8;"
                            ></i>
                        </div>
                        <h6 class="text-muted">Buscar Ticket</h6>
                        <small class="text-muted">Ingrese un Id Encabezado para buscar y habilitar un ticket para
                            facturación</small>
                    </div>
                </div>
            </div>
        @endif

        <!-- Historial -->
        <div class="p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                <div>
                    <h5 class="section-content-title">
                        <i
                            class="bi bi-clock-history me-2"
                            style="color: #64748b;"
                        ></i>Historial de Tickets Habilitados
                    </h5>
                    <p class="section-content-subtitle">{{ $ticketsHabilitados->total() }} registros</p>
                </div>
            </div>

            <div
                class="table-responsive"
                style="max-height: 50vh; overflow-y: auto;"
            >
                <table class="table-hover table-custom table">
                    <thead style="position: sticky; top: 0; z-index: 2; background: #f8fafc;">
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>Id</th>
                            <th><i class="bi bi-ticket me-1"></i>Ticket</th>
                            <th><i class="bi bi-shop me-1"></i>Tienda</th>
                            <th><i class="bi bi-currency-dollar me-1"></i>Importe</th>
                            <th><i class="bi bi-person me-1"></i>Usuario</th>
                            <th><i class="bi bi-calendar me-1"></i>Fecha</th>
                            <th><i class="bi bi-chat-text me-1"></i>Observaciones</th>
                            <th><i
                                    class="bi bi-circle-fill me-1"
                                    style="font-size: 0.5rem;"
                                ></i>Estatus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ticketsHabilitados as $item)
                            @php
                                $esHoy = $item->Fecha->format('Y-m-d') == now()->format('Y-m-d');
                            @endphp
                            <tr>
                                <td style="font-weight: 600; color: #0f172a;">{{ $item->Id }}</td>
                                <td>
                                    <span style="font-weight: 500;">{{ $item->IdEncabezado }}</span>
                                    <br>
                                    <small style="color: #94a3b8;">Ticket:
                                        {{ $item->encabezado->IdTicket ?? 'N/A' }}</small>
                                </td>
                                <td style="color: #64748b;">{{ $item->encabezado->Tienda->NomTienda ?? 'N/A' }}</td>
                                <td
                                    class="text-end"
                                    style="font-weight: 500;"
                                >
                                    ${{ $item->encabezado ? number_format($item->encabezado->ImporteVenta, 2) : '0.00' }}
                                </td>
                                <td>
                                    {{ strtoupper($item->usuario->NomUsuario ?? '') }} -
                                    {{ $item->usuario->Nombre ?? '' }}
                                    {{ $item->usuario->Apellidos ?? ($item->usuario->NomUsuario ?? 'N/A') }}
                                </td>
                                <td style="color: #64748b;">{{ $item->Fecha->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span
                                        class="text-truncate d-inline-block"
                                        style="max-width: 150px;"
                                        title="{{ $item->Observaciones }}"
                                    >
                                        {{ $item->Observaciones ?: '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($item->Activo == 1 && $esHoy)
                                        <span class="badge-status badge-active">Activo hoy</span>
                                    @elseif ($item->Activo == 1 && !$esHoy)
                                        <span class="badge-status badge-pending">Expirado</span>
                                    @else
                                        <span class="badge-status badge-inactive">Inactivo</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="py-5 text-center">
                                        <div class="empty-state-icon mx-auto mb-3">
                                            <i
                                                class="bi bi-inbox fs-3"
                                                style="color: #94a3b8;"
                                            ></i>
                                        </div>
                                        <h6 class="text-muted">Sin registros</h6>
                                        <small class="text-muted">No hay tickets habilitados aún</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @include('components.paginate', ['items' => $ticketsHabilitados])
        </div>
    </x-card-gradient-header>
</x-page-container>
