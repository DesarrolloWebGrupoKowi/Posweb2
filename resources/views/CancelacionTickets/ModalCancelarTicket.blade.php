{{-- <div class="modal fade" id="ModalCancelarTicket" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
    tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title">¿Desea Solicitar Cancelación para el Ticket #{{ $ticket->IdTicket }}?</h5>
            </div>
            <form action="/SolicitarCancelacion/{{ $ticket->IdEncabezado }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-auto">
                            <textarea class="form-control" name="motivoCancelacion" id="motivoCancelacion" cols="60" rows="5"
                                placeholder="Motivo de Cancelación" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                        <i class="fa fa-close"></i> Cerrar
                    </button>
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fa fa-ban"></i> Solicitar cancelación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> --}}
<div class="modal fade"
    id="ModalCancelarTicket"
    tabindex="-1"
    aria-labelledby="modalCancelarTicketLabel"
    aria-hidden="true">
    <div class="modal-dialog"
        style="margin-top: 10vh;">
        <div class="modal-content border-0 shadow"
            style="border-radius: 10px;">
            <!-- Modal Header -->
            <div class="modal-header border-bottom-0 pb-0"
                style="border-radius: 10px 10px 0 0;">
                <h5 class="text-white"
                    id="modalCancelarTicketLabel">
                    <div class="d-flex align-items-center gap-2">
                        <span>Solicitar Cancelación de Ticket #{{ $ticket->IdTicket }}</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form action="/SolicitarCancelacion/{{ $ticket->IdEncabezado }}"
                    method="POST"
                    id="formCancelarTicket">
                    @csrf

                    <!-- Información del ticket -->
                    <div class="border mb-4 p-3"
                        style="background-color: #f8f9fa; border-radius: 8px;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-danger me-2">TICKET</span>
                                <span class="fw-500">#{{ $ticket->IdTicket }}</span>
                            </div>
                            <span class="badge"
                                style="background-color: #1e293b; color: white;">{{ \Carbon\Carbon::parse($ticket->FechaVenta)->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="mt-2 text-muted small">
                            <div class="d-flex justify-content-between">
                                <span>Total: <span
                                        class="fw-500">${{ number_format($ticket->ImporteVenta, 2) }}</span></span>
                                <span>Artículos: <span class="fw-500">{{ count($ticket->detalle) }}</span></span>
                            </div>
                        </div>
                    </div>

                    <!-- Motivo de cancelación -->
                    <div class="mb-4">
                        <label for="motivoCancelacion"
                            class="form-label fw-500 text-gray-700">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div style="color: #dc2626; width: 20px; height: 20px;">
                                    @include('components.icons.file-text')
                                </div>
                                <span>Motivo de Cancelación</span>
                            </div>
                        </label>
                        <textarea class="form-control"
                            id="motivoCancelacion"
                            name="motivoCancelacion"
                            rows="5"
                            placeholder="Describa el motivo por el cual solicita la cancelación de este ticket..."
                            required
                            style="border-color: #e5e7eb; border-radius: 6px; resize: vertical;"></textarea>
                        <div class="form-text text-muted mt-2">
                            Esta solicitud será revisada y aprobada por un administrador.
                        </div>
                    </div>
                </form>
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
                <button type="submit"
                    form="formCancelarTicket"
                    class="btn btn-danger"
                    id="btnCancelarTicket"
                    style="border-radius: 6px; padding: 8px 20px; background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; border: none;">
                    <span class="d-flex align-items-center gap-2">
                        @include('components.icons.delete')
                        Solicitar cancelación
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
