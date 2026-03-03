<!-- Modal Confirmar Solicitud Cliente -->
<div class="modal fade"
    id="ModalConfirmarSolicitudCliente"
    tabindex="-1"
    aria-labelledby="modalConfirmarSolicitudLabel"
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
                    id="modalConfirmarSolicitudLabel">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.2); width: 32px; height: 32px;">
                            @include('components.icons.check')
                        </div>
                        <span>Confirmar Solicitud</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4 text-center">

                @if (!empty($nomCliente))
                    <div class="mb-3">
                        {{-- <div class="badge bg-primary mb-3">CLIENTE</div> --}}

                        <p class="fs-5 fw-500 text-dark mb-1">
                            {{ $nomCliente->NomCliente }}
                        </p>

                        <p class="text-muted small mb-0">
                            ¿Desea confirmar la solicitud de factura para este cliente?
                        </p>
                    </div>
                @else
                    <div class="mb-3">
                        {{-- <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3"
                            style="background-color: rgba(30, 41, 59, 0.1); width: 60px; height: 60px;">
                            @include('components.icons.user')
                        </div> --}}

                        <p class="fs-6 text-secondary mb-0">
                            ¿Desea agregar el cliente y solicitar la factura?
                        </p>
                    </div>
                @endif

            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top-0 pt-0 justify-content-center gap-2">

                <button type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal"
                    style="border-radius: 6px; padding: 8px 20px;">
                    <span class="d-flex align-items-center gap-2">
                        @include('components.icons.x')
                        Cancelar
                    </span>
                </button>

                {{-- <button class="btn btn-sm btn-warning"> Confirmar </button> --}}

                <button class="btn btn-warning"
                    style="border-radius: 6px; padding: 8px 20px;">
                    <span class="d-flex align-items-center gap-2">
                        @include('components.icons.check')
                        Confirmar
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
