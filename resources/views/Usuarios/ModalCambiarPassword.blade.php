<!-- Modal Cambiar Password -->
<div
    class="modal fade"
    id="modalCambiarPassword{{ $usuario->IdUsuario }}"
    aria-hidden="true"
    aria-labelledby="modalCambiarPassword{{ $usuario->IdUsuario }}"
    tabindex="-1"
>
    <div
        class="modal-dialog"
        style="margin-top: 10vh;"
    >
        <div
            class="modal-content border-0"
            style="border-radius: 16px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);"
        >

            <!-- Modal Header -->
            <div
                class="modal-header border-bottom-0 px-4 pb-0 pt-3"
                style="background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);"
            >
                <h5
                    class="mb-0 text-white"
                    style="font-weight: 600; font-size: 1.1rem;"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: var(--card-header-icon-bg); width: 32px; height: 32px;"
                        >
                            @include('components.icons.lock')
                        </div>
                        <span>Cambiar Contraseña</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="/CambiarContraseña/{{ $usuario->IdUsuario }}"
                    method="POST"
                    id="formCambiarPassword{{ $usuario->IdUsuario }}"
                    onsubmit="return validarFormulario({{ $usuario->IdUsuario }})"
                >
                    @csrf

                    <!-- Información del usuario -->
                    <div class="mb-4 text-center">
                        <div
                            class="d-flex align-items-center justify-content-center mx-auto mb-3"
                            style="width: 48px; height: 48px; background: var(--bg-subtle); border-radius: 50%;"
                        >
                            @include('components.icons.user')
                        </div>
                        <h5
                            style="font-weight: 600; color: var(--text-primary); margin-bottom: 0.25rem; font-size: 0.95rem;">
                            {{ $usuario->NomUsuario }}
                        </h5>
                        <small style="color: var(--text-muted); font-size: 0.78rem;">Completa los campos para cambiar tu
                            contraseña</small>
                    </div>

                    <!-- Línea divisoria -->
                    <hr style="border-color: var(--border-light); margin: 1rem 0;">

                    <!-- Nueva Contraseña -->
                    <div class="mb-3">
                        <label
                            for="Password1{{ $usuario->IdUsuario }}"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Nueva Contraseña <span style="color: var(--danger-color);">*</span>
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
                                class="form-control border-start-0 border-end-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-right: none; padding: 8px 12px; font-size: 0.85rem;"
                                id="Password1{{ $usuario->IdUsuario }}"
                                name="Password1"
                                placeholder="Ingresa la nueva contraseña"
                                autocomplete="new-password"
                                onkeyup="validarPassword({{ $usuario->IdUsuario }})"
                                required
                            >
                            <button
                                class="btn btn-outline-secondary"
                                type="button"
                                onclick="mostrarPass1({{ $usuario->IdUsuario }})"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; color: var(--text-muted); padding: 8px 10px; transition: all 0.2s ease;"
                            >
                                @include('components.icons.eye')
                            </button>
                        </div>
                        <div
                            class="form-text mt-2"
                            id="requisitosPassword{{ $usuario->IdUsuario }}"
                            style="font-size: 0.78rem;"
                        >
                            <span
                                id="reqLongitud{{ $usuario->IdUsuario }}"
                                class="d-block"
                                style="color: var(--danger-color);"
                            >✗ Mínimo 6 caracteres</span>
                            <span
                                id="reqMayuscula{{ $usuario->IdUsuario }}"
                                class="d-block"
                                style="color: var(--danger-color);"
                            >✗ Al menos una mayúscula</span>
                            <span
                                id="reqNumero{{ $usuario->IdUsuario }}"
                                class="d-block"
                                style="color: var(--danger-color);"
                            >✗ Al menos un número</span>
                        </div>
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div class="mb-3">
                        <label
                            for="Password2{{ $usuario->IdUsuario }}"
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
                                class="form-control border-start-0 border-end-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-right: none; padding: 8px 12px; font-size: 0.85rem;"
                                id="Password2{{ $usuario->IdUsuario }}"
                                name="Password2"
                                placeholder="Confirma la nueva contraseña"
                                autocomplete="new-password"
                                onkeyup="validarConfirmacion({{ $usuario->IdUsuario }})"
                                required
                            >
                            <button
                                class="btn btn-outline-secondary"
                                type="button"
                                onclick="mostrarPass2({{ $usuario->IdUsuario }})"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; color: var(--text-muted); padding: 8px 10px; transition: all 0.2s ease;"
                            >
                                @include('components.icons.eye')
                            </button>
                        </div>
                        <div
                            class="form-text mt-1"
                            id="errorConfirmacion{{ $usuario->IdUsuario }}"
                            style="font-size: 0.78rem; color: var(--danger-color);"
                        >
                            ✗ Las contraseñas no coinciden
                        </div>
                    </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                <button
                    type="button"
                    class="btn d-flex align-items-center gap-1"
                    data-bs-dismiss="modal"
                    style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    onmouseover="this.style.background='var(--btn-gray-hover)'; this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.background='var(--btn-gray-bg)'; this.style.transform='translateY(0)'"
                >
                    @include('components.icons.x')
                    Cancelar
                </button>
                <button
                    type="submit"
                    class="btn d-flex align-items-center gap-1"
                    id="btnSubmit{{ $usuario->IdUsuario }}"
                    style="background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    onmouseover="this.style.background='linear-gradient(135deg, var(--gradient-end) 0%, var(--gradient-start) 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px var(--btn-gradient-shadow)'"
                    onmouseout="this.style.background='linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                >
                    @include('components.icons.send')
                    Cambiar Contraseña
                </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function mostrarPass1(id) {
        const input = document.getElementById('Password1' + id);
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
    }

    function mostrarPass2(id) {
        const input = document.getElementById('Password2' + id);
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
    }

    function validarPassword(id) {
        const password = document.getElementById('Password1' + id).value;
        const reqLongitud = document.getElementById('reqLongitud' + id);
        const reqMayuscula = document.getElementById('reqMayuscula' + id);
        const reqNumero = document.getElementById('reqNumero' + id);
        let valida = true;

        if (password.length >= 6) {
            reqLongitud.innerHTML = '✓ Mínimo 6 caracteres';
            reqLongitud.style.color = 'var(--success-color)';
        } else {
            reqLongitud.innerHTML = '✗ Mínimo 6 caracteres';
            reqLongitud.style.color = 'var(--danger-color)';
            valida = false;
        }

        if (/[A-Z]/.test(password)) {
            reqMayuscula.innerHTML = '✓ Al menos una mayúscula';
            reqMayuscula.style.color = 'var(--success-color)';
        } else {
            reqMayuscula.innerHTML = '✗ Al menos una mayúscula';
            reqMayuscula.style.color = 'var(--danger-color)';
            valida = false;
        }

        if (/[0-9]/.test(password)) {
            reqNumero.innerHTML = '✓ Al menos un número';
            reqNumero.style.color = 'var(--success-color)';
        } else {
            reqNumero.innerHTML = '✗ Al menos un número';
            reqNumero.style.color = 'var(--danger-color)';
            valida = false;
        }

        const btnSubmit = document.getElementById('btnSubmit' + id);
        btnSubmit.disabled = !valida;
        return valida;
    }

    function validarConfirmacion(id) {
        const password = document.getElementById('Password1' + id).value;
        const confirmacion = document.getElementById('Password2' + id).value;
        const errorDiv = document.getElementById('errorConfirmacion' + id);
        const btnSubmit = document.getElementById('btnSubmit' + id);
        const passwordValida = validarPassword(id);

        if (password === confirmacion && confirmacion !== '') {
            errorDiv.innerHTML = '✓ Las contraseñas coinciden';
            errorDiv.style.color = 'var(--success-color)';
            btnSubmit.disabled = !passwordValida;
            return true;
        } else if (confirmacion !== '') {
            errorDiv.innerHTML = '✗ Las contraseñas no coinciden';
            errorDiv.style.color = 'var(--danger-color)';
            btnSubmit.disabled = true;
            return false;
        } else {
            errorDiv.innerHTML = '✗ Las contraseñas no coinciden';
            errorDiv.style.color = 'var(--danger-color)';
            btnSubmit.disabled = !passwordValida;
            return false;
        }
    }

    function validarFormulario(id) {
        const password = document.getElementById('Password1' + id).value;
        const confirmacion = document.getElementById('Password2' + id).value;
        const validaRequisitos = validarPassword(id);
        const validaConfirmacion = (password === confirmacion && password !== '');

        if (!validaRequisitos) {
            alert(
                'La contraseña no cumple con los requisitos mínimos:\n- Mínimo 6 caracteres\n- Al menos una mayúscula\n- Al menos un número');
            return false;
        }

        if (!validaConfirmacion) {
            alert('Las contraseñas no coinciden');
            return false;
        }

        return true;
    }

    document.getElementById('modalCambiarPassword{{ $usuario->IdUsuario }}').addEventListener('shown.bs.modal',
        function() {
            document.getElementById('Password1{{ $usuario->IdUsuario }}').focus();
        });
</script>
