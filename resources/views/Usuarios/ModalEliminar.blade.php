<!-- Modal Eliminar (Unificado) -->
<div
    class="modal fade"
    id="ModalEliminar{{ $usuario->IdUsuario }}"
    tabindex="-1"
    aria-labelledby="ModalEliminar{{ $usuario->IdUsuario }}"
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
                style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);"
            >
                <h5
                    class="mb-0 text-white"
                    style="font-weight: 600; font-size: 1.1rem;"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
                        >
                            @include('components.icons.trash')
                        </div>
                        <span>Desactivar Usuario</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="/ConfirmContrasena/{{ $usuario->IdUsuario }}"
                    method="POST"
                >
                    @csrf

                    <!-- Mensaje de confirmación -->
                    <div class="mb-4 text-center">
                        <div
                            class="d-flex align-items-center justify-content-center mx-auto mb-3"
                            style="width: 48px; height: 48px; background-color: #fef2f2; border-radius: 50%;"
                        >
                            <x-icons.box
                                :width="20"
                                :height="20"
                                color="#dc2626"
                            />
                        </div>
                        <p style="font-weight: 500; color: #475569; margin-bottom: 0.25rem; font-size: 0.9rem;">
                            ¿Seguro que desea desactivar el usuario?
                        </p>
                        <p style="font-weight: 600; color: #dc2626; margin-bottom: 0.25rem; font-size: 0.9rem;">
                            {{ $usuario->NomUsuario }}
                        </p>
                        <small style="color: #94a3b8; font-size: 0.78rem;">
                            Esta acción puede ser revertida posteriormente.
                        </small>
                    </div>

                    <!-- Línea divisoria -->
                    <hr style="border-color: #e2e8f0; margin: 1.25rem 0;">

                    <!-- Confirmar Contraseña -->
                    <div class="mb-3">
                        <label
                            for="passAdminD{{ $usuario->IdUsuario }}"
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            Confirmar Contraseña <span style="color: #ef4444;">*</span>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                            >
                                @include('components.icons.lock')
                            </span>
                            <input
                                type="password"
                                id="passAdminD{{ $usuario->IdUsuario }}"
                                name="passAdmin"
                                class="form-control border-start-0"
                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
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
                            style="font-size: 0.78rem; color: #ef4444;"
                        ></div>
                        <small style="color: #94a3b8; font-size: 0.75rem;">
                            <i class="bi bi-info-circle me-1"></i>Ingresa la contraseña del administrador para continuar
                        </small>
                    </div>

                    <!-- Información del usuario a desactivar -->
                    <div
                        class="d-flex align-items-center gap-3 rounded p-3"
                        style="background: #fef2f2; border-left: 3px solid #ef4444;"
                    >
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="background: #fee2e2; width: 36px; height: 36px;"
                        >
                            @include('components.icons.user')
                        </div>
                        <div>
                            <small style="color: #64748b; font-size: 0.78rem;">Usuario a desactivar:</small>
                            <br>
                            <span
                                style="font-weight: 600; color: #0f172a; font-size: 0.85rem;">{{ $usuario->NomUsuario }}</span>
                        </div>
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
                    @include('components.icons.x')
                    Cancelar
                </button>
                <button
                    type="submit"
                    class="btn d-flex align-items-center gap-1"
                    style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    onmouseover="this.style.background='linear-gradient(135deg, #dc2626 0%, #b91c1c 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(239, 68, 68, 0.3)'"
                    onmouseout="this.style.background='linear-gradient(135deg, #ef4444 0%, #dc2626 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                >
                    @include('components.icons.trash')
                    Desactivar
                </button>
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
