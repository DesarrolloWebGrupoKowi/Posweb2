<!-- Modal Desactivar Ticket -->
<div
    class="modal fade"
    id="ModalDesactivarTicket"
    tabindex="-1"
    aria-labelledby="ModalDesactivarTicketLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div
            class="modal-content"
            style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);"
        >
            <div class="p-4 text-center">
                <div
                    class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                    style="width: 64px; height: 64px; background: #fef2f2;"
                >
                    <i
                        class="bi bi-exclamation-triangle"
                        style="font-size: 1.5rem; color: #ef4444;"
                    ></i>
                </div>

                <h5
                    class="fw-bold mb-2"
                    style="color: #0f172a;"
                >Desactivar Ticket</h5>

                <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 8px;">
                    ¿Está seguro de desactivar este ticket?
                </p>
                <p style="color: #94a3b8; font-size: 0.78rem; margin-bottom: 24px;">
                    El ticket ya no estará habilitado para facturación fuera del plazo.
                </p>

                <div class="d-flex gap-2">
                    <button
                        type="button"
                        class="btn flex-grow-1"
                        data-bs-dismiss="modal"
                        style="background: #f1f5f9; color: #475569; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;"
                    >
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </button>
                    <form
                        action="/TicketFacturacion/Desactivar/{{ $ticketHabilitado->Id }}"
                        method="POST"
                        class="flex-grow-1"
                    >
                        @csrf
                        <button
                            type="submit"
                            class="btn w-100"
                            style="background: #ef4444; color: white; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;"
                        >
                            <i class="bi bi-check-lg me-1"></i> Desactivar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
