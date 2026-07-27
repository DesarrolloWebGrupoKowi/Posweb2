<!-- Modal Eliminar (Unificado) -->
<div
    class="modal fade"
    id="ModalEliminar{{ $usuario->IdUsuario }}"
    tabindex="-1"
    aria-labelledby="ModalEliminar{{ $usuario->IdUsuario }}"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div
            class="modal-content border-0"
            style="border-radius: 16px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);"
        >
            <div class="p-4">
                <!-- Icono -->
                <div class="text-center mb-3">
                    <div
                        class="rounded-circle d-inline-flex align-items-center justify-content-center"
                        style="width: 64px; height: 64px; background: var(--badge-inactive-bg);"
                    >
                        @include('components.icons.trash')
                    </div>
                </div>

                <!-- Título -->
                <h5 class="fw-bold text-center mb-2" style="color: var(--text-primary);">Desactivar Usuario</h5>

                <!-- Mensaje -->
                <p class="text-center" style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 4px;">
                    ¿Seguro que desea desactivar el usuario?
                </p>
                <p class="fw-semibold text-center mb-3" style="color: var(--danger-color); font-size: 1rem;">
                    {{ $usuario->NomUsuario }}
                </p>
                <p class="text-center" style="color: var(--text-muted); font-size: 0.78rem; margin-bottom: 20px;">
                    Esta acción puede ser revertida posteriormente.
                </p>

                <hr style="border-color: var(--border-light); margin: 1rem 0;">

                <form
                    action="/ConfirmContrasena/{{ $usuario->IdUsuario }}"
                    method="POST"
                >
                    @csrf

                    <!-- Confirmar Contraseña -->
                    <div class="mb-3">
                        <label
                            for="passAdminD{{ $usuario->IdUsuario }}"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Confirmar Contraseña <span style="color: var(--danger-color);">*</span>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-muted); border-radius: 8px 0 0 8px;"
                            >
                                @include('components.icons.lock')
                            </span>
                            <input
                                type="password"
                                id="passAdminD{{ $usuario->IdUsuario }}"
                                name="passAdmin"
                                class="form-control border-start-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                tabindex="1"
                                placeholder="Ingresa tu contraseña para confirmar"
                                autocomplete="new-password"
                                autofocus
                                required
                            >
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-passAdminD{{ $usuario->IdUsuario }}"
                            style="font-size: 0.78rem; color: var(--danger-color);"
                        ></div>
                        <small style="color: var(--text-muted); font-size: 0.75rem;">
                            <i class="bi bi-info-circle me-1"></i>Ingresa la contraseña del administrador para continuar
                        </small>
                    </div>

                    <!-- Información del usuario a desactivar -->
                    <!--<div
                        class="d-flex align-items-center gap-3 rounded p-3"
                        style="background: var(--tag-red-bg, #fef2f2); border-left: 3px solid var(--danger-color);"
                    >
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="background: var(--tag-red-bg, #fee2e2); width: 36px; height: 36px;"
                        >
                            @include('components.icons.user')
                        </div>
                        <div>
                            <small style="color: var(--text-secondary); font-size: 0.78rem;">Usuario a desactivar:</small>
                            <br>
                            <span
                                style="font-weight: 600; color: var(--text-primary); font-size: 0.85rem;">{{ $usuario->NomUsuario }}</span>
                        </div>
                    </div>-->

                    <!-- Botones -->
                    <div class="d-flex gap-2 mt-4">
                        <button
                            type="button"
                            class="btn flex-grow-1"
                            data-bs-dismiss="modal"
                            style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;"
                        >
                            @include('components.icons.x')
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="btn flex-grow-1"
                            style="background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%); color: white; border-radius: 8px; padding: 10px 24px; font-weight: 600; font-size: 0.85rem;"
                        >
                            @include('components.icons.trash')
                            Desactivar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById('ModalEliminar{{ $usuario->IdUsuario }}');
        modal.addEventListener('shown.bs.modal', function() {
            document.getElementById('passAdminD{{ $usuario->IdUsuario }}').focus();
        });
    });
</script>
