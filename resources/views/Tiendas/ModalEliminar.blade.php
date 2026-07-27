<!-- Modal Eliminar Tienda -->
<div
    class="modal fade"
    id="ModalEliminar{{ $tienda->IdTienda }}"
    tabindex="-1"
    aria-labelledby="ModalEliminar{{ $tienda->IdTienda }}Label"
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
                    style="width: 64px; height: 64px; background: var(--badge-inactive-bg);"
                >
                    <i
                        class="bi bi-exclamation-triangle"
                        style="font-size: 1.5rem; color: var(--badge-inactive-text);"
                    ></i>
                </div>

                <!-- Título -->
                <h5
                    class="fw-bold mb-2"
                    style="color: var(--text-primary);"
                >Eliminar Tienda</h5>

                <!-- Mensaje -->
                <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 8px;">
                    ¿Seguro que desea eliminar esta tienda?
                </p>
                <p
                    class="fw-semibold mb-3"
                    style="color: var(--danger-color); font-size: 1rem;"
                >
                    {{ $tienda->NomTienda }}
                </p>
                <p style="color: var(--text-muted); font-size: 0.78rem; margin-bottom: 24px;">
                    Esta acción no se puede revertir.
                </p>

                <!-- Botones -->
                <div class="d-flex gap-2">
                    <button
                        type="button"
                        class="btn flex-grow-1"
                        data-bs-dismiss="modal"
                        style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;"
                    >
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </button>
                    <form
                        action="/EliminarTienda/{{ $tienda->IdTienda }}"
                        method="POST"
                        class="flex-grow-1"
                    >
                        @csrf
                        <button
                            type="submit"
                            class="btn w-100"
                            style="background: var(--danger-color); color: white; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;"
                        >
                            <i class="bi bi-trash me-1"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
