<!--Modal Cambiar Password-->
<div
    class="modal fade"
    id="modalCambiarPassword{{ $usuario->IdUsuario }}"
    aria-hidden="true"
    aria-labelledby="modalCambiarPassword{{ $usuario->IdUsuario }}"
    tabindex="-1"
>
    <div
        class="modal-dialog"
        style="margin-top: 15vh;"
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
                    id="modalCambiarPassword{{ $usuario->IdUsuario }}"
                >
                    <div class="d-flex align-items-center gap-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.2); width: 32px; height: 32px;"
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
                            class="d-flex align-items-center justify-content-center mx-auto mb-2"
                            style="width: 48px; height: 48px; background-color: rgba(30, 41, 59, 0.1); border-radius: 50%;"
                        >
                            @include('components.icons.user')
                        </div>
                        <h5 class="fw-semibold mb-0 text-gray-800">{{ $usuario->NomUsuario }}</h5>
                        <p class="small text-muted mt-1">Completa los campos para cambiar tu contraseña</p>
                    </div>

                    <!-- Línea divisoria -->
                    <hr
                        class="my-3"
                        style="border-color: #e5e7eb;"
                    >

                    <!-- Nueva Contraseña -->
                    <div class="mb-3">
                        <label
                            for="Password1{{ $usuario->IdUsuario }}"
                            class="form-label fw-500 mb-2 text-gray-700"
                        >
                            <div class="d-flex align-items-center gap-2">
                                <span>Nueva Contraseña</span>
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
                                class="form-control border-start-0"
                                style="border-color: #e5e7eb; border-radius: 0 0 0 6px; line-height: 18px;"
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
                                style="border-color: #e5e7eb; border-left: none; border-radius: 0 6px 6px 0;"
                            >
                                @include('components.icons.eye')
                            </button>
                        </div>
                        <div
                            class="form-text text-muted mt-2"
                            id="requisitosPassword{{ $usuario->IdUsuario }}"
                        >
                            <span
                                id="reqLongitud{{ $usuario->IdUsuario }}"
                                class="d-block small"
                            >✗ Mínimo 6 caracteres</span>
                            <span
                                id="reqMayuscula{{ $usuario->IdUsuario }}"
                                class="d-block small"
                            >✗ Al menos una mayúscula</span>
                            <span
                                id="reqNumero{{ $usuario->IdUsuario }}"
                                class="d-block small"
                            >✗ Al menos un número</span>
                        </div>
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div class="mb-3">
                        <label
                            for="Password2{{ $usuario->IdUsuario }}"
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
                                class="form-control border-start-0"
                                style="border-color: #e5e7eb; border-radius: 0 0 0 6px; line-height: 18px;"
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
                                style="border-color: #e5e7eb; border-left: none; border-radius: 0 6px 6px 0;"
                            >
                                @include('components.icons.eye')
                            </button>
                        </div>
                        <div
                            class="form-text mt-2"
                            id="errorConfirmacion{{ $usuario->IdUsuario }}"
                            style="color: #dc2626;"
                        >
                            ✗ Las contraseñas no coinciden
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
                    class="btn btn-primary"
                    id="btnSubmit{{ $usuario->IdUsuario }}"
                    style="border-radius: 6px; padding: 6px 16px; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; transition: all 0.2s ease;"
                    onmouseover="this.style.background='linear-gradient(135deg, #0f172a 0%, #1e293b 100%)'"
                    onmouseout="this.style.background='linear-gradient(135deg, #1e293b 0%, #334155 100%)'"
                >
                    <span class="d-flex align-items-center gap-1">
                        @include('components.icons.send')
                        Cambiar Contraseña
                    </span>
                </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Función para mostrar/ocultar contraseña
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

    // Validar requisitos de la contraseña
    function validarPassword(id) {
        const password = document.getElementById('Password1' + id).value;

        const reqLongitud = document.getElementById('reqLongitud' + id);
        const reqMayuscula = document.getElementById('reqMayuscula' + id);
        const reqNumero = document.getElementById('reqNumero' + id);

        let valida = true;

        // Validar longitud mínima 6
        if (password.length >= 6) {
            reqLongitud.innerHTML = '✓ Mínimo 6 caracteres';
            reqLongitud.style.color = '#059669';
        } else {
            reqLongitud.innerHTML = '✗ Mínimo 6 caracteres';
            reqLongitud.style.color = '#dc2626';
            valida = false;
        }

        // Validar al menos una mayúscula
        if (/[A-Z]/.test(password)) {
            reqMayuscula.innerHTML = '✓ Al menos una mayúscula';
            reqMayuscula.style.color = '#059669';
        } else {
            reqMayuscula.innerHTML = '✗ Al menos una mayúscula';
            reqMayuscula.style.color = '#dc2626';
            valida = false;
        }

        // Validar al menos un número
        if (/[0-9]/.test(password)) {
            reqNumero.innerHTML = '✓ Al menos un número';
            reqNumero.style.color = '#059669';
        } else {
            reqNumero.innerHTML = '✗ Al menos un número';
            reqNumero.style.color = '#dc2626';
            valida = false;
        }

        // Habilitar/deshabilitar botón submit
        const btnSubmit = document.getElementById('btnSubmit' + id);
        const confirmacion = document.getElementById('Password2' + id).value;

        btnSubmit.disabled = !valida;

        return valida;
    }

    // Validar que las contraseñas coincidan
    function validarConfirmacion(id) {
        const password = document.getElementById('Password1' + id).value;
        const confirmacion = document.getElementById('Password2' + id).value;
        const errorDiv = document.getElementById('errorConfirmacion' + id);
        const btnSubmit = document.getElementById('btnSubmit' + id);
        const passwordValida = validarPassword(id);

        if (password === confirmacion && confirmacion !== '') {
            errorDiv.innerHTML = '✓ Las contraseñas coinciden';
            errorDiv.style.color = '#059669';
            btnSubmit.disabled = !passwordValida;
            return true;
        } else if (confirmacion !== '') {
            errorDiv.innerHTML = '✗ Las contraseñas no coinciden';
            errorDiv.style.color = '#dc2626';
            btnSubmit.disabled = true;
            return false;
        } else {
            errorDiv.innerHTML = '✗ Las contraseñas no coinciden';
            errorDiv.style.color = '#dc2626';
            btnSubmit.disabled = !passwordValida;
            return false;
        }
    }

    // Validar formulario completo antes de enviar
    function validarFormulario(id) {
        const password = document.getElementById('Password1' + id).value;
        const confirmacion = document.getElementById('Password2' + id).value;

        // Validar requisitos de contraseña
        const validaRequisitos = validarPassword(id);

        // Validar que coincidan
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

    // Autofocus al abrir el modal
    document.getElementById('modalCambiarPassword{{ $usuario->IdUsuario }}').addEventListener('shown.bs.modal',
        function() {
            document.getElementById('Password1{{ $usuario->IdUsuario }}').focus();
        });
</script>
