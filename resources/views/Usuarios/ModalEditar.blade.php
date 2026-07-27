<!-- Modal Editar -->
<div
    class="modal fade"
    id="ModalEditar{{ $usuario->IdUsuario }}"
    tabindex="-1"
    aria-labelledby="ModalEditar{{ $usuario->IdUsuario }}"
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
                        <span>Editar Usuario: {{ $usuario->NomUsuario }}</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="Editar/{{ $usuario->IdUsuario }}"
                    method="POST"
                >
                    @csrf

                    <!-- Número de Nómina -->
                    <div class="mb-3">
                        <label
                            for="NumNomina{{ $usuario->IdUsuario }}"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Número de Nómina
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-muted); border-radius: 8px 0 0 8px;"
                            >
                                @include('components.icons.hash')
                            </span>
                            <input
                                type="text"
                                id="NumNomina{{ $usuario->IdUsuario }}"
                                name="NumNomina"
                                class="form-control border-start-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                tabindex="1"
                                required
                                value="{{ $usuario->NumNomina }}"
                                autocomplete="off"
                            >
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-NumNomina{{ $usuario->IdUsuario }}"
                            style="font-size: 0.78rem; color: var(--danger-color);"
                        ></div>
                    </div>

                    <!-- Nombre del Empleado -->
                    <div class="mb-3">
                        <label
                            for="EmployeeName{{ $usuario->IdUsuario }}"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Nombre del Empleado (ORACLE)
                        </label>

                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-muted); border-radius: 8px 0 0 8px;"
                            >
                                @include('components.icons.user')
                            </span>

                            <input
                                type="text"
                                id="EmployeeName{{ $usuario->IdUsuario }}"
                                name="EmployeeName"
                                class="form-control border-start-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                tabindex="2"
                                value="{{ $usuario->EmployeeName }}"
                                placeholder="Nombre del empleado en ORACLE"
                                autocomplete="off"
                            >
                        </div>

                        <div
                            class="form-text mt-1"
                            id="error-EmployeeName{{ $usuario->IdUsuario }}"
                            style="font-size: 0.78rem; color: var(--danger-color);"
                        ></div>
                    </div>

                    <!-- Correo -->
                    <div class="mb-3">
                        <label
                            for="Correo{{ $usuario->IdUsuario }}"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Correo
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-muted); border-radius: 8px 0 0 8px;"
                            >
                                @include('components.icons.mail')
                            </span>
                            <input
                                type="email"
                                id="Correo{{ $usuario->IdUsuario }}"
                                name="Correo"
                                class="form-control border-start-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                tabindex="2"
                                required
                                value="{{ $usuario->Correo }}"
                                autocomplete="off"
                            >
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-Correo{{ $usuario->IdUsuario }}"
                            style="font-size: 0.78rem; color: var(--danger-color);"
                        ></div>
                    </div>

                    <!-- Tipo Usuario -->
                    <div class="mb-3">
                        <label
                            for="IdTipoUsuario{{ $usuario->IdUsuario }}"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Tipo Usuario
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-muted); border-radius: 8px 0 0 8px;"
                            >
                                @include('components.icons.tag')
                            </span>
                            <select
                                name="IdTipoUsuario"
                                id="IdTipoUsuario{{ $usuario->IdUsuario }}"
                                class="form-select border-start-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                tabindex="3"
                            >
                                @foreach ($tipoUsuarios as $tipoUsuario)
                                    <option
                                        {{ $usuario->IdTipoUsuario == $tipoUsuario->IdTipoUsuario ? 'selected' : '' }}
                                        value="{{ $tipoUsuario->IdTipoUsuario }}"
                                    >
                                        {{ $tipoUsuario->NomTipoUsuario }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-IdTipoUsuario{{ $usuario->IdUsuario }}"
                            style="font-size: 0.78rem; color: var(--danger-color);"
                        ></div>
                    </div>
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
                    class="btn d-flex align-items-center gap-1"
                    style="background: linear-gradient(135deg, var(--btn-blue-bg) 0%, var(--btn-blue-hover) 100%); color: var(--btn-blue-text); border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                >
                    @include('components.icons.send')
                    Guardar Cambios
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
