<!-- Modal Detalle -->
<div
    class="modal fade"
    id="ModalDetalle"
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
                            <i class="bi bi-list-ul"></i>
                        </div>
                        <span>Detalle del Pedido: <strong id="detalleFolio"></strong></span>
                    </div>
                </h5>
            </div>
            <div class="modal-body p-4">
                <div
                    class="table-responsive"
                    style="max-height: 50vh; overflow-y: auto; border: 1px solid var(--border-light); border-radius: 10px;"
                >
                    <table
                        class="table-sm mb-0 table"
                        id="tablaDetalle"
                    >
                        <thead style="position: sticky; top: 0; background: var(--bg-subtle);">
                            <tr>
                                <th>#</th>
                                <th>Código</th>
                                <th class="text-end">Cantidad</th>
                                <th>UOM</th>
                                <th class="text-end">Precio</th>
                                <th class="text-end">Importe</th>
                            </tr>
                        </thead>
                        <tbody id="detalleBody">
                            <tr>
                                <td
                                    colspan="6"
                                    class="py-4 text-center"
                                >Cargando...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                <button
                    type="button"
                    class="btn d-flex align-items-center gap-1"
                    data-bs-dismiss="modal"
                    style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500;"
                >
                    <i class="bi bi-x"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    #tablaDetalle {
        margin-bottom: 0;
    }

    #tablaDetalle thead th {
        background: var(--bg-subtle);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-subtle);
        padding: 8px 12px;
        border-bottom: 2px solid var(--border-medium);
    }

    #tablaDetalle thead th:first-child {
        border-radius: 0;
    }

    #tablaDetalle tbody td {
        padding: 6px 12px;
        font-size: 0.8rem;
        border-bottom: 1px solid var(--border-light);
    }

    #tablaDetalle tbody tr:last-child td {
        border-bottom: none;
    }
</style>
