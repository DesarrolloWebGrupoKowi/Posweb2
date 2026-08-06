<!-- ============================================================ -->
<!-- MODAL WIZARD MEJORADO -->
<!-- ============================================================ -->
<div
    class="modal fade"
    id="ModalWizard"
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
                            <i class="bi bi-sliders"></i>
                        </div>
                        <span>Configurar: <span id="wizardClienteNombre"></span></span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div
                class="modal-body p-4"
                style="height: 60vh; display: flex; flex-direction: column; overflow: hidden;"
            >

                <!-- TIMELINE CON FLECHAS -->
                <div
                    class="timeline-wizard mb-3"
                    style="flex-shrink: 0;"
                >
                    <div
                        class="timeline-item active"
                        data-paso="1"
                        onclick="irPaso(1)"
                    >
                        <div class="timeline-numero d-lg-none">1</div>
                        <div class="timeline-texto d-none d-lg-inline">Cliente</div>
                    </div>
                    <div class="timeline-flecha"><i class="bi bi-chevron-right"></i></div>
                    <div
                        class="timeline-item"
                        data-paso="2"
                        onclick="irPaso(2)"
                    >
                        <div class="timeline-numero d-lg-none">2</div>
                        <div class="timeline-texto d-none d-lg-inline">Envío</div>
                    </div>
                    <div class="timeline-flecha"><i class="bi bi-chevron-right"></i></div>
                    <div
                        class="timeline-item"
                        data-paso="3"
                        onclick="irPaso(3)"
                    >
                        <div class="timeline-numero d-lg-none">3</div>
                        <div class="timeline-texto d-none d-lg-inline">Facturación</div>
                    </div>
                    <div class="timeline-flecha"><i class="bi bi-chevron-right"></i></div>
                    <div
                        class="timeline-item"
                        data-paso="4"
                        onclick="irPaso(4)"
                    >
                        <div class="timeline-numero d-lg-none">4</div>
                        <div class="timeline-texto d-none d-lg-inline">Precios</div>
                    </div>
                    <div class="timeline-flecha"><i class="bi bi-chevron-right"></i></div>
                    <div
                        class="timeline-item"
                        data-paso="5"
                        onclick="irPaso(5)"
                    >
                        <div class="timeline-numero d-lg-none">5</div>
                        <div class="timeline-texto d-none d-lg-inline">Tipo Orden</div>
                    </div>
                    <div class="timeline-flecha"><i class="bi bi-chevron-right"></i></div>
                    <div
                        class="timeline-item"
                        data-paso="6"
                        onclick="irPaso(6)"
                    >
                        <div class="timeline-numero d-lg-none">6</div>
                        <div class="timeline-texto d-none d-lg-inline">Resumen</div>
                    </div>
                </div>

                <!-- Contenido de cada paso -->
                <div style="flex: 1; overflow-y: auto; min-height: 0;">
                    <form
                        id="formWizard"
                        action="/ClientesAutoservicio/guardar"
                        method="POST"
                    >
                        @csrf
                        <input
                            type="hidden"
                            name="cliente"
                            id="inputCliente"
                        >
                        <!-- Paso 1: Cliente -->
                        <div
                            class="paso-contenido"
                            id="paso1"
                        >
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 style="color: var(--text-primary); font-weight: 600; margin: 0;">
                                        <i class="bi bi-person-badge me-1"></i>Cliente
                                    </h6>
                                    <small style="color: var(--text-muted);">ID, tipo y términos de pago</small>
                                </div>
                                <span
                                    id="badgePaso1"
                                    class="tags-yellow"
                                    style="display: none;"
                                >Sin cambios</span>
                            </div>

                            <!-- Buscador centrado con estilo -->
                            <div class="buscador-wizard">
                                <div class="input-group">
                                    <span class="input-group-text buscador-icon">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input
                                        type="text"
                                        id="buscarCliente"
                                        class="form-control buscador-input"
                                        placeholder="Buscar por nombre del cliente..."
                                        onkeypress="if(event.key==='Enter'){cargarPaso1(this.value); event.preventDefault();}"
                                    >
                                    <button
                                        type="button"
                                        class="btn buscador-btn"
                                        onclick="cargarPaso1(document.getElementById('buscarCliente').value)"
                                    >
                                        <i class="bi bi-search me-1"></i> Buscar
                                    </button>
                                </div>
                            </div>

                            <!-- Valor actual -->
                            <div
                                id="clienteActual"
                                class="configurado-actual"
                                style="display: none;"
                            >
                                <div class="configurado-header">
                                    <i class="bi bi-check-circle-fill me-1"></i> Actualmente configurado
                                </div>
                                <div class="configurado-body">
                                    <span
                                        id="clienteActualTexto"
                                        style="font-size: 0.8rem;"
                                    ></span>
                                </div>
                            </div>

                            <!-- Tabla de resultados -->
                            <div class="tabla-wizard">
                                <div
                                    id="listaClientes"
                                    class="table-responsive"
                                    style="max-height: 250px; overflow-y: auto;"
                                ></div>
                            </div>
                        </div>

                        <!-- Paso 2: Envío -->
                        <div
                            class="paso-contenido"
                            id="paso2"
                            style="display: none;"
                        >
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 style="color: var(--text-primary); font-weight: 600; margin: 0;">
                                        <i class="bi bi-truck me-1"></i>Dirección de Envío
                                    </h6>
                                    <small style="color: var(--text-muted);">SHIP_TO</small>
                                </div>
                                <span
                                    id="badgePaso2"
                                    class="tags-yellow"
                                    style="display: none;"
                                >Sin cambios</span>
                            </div>

                            <!-- Buscador principal -->
                            <div class="buscador-wizard">
                                <div class="input-group">
                                    <span class="input-group-text buscador-icon">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input
                                        type="text"
                                        id="buscarShipTo"
                                        class="form-control buscador-input"
                                        placeholder="Buscar por nombre del cliente..."
                                        onkeypress="if(event.key==='Enter'){cargarPaso2(this.value); event.preventDefault();}"
                                    >
                                    <button
                                        type="button"
                                        class="btn buscador-btn"
                                        onclick="cargarPaso2(document.getElementById('buscarShipTo').value)"
                                    >
                                        <i class="bi bi-search me-1"></i> Buscar
                                    </button>
                                </div>
                            </div>

                            <!-- Valor actual -->
                            <div
                                id="shipToActual"
                                class="configurado-actual"
                                style="display: none;"
                            >
                                <div class="configurado-header">
                                    <i class="bi bi-check-circle-fill me-1"></i> Actualmente configurado
                                </div>
                                <div class="configurado-body">
                                    <span
                                        id="shipToActualTexto"
                                        style="font-size: 0.8rem;"
                                    ></span>
                                </div>
                            </div>

                            <!-- Filtro secundario -->
                            <div
                                id="filtroShipToContainer"
                                class="buscador-wizard"
                                style="display: none;"
                            >
                                <div class="input-group">
                                    <span class="input-group-text buscador-icon">
                                        <i class="bi bi-funnel"></i>
                                    </span>
                                    <input
                                        type="text"
                                        id="filtroShipTo"
                                        class="form-control buscador-input"
                                        placeholder="Filtrar resultados por dirección..."
                                        onkeyup="filtrarLista('listaShipTo', 'filtroShipTo', 'contadorShipTo')"
                                    >
                                    <span
                                        class="input-group-text"
                                        style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-muted); font-size: 0.75rem;"
                                        id="contadorShipTo"
                                    ></span>
                                </div>
                            </div>

                            <!-- Tabla -->
                            <div class="tabla-wizard">
                                <div
                                    id="listaShipTo"
                                    class="table-responsive"
                                    style="max-height: 250px; overflow-y: auto;"
                                ></div>
                            </div>
                        </div>

                        <!-- Paso 3: Facturación -->
                        <div
                            class="paso-contenido"
                            id="paso3"
                            style="display: none;"
                        >
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 style="color: var(--text-primary); font-weight: 600; margin: 0;">
                                        <i class="bi bi-receipt me-1"></i>Dirección de Facturación
                                    </h6>
                                    <small style="color: var(--text-muted);">BILL_TO</small>
                                </div>
                                <span
                                    id="badgePaso3"
                                    class="tags-yellow"
                                    style="display: none;"
                                >Sin cambios</span>
                            </div>

                            <!-- Buscador principal -->
                            <div class="buscador-wizard">
                                <div class="input-group">
                                    <span class="input-group-text buscador-icon">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input
                                        type="text"
                                        id="buscarBillTo"
                                        class="form-control buscador-input"
                                        placeholder="Buscar por nombre del cliente..."
                                        onkeypress="if(event.key==='Enter'){cargarPaso3(this.value); event.preventDefault();}"
                                    >
                                    <button
                                        type="button"
                                        class="btn buscador-btn"
                                        onclick="cargarPaso3(document.getElementById('buscarBillTo').value)"
                                    >
                                        <i class="bi bi-search me-1"></i> Buscar
                                    </button>
                                </div>
                            </div>

                            <!-- Valor actual -->
                            <div
                                id="billToActual"
                                class="configurado-actual"
                                style="display: none;"
                            >
                                <div class="configurado-header">
                                    <i class="bi bi-check-circle-fill me-1"></i> Actualmente configurado
                                </div>
                                <div class="configurado-body">
                                    <span
                                        id="billToActualTexto"
                                        style="font-size: 0.8rem;"
                                    ></span>
                                </div>
                            </div>

                            <!-- Filtro secundario -->
                            <div
                                id="filtroBillToContainer"
                                class="buscador-wizard"
                                style="display: none;"
                            >
                                <div class="input-group">
                                    <span class="input-group-text buscador-icon">
                                        <i class="bi bi-funnel"></i>
                                    </span>
                                    <input
                                        type="text"
                                        id="filtroBillTo"
                                        class="form-control buscador-input"
                                        placeholder="Filtrar resultados por dirección..."
                                        onkeyup="filtrarLista('listaBillTo', 'filtroBillTo', 'contadorBillTo')"
                                    >
                                    <span
                                        class="input-group-text"
                                        style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-muted); font-size: 0.75rem;"
                                        id="contadorBillTo"
                                    ></span>
                                </div>
                            </div>

                            <!-- Tabla -->
                            <div class="tabla-wizard">
                                <div
                                    id="listaBillTo"
                                    class="table-responsive"
                                    style="max-height: 250px; overflow-y: auto;"
                                ></div>
                            </div>
                        </div>

                        <!-- Paso 4: Precios -->
                        <div
                            class="paso-contenido"
                            id="paso4"
                            style="display: none;"
                        >
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 style="color: var(--text-primary); font-weight: 600; margin: 0;">
                                        <i class="bi bi-cash-stack me-1"></i>Lista de Precios
                                    </h6>
                                    <small style="color: var(--text-muted);">PRICE_LIST_ID</small>
                                </div>
                                <span
                                    id="badgePaso4"
                                    class="tags-yellow"
                                    style="display: none;"
                                >Sin cambios</span>
                            </div>

                            <!-- Buscador -->
                            <div class="buscador-wizard">
                                <div class="input-group">
                                    <span class="input-group-text buscador-icon">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input
                                        type="text"
                                        id="buscarPrecios"
                                        class="form-control buscador-input"
                                        placeholder="Buscar lista de precios..."
                                        onkeypress="if(event.key==='Enter'){cargarPaso4(this.value); event.preventDefault();}"
                                    >
                                    <button
                                        type="button"
                                        class="btn buscador-btn"
                                        onclick="cargarPaso4(document.getElementById('buscarPrecios').value)"
                                    >
                                        <i class="bi bi-search me-1"></i> Buscar
                                    </button>
                                </div>
                            </div>

                            <!-- Valor actual -->
                            <div
                                id="precioActual"
                                class="configurado-actual"
                                style="display: none;"
                            >
                                <div class="configurado-header">
                                    <i class="bi bi-check-circle-fill me-1"></i> Actualmente configurado
                                </div>
                                <div class="configurado-body">
                                    <span
                                        id="precioActualTexto"
                                        style="font-size: 0.8rem;"
                                    ></span>
                                </div>
                            </div>

                            <!-- Tabla -->
                            <div class="tabla-wizard">
                                <div
                                    id="listaPrecios"
                                    class="table-responsive"
                                    style="max-height: 250px; overflow-y: auto;"
                                ></div>
                            </div>
                        </div>

                        <!-- Paso 5: Tipo de Orden -->
                        <div
                            class="paso-contenido"
                            id="paso5"
                            style="display: none;"
                        >
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 style="color: var(--text-primary); font-weight: 600; margin: 0;">
                                        <i class="bi bi-diagram-3 me-1"></i>Tipo de Orden
                                    </h6>
                                    <small style="color: var(--text-muted);">ORDER_TYPE</small>
                                </div>
                                <span
                                    id="badgePaso5"
                                    class="tags-yellow"
                                    style="display: none;"
                                >Sin cambios</span>
                            </div>

                            <!-- Buscador -->
                            <div class="buscador-wizard">
                                <div class="input-group">
                                    <span class="input-group-text buscador-icon">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input
                                        type="text"
                                        id="buscarTipoOrden"
                                        class="form-control buscador-input"
                                        placeholder="Filtrar tipo de orden..."
                                        onkeyup="filtrarLista('listaTipoOrden', 'buscarTipoOrden', 'contadorTipoOrden')"
                                    >
                                </div>
                            </div>

                            <!-- Valor actual -->
                            <div
                                id="tipoOrdenActual"
                                class="configurado-actual"
                                style="display: none;"
                            >
                                <div class="configurado-header">
                                    <i class="bi bi-check-circle-fill me-1"></i> Actualmente configurado
                                </div>
                                <div class="configurado-body">
                                    <span
                                        id="tipoOrdenActualTexto"
                                        style="font-size: 0.8rem;"
                                    ></span>
                                </div>
                            </div>

                            <!-- Contador de resultados -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small
                                    style="color: var(--text-muted);"
                                    id="contadorTipoOrden"
                                ></small>
                            </div>

                            <!-- Tabla de resultados -->
                            <div class="tabla-wizard">
                                <div
                                    id="listaTipoOrden"
                                    class="table-responsive"
                                    style="max-height: 250px; overflow-y: auto;"
                                >
                                    <div class="py-4 text-center">
                                        <span
                                            class="spinner-border spinner-border-sm"
                                            style="color: var(--text-muted);"
                                        ></span>
                                        <span style="color: var(--text-muted);">Cargando tipos de orden...</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Paso 6: Resumen -->
                        <div
                            class="paso-contenido"
                            id="paso6"
                            style="display: none;"
                        >
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 style="color: var(--text-primary); font-weight: 600; margin: 0;">
                                        <i class="bi bi-clipboard-check me-1"></i>Resumen de Configuración
                                    </h6>
                                    <small style="color: var(--text-muted);">Verifica los datos antes de
                                        guardar</small>
                                </div>
                            </div>

                            <div class="row g-3">
                                <!-- Cliente -->
                                <div class="col-md-6">
                                    <div class="resumen-card">
                                        <div class="resumen-card-header">
                                            <div
                                                class="resumen-icono"
                                                style="background: var(--btn-blue-bg);"
                                            >
                                                <i
                                                    class="bi bi-person-badge"
                                                    style="color: var(--btn-blue-text); font-size: 0.75rem;"
                                                ></i>
                                            </div>
                                            <span>Cliente</span>
                                        </div>
                                        <div class="resumen-card-body">
                                            <table
                                                class="table-sm mb-0 table"
                                                style="font-size: 0.8rem;"
                                            >
                                                <tr>
                                                    <td class="resumen-label">ID:</td>
                                                    <td><span
                                                            id="resumenIdCliente"
                                                            class="resumen-valor"
                                                        >-</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="resumen-label">Nombre:</td>
                                                    <td><span
                                                            id="resumenNombreCliente"
                                                            class="resumen-valor"
                                                            style="max-width: 250px; color: var(--text-primary); font-size: 0.8rem; vertical-align: middle;"
                                                            title=""
                                                        >-</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="resumen-label">Tipo:</td>
                                                    <td><span
                                                            id="resumenTipoCliente"
                                                            class="tags-blue"
                                                            style="font-size: 0.7rem;"
                                                        >-</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="resumen-label">Términos:</td>
                                                    <td><span
                                                            id="resumenTerminos"
                                                            class="resumen-valor"
                                                        >-</span></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Direcciones -->
                                <div class="col-md-6">
                                    <div class="resumen-card">
                                        <div class="resumen-card-header">
                                            <div
                                                class="resumen-icono"
                                                style="background: var(--btn-amber-bg);"
                                            >
                                                <i
                                                    class="bi bi-geo-alt"
                                                    style="color: var(--btn-amber-text); font-size: 0.75rem;"
                                                ></i>
                                            </div>
                                            <span>Direcciones</span>
                                        </div>
                                        <div class="resumen-card-body">
                                            <table
                                                class="table-sm mb-0 table"
                                                style="font-size: 0.8rem;"
                                            >
                                                <tr>
                                                    <td class="resumen-label">Envío:</td>
                                                    <td><span
                                                            id="resumenShipTo"
                                                            style="font-size: 0.75rem; color: var(--text-primary);"
                                                        >-</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="resumen-label">Facturación:</td>
                                                    <td><span
                                                            id="resumenBillTo"
                                                            style="font-size: 0.75rem; color: var(--text-primary);"
                                                        >-</span></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Lista de Precios -->
                                <div class="col-md-6">
                                    <div class="resumen-card">
                                        <div class="resumen-card-header">
                                            <div
                                                class="resumen-icono"
                                                style="background: var(--btn-green-bg);"
                                            >
                                                <i
                                                    class="bi bi-cash-stack"
                                                    style="color: var(--btn-green-text); font-size: 0.75rem;"
                                                ></i>
                                            </div>
                                            <span>Lista de Precios</span>
                                        </div>
                                        <div class="resumen-card-body">
                                            <table
                                                class="table-sm mb-0 table"
                                                style="font-size: 0.8rem;"
                                            >
                                                <tr>
                                                    <td class="resumen-label">ID:</td>
                                                    <td><span
                                                            id="resumenPriceListId"
                                                            class="resumen-valor"
                                                        >-</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="resumen-label">Nombre:</td>
                                                    <td><span
                                                            id="resumenPriceListName"
                                                            class="resumen-valor"
                                                        >-</span></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tipo de Orden -->
                                <div class="col-md-6">
                                    <div class="resumen-card">
                                        <div class="resumen-card-header">
                                            <div
                                                class="resumen-icono"
                                                style="background: #f3e8ff;"
                                            >
                                                <i
                                                    class="bi bi-diagram-3"
                                                    style="color: #7c3aed; font-size: 0.75rem;"
                                                ></i>
                                            </div>
                                            <span>Tipo de Orden</span>
                                        </div>
                                        <div class="resumen-card-body">
                                            <table
                                                class="table-sm mb-0 table"
                                                style="font-size: 0.8rem;"
                                            >
                                                <tr>
                                                    <td class="resumen-label">Código:</td>
                                                    <td><span
                                                            id="resumenOrderTypeCodigo"
                                                            class="resumen-valor"
                                                        >-</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="resumen-label">Descripción:</td>
                                                    <td>
                                                        <span
                                                            id="resumenOrderTypeDescripcion"
                                                            class="text-truncate d-inline-block"
                                                            style="max-width: 250px; color: var(--text-primary); font-size: 0.8rem; vertical-align: middle;"
                                                            title=""
                                                        >-</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
                    <i class="bi bi-x"></i> Cancelar
                </button>
                <button
                    type="button"
                    id="btnAnterior"
                    class="btn d-flex align-items-center d-none gap-1"
                    style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    onmouseover="this.style.background='var(--btn-gray-hover)'; this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.background='var(--btn-gray-bg)'; this.style.transform='translateY(0)'"
                >
                    <i class="bi bi-arrow-left"></i> Anterior
                </button>
                <button
                    type="button"
                    id="btnSiguiente"
                    class="btn d-flex align-items-center gap-1"
                    style="background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    onmouseover="this.style.background='linear-gradient(135deg, var(--gradient-end) 0%, var(--gradient-start) 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px var(--btn-gradient-shadow)'"
                    onmouseout="this.style.background='linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                >
                    Siguiente <i class="bi bi-arrow-right"></i>
                </button>
                <button
                    type="button"
                    id="btnGuardarWizard"
                    class="btn d-flex align-items-center d-none gap-1"
                    style="background: var(--btn-green-bg); color: var(--btn-green-text); border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    onmouseover="this.style.background='var(--btn-green-hover)'; this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.background='var(--btn-green-bg)'; this.style.transform='translateY(0)'"
                >
                    <i class="bi bi-floppy"></i> Guardar Configuración
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* ============================================================ */
    /* BUSCADOR WIZARD */
    /* ============================================================ */
    .buscador-wizard {
        margin-bottom: 12px;
    }

    .buscador-wizard .input-group {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        border-radius: 10px;
        overflow: hidden;
    }

    .buscador-icon {
        background: var(--gradient-start) !important;
        color: white !important;
        border: none !important;
        padding: 8px 14px !important;
    }

    .buscador-input {
        border: 1px solid var(--border-input) !important;
        padding: 8px 12px !important;
        font-size: 0.85rem !important;
        border-left: none !important;
    }

    .buscador-input:focus {
        border-color: var(--gradient-start) !important;
        box-shadow: 0 0 0 3px rgba(30, 41, 59, 0.1) !important;
    }

    .buscador-btn {
        background: var(--gradient-start) !important;
        color: white !important;
        border: none !important;
        padding: 8px 16px !important;
        font-size: 0.85rem !important;
        font-weight: 500 !important;
        transition: all 0.3s ease !important;
    }

    .buscador-btn:hover {
        background: var(--gradient-end) !important;
    }

    /* ============================================================ */
    /* RESUMEN CARDS */
    /* ============================================================ */
    .resumen-card {
        border: 1px solid var(--border-input);
        border-radius: 10px;
        overflow: hidden;
        background: var(--card-bg);
        height: 100%;
    }

    .resumen-card-header {
        background: var(--bg-subtle);
        padding: 10px 14px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid var(--border-light);
    }

    .resumen-icono {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .resumen-card-body {
        padding: 12px 14px;
    }

    .resumen-label {
        color: var(--text-secondary) !important;
        width: 80px;
        padding: 4px 0;
    }

    .resumen-valor {
        color: var(--text-primary);
        font-weight: 600;
    }

    /* ============================================================ */
    /* ACTUALMENTE CONFIGURADO */
    /* ============================================================ */
    .configurado-actual {
        margin-bottom: 12px;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid var(--border-input);
    }

    .configurado-header {
        background: var(--bg-subtle);
        color: var(--text-subtle);
        padding: 6px 12px;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .configurado-header i {
        color: var(--success-color);
    }

    .configurado-body {
        background: var(--card-bg);
        padding: 8px 12px;
    }

    /* ============================================================ */
    /* TABLA WIZARD */
    /* ============================================================ */
    .tabla-wizard {
        border: 1px solid var(--border-input);
        border-radius: 10px;
        overflow: hidden;
        background: var(--card-bg);
    }

    .tabla-wizard .table {
        margin-bottom: 0 !important;
    }

    .tabla-wizard .table thead th {
        background: var(--bg-subtle) !important;
        color: var(--text-subtle) !important;
        font-size: 0.7rem !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 8px 10px !important;
        border-bottom: 2px solid var(--border-medium) !important;
    }

    .tabla-wizard .table tbody td {
        padding: 6px 10px !important;
        border-bottom: 1px solid var(--border-light);
        font-size: 0.78rem;
    }

    .tabla-wizard .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* ============================================================ */
    /* TIMELINE CON FLECHAS */
    /* ============================================================ */
    .timeline-wizard {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        padding: 12px 4px;
        border-radius: 12px;
    }

    .timeline-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        white-space: nowrap;
    }

    .timeline-item:hover {
        background: var(--bg-light);
    }

    .timeline-numero {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--border-light);
        color: var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .timeline-texto {
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--text-muted);
    }

    .timeline-icono {
        display: none;
    }

    /* FLECHA SEPARADORA - Más espacio */
    .timeline-flecha {
        color: var(--border-medium);
        font-size: 1rem;
        padding: 0 12px;
        display: flex;
        align-items: center;
    }

    /* Estado activo - Más padding para destacar */
    .timeline-item.active {
        background: var(--gradient-start);
        padding: 10px 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .timeline-item.active .timeline-numero {
        background: white;
        color: var(--gradient-start);
    }

    .timeline-item.active .timeline-texto {
        color: white;
        font-weight: 600;
    }

    /* Estado completado */
    .timeline-item.completado .timeline-numero {
        background: var(--success-color);
        color: white;
    }

    .timeline-item.completado .timeline-texto {
        color: var(--success-color);
    }

    .timeline-item.completado .timeline-numero span {
        display: none;
    }

    /* Quitar número y mostrar check en completado */
    .timeline-item.completado .timeline-numero {
        font-size: 0;
    }

    .timeline-item.completado .timeline-numero::after {
        content: '✓';
        font-size: 0.8rem;
        font-weight: 700;
    }

    /* ============================================================ */
    /* RESPONSIVE: Pantallas pequeñas */
    /* ============================================================ */
    @media (max-width: 768px) {
        .timeline-item {
            padding: 8px 12px;
            gap: 4px;
        }

        .timeline-item.active {
            padding: 8px 16px;
        }

        .timeline-flecha {
            padding: 0 4px;
            font-size: 0.75rem;
        }

        .timeline-numero {
            width: 28px;
            height: 28px;
            font-size: 0.7rem;
        }
    }

    @media (max-width: 576px) {
        .timeline-item {
            padding: 6px 8px;
        }

        .timeline-item.active {
            padding: 6px 12px;
        }

        .timeline-flecha {
            padding: 0 2px;
            font-size: 0.65rem;
        }

        .timeline-numero {
            width: 24px;
            height: 24px;
            font-size: 0.65rem;
        }
    }

    /* Scroll suave en el contenido del wizard */
    .modal-body>div:last-child {
        scroll-behavior: smooth;
    }

    /* Scrollbar estilizado */
    .modal-body>div:last-child::-webkit-scrollbar {
        width: 6px;
    }

    .modal-body>div:last-child::-webkit-scrollbar-track {
        background: transparent;
    }

    .modal-body>div:last-child::-webkit-scrollbar-thumb {
        background: var(--border-medium);
        border-radius: 3px;
    }

    /* ============================================================ */
    /* EVITAR SCROLL HORIZONTAL */
    /* ============================================================ */
    .modal-body>div:last-child {
        overflow-x: hidden !important;
        overflow-y: auto !important;
    }

    .paso-contenido {
        overflow-x: hidden;
        max-width: 100%;
    }

    .tabla-wizard {
        overflow-x: auto;
    }

    .tabla-wizard .table {
        width: 100%;
        min-width: 100%;
    }

    .resumen-card {
        overflow-x: hidden;
    }

    .resumen-card-body table {
        width: 100%;
    }

    .buscador-wizard .input-group {
        width: 100%;
    }

    .buscador-input {
        min-width: 0;
    }
</style>
