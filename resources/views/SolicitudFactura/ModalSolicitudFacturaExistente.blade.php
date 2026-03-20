<!-- Modal: Solicitud de Factura Existente -->
<div class="modal fade"
    id="ModalSolicitudFacturaExistente"
    tabindex="-1"
    aria-labelledby="modalSolicitudFacturaExistenteLabel"
    aria-hidden="true">

    <div class="modal-dialog"
        style="margin-top: 10vh;">
        <div class="modal-content border-0 shadow"
            style="border-radius: 10px;">

            <!-- Modal Header -->
            <div class="modal-header border-bottom-0 pb-0"
                style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
                border-radius: 10px 10px 0 0;">

                <h5 class="text-white"
                    id="modalSolicitudFacturaExistenteLabel">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.2); width: 32px; height: 32px;">
                            @include('components.icons.file-text')
                        </div>
                        <span>Este ticket ya tiene solicitud de factura</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="badge"
                            style="background-color: #1e293b; color: white; padding: 4px 8px; font-size: 0.75rem;">
                            Ticket #{{ $ticket->IdTicket ?? '-' }}
                        </span>

                        <span class="badge"
                            style="background-color: rgba(30, 66, 159, 0.12); color: #1e429f; padding: 4px 8px; font-size: 0.75rem;">
                            Solicitud #{{ $solicitudFactura->IdSolicitudFactura ?? ($solicitudFactura->id ?? '-') }}
                        </span>

                        <span class="badge {{ ($solicitudFactura->Subir ?? 0) == 1 ? 'tags-green' : 'tags-yellow' }}"
                            style="font-size: 0.75rem;">
                            @if (($solicitudFactura->Subir ?? 0) == 1)
                                @include('components.icons.cloud-check')
                                <span class="ps-1">Subida</span>
                            @else
                                @include('components.icons.clock')
                                <span class="ps-1">Pendiente</span>
                            @endif
                        </span>
                    </div>

                    <div class="card border-0"
                        style="border-radius: 10px; background-color: #f8f9fa; border-left: 4px solid rgba(30, 66, 159, 0.8);">
                        <div class="card-body p-3">
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <span class="small text-muted">
                                        <span class="fw-500">RFC:</span>
                                        {{ $solicitudFactura->RFC ?? ($rfcCliente ?? '-') }}
                                    </span>
                                    <span class="small text-muted">
                                        <span class="fw-500">Fecha:</span>
                                        @php
                                            $fechaSol = $solicitudFactura->FechaSolicitud ?? $solicitudFactura->created_at ?? null;
                                        @endphp
                                        {{ $fechaSol ? \Carbon\Carbon::parse($fechaSol)->format('d/m/Y H:i') : '-' }}
                                    </span>
                                </div>

                                <span class="small text-muted">
                                    <span class="fw-500">Correo:</span>
                                    {{ $solicitudFactura->Email ?? ($correo ?? '-') }}
                                </span>

                                <div class="small"
                                    style="color: #334155;">
                                    Para evitar duplicados, no es necesario volver a capturar la solicitud.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-muted small">
                        Si necesitas corregir datos, revisa la solicitud existente o contacta a administración.
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top-0 pt-0 justify-content-center">
                <a class="btn btn-warning"
                    href="/SolicitudFactura"
                    style="border-radius: 6px; padding: 8px 20px;">
                    <span class="d-flex align-items-center gap-2">
                        @include('components.icons.check')
                        Entendido
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>

