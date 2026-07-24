<x-page-container title="Solicitudes de Cancelación">
    <x-card-gradient-header
        icon="x-circle"
        title="Solicitudes de Cancelación"
        subtitle="Gestione las solicitudes de cancelación de tickets"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <x-form.form
            action="/CancelacionTickets"
            id="formTickets"
            method="GET"
        >
            <x-form.group>
                <x-form.select
                    name="idTienda"
                    label="Tienda"
                    icon="shop"
                    col="col-md-4"
                    placeholder="Todas las tiendas"
                    :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                    :selected="$idTienda ?? ''"
                    onchange="document.getElementById('formTickets').submit()"
                    :autofocus="true"
                />
            </x-form.group>
        </x-form.form>

        <div class="p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                <div>
                    <h5 class="section-content-title">
                        <i
                            class="bi bi-table me-2"
                            style="color: #64748b;"
                        ></i>Solicitudes Pendientes
                    </h5>
                    <p class="section-content-subtitle">{{ $solicitudesCancelacion->total() }} solicitudes</p>
                </div>
                <a
                    href="/HistorialCancelacionTickets"
                    class="btn-modern btn-outline-modern"
                >
                    <i class="bi bi-clock-history me-2"></i>Historial de solicitudes
                </a>
            </div>

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-shop me-1"></i>Tienda</th>
                            <th><i class="bi bi-calendar me-1"></i>Fecha Solicitud</th>
                            <th><i class="bi bi-cash-register me-1"></i>Caja</th>
                            <th><i class="bi bi-ticket me-1"></i>Ticket</th>
                            <th class="text-end"><i class="bi bi-currency-dollar me-1"></i>Importe</th>
                            <th><i class="bi bi-chat-text me-1"></i>Motivo</th>
                            <th class="text-center"><i class="bi bi-gear me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($solicitudesCancelacion as $solicitud)
                            @if (is_null($solicitud->Encabezado))
                                <tr>
                                    <td>{{ $solicitud->Tienda->NomTienda }}</td>
                                    <td style="color: #64748b;">
                                        {{ strftime('%d %B %Y, %H:%M', strtotime($solicitud->FechaSolicitud)) }}</td>
                                    <td colspan="5">
                                        <span class="badge-status badge-inactive">
                                            <i class="bi bi-exclamation-circle me-1"></i>No ha subido la venta
                                        </span>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td>{{ $solicitud->Tienda->NomTienda }}</td>
                                    <td style="color: #64748b;">
                                        {{ strftime('%d %B %Y, %H:%M', strtotime($solicitud->FechaSolicitud)) }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <i
                                                class="bi bi-cash-register me-1"></i>{{ $solicitud->Encabezado->NumCaja }}
                                        </span>
                                    </td>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $solicitud->Encabezado->IdTicket }}
                                    </td>
                                    <td
                                        class="text-end"
                                        style="font-weight: 600; color: #0f172a;"
                                    >
                                        ${{ number_format($solicitud->Encabezado->ImporteVenta, 2) }}
                                    </td>
                                    <td>
                                        <span
                                            class="text-truncate d-inline-block"
                                            style="max-width: 250px;"
                                            title="{{ $solicitud->MotivoCancelacion }}"
                                        >
                                            {{ $solicitud->MotivoCancelacion }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <button
                                                class="btn-table-action btn-table-activate"
                                                data-bs-toggle="modal"
                                                data-bs-target="#ModalConfirmarCancelacion{{ $solicitud->IdEncabezado }}"
                                                title="Aprobar solicitud"
                                            >
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            <button
                                                class="btn-table-action btn-table-delete"
                                                data-bs-toggle="modal"
                                                data-bs-target="#ModalCancelarCancelacion{{ $solicitud->IdEncabezado }}"
                                                title="Cancelar solicitud"
                                            >
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                            <button
                                                class="btn-table-action btn-table-edit"
                                                data-bs-toggle="modal"
                                                data-bs-target="#ModalDetalleTicket{{ $solicitud->IdEncabezado }}"
                                                title="Ver detalle de venta"
                                            >
                                                <i class="bi bi-list"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="py-5 text-center">
                                        <div class="empty-state-icon mx-auto mb-3">
                                            <i
                                                class="bi bi-inbox fs-3"
                                                style="color: #94a3b8;"
                                            ></i>
                                        </div>
                                        <h6 class="text-muted">Sin solicitudes pendientes</h6>
                                        <small class="text-muted">No se encontraron solicitudes con los filtros
                                            seleccionados</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Modales fuera de la tabla -->
            @foreach ($solicitudesCancelacion as $solicitud)
                @if (!is_null($solicitud->Encabezado))
                    @include('CancelacionTickets.ModalDetalleTicket')
                    @include('CancelacionTickets.ModalConfirmarCancelacion')
                    @include('CancelacionTickets.ModalCancelarCancelacion')
                @endif
            @endforeach
            @include('components.paginate', ['items' => $solicitudesCancelacion])
        </div>
    </x-card-gradient-header>
</x-page-container>

<script>
    document.getElementById('idTienda').addEventListener('change', (e) => {
        document.getElementById('formTickets').submit();
    });
</script>
