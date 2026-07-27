<div
    class="modal fade"
    id="ModalAgregar"
    tabindex="-1"
    aria-labelledby="ModalAgregar"
    aria-hidden="true"
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
                            @include('components.icons.user')
                        </div>
                        <span>Agregar Usuario</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="/CrearUsuario"
                    method="POST"
                    autocomplete="off"
                >
                    @csrf

                    <!-- ============================================================ -->
                    <!-- SECCIÃ“N 1: BÃšSQUEDA DE EMPLEADO -->
                    <!-- ============================================================ -->

                    <!-- Número de Nómina -->
                    <div class="mb-3">
                        <label
                            for="NumNomina"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Número de Nómina <span style="color: var(--danger-color);">*</span>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-secondary); border-radius: 8px 0 0 8px;"
                            >
                                @include('components.icons.hash')
                            </span>
                            <input
                                type="text"
                                id="NumNomina"
                                name="NumNomina"
                                class="form-control border-start-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                placeholder="Escribe el número de nómina para buscar"
                                tabindex="1"
                                required
                            >
                            <!-- Spinner de bÃºsqueda -->
                            <span
                                class="input-group-text"
                                id="spinnerNomina"
                                style="display: none; background: var(--bg-light); border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0;"
                            >
                                <span
                                    class="spinner-border spinner-border-sm"
                                    style="color: var(--text-secondary);"
                                ></span>
                            </span>
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-NumNomina"
                            style="font-size: 0.78rem; color: var(--danger-color);"
                        ></div>

                        <!-- Nombre del empleado encontrado -->
                        <div
                            id="empleadoEncontrado"
                            class="mt-2"
                            style="display: none;"
                        >
                            <span
                                class="tags-green"
                                style="font-size: 0.8rem;"
                            >
                                <i class="bi bi-person-check me-1"></i>
                                <span id="nombreEmpleado"></span>
                            </span>
                        </div>
                    </div>

                    <!-- ============================================================ -->
                    <!-- LÃNEA DIVISORIA -->
                    <!-- ============================================================ -->
                    <div
                        id="divisorFormulario"
                        style="display: none;"
                    >
                        <hr style="border-color: var(--border-light); margin: 1.5rem 0;">

                        <div class="mb-3 text-center">
                            <span
                                style="background: var(--bg-subtle); color: var(--text-subtle); padding: 4px 16px; border-radius: 20px; font-size: 0.75rem; font-weight: 500;"
                            >
                                <i class="bi bi-pencil-square me-1"></i>Datos del Usuario
                            </span>
                        </div>
                    </div>

                    <!-- ============================================================ -->
                    <!-- SECCIÃ“N 2: DATOS DEL USUARIO -->
                    <!-- ============================================================ -->
                    <div
                        id="seccionDatosUsuario"
                        style="display: none;"
                    >

                        <!-- Nombre de Usuario (AUTO-GENERADO) -->
                        <div class="mb-3">
                            <label
                                for="NomUsuario"
                                class="form-label fw-medium mb-2"
                                style="color: var(--text-secondary); font-size: 0.85rem;"
                            >
                                Nombre de Usuario <span style="color: var(--danger-color);">*</span>
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-secondary); border-radius: 8px 0 0 8px;"
                                >
                                    @include('components.icons.user')
                                </span>
                                <input
                                    type="text"
                                    id="NomUsuario"
                                    name="NomUsuario"
                                    class="form-control border-start-0"
                                    style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem; background: var(--bg-subtle);"
                                    onkeypress="return (event.charCode != 32)"
                                    tabindex="2"
                                    placeholder="Se generarÃ¡ automÃ¡ticamente"
                                    required
                                >
                            </div>
                            <div
                                class="form-text mt-1"
                                id="error-NomUsuario"
                                style="font-size: 0.78rem; color: var(--danger-color);"
                            ></div>
                            <small style="color: var(--text-muted); font-size: 0.75rem;">
                                <i class="bi bi-info-circle me-1"></i>Generado como: nombre.apellido
                            </small>
                        </div>

                        <!-- Contraseña -->
                        <div class="mb-3">
                            <label
                                for="Password"
                                class="form-label fw-medium mb-2"
                                style="color: var(--text-secondary); font-size: 0.85rem;"
                            >
                                Contraseña <span style="color: var(--danger-color);">*</span>
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-secondary); border-radius: 8px 0 0 8px;"
                                >
                                    @include('components.icons.lock')
                                </span>
                                <input
                                    type="text"
                                    id="Password"
                                    name="Password"
                                    class="form-control border-start-0"
                                    style="border: 1px solid var(--border-input); border-left: none; padding: 8px 12px; font-size: 0.85rem;"
                                    placeholder="Se generará automáticamente"
                                    tabindex="3"
                                    required
                                >
                                <!-- Botón para regenerar contraseña -->
                                <button
                                    type="button"
                                    class="btn border-start-0"
                                    id="btnRegenerarPassword"
                                    style="background: var(--btn-amber-bg); color: var(--btn-amber-text); border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.8rem; font-weight: 500; transition: all 0.3s ease;"
                                    title="Regenerar contraseña"
                                >
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </div>
                            <div
                                class="form-text mt-1"
                                id="error-Password"
                                style="font-size: 0.78rem; color: var(--danger-color);"
                            ></div>
                            <small style="color: var(--text-muted); font-size: 0.75rem;">
                                <i class="bi bi-shield-lock me-1"></i>Formato: Iniciales + Nómina + 2 caracteres
                                aleatorios
                            </small>
                        </div>

                        <!-- Correo -->
                        <div class="mb-3">
                            <label
                                for="Correo"
                                class="form-label fw-medium mb-2"
                                style="color: var(--text-secondary); font-size: 0.85rem;"
                            >
                                Correo <span style="color: var(--danger-color);">*</span>
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-secondary); border-radius: 8px 0 0 8px;"
                                >
                                    @include('components.icons.mail')
                                </span>
                                <input
                                    type="email"
                                    id="Correo"
                                    name="Correo"
                                    class="form-control border-start-0"
                                    style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    tabindex="4"
                                    placeholder="Escribe el correo"
                                    required
                                >
                            </div>
                            <div
                                class="form-text mt-1"
                                id="error-Correo"
                                style="font-size: 0.78rem; color: var(--danger-color);"
                            ></div>
                        </div>

                        <!-- Tipo Usuario -->
                        <div class="mb-3">
                            <label
                                for="IdTipoUsuario"
                                class="form-label fw-medium mb-2"
                                style="color: var(--text-secondary); font-size: 0.85rem;"
                            >
                                Tipo Usuario <span style="color: var(--danger-color);">*</span>
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-secondary); border-radius: 8px 0 0 8px;"
                                >
                                    @include('components.icons.tag')
                                </span>
                                <select
                                    name="IdTipoUsuario"
                                    id="IdTipoUsuario"
                                    class="form-select border-start-0"
                                    style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                    tabindex="5"
                                >
                                    @foreach ($tipoUsuarios as $tipoUsuario)
                                        <option value="{{ $tipoUsuario->IdTipoUsuario }}">
                                            {{ $tipoUsuario->NomTipoUsuario }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div
                                class="form-text mt-1"
                                id="error-IdTipoUsuario"
                                style="font-size: 0.78rem; color: var(--danger-color);"
                            ></div>
                        </div>

                    </div>
                    <!-- FIN SECCIÃ“N DATOS USUARIO -->
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                <button
                    type="button"
                    class="btn d-flex align-items-center gap-1"
                    data-bs-dismiss="modal"
                    style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                >
                    @include('components.icons.x')
                    Cancelar
                </button>
                <button
                    type="submit"
                    id="btnGuardar"
                    class="btn d-flex align-items-center gap-1"
                    style="background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    disabled
                >
                    @include('components.icons.send')
                    Guardar
                </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const numNominaInput = document.getElementById('NumNomina');
        const nomUsuarioInput = document.getElementById('NomUsuario');
        const empleadoEncontrado = document.getElementById('empleadoEncontrado');
        const nombreEmpleado = document.getElementById('nombreEmpleado');
        const btnGuardar = document.getElementById('btnGuardar');
        const spinner = document.getElementById('spinnerNomina');
        const divisorFormulario = document.getElementById('divisorFormulario');
        const seccionDatosUsuario = document.getElementById('seccionDatosUsuario');
        let empleadoData = null;

        // Buscar empleado al perder el foco
        numNominaInput.addEventListener('blur', function() {
            buscarEmpleado(this.value.trim());
        });

        // TambiÃ©n buscar con Enter
        numNominaInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                buscarEmpleado(this.value.trim());
            }
        });

        function buscarEmpleado(numNomina) {
            if (!numNomina) {
                limpiarEmpleado();
                return;
            }

            // Mostrar spinner
            spinner.style.display = 'flex';
            numNominaInput.disabled = true;
            btnGuardar.disabled = true;

            fetch(`/api/buscar-empleado/${numNomina}`)
                .then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        empleadoData = data.empleado;
                        mostrarEmpleado(empleadoData);
                        generarUsuario(empleadoData, numNomina);
                        generarPassword(empleadoData, numNomina);
                        // Mostrar la secciÃ³n de datos
                        mostrarSeccionDatos();
                    } else {
                        limpiarEmpleado();
                        ocultarSeccionDatos();
                        document.getElementById('error-NumNomina').textContent = data.message ||
                            'Empleado no encontrado';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    limpiarEmpleado();
                    ocultarSeccionDatos();
                    document.getElementById('error-NumNomina').textContent = 'Error al buscar empleado';
                })
                .finally(() => {
                    spinner.style.display = 'none';
                    numNominaInput.disabled = false;
                });
        }

        function mostrarEmpleado(empleado) {
            nombreEmpleado.textContent = `${empleado.Nombre} ${empleado.Apellidos}`;
            empleadoEncontrado.style.display = 'block';
            document.getElementById('error-NumNomina').textContent = '';
        }

        function mostrarSeccionDatos() {
            divisorFormulario.style.display = 'block';
            seccionDatosUsuario.style.display = 'block';
        }

        function ocultarSeccionDatos() {
            divisorFormulario.style.display = 'none';
            seccionDatosUsuario.style.display = 'none';
        }

        function limpiarEmpleado() {
            empleadoData = null;
            empleadoEncontrado.style.display = 'none';
            nombreEmpleado.textContent = '';
            nomUsuarioInput.value = '';
            btnGuardar.disabled = true;
        }

        async function generarUsuario(empleado, numNomina) {
            // Tomar solo el PRIMER nombre y PRIMER apellido
            const primerNombre = empleado.Nombre.trim().split(' ')[0];
            const primerApellido = empleado.Apellidos.trim().split(' ')[0];

            // Crear nombre de usuario base: daniel.hernandez
            const nombre = primerNombre.toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // quitar acentos
                .replace(/[^a-z0-9]/g, ''); // quitar caracteres especiales
            const apellido = primerApellido.toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // quitar acentos
                .replace(/[^a-z0-9]/g, ''); // quitar caracteres especiales

            let usuarioBase = `${nombre}.${apellido}`;

            // Verificar si el usuario ya existe
            try {
                const response = await fetch(`/api/verificar-usuario?usuario=${usuarioBase}`);
                const data = await response.json();

                if (data.existe) {
                    nomUsuarioInput.value = `${usuarioBase}.${numNomina}`;
                } else {
                    nomUsuarioInput.value = usuarioBase;
                }

                btnGuardar.disabled = false;
            } catch (error) {
                console.error('Error al verificar usuario:', error);
                nomUsuarioInput.value = usuarioBase;
                btnGuardar.disabled = false;
            }
        }

        function generarPassword(empleado, numNomina) {
            const primerNombre = empleado.Nombre.trim().split(' ')[0];
            const primerApellido = empleado.Apellidos.trim().split(' ')[0];

            const inicialNombre = primerNombre.charAt(0).toUpperCase();
            const inicialApellido = primerApellido.charAt(0).toUpperCase();

            // 2 caracteres aleatorios (letras minÃºsculas + nÃºmeros)
            const caracteres = 'abcdefghijklmnopqrstuvwxyz0123456789';
            const aleatorio1 = caracteres.charAt(Math.floor(Math.random() * caracteres.length));
            const aleatorio2 = caracteres.charAt(Math.floor(Math.random() * caracteres.length));

            const password = `${inicialNombre}${inicialApellido}${numNomina}${aleatorio1}${aleatorio2}`;

            document.getElementById('Password').value = password;
        }

        // BotÃ³n para regenerar contraseÃ±a
        document.getElementById('btnRegenerarPassword').addEventListener('click', function() {
            if (empleadoData) {
                generarPassword(empleadoData, numNominaInput.value.trim());
            }
        });

        // Limpiar al cerrar el modal
        document.getElementById('ModalAgregar').addEventListener('hidden.bs.modal', function() {
            limpiarEmpleado();
            ocultarSeccionDatos();
            document.getElementById('error-NumNomina').textContent = '';
        });
    });
</script>

<style>
    .modal-body .form-control:focus,
    .modal-body .form-select:focus {
        border-color: var(--border-input) !important;
        box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.1) !important;
    }

    .modal-body .input-group:focus-within .input-group-text {
        border-color: var(--border-input) !important;
        color: var(--text-primary) !important;
    }

    .modal.fade .modal-dialog {
        transform: translateY(-10px);
        transition: transform 0.3s ease-out;
    }

    .modal.show .modal-dialog {
        transform: translateY(0);
    }
</style>
