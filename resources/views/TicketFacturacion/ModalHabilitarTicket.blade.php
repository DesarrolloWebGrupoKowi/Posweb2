<!-- Modal Habilitar / Rehabilitar Ticket -->
<div
    class="modal fade"
    id="ModalHabilitarTicket"
    tabindex="-1"
    aria-labelledby="ModalHabilitarTicketLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div
            class="modal-content"
            style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);"
        >
            <div class="p-4 text-center">
                <!-- Icono -->
                <div
                    class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                    style="width: 64px; height: 64px; background: {{ $estaExpirado ? '#fffbeb' : '#eff6ff' }};"
                >
                    <i
                        class="bi {{ $estaExpirado ? 'bi-arrow-repeat' : 'bi-check-circle' }}"
                        style="font-size: 1.5rem; color: {{ $estaExpirado ? '#f59e0b' : '#3b82f6' }};"
                    ></i>
                </div>

                <!-- Título -->
                <h5
                    class="fw-bold mb-2"
                    style="color: #0f172a;"
                >{{ $estaExpirado ? 'Rehabilitar Ticket' : 'Habilitar Ticket para Facturación' }}</h5>

                <!-- Mensaje -->
                @if ($estaExpirado)
                    <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 8px;">
                        Este ticket fue habilitado el <strong>{{ $ticketHabilitado->Fecha->format('d/m/Y') }}</strong> y
                        ya expiró.
                    </p>
                    <p style="color: #94a3b8; font-size: 0.78rem; margin-bottom: 16px;">
                        ¿Desea rehabilitarlo para que pueda ser facturado hoy?
                    </p>
                @else
                    <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 8px;">
                        ¿Está seguro de habilitar este ticket?
                    </p>
                    <p style="color: #94a3b8; font-size: 0.78rem; margin-bottom: 16px;">
                        El ticket podrá ser facturado fuera del plazo normal.
                    </p>
                @endif

                <!-- Formulario -->
                <form
                    action="/TicketFacturacion/Habilitar"
                    method="POST"
                >
                    @csrf
                    <input
                        type="hidden"
                        name="idEncabezado"
                        value="{{ $ticket->IdEncabezado }}"
                    >

                    <div class="mb-4 text-start">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            <i class="bi bi-chat-text me-1"></i>Observaciones
                        </label>
                        <textarea
                            class="form-control"
                            style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                            name="observaciones"
                            rows="3"
                            placeholder="Motivo de la excepción..."
                            maxlength="250"
                        >{{ $ticketHabilitado->Observaciones ?? '' }}</textarea>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex gap-2">
                        <button
                            type="button"
                            class="btn flex-grow-1"
                            data-bs-dismiss="modal"
                            style="background: #f1f5f9; color: #475569; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;"
                        >
                            <i class="bi bi-x-circle me-1"></i> Cancelar
                        </button>
                        <button
                            type="submit"
                            class="btn flex-grow-1"
                            style="background: {{ $estaExpirado ? '#f59e0b' : '#3b82f6' }}; color: white; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;"
                        >
                            <i class="bi {{ $estaExpirado ? 'bi-arrow-repeat' : 'bi-check-lg' }} me-1"></i>
                            {{ $estaExpirado ? 'Rehabilitar' : 'Habilitar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
