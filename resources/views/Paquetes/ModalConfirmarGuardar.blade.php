<!-- Modal Confirmar Guardar Edición de Paquete -->
<div
    class="modal fade"
    id="ModalConfirmarGuardar"
    tabindex="-1"
    aria-labelledby="ModalConfirmarGuardarLabel"
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
                    style="width: 64px; height: 64px; background: #eff6ff;"
                >
                    <i
                        class="bi bi-pencil-square"
                        style="font-size: 1.5rem; color: #3b82f6;"
                    ></i>
                </div>

                <!-- Título -->
                <h5
                    class="fw-bold mb-2"
                    style="color: #0f172a;"
                >Editar Paquete</h5>

                <!-- Mensaje -->
                <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 8px;">
                    ¿Desea guardar los cambios del paquete?
                </p>
                <p
                    class="fw-semibold mb-3"
                    style="color: #3b82f6; font-size: 1rem;"
                >
                    {{ $nomPaquete }}
                </p>
                <p style="color: #94a3b8; font-size: 0.78rem; margin-bottom: 24px;">
                    Se actualizarán los artículos, cantidades y precios.
                </p>

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
                        id="btnEditarPaquete"
                        type="button"
                        class="btn flex-grow-1"
                        style="background: #3b82f6; color: white; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;"
                    >
                        <i class="bi bi-floppy me-1"></i> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
