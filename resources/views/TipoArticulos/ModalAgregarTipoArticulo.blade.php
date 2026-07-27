<!-- Modal Agregar Tipo Artículo -->
<div
    class="modal fade"
    id="ModalAgregarTipoArticulo"
    tabindex="-1"
    aria-labelledby="ModalAgregarTipoArticuloLabel"
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
                style="background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);"
            >
                <h5
                    class="mb-0 text-white"
                    style="font-weight: 600; font-size: 1.1rem;"
                    id="ModalAgregarTipoArticuloLabel"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: var(--card-header-icon-bg); width: 32px; height: 32px;"
                        >
                            <i class="fa fa-tag"></i>
                        </div>
                        <span>Agregar Tipo de Artículo</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="/AgregarTipoArticulo"
                    method="POST"
                >
                    @csrf

                    <!-- Unidad de Negocio (CLAVE) -->
                    <div class="mb-3">
                        <label
                            for="idTipoArticulo"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Unidad de Negocio (CLAVE) <span style="color: var(--danger-color);">*</span>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-secondary); border-radius: 8px 0 0 8px;"
                            >
                                <i class="fa fa-briefcase"></i>
                            </span>
                            <input
                                type="text"
                                class="form-control border-start-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                name="idTipoArticulo"
                                id="idTipoArticulo"
                                placeholder="Unidad de Negocio"
                                tabindex="1"
                                required
                            >
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-idTipoArticulo"
                            style="font-size: 0.78rem; color: var(--danger-color);"
                        ></div>
                    </div>

                    <!-- Nombre Tipo de Artículo -->
                    <div class="mb-3">
                        <label
                            for="nomTipoArticulo"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Nombre Tipo de Artículo <span style="color: var(--danger-color);">*</span>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-secondary); border-radius: 8px 0 0 8px;"
                            >
                                <i class="fa fa-font"></i>
                            </span>
                            <input
                                type="text"
                                class="form-control border-start-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                name="nomTipoArticulo"
                                id="nomTipoArticulo"
                                placeholder="Nombre"
                                tabindex="2"
                                required
                            >
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-nomTipoArticulo"
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
                    onmouseover="this.style.background='var(--btn-gray-hover)'; this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.background='var(--btn-gray-bg)'; this.style.transform='translateY(0)'"
                >
                    <i class="fa fa-times"></i>
                    Cerrar
                </button>
                <button
                    type="submit"
                    class="btn d-flex align-items-center gap-1"
                    style="background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    onmouseover="this.style.background='linear-gradient(135deg, var(--gradient-end) 0%, var(--gradient-start) 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px var(--btn-gradient-shadow)'"
                    onmouseout="this.style.background='linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                >
                    <i class="fa fa-check"></i>
                    Agregar
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
