<!-- Modal Editar Tipo de Menú -->
<div
    class="modal fade"
    id="ModalEditar{{ $tipoMenu->IdTipoMenu }}"
    tabindex="-1"
    aria-labelledby="ModalEditarLabel{{ $tipoMenu->IdTipoMenu }}"
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
                    id="ModalEditarLabel{{ $tipoMenu->IdTipoMenu }}"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: var(--card-header-icon-bg); width: 32px; height: 32px;"
                        >
                            <i class="fa fa-tag"></i>
                        </div>
                        <span>Editar Tipo de Menú</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="/EditarTipoMenu/{{ $tipoMenu->IdTipoMenu }}"
                    method="POST"
                >
                    @csrf

                    <!-- Nombre de Tipo de Menú -->
                    <div class="mb-3">
                        <label
                            for="NomTipoMenu{{ $tipoMenu->IdTipoMenu }}"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Nombre de Tipo de Menú <span style="color: var(--danger-color);">*</span>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-muted); border-radius: 8px 0 0 8px;"
                            >
                                <i class="fa fa-font"></i>
                            </span>
                            <input
                                type="text"
                                id="NomTipoMenu{{ $tipoMenu->IdTipoMenu }}"
                                name="NomTipoMenu"
                                class="form-control border-start-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                tabindex="1"
                                value="{{ $tipoMenu->NomTipoMenu }}"
                                onkeyup="mayusculas(this)"
                                required
                            >
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-NomTipoMenu{{ $tipoMenu->IdTipoMenu }}"
                            style="font-size: 0.78rem; color: var(--danger-color);"
                        ></div>
                    </div>

                    <!-- Ícono -->
                    <div class="mb-3">
                        <label
                            for="Icono{{ $tipoMenu->IdTipoMenu }}"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Ícono
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-muted); border-radius: 8px 0 0 8px;"
                            >
                                <i class="fa fa-image"></i>
                            </span>
                            <input
                                type="text"
                                id="Icono{{ $tipoMenu->IdTipoMenu }}"
                                name="Icono"
                                class="form-control border-start-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                tabindex="2"
                                value="{{ $tipoMenu->Icono }}"
                            >
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-Icono{{ $tipoMenu->IdTipoMenu }}"
                            style="font-size: 0.78rem; color: var(--danger-color);"
                        ></div>
                    </div>

                    <!-- Posición -->
                    <div class="mb-3">
                        <label
                            for="Posicion{{ $tipoMenu->IdTipoMenu }}"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Posición <span style="color: var(--danger-color);">*</span>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-muted); border-radius: 8px 0 0 8px;"
                            >
                                <i class="fa fa-group"></i>
                            </span>
                            <input
                                type="number"
                                id="Posicion{{ $tipoMenu->IdTipoMenu }}"
                                name="Posicion"
                                class="form-control border-start-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                tabindex="3"
                                min="1"
                                value="{{ $tipoMenu->Posicion }}"
                                required
                            >
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-Posicion{{ $tipoMenu->IdTipoMenu }}"
                            style="font-size: 0.78rem; color: var(--danger-color);"
                        ></div>
                    </div>

                    <!-- Estatus -->
                    <div class="mb-3">
                        <label
                            for="Status{{ $tipoMenu->IdTipoMenu }}"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Estatus <span style="color: var(--danger-color);">*</span>
                        </label>
                        <select
                            name="Status"
                            id="Status{{ $tipoMenu->IdTipoMenu }}"
                            class="form-select"
                            style="border: 1px solid var(--border-input); border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                            tabindex="4"
                        >
                            <option
                                {{ $tipoMenu->Status == 0 ? 'selected' : '' }}
                                value="0"
                            >Activo</option>
                            <option
                                {{ $tipoMenu->Status == 1 ? 'selected' : '' }}
                                value="1"
                            >Inactivo</option>
                        </select>
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
                    <i class="fa fa-times"></i>
                    Cerrar
                </button>
                <button
                    type="submit"
                    class="btn d-flex align-items-center gap-1"
                    style="background: linear-gradient(135deg, var(--btn-blue-bg) 0%, var(--btn-blue-hover) 100%); color: var(--btn-blue-text); border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                >
                    <i class="fa fa-pencil"></i>
                    Guardar Cambios
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
