<!-- ============================================================ -->
<!-- MODAL VER CLIENTE -->
<!-- ============================================================ -->
<div
    class="modal fade"
    id="ModalVerCliente"
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
                            <i class="bi bi-eye"></i>
                        </div>
                        <span>Información del Cliente: <strong id="verClienteCodigo"></strong></span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <div class="row g-3">
                    <!-- Columna 1 -->
                    <div class="col-md-6">
                        <div class="card-chart h-100 rounded p-3">
                            <h6 style="color: var(--text-primary); font-weight: 600; font-size: 0.85rem;">
                                <i class="bi bi-shop me-1"></i>Datos del Cliente
                            </h6>
                            <hr style="border-color: var(--border-light); margin: 0.5rem 0;">
                            <table
                                class="table-sm mb-0 table"
                                style="font-size: 0.8rem;"
                            >
                                <tbody>
                                    <tr>
                                        <td style="color: var(--text-secondary); width: 120px;">Código:</td>
                                        <td><span
                                                id="verCliente"
                                                style="color: var(--text-primary);"
                                            >-</span></td>
                                    </tr>
                                    <tr>
                                        <td style="color: var(--text-secondary);">Nombre:</td>
                                        <td><span
                                                id="verNombre"
                                                style="color: var(--text-primary);"
                                            >-</span></td>
                                    </tr>
                                    <tr>
                                        <td style="color: var(--text-secondary);">Dirección:</td>
                                        <td><span
                                                id="verDireccion"
                                                style="color: var(--text-primary);"
                                            >-</span></td>
                                    </tr>
                                    <tr>
                                        <td style="color: var(--text-secondary);">ID Cliente:</td>
                                        <td>
                                            <span
                                                id="verIdCliente"
                                                {{-- class="tags-green" --}}
                                                style="font-size: 0.75rem;"
                                            >-</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="color: var(--text-secondary);">Nombre Cliente:</td>
                                        <td>
                                            <span
                                                id="verNombreCliente"
                                                style="color: var(--text-primary);"
                                            >-</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Columna 2 -->
                    <div class="col-md-6">
                        <div class="card-chart h-100 rounded p-3">
                            <h6 style="color: var(--text-primary); font-weight: 600; font-size: 0.85rem;">
                                <i class="bi bi-gear me-1"></i>Configuración
                            </h6>
                            <hr style="border-color: var(--border-light); margin: 0.5rem 0;">
                            <table
                                class="table-sm mb-0 table"
                                style="font-size: 0.8rem;"
                            >
                                <tbody>
                                    <tr>
                                        <td style="color: var(--text-secondary); width: 120px;">Tipo Cliente:</td>
                                        <td><span
                                                id="verTipoCliente"
                                                class="tags-blue"
                                                style="font-size: 0.75rem;"
                                            >-</span></td>
                                    </tr>
                                    <tr>
                                        <td style="color: var(--text-secondary);">Términos:</td>
                                        <td><span
                                                id="verTerminos"
                                                style="color: var(--text-primary);"
                                            >-</span></td>
                                    </tr>
                                    <tr>
                                        <td style="color: var(--text-secondary);">Envío (SHIP_TO):</td>
                                        <td>
                                            <span
                                                id="verShipTo"
                                                style="font-size: 0.75rem;"
                                            >-</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="color: var(--text-secondary);">Facturación (BILL_TO):</td>
                                        <td>
                                            <span
                                                id="verBillTo"
                                                style="font-size: 0.75rem;"
                                            >-</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="color: var(--text-secondary);">Lista Precio:</td>
                                        <td>
                                            <span
                                                id="verPriceList"
                                                style="font-size: 0.75rem;"
                                            >-</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Estados -->
                <div class="row g-3 mt-3">
                    <div class="col-12">
                        <div class="card-chart rounded p-3">
                            <h6 style="color: var(--text-primary); font-weight: 600; font-size: 0.85rem;">
                                <i class="bi bi-check-circle me-1"></i>Estado de Configuración
                            </h6>
                            <hr style="border-color: var(--border-light); margin: 0.5rem 0;">
                            <div
                                class="d-flex flex-wrap gap-2"
                                id="verEstados"
                            >
                                <!-- Se llena con JavaScript -->
                            </div>
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
                    onmouseover="this.style.background='var(--btn-gray-hover)'; this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.background='var(--btn-gray-bg)'; this.style.transform='translateY(0)'"
                >
                    <i class="bi bi-x"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
