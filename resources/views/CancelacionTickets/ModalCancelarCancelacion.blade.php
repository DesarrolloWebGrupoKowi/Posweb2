<!-- Modal Cancelar Cancelación -->
<div
    class="modal fade"
    id="ModalCancelarCancelacion{{ $solicitud->IdEncabezado }}"
    tabindex="-1"
    aria-labelledby="ModalCancelarCancelacion{{ $solicitud->IdEncabezado }}Label"
    aria-hidden="true"
>
    <div
        class="modal-dialog"
        style="margin-top: 10vh;"
    >
        <div
            class="modal-content border-0 shadow"
            style="border-radius: 10px; overflow: hidden;"
        >
            <!-- Modal Header -->
            <div
                class="modal-header border-bottom-0 px-4 pb-0 pt-3"
                style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);"
            >
                <h5
                    class="mb-0 text-white"
                    style="font-weight: 600; font-size: 1.1rem;"
                    id="ModalCancelarCancelacion{{ $solicitud->IdEncabezado }}Label"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
                        >
                            <i class="bi bi-x-circle"></i>
                        </div>
                        <span>Cancelar Solicitud</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <form
                action="/CancelarTicket/Cancelar/{{ $solicitud->IdEncabezado }}"
                method="POST"
            >
                @csrf
                <div class="modal-body p-4">
                    <p style="color: #475569; font-size: 0.85rem; margin-bottom: 16px;">
                        ¿Desea cancelar la solicitud del <strong>Ticket
                            #{{ $solicitud->Encabezado->IdTicket }}</strong>?
                    </p>

                    <div class="mb-0">
                        <label
                            for="motivoCancelacion{{ $solicitud->IdEncabezado }}"
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            Motivo de Cancelación
                        </label>
                        <textarea
                            class="form-control"
                            style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                            name="motivoCancelacion"
                            id="motivoCancelacion{{ $solicitud->IdEncabezado }}"
                            rows="4"
                            placeholder="Motivo de cancelación..."
                            required
                        >{{ $solicitud->MotivoCancelacion }}</textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                    <button
                        type="button"
                        class="btn d-flex align-items-center gap-1"
                        data-bs-dismiss="modal"
                        style="background: #f1f5f9; color: #475569; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                        onmouseover="this.style.background='#e2e8f0'; this.style.transform='translateY(-1px)'"
                        onmouseout="this.style.background='#f1f5f9'; this.style.transform='translateY(0)'"
                    >
                        <i class="bi bi-x-lg"></i>
                        Cerrar
                    </button>
                    <button
                        type="submit"
                        class="btn d-flex align-items-center gap-1"
                        style="background: #ef4444; color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                        onmouseover="this.style.background='#dc2626'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(239, 68, 68, 0.3)'"
                        onmouseout="this.style.background='#ef4444'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                    >
                        <i class="bi bi-x-lg"></i>
                        Cancelar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
