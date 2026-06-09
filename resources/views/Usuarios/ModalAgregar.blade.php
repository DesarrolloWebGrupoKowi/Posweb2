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
            style="border-radius: 10px;"
        >
            <!-- Modal Header -->
            <div
                class="modal-header border-bottom-0 pb-0"
                style="background: linear-gradient(135deg, #1e293b 0%, #1e293b 100%); border-radius: 10px 10px 0 0;"
            >
                <h5
                    class="text-white"
                    id="ModalAgregar"
                >
                    <div class="d-flex align-items-center gap-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.2); width: 32px; height: 32px;"
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
                    <div class="mb-2">
                        <label
                            for="NomUsuario"
                            class="form-label fw-500 mb-2 text-gray-700"
                        >
                            <div class="d-flex align-items-center gap-2">
                                <span>Nombre de Usuario</span>
                            </div>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background-color: rgba(30, 41, 59, 0.1); border-color: #e5e7eb; color: #1e293b"
                            >
                                @include('components.icons.user')
                            </span>
                            <input
                                type="text"
                                id="NomUsuario"
                                name="NomUsuario"
                                class="form-control border-start-0"
                                style="border-color: #e5e7eb; border-radius: 0 6px 6px 0; line-height: 18px;"
                                onkeypress="return (event.charCode != 32)"
                                tabindex="1"
                                placeholder="Escribe el nombre de usuario"
                                autocomplete="off"
                                required
                            >
                        </div>
                        <div
                            class="form-text text-muted mt-2"
                            id="error-NomUsuario"
                        >
                        </div>
                    </div>

                    <!-- Número de Nómina -->
                    <div class="mb-2">
                        <label
                            for="NumNomina"
                            class="form-label fw-500 mb-2 text-gray-700"
                        >
                            <div class="d-flex align-items-center gap-2">
                                <span>Número de Nómina</span>
                            </div>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background-color: rgba(30, 41, 59, 0.1); border-color: #e5e7eb; color: #1e293b"
                            >
                                @include('components.icons.hash')
                            </span>
                            <input
                                type="text"
                                id="NumNomina"
                                name="NumNomina"
                                class="form-control border-start-0"
                                style="border-color: #e5e7eb; border-radius: 0 6px 6px 0; line-height: 18px;"
                                placeholder="Escribe el número de nómina"
                                tabindex="2"
                                required
                            >
                        </div>
                        <div
                            class="form-text text-muted mt-2"
                            id="error-NumNomina"
                        ></div>
                    </div>

                    <!-- Contraseña -->
                    <div class="mb-2">
                        <label
                            for="Password"
                            class="form-label fw-500 mb-2 text-gray-700"
                        >
                            <div class="d-flex align-items-center gap-2">
                                <span>Contraseña</span>
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
                                id="Password"
                                name="Password"
                                class="form-control border-start-0"
                                style="border-color: #e5e7eb; border-radius: 0 6px 6px 0; line-height: 18px;"
                                placeholder="Escribe la contraseña"
                                tabindex="3"
                                autocomplete="new-password"
                                required
                            >
                        </div>
                        <div
                            class="form-text text-muted mt-2"
                            id="error-Password"
                        ></div>
                    </div>

                    <!-- Correo -->
                    <div class="mb-2">
                        <label
                            for="Correo"
                            class="form-label fw-500 mb-2 text-gray-700"
                        >
                            <div class="d-flex align-items-center gap-2">
                                <span>Correo</span>
                            </div>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background-color: rgba(30, 41, 59, 0.1); border-color: #e5e7eb; color: #1e293b"
                            >
                                @include('components.icons.mail')
                            </span>
                            <input
                                type="email"
                                id="Correo"
                                name="Correo"
                                class="form-control border-start-0"
                                style="border-color: #e5e7eb; border-radius: 0 6px 6px 0; line-height: 18px;"
                                tabindex="4"
                                placeholder="Escribe el correo"
                                required
                            >
                        </div>
                        <div
                            class="form-text text-muted mt-2"
                            id="error-Correo"
                        ></div>
                    </div>

                    <!-- Tipo Usuario -->
                    <div class="mb-2">
                        <label
                            for="IdTipoUsuario"
                            class="form-label fw-500 mb-2 text-gray-700"
                        >
                            <div class="d-flex align-items-center gap-2">
                                <span>Tipo Usuario</span>
                            </div>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background-color: rgba(30, 41, 59, 0.1); border-color: #e5e7eb; color: #1e293b"
                            >
                                @include('components.icons.tag')
                            </span>
                            <select
                                name="IdTipoUsuario"
                                id="IdTipoUsuario"
                                class="form-select border-start-0"
                                style="border-color: #e5e7eb; border-radius: 0 6px 6px 0; line-height: 18px;"
                            >
                                @foreach ($tipoUsuarios as $tipoUsuario)
                                    <option value="{{ $tipoUsuario->IdTipoUsuario }}">
                                        {{ $tipoUsuario->NomTipoUsuario }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div
                            class="form-text text-muted mt-2"
                            id="error-IdTipoUsuario"
                        ></div>
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
                    style="border-radius: 6px; padding: 6px 16px; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; transition: all 0.2s ease;"
                    onmouseover="this.style.background='linear-gradient(135deg, #0f172a 0%, #1e293b 100%)'"
                    onmouseout="this.style.background='linear-gradient(135deg, #1e293b 0%, #334155 100%)'"
                >
                    <span class="d-flex align-items-center gap-1">
                        @include('components.icons.send')
                        Guardar
                    </span>
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
