<!-- MODAL CONFIRMAR GUARDAR EDICION DE PAQUETE -->
<div
    class="modal fade"
    id="ModalConfirmarGuardar"
    tabindex="-1"
    aria-labelledby="modalConfirmarGuardarLabel"
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
                style="border-radius: 10px 10px 0 0;"
            >
                <h5
                    class="text-white"
                    id="modalConfirmarGuardarLabel"
                >
                    <div class="d-flex align-items-center gap-2">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M12 4v16M8 8V4h8v4" />
                            <rect
                                x="4"
                                y="8"
                                width="16"
                                height="12"
                                rx="2"
                            />
                        </svg>
                        <span>Confirmar cambios</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <!-- Información de la promoción -->
                <div
                    class="mb-4 border p-3"
                    style="background-color: #f8f9fa; border-radius: 8px;"
                >
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span
                                class="badge me-2"
                                style="background-color: #f59e0b;"
                            >PROMOCIÓN</span>
                            <span class="fw-500">{{ $descuento->NomDescuento }}</span>
                        </div>
                        <span
                            class="badge"
                            style="background-color: #1e293b; color: white;"
                        >
                            @if ($descuento->TipoDescuento == 1)
                                Producto
                            @elseif ($descuento->TipoDescuento == 2)
                                Tienda
                            @else
                                Plaza
                            @endif
                        </span>
                    </div>
                    <div class="text-muted small mt-2">
                        <div class="d-flex justify-content-between">
                            <span>Vigencia:
                                <span class="fw-500">
                                    {{ \Carbon\Carbon::parse($descuento->FechaInicio)->format('d/m/Y') }} -
                                    {{ \Carbon\Carbon::parse($descuento->FechaFin)->format('d/m/Y') }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Mensaje de confirmación -->
                <div class="mb-3 text-center">
                    <div class="mb-3">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="48"
                            height="48"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="#f59e0b"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            style="margin: 0 auto;"
                        >
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                    </div>
                    <p
                        class="fs-6 fw-normal text-secondary m-0"
                        style="line-height: 24px"
                    >
                        ¿Desea guardar los cambios realizados en la promoción?
                    </p>
                    <p
                        class="fs-6 fw-normal text-muted m-0 mt-1"
                        style="font-size: 0.85rem;"
                    >
                        Se actualizarán los artículos y sus precios
                    </p>
                </div>

                <!-- Advertencia si hay artículos con precio 0 -->
                <div
                    id="advertenciaPrecioCero"
                    class="alert alert-warning d-none"
                    role="alert"
                    style="border-radius: 8px;"
                >
                    <div class="d-flex align-items-center gap-2">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <circle
                                cx="12"
                                cy="12"
                                r="10"
                            />
                            <line
                                x1="12"
                                y1="8"
                                x2="12"
                                y2="12"
                            />
                            <line
                                x1="12"
                                y1="16"
                                x2="12.01"
                                y2="16"
                            />
                        </svg>
                        <small>Hay artículos con precio 0. Por favor, verifique los precios antes de guardar.</small>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top-0 pt-0">
                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal"
                    style="border-radius: 6px; padding: 8px 20px;"
                >
                    <span class="d-flex align-items-center gap-2">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="16"
                            height="16"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <line
                                x1="18"
                                y1="6"
                                x2="6"
                                y2="18"
                            />
                            <line
                                x1="6"
                                y1="6"
                                x2="18"
                                y2="18"
                            />
                        </svg>
                        Cancelar
                    </span>
                </button>
                <button
                    id="btnEditarPaquete"
                    class="btn"
                    style="border-radius: 6px; padding: 8px 20px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none;"
                >
                    <span class="d-flex align-items-center gap-2">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="16"
                            height="16"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        Guardar cambios
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
