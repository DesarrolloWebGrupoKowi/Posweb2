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
            class="modal-content border-0 shadow"
            style="border-radius: 10px; overflow: hidden;"
        >
            <!-- Modal Header -->
            <div
                class="modal-header border-bottom-0 px-4 pb-0 pt-3"
                style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);"
            >
                <h5
                    class="mb-0 text-white"
                    style="font-weight: 600; font-size: 1.1rem;"
                    id="ModalAgregarCajaTiendaLabel"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
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
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            Tienda
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                            >
                                <i class="bi bi-shop"></i>
                            </span>
                            <select
                                name="idTiendaCaja"
                                id="idTiendaCaja"
                                class="form-select border-start-0"
                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
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
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            Número de Caja
                        </label>
                        @if ($cajas->count() == 0)
                            <div
                                class="py-4 text-center"
                                style="background: #fef2f2; border-radius: 8px;"
                            >
                                <i
                                    class="bi bi-exclamation-triangle d-block mb-2"
                                    style="color: #ef4444; font-size: 1.5rem;"
                                ></i>
                                <p
                                    class="fw-medium m-0"
                                    style="color: #991b1b; font-size: 0.85rem;"
                                >
                                    No hay cajas por agregar
                                </p>
                            </div>
                        @else
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                >
                                    <i class="bi bi-123"></i>
                                </span>
                                <select
                                    name="idCaja"
                                    id="idCaja"
                                    class="form-select border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
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
                        style="background: #f1f5f9; color: #475569; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                        onmouseover="this.style.background='#e2e8f0'; this.style.transform='translateY(-1px)'"
                        onmouseout="this.style.background='#f1f5f9'; this.style.transform='translateY(0)'"
                    >
                        <i class="bi bi-x-lg"></i>
                        Cerrar
                    </button>
                    @if ($cajas->count() > 0)
                        <button
                            type="submit"
                            class="btn d-flex align-items-center gap-1"
                            style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                            onmouseover="this.style.background='linear-gradient(135deg, #0f172a 0%, #1e293b 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(30, 41, 59, 0.3)'"
                            onmouseout="this.style.background='linear-gradient(135deg, #1e293b 0%, #334155 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
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
