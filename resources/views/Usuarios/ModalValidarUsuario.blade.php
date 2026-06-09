<!--Modal Validar Usuario Para Eliminar Usuario (Borrado Lógico)-->
<div
    class="modal fade"
    id="modalValidaUsuario{{ $usuario->IdUsuario }}"
    aria-hidden="true"
    aria-labelledby="modalValidaUsuario{{ $usuario->IdUsuario }}"
    tabindex="-1"
>
    <div
        class="modal-dialog"
        style="margin-top: 10vh;"
    >
        <div
            class="modal-content border-0 shadow"
            style="border-radius: 10px;"
        >
            <!-- Modal Header -->
            <div
                class="modal-header border-bottom-0 pb-0"
                style="background: linear-gradient(135deg, #1e293b 0%, #1e293b 100%); border-radius: 10px 10px 0 0;"
            >
                <h5
                    class="text-white"
                    id="modalValidaUsuario{{ $usuario->IdUsuario }}"
                >
                    <div class="d-flex align-items-center gap-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.2); width: 32px; height: 32px;"
                        >
                            @include('components.icons.lock')
                        </div>
                        <span>Confirmar Usuario</span>
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

                    <!-- Confirmar Contraseña -->
                    <div class="mb-3">
                        <label
                            for="passAdmin{{ $usuario->IdUsuario }}"
                            class="form-label fw-500 mb-2 text-gray-700"
                        >
                            <div class="d-flex align-items-center gap-2">
                                <span>Confirmar Contraseña</span>
                                <span class="text-danger">*</span>
                            </div>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background-color: rgba(30, 41, 59, 0.1); border-color: #e5e7eb; color: #1e293b"
                            >
                                @include('components.icons.lock')
                            </span>
                            <input
                                type="password"
                                id="passAdmin{{ $usuario->IdUsuario }}"
                                name="passAdmin"
                                class="form-control border-start-0"
                                style="border-color: #e5e7eb; border-radius: 0 6px 6px 0; line-height: 18px;"
                                tabindex="1"
                                placeholder="Ingresa tu contraseña para confirmar"
                                autocomplete="new-password"
                                autofocus
                                required
                            >
                        </div>
                        <div
                            class="form-text text-muted mt-2"
                            id="error-passAdmin{{ $usuario->IdUsuario }}"
                        >
                            Ingresa la contraseña del administrador para continuar
                        </div>
                    </div>

                    <!-- Información del usuario a eliminar -->
                    <div
                        class="mt-4 rounded p-3"
                        style="background-color: #f8f9fa; border-left: 3px solid #dc2626;"
                    >
                        <div class="d-flex align-items-center gap-2">
                            <div style="width: 16px; height: 16px; color: #dc2626;">
                                @include('components.icons.user')
                            </div>
                            <span class="small text-muted">Usuario a desactivar:</span>
                            <span class="small fw-semibold text-dark">{{ $usuario->NomUsuario }}</span>
                        </div>
                    </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top-0 pt-0">
                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal"
                    style="border-radius: 6px; padding: 6px 16px;"
                >
                    <span class="d-flex align-items-center gap-1">
                        @include('components.icons.x')
                        Cancelar
                    </span>
                </button>
                <button
                    type="submit"
                    class="btn btn-danger"
                    style="border-radius: 6px; padding: 6px 16px; background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; border: none; transition: all 0.2s ease;"
                    onmouseover="this.style.background='linear-gradient(135deg, #b91c1c 0%, #991b1b 100%)'"
                    onmouseout="this.style.background='linear-gradient(135deg, #dc2626 0%, #b91c1c 100%)'"
                >
                    <span class="d-flex align-items-center gap-1">
                        @include('components.icons.trash')
                        Eliminar Usuario
                    </span>
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
