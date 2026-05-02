<!-- Modal Confirmacion Eliminar-->
{{-- <div class="modal fade" id="ModalEliminarConfirm{{ $descuento->IdEncDescuento }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Solicitud de Eliminación</h5>
            </div>
            <div class="modal-body">
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    ¿Seguro Desea Eliminar este Descuento?
                </p>
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    {{ $descuento->NomDescuento }}
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">Cerrar </button>
                <form class="d-flex" action="EliminarDescuento/{{ $descuento->IdEncDescuento }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-danger">Eliminar </button>
                </form>
            </div>
        </div>
    </div>
</div> --}}<!-- MODAL CONFIRMAR DESHABILITAR PROMOCIÓN -->
<div
    class="modal fade"
    id="ModalEliminarConfirm{{ $descuento->IdEncDescuento }}"
    tabindex="-1"
    aria-labelledby="modalDeshabilitarLabel{{ $descuento->IdEncDescuento }}"
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
                style="border-radius: 10px 10px 0 0; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);"
            >
                <h5
                    class="text-white"
                    id="modalDeshabilitarLabel{{ $descuento->IdEncDescuento }}"
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
                        <span>Deshabilitar Promoción</span>
                    </div>
                </h5>
                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
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
                        ¿Seguro desea deshabilitar esta promoción?
                    </p>
                    <p
                        class="fs-6 fw-normal text-muted m-0 mt-1"
                        style="font-size: 0.85rem;"
                    >
                        La promoción dejará de estar disponible temporalmente
                    </p>
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
                <form
                    class="d-flex"
                    action="EliminarDescuento/{{ $descuento->IdEncDescuento }}"
                    method="POST"
                >
                    @csrf
                    <button
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
                            Deshabilitar
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
