<!-- Modal Confirmar Eliminar Tipo Usuario -->
<div
    class="modal fade"
    id="ModalConfirmar{{ $tipoUsuario->IdTipoUsuario }}"
    tabindex="-1"
    aria-labelledby="ModalConfirmarLabel{{ $tipoUsuario->IdTipoUsuario }}"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div
            class="modal-content border-0"
            style="border-radius: 16px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);"
        >
            <div class="p-4 text-center">

                <!-- Icono -->
                <div
                    class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                    style="width: 64px; height: 64px; background: var(--badge-inactive-bg);"
                >
                    <i
                        class="bi bi-exclamation-triangle-fill"
                        style="font-size: 1.5rem; color: var(--badge-inactive-text);"
                    ></i>
                </div>

                <!-- Título -->
                <h5
                    class="fw-bold mb-2"
                    style="color: var(--text-primary);"
                >Desactivar Tipo de Usuario</h5>

                <!-- Mensaje -->
                <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 4px;">
                    ¿Seguro que desea desactivar el tipo de usuario?
                </p>
                <p
                    class="fw-semibold mb-3"
                    style="color: var(--danger-color); font-size: 1rem;"
                >
                    {{ $tipoUsuario->NomTipoUsuario }}
                </p>
                <p style="color: var(--text-muted); font-size: 0.78rem; margin-bottom: 20px;">
                    Esta acción puede ser revertida posteriormente.
                </p>

                <!-- Información adicional -->
                <!--<div
                    class="d-flex align-items-center mb-4 gap-3 rounded p-3 text-start"
                    style="background: var(--tag-red-bg, #fef2f2); border-left: 3px solid var(--danger-color);"
                >
                    <div
                        class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                        style="background: var(--tag-red-bg, #fee2e2); width: 36px; height: 36px;"
                    >
                        <i
                            class="bi bi-info-circle"
                            style="color: var(--danger-color);"
                        ></i>
                    </div>
                    <div>
                        <small style="color: var(--tag-red-text, #991b1b); font-size: 0.78rem;">
                            Esta acción desactivará el tipo de usuario. Puede ser revertida posteriormente.
                        </small>
                    </div>
                </div>-->

                <!-- Botones -->
                <form
                    action="/EliminarTipoUsuario/{{ $tipoUsuario->IdTipoUsuario }}"
                    method="POST"
                >
                    @csrf
                    <div class="d-flex gap-2">
                        <button
                            type="button"
                            class="btn flex-grow-1"
                            data-bs-dismiss="modal"
                            style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;"
                        >
                            <i class="bi bi-x"></i> Cancelar
                        </button>
                        <button
                            type="submit"
                            class="btn flex-grow-1"
                            style="background: var(--danger-color); color: white; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;"
                        >
                            <i class="bi bi-trash"></i> Desactivar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
