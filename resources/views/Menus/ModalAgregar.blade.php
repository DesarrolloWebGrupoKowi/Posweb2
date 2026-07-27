<!-- Modal Agregar Menú Posweb -->
<div
    class="modal fade"
    id="ModalAgregar"
    tabindex="-1"
    aria-labelledby="ModalAgregarLabel"
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
                    id="ModalAgregarLabel"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: var(--card-header-icon-bg); width: 32px; height: 32px;"
                        >
                            <i class="fa fa-bars"></i>
                        </div>
                        <span>Agregar Menú Posweb</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="/CrearMenuPosweb"
                    method="POST"
                >
                    @csrf

                    <!-- Fila 1: Nombre y Tipo Menú -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label
                                for="NomMenu"
                                class="form-label fw-medium mb-2"
                                style="color: var(--text-secondary); font-size: 0.85rem;"
                            >
                                Nombre de Menú <span style="color: var(--danger-color);">*</span>
                            </label>
                            <input
                                type="text"
                                id="NomMenu"
                                name="NomMenu"
                                class="form-control"
                                style="border: 1px solid var(--border-input); border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                onkeyup="mayusculas(this)"
                                tabindex="1"
                                required
                                placeholder="Escribe el nombre del menú"
                            >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label
                                for="IdTipoMenu"
                                class="form-label fw-medium mb-2"
                                style="color: var(--text-secondary); font-size: 0.85rem;"
                            >
                                Menú <span style="color: var(--danger-color);">*</span>
                            </label>
                            <select
                                name="IdTipoMenu"
                                id="IdTipoMenu"
                                class="form-select"
                                style="border: 1px solid var(--border-input); border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                tabindex="2"
                            >
                                @foreach ($tipoMenus as $tipoMenu)
                                    <option value="{{ $tipoMenu->IdTipoMenu }}">{{ $tipoMenu->NomTipoMenu }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Link -->
                    <div class="mb-3">
                        <label
                            for="Link"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Link <span style="color: var(--danger-color);">*</span>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-muted); border-radius: 8px 0 0 8px;"
                            >
                                <i class="fa fa-link"></i>
                            </span>
                            <input
                                class="form-control border-start-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                type="text"
                                name="Link"
                                id="Link"
                                placeholder="/CatMenuPosweb"
                                tabindex="3"
                                required
                            >
                        </div>
                    </div>

                    <!-- Fila 2: Icono y Background Color -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label
                                for="Icono"
                                class="form-label fw-medium mb-2"
                                style="color: var(--text-secondary); font-size: 0.85rem;"
                            >
                                Icono <span style="color: var(--danger-color);">*</span>
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-muted); border-radius: 8px 0 0 8px;"
                                >
                                    <i class="fa fa-star"></i>
                                </span>
                                <input
                                    class="form-control border-start-0"
                                    style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    type="text"
                                    name="Icono"
                                    id="Icono"
                                    placeholder="fa fa-icons"
                                    tabindex="4"
                                    required
                                >
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label
                                for="BgColor"
                                class="form-label fw-medium mb-2"
                                style="color: var(--text-secondary); font-size: 0.85rem;"
                            >
                                Background Color <span style="color: var(--danger-color);">*</span>
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-muted); border-radius: 8px 0 0 8px;"
                                >
                                    <i class="fa fa-paint-brush"></i>
                                </span>
                                <input
                                    class="form-control border-start-0"
                                    style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    type="text"
                                    name="BgColor"
                                    id="BgColor"
                                    placeholder="bg-orange"
                                    tabindex="5"
                                    required
                                >
                            </div>
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
                >
                    <i class="fa fa-times"></i>
                    Cerrar
                </button>
                <button
                    type="submit"
                    class="btn d-flex align-items-center gap-1"
                    style="background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                >
                    <i class="fa fa-check"></i>
                    Guardar
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
