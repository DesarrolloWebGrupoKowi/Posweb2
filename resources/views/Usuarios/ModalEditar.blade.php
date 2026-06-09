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
                    id="ModalEditar{{ $usuario->IdUsuario }}"
                >
                    <div class="d-flex align-items-center gap-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.2); width: 32px; height: 32px;"
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
                    <div class="mb-2">
                        <label
                            for="NumNomina{{ $usuario->IdUsuario }}"
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
                                id="NumNomina{{ $usuario->IdUsuario }}"
                                name="NumNomina"
                                class="form-control border-start-0"
                                style="border-color: #e5e7eb; border-radius: 0 6px 6px 0; line-height: 18px;"
                                tabindex="1"
                                required
                                value="{{ $usuario->NumNomina }}"
                                autocomplete="off"
                            >
                        </div>
                        <div
                            class="form-text text-muted mt-2"
                            id="error-NumNomina{{ $usuario->IdUsuario }}"
                        ></div>
                    </div>

                    <!-- Correo -->
                    <div class="mb-2">
                        <label
                            for="Correo{{ $usuario->IdUsuario }}"
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
                                id="Correo{{ $usuario->IdUsuario }}"
                                name="Correo"
                                class="form-control border-start-0"
                                style="border-color: #e5e7eb; border-radius: 0 6px 6px 0; line-height: 18px;"
                                tabindex="2"
                                required
                                value="{{ $usuario->Correo }}"
                                autocomplete="off"
                            >
                        </div>
                        <div
                            class="form-text text-muted mt-2"
                            id="error-Correo{{ $usuario->IdUsuario }}"
                        ></div>
                    </div>

                    <!-- Tipo Usuario -->
                    <div class="mb-2">
                        <label
                            for="IdTipoUsuario{{ $usuario->IdUsuario }}"
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
                                id="IdTipoUsuario{{ $usuario->IdUsuario }}"
                                class="form-select border-start-0"
                                style="border-color: #e5e7eb; border-radius: 0 6px 6px 0; line-height: 18px;"
                                tabindex="4"
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
                            class="form-text text-muted mt-2"
                            id="error-IdTipoUsuario{{ $usuario->IdUsuario }}"
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
                        Editar Usuario
                    </span>
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
