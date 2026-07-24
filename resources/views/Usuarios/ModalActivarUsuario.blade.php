<!--Modal Activar Usuario-->
<div
    class="modal fade"
    id="modalActivarUsuario{{ $usuario->IdUsuario }}"
    aria-hidden="true"
    aria-labelledby="modalActivarUsuario{{ $usuario->IdUsuario }}"
    tabindex="-1"
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
                style="background: linear-gradient(135deg, #059669 0%, #047857 100%);"
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
                            @include('components.icons.user')
                        </div>
                        <span>Activar Usuario</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="ActivarUsuario/{{ $usuario->IdUsuario }}"
                    method="POST"
                >
                    @csrf

                    <!-- Confirmar Contraseña -->
                    <div class="mb-3">
                        <label
                            for="passAdmin{{ $usuario->IdUsuario }}"
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
                                id="passAdmin{{ $usuario->IdUsuario }}"
                                name="passAdmin"
                                class="form-control border-start-0"
                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                tabindex="1"
                                placeholder="Ingresa tu contraseña para confirmar"
                                autocomplete="new-password"
                                required
                            >
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-passAdmin{{ $usuario->IdUsuario }}"
                            style="font-size: 0.78rem; color: #ef4444;"
                        ></div>
                        <small
                            class="text-muted"
                            style="font-size: 0.75rem;"
                        >
                            <i class="bi bi-info-circle me-1"></i>Ingresa la contraseña del administrador para continuar
                        </small>
                    </div>

                    <!-- Información del usuario a activar -->
                    <div
                        class="d-flex align-items-center mt-4 gap-3 rounded p-3"
                        style="background: #f0fdf4; border-left: 3px solid #10b981;"
                    >
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="background: #d1fae5; width: 36px; height: 36px;"
                        >
                            @include('components.icons.user')
                        </div>
                        <div>
                            <small style="color: #64748b; font-size: 0.78rem;">Usuario a activar:</small>
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
                    style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    onmouseover="this.style.background='linear-gradient(135deg, #059669 0%, #047857 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.3)'"
                    onmouseout="this.style.background='linear-gradient(135deg, #10b981 0%, #059669 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                >
                    @include('components.icons.user')
                    Activar Usuario
                </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById('modalActivarUsuario{{ $usuario->IdUsuario }}');
        modal.addEventListener('shown.bs.modal', function() {
            document.getElementById('passAdmin{{ $usuario->IdUsuario }}').focus();
        });
    });
</script>
