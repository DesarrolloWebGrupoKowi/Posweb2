<div
    class="modal fade"
    id="modalConfirmarCancelacion"
    tabindex="-1"
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
                    style="width: 64px; height: 64px; background: var(--tag-red-bg);"
                >
                    <i
                        class="bi bi-x-circle"
                        style="font-size: 2rem; color: var(--danger-color);"
                    ></i>
                </div>

                <!-- Título -->
                <h5
                    class="fw-bold mb-2"
                    style="color: var(--text-primary);"
                >
                    Cancelar Pedido
                </h5>

                <!-- Mensaje -->
                <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 16px;">
                    ¿Estás seguro de que deseas cancelar este pedido?
                </p>

                <!-- Info adicional -->
                <div class="d-flex align-items-center justify-content-center mb-4 gap-2">
                    <span
                        style="color: var(--text-muted); font-size: 0.7rem; text-transform: uppercase; font-weight: 600; letter-spacing: 1px;"
                    >
                        Folio
                    </span>
                    <span
                        style="background: var(--bg-subtle); border: 1px solid var(--border-light); border-radius: 8px; padding: 8px 12px; font-weight: 700; color: var(--text-primary); font-size: 1rem;"
                    >
                        {{ $packList }}
                    </span>
                </div>

                <!-- Spinner de carga (oculto inicialmente) -->
                <div
                    id="cancelarLoader"
                    class="d-none mb-3"
                >
                    <div
                        class="spinner-border"
                        style="color: var(--danger-color);"
                        role="status"
                    >
                        <span class="visually-hidden">Procesando...</span>
                    </div>
                    <p
                        class="mt-2"
                        style="color: var(--text-secondary); font-size: 0.85rem;"
                    >
                        Cancelando pedido...
                    </p>
                </div>

                <!-- Botones -->
                <div
                    id="cancelarButtons"
                    class="d-flex gap-2"
                >
                    <button
                        type="button"
                        class="btn flex-grow-1"
                        data-bs-dismiss="modal"
                        style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem; transition: all 0.2s;"
                        onmouseover="this.style.background='var(--btn-gray-hover)';"
                        onmouseout="this.style.background='var(--btn-gray-bg)';"
                    >
                        <i class="bi bi-x-circle me-1"></i> No, mantener
                    </button>
                    <button
                        type="button"
                        id="btnConfirmarCancelacion"
                        class="btn flex-grow-1"
                        style="background: var(--danger-color); color: white; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem; transition: all 0.2s;"
                        onmouseover="this.style.background='#dc2626';"
                        onmouseout="this.style.background='var(--danger-color)';"
                        onmousedown="this.style.transform='scale(0.97)';"
                        onmouseup="this.style.transform='scale(1)';"
                    >
                        <i class="bi bi-check-circle me-1"></i> Sí, cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
