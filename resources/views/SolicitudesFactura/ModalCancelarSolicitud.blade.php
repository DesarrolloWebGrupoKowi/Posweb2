<div
    class="modal fade"
    id="ModalCancelarSolicitud{{ $solicitud->Id }}"
    tabindex="-1"
    aria-labelledby="modalCancelarSolicitudLabel{{ $solicitud->Id }}"
    aria-hidden="true"
>
    <div
        class="modal-dialog"
        style="margin-top: 10vh;"
    >
        <div
            class="modal-content border-0 shadow"
            style="border-radius: 10px;"
        >
            <!-- Modal Header -->
            <div
                class="modal-header border-bottom-0 pb-0"
                style="background: linear-gradient(135deg, #991b1b 0%, #dc2626 100%); border-radius: 10px 10px 0 0;"
            >
                <h5
                    class="text-white"
                    id="modalCancelarSolicitudLabel{{ $solicitud->Id }}"
                >
                    <div class="d-flex align-items-center gap-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.2); width: 32px; height: 32px;"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <circle
                                    cx="12"
                                    cy="12"
                                    r="10"
                                ></circle>
                                <line
                                    x1="18"
                                    y1="6"
                                    x2="6"
                                    y2="18"
                                ></line>
                                <line
                                    x1="6"
                                    y1="6"
                                    x2="18"
                                    y2="18"
                                ></line>
                            </svg>
                        </div>
                        <span>Cancelar Solicitud</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="/SolicitudesFactura/Cancelar/{{ $solicitud->Id }}"
                    method="POST"
                    id="formCancelarSolicitud{{ $solicitud->Id }}"
                >
                    @csrf

                    <!-- Advertencia visual -->
                    <div class="mb-4 text-center">
                        <div
                            class="d-flex align-items-center justify-content-center mx-auto mb-3"
                            style="width: 64px; height: 64px; background-color: rgba(220, 38, 38, 0.1); border-radius: 50%;"
                        >
                            @include('components.icons.warnig-triangle')
                        </div>
                        <h6 class="fw-semibold mb-2">¿Estás seguro de cancelar esta solicitud?</h6>
                        <p class="text-muted small mb-0">
                            Esta acción no se puede deshacer. La solicitud será cancelada permanentemente.
                        </p>
                    </div>

                    <!-- Información de la solicitud -->
                    <div
                        class="mb-4 border p-3"
                        style="background-color: #f8f9fa; border-radius: 8px;"
                    >
                        {{-- <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="badge bg-danger me-2">SOLICITUD</span>
                                <span class="fw-500">#{{ $solicitud->IdSolicitudFactura }}</span>
                            </div>
                            <span class="badge bg-warning text-dark">PENDIENTE DE CANCELACIÓN</span>
                        </div> --}}
                        <div class="text-muted small">
                            <div class="row">
                                {{-- <div class="col-4">Cliente:</div> --}}
                                <div class="col-8 fw-500">{{ $solicitud->NomCliente }}</div>
                            </div>
                            {{-- @if ($solicitud->Total ?? false)
                                <div class="row mt-1">
                                    <div class="col-4">Total:</div>
                                    <div class="col-8 fw-500">${{ number_format($solicitud->Total, 2) }}</div>
                                </div>
                            @endif
                            @if ($solicitud->FechaCreacion ?? false)
                                <div class="row mt-1">
                                    <div class="col-4">Fecha:</div>
                                    <div class="col-8">
                                        {{ \Carbon\Carbon::parse($solicitud->FechaCreacion)->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            @endif --}}
                        </div>
                    </div>

                    <!-- Advertencia adicional -->
                    {{-- <div
                        class="alert alert-warning mb-0 py-2"
                        style="border-radius: 8px; font-size: 0.75rem;"
                        role="alert"
                    >
                        <div class="d-flex align-items-center gap-2">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="14"
                                height="14"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.314 16.5c-.77.833.192 2.5 1.732 2.5z"
                                ></path>
                            </svg>
                            <span>Esta acción no se puede revertir. Confirma que deseas continuar.</span>
                        </div>
                    </div> --}}
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top-0 pt-0">
                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal"
                    style="border-radius: 6px; padding: 8px 20px;"
                >
                    <span class="d-flex align-items-center gap-2">
                        @include('components.icons.x')
                        Cancelar
                    </span>
                </button>
                <button
                    type="submit"
                    form="formCancelarSolicitud{{ $solicitud->Id }}"
                    class="btn btn-danger"
                    style="border-radius: 6px; padding: 8px 20px; background: linear-gradient(135deg, #991b1b 0%, #dc2626 100%); color: white; border: none;"
                >
                    <span class="d-flex align-items-center gap-2">
                        @include('components.icons.x-square')
                        Cancelar Solicitud
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
