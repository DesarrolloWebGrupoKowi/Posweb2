<div class="modal fade"
    id="ModalSolicitudFe{{ $ticket->IdTicket }}"
    tabindex="-1"
    aria-labelledby="modalSolicitudFeLabel-{{ $ticket->IdTicket }}"
    aria-hidden="true">
    <div class="modal-dialog modal-lg"
        style="margin-top: 10vh;">
        <div class="modal-content border-0 shadow"
            style="border-radius: 10px;">

            <!-- Modal Header -->
            <div class="modal-header border-bottom-0 pb-0"
                style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border-radius: 10px 10px 0 0;">
                <h5 class="text-white"
                    id="modalSolicitudFeLabel-{{ $ticket->IdTicket }}">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.2); width: 32px; height: 32px;">
                            @include('components.icons.file-text')
                        </div>
                        <span>Solicitud de Factura #{{ $ticket->IdTicket }}</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                @foreach ($ticket->SolicitudFactura as $factura)
                    <!-- Información del cliente -->
                    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom"
                        style="border-color: #e5e7eb !important;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(30, 41, 59, 0.1); width: 48px; height: 48px;">
                            <div style="color: #1e293b; width: 24px; height: 24px;">
                                @include('components.icons.user')
                            </div>
                        </div>
                        <div>
                            {{-- <span class="badge mb-2"
                                style="background-color: #1e293b; color: white;">CLIENTE</span> --}}
                            <h5 class="fw-600 mb-1" style="color: #1e293b;">{{ $factura->NomCliente }}</h5>
                            <p class="small text-muted mb-0">
                                {{ $factura->RFC }} • {{ $factura->Email }}
                            </p>
                        </div>
                    </div>

                    <!-- Grid de información -->
                    <div class="row g-3">
                        <!-- Columna izquierda -->
                        <div class="col-md-6">
                            <div class="p-3" style="background-color: #f8f9fa; border-radius: 8px;">
                                <span class="d-block text-muted small mb-2">FECHA DE SOLICITUD</span>
                                <span class="fw-500">
                                    {{ \Carbon\Carbon::parse($factura->FechaSolicitud)->locale('es')->isoFormat('D MMMM YYYY') }}
                                </span>
                                <span class="d-block small text-muted mt-1">
                                    {{ \Carbon\Carbon::parse($factura->FechaSolicitud)->format('H:i') }} hrs
                                </span>
                            </div>

                            <div class="p-3 mt-3" style="background-color: #f8f9fa; border-radius: 8px;">
                                <span class="d-block text-muted small mb-2">DIRECCIÓN</span>
                                <span class="small">
                                    {{ $factura->Calle }} {{ $factura->NumExt }} {{ $factura->NumInt }}<br>
                                    {{ $factura->Colonia }}<br>
                                    {{ $factura->Ciudad }}, {{ $factura->Estado }}<br>
                                    CP {{ $factura->CodigoPostal }}
                                </span>
                            </div>
                        </div>

                        <!-- Columna derecha -->
                        <div class="col-md-6">
                            <div class="p-3" style="background-color: #f8f9fa; border-radius: 8px;">
                                <span class="d-block text-muted small mb-2">DATOS FISCALES</span>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="small text-muted">Uso CFDI:</span>
                                    <span class="small fw-500">{{ $factura->UsoCFDI }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="small text-muted">Método de pago:</span>
                                    <span class="small fw-500">{{ $factura->MetodoPago }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="small text-muted">Tipo persona:</span>
                                    <span class="small fw-500">{{ $factura->TipoPersona }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="small text-muted">Bill To:</span>
                                    <span class="small fw-500">{{ $factura->Bill_To }}</span>
                                </div>
                            </div>

                            <div class="p-3 mt-3" style="background-color: #f8f9fa; border-radius: 8px;">
                                <span class="d-block text-muted small mb-2">ESTATUS</span>
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <span class="{{ $factura->Subir == 1 ? 'tags-green' : 'tags-red' }}"
                                        style="padding: 6px 12px;">
                                        @if ($factura->Subir == 1)
                                            @include('components.icons.cloud-check')
                                            <span class="ps-2 d-none d-md-inline">Subido</span>
                                        @else
                                            @include('components.icons.cloud-slash')
                                            <span class="ps-2 d-none d-md-inline">No subido</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ID de Solicitud -->
                    <div class="mt-4 p-2 d-flex justify-content-end">
                        <span class="small text-muted">
                            Solicitud: <span class="fw-500">{{ $factura->IdSolicitudFactura }}</span>
                        </span>
                    </div>
                @endforeach
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top-0 pt-0">
                <button type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal"
                    style="border-radius: 6px; padding: 8px 20px;">
                    <span class="d-flex align-items-center gap-2">
                        @include('components.icons.x')
                        Cerrar
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
