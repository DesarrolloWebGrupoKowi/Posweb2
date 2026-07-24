<x-page-container title="Historial de Cancelación">
    <x-card-gradient-header
        icon="clock-history"
        title="Historial de Solicitudes de Cancelación"
        subtitle="Consulte el historial de solicitudes procesadas"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <x-form.form
            action="/HistorialCancelacionTickets"
            id="formHistorial"
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
                    onchange="document.getElementById('formHistorial').submit()"
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
                        ></i>Historial de Solicitudes
                    </h5>
                    <p class="section-content-subtitle">{{ $solicitudesCancelacion->total() }} solicitudes procesadas
                    </p>
                </div>
                <a
                    href="/CancelacionTickets"
                    class="btn-modern btn-outline-modern"
                >
                    <i class="bi bi-arrow-left me-2"></i>Solicitudes pendientes
                </a>
            </div>

            <table class="table-hover table-custom table">
                <thead>
                    <tr>
                        <th><i class="bi bi-shop me-1"></i>Tienda</th>
                        <th><i class="bi bi-calendar me-1"></i>Fecha Solicitud</th>
                        <th><i class="bi bi-cash-register me-1"></i>Caja</th>
                        <th><i class="bi bi-ticket me-1"></i>Ticket</th>
                        <th class="text-end"><i class="bi bi-currency-dollar me-1"></i>Importe</th>
                        <th><i class="bi bi-check-circle me-1"></i>Status</th>
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
                                <td colspan="6">
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
                                        <i class="bi bi-cash-register me-1"></i>{{ $solicitud->Encabezado->NumCaja }}
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
                                    @if ($solicitud->SolicitudAprobada == '0')
                                        <span class="badge-status badge-active">
                                            <i class="bi bi-check-circle me-1"></i>Aprobada
                                        </span>
                                    @else
                                        <span class="badge-status badge-inactive">
                                            <i class="bi bi-x-circle me-1"></i>Rechazada
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="text-truncate d-inline-block"
                                        style="max-width: 150px;"
                                        title="{{ $solicitud->MotivoCancelacion }}"
                                    >
                                        {{ $solicitud->MotivoCancelacion }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button
                                        class="btn-table-action btn-table-edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#ModalDetalleTicket{{ $solicitud->IdEncabezado }}"
                                        title="Ver detalle de venta"
                                    >
                                        <i class="bi bi-list"></i>
                                    </button>
                                </td>
                            </tr>
                        @endif
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
                                    <h6 class="text-muted">Sin historial disponible</h6>
                                    <small class="text-muted">No se encontraron solicitudes con los filtros
                                        seleccionados</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @include('components.paginate', ['items' => $solicitudesCancelacion])
        </div>
    </x-card-gradient-header>

    @foreach ($solicitudesCancelacion as $solicitud)
        @if (!is_null($solicitud->Encabezado))
            @include('CancelacionTickets.ModalDetalleTicket')
        @endif
    @endforeach
</x-page-container>

<script>
    document.getElementById('idTienda').addEventListener('change', (e) => {
        document.getElementById('formHistorial').submit();
    });
</script>
