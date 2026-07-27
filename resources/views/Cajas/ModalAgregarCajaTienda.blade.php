<!-- Modal Agregar Caja Tienda -->
<div
    class="modal fade"
    id="ModalAgregarCajaTienda"
    tabindex="-1"
    aria-labelledby="ModalAgregarCajaTiendaLabel"
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
                    id="ModalAgregarCajaTiendaLabel"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: var(--card-header-icon-bg); width: 32px; height: 32px;"
                        >
                            <i class="bi bi-upc-scan"></i>
                        </div>
                        <span>Agregar Caja a Tienda</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <form
                action="/AgregarCajaTienda"
                method="POST"
            >
                @csrf
                <div class="modal-body p-4">
                    <!-- Tienda -->
                    <div class="mb-3">
                        <label
                            for="idTiendaCaja"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Tienda
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-muted); border-radius: 8px 0 0 8px;"
                            >
                                <i class="bi bi-shop"></i>
                            </span>
                            <select
                                name="idTiendaCaja"
                                id="idTiendaCaja"
                                class="form-select border-start-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                            >
                                @foreach ($tiendas as $tienda)
                                    <option
                                        {{ $idTienda == $tienda->IdTienda ? 'selected' : '' }}
                                        {{ $idTienda != $tienda->IdTienda ? 'disabled' : '' }}
                                        value="{{ $tienda->IdTienda }}"
                                    >
                                        {{ $tienda->NomTienda }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Número de Caja -->
                    <div class="mb-0">
                        <label
                            for="idCaja"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Número de Caja
                        </label>
                        @if ($cajas->count() == 0)
                            <div
                                class="py-4 text-center"
                                style="background: var(--tag-red-bg, #fef2f2); border-radius: 8px;"
                            >
                                <i
                                    class="bi bi-exclamation-triangle d-block mb-2"
                                    style="color: var(--danger-color); font-size: 1.5rem;"
                                ></i>
                                <p
                                    class="fw-medium m-0"
                                    style="color: var(--tag-red-text, #991b1b); font-size: 0.85rem;"
                                >
                                    No hay cajas por agregar
                                </p>
                            </div>
                        @else
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-muted); border-radius: 8px 0 0 8px;"
                                >
                                    <i class="bi bi-123"></i>
                                </span>
                                <select
                                    name="idCaja"
                                    id="idCaja"
                                    class="form-select border-start-0"
                                    style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                >
                                    @foreach ($cajas as $caja)
                                        <option value="{{ $caja->IdCaja }}">{{ $caja->NumCaja }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
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
                        <i class="bi bi-x-lg"></i>
                        Cerrar
                    </button>
                    @if ($cajas->count() > 0)
                        <button
                            type="submit"
                            class="btn d-flex align-items-center gap-1"
                            style="background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                        >
                            <i class="bi bi-floppy"></i>
                            Guardar
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
