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
            class="modal-content border-0 shadow"
            style="border-radius: 10px; overflow: hidden;"
        >
            <!-- Modal Header -->
            <div
                class="modal-header border-bottom-0 px-4 pb-0 pt-3"
                style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);"
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
                        <span>Agregar Usuario</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="/CrearUsuario"
                    method="POST"
                >
                    @csrf

                    <!-- Nombre de Usuario -->
                    <div class="mb-3">
                        <label
                            for="NomUsuario"
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            Nombre de Usuario
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                            >
                                @include('components.icons.user')
                            </span>
                            <input
                                type="text"
                                id="NomUsuario"
                                name="NomUsuario"
                                class="form-control border-start-0"
                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                onkeypress="return (event.charCode != 32)"
                                tabindex="1"
                                placeholder="Escribe el nombre de usuario"
                                autocomplete="off"
                                required
                            >
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-NomUsuario"
                            style="font-size: 0.78rem; color: #ef4444;"
                        ></div>
                    </div>

                    <!-- Número de Nómina -->
                    <div class="mb-3">
                        <label
                            for="NumNomina"
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            Número de Nómina
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                            >
                                @include('components.icons.hash')
                            </span>
                            <input
                                type="text"
                                id="NumNomina"
                                name="NumNomina"
                                class="form-control border-start-0"
                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                placeholder="Escribe el número de nómina"
                                tabindex="2"
                                required
                            >
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-NumNomina"
                            style="font-size: 0.78rem; color: #ef4444;"
                        ></div>
                    </div>

                    <!-- Contraseña -->
                    <div class="mb-3">
                        <label
                            for="Password"
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            Contraseña
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
                                id="Password"
                                name="Password"
                                class="form-control border-start-0"
                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                placeholder="Escribe la contraseña"
                                tabindex="3"
                                autocomplete="new-password"
                                required
                            >
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-Password"
                            style="font-size: 0.78rem; color: #ef4444;"
                        ></div>
                    </div>

                    <!-- Correo -->
                    <div class="mb-3">
                        <label
                            for="Correo"
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            Correo
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                            >
                                @include('components.icons.mail')
                            </span>
                            <input
                                type="email"
                                id="Correo"
                                name="Correo"
                                class="form-control border-start-0"
                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                tabindex="4"
                                placeholder="Escribe el correo"
                                required
                            >
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-Correo"
                            style="font-size: 0.78rem; color: #ef4444;"
                        ></div>
                    </div>

                    <!-- Tipo Usuario -->
                    <div class="mb-3">
                        <label
                            for="IdTipoUsuario"
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            Tipo Usuario
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                            >
                                @include('components.icons.tag')
                            </span>
                            <select
                                name="IdTipoUsuario"
                                id="IdTipoUsuario"
                                class="form-select border-start-0"
                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
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
                            style="font-size: 0.78rem; color: #ef4444;"
                        ></div>
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
                    style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    onmouseover="this.style.background='linear-gradient(135deg, #0f172a 0%, #1e293b 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(30, 41, 59, 0.3)'"
                    onmouseout="this.style.background='linear-gradient(135deg, #1e293b 0%, #334155 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                >
                    @include('components.icons.send')
                    Guardar
                </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos para el modal */
    .modal-content {
        border-radius: 10px;
        overflow: hidden;
    }

    /* Focus en inputs */
    .modal-body .form-control:focus,
    .modal-body .form-select:focus {
        border-color: #94a3b8 !important;
        box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.1) !important;
    }

    .modal-body .input-group:focus-within .input-group-text {
        border-color: #94a3b8 !important;
        color: #1e293b !important;
    }

    /* Animación del modal */
    .modal.fade .modal-dialog {
        transform: translateY(-10px);
        transition: transform 0.3s ease-out;
    }

    .modal.show .modal-dialog {
        transform: translateY(0);
    }
</style>
