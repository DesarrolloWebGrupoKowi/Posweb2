<!-- Modal Buscador de Direcciones -->
<div
    class="modal fade"
    id="ModalBuscadorDireccion"
    tabindex="-1"
    aria-hidden="true"
>
    <div
        class="modal-dialog modal-lg"
        style="margin-top: 5vh;"
    >
        <div
            class="modal-content border-0 shadow"
            style="border-radius: 10px; overflow: hidden;"
        >

            <!-- Header -->
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
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <span>Cambiar Dirección de <span id="tituloTipoDireccion">Envío</span></span>
                    </div>
                </h5>
            </div>

            <!-- Body -->
            <div class="modal-body p-4">
                <!-- Buscador -->
                <div class="buscador-wizard mb-2">
                    <div class="input-group">
                        <span class="input-group-text buscador-icon">
                            <i class="bi bi-search"></i>
                        </span>
                        <input
                            type="text"
                            id="buscarDireccionNombre"
                            class="form-control buscador-input"
                            placeholder="Buscar por nombre del cliente..."
                            onkeypress="if(event.key==='Enter'){buscarDirecciones(this.value); event.preventDefault();}"
                        >
                        <button
                            type="button"
                            class="btn buscador-btn"
                            onclick="buscarDirecciones(document.getElementById('buscarDireccionNombre').value)"
                        >
                            <i class="bi bi-search me-1"></i> Buscar
                        </button>
                    </div>
                </div>

                <!-- Filtro secundario -->
                <div
                    id="filtroDireccionContainer"
                    class="buscador-wizard"
                    style="display: none;"
                >
                    <div class="input-group">
                        <span class="input-group-text buscador-icon">
                            <i class="bi bi-funnel"></i>
                        </span>
                        <input
                            type="text"
                            id="filtroDireccion"
                            class="form-control buscador-input"
                            placeholder="Filtrar resultados por dirección..."
                            onkeyup="filtrarLista('listaDirecciones', 'filtroDireccion', 'contadorDirecciones')"
                        >
                        <span
                            class="input-group-text"
                            style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-muted); font-size: 0.75rem;"
                            id="contadorDirecciones"
                        ></span>
                    </div>
                </div>

                <!-- Tabla de resultados -->
                <div class="tabla-wizard">
                    <div
                        id="listaDirecciones"
                        class="table-responsive"
                        style="max-height: 400px; overflow-y: auto;"
                    >
                        <div class="py-4 text-center">
                            <span
                                class="spinner-border spinner-border-sm"
                                style="color: var(--text-muted);"
                            ></span>
                            <span style="color: var(--text-muted);">Buscando direcciones...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                <button
                    type="button"
                    class="btn d-flex align-items-center gap-1"
                    data-bs-dismiss="modal"
                    style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem;"
                >
                    <i class="bi bi-x"></i> Cancelar
                </button>
                <button
                    type="button"
                    id="btnConfirmarDireccion"
                    class="btn d-flex align-items-center gap-1"
                    style="background: var(--btn-green-bg); color: var(--btn-green-text); border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem;"
                    onclick="confirmarDireccion()"
                >
                    <i class="bi bi-check"></i> Seleccionar
                </button>
            </div>
        </div>
    </div>
</div>
