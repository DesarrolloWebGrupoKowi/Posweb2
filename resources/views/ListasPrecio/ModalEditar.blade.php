<!-- Modal Editar Lista de Precio -->
<div
    class="modal fade"
    id="ModalEditar{{ $listaPrecio->IdListaPrecio }}"
    tabindex="-1"
    aria-labelledby="ModalEditarLabel{{ $listaPrecio->IdListaPrecio }}"
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
                    id="ModalEditarLabel{{ $listaPrecio->IdListaPrecio }}"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
                        >
                            <i class="bi bi-tags"></i>
                        </div>
                        <span>Editar Lista de Precio: {{ $listaPrecio->NomListaPrecio }}</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="EditarListaPrecio/{{ $listaPrecio->IdListaPrecio }}"
                    method="POST"
                >
                    @csrf

                    <!-- Fila: Peso Mínimo y Peso Máximo -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label
                                for="PesoMinimo{{ $listaPrecio->IdListaPrecio }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Peso Mínimo (kg) <span style="color: #ef4444;">*</span>
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                >
                                    <i class="bi bi-weight"></i>
                                </span>
                                <input
                                    class="form-control border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    type="number"
                                    name="PesoMinimo"
                                    id="PesoMinimo{{ $listaPrecio->IdListaPrecio }}"
                                    step="0.001"
                                    value="{{ $listaPrecio->PesoMinimo }}"
                                    tabindex="1"
                                    required
                                >
                            </div>
                            <div
                                class="form-text mt-1"
                                id="error-PesoMinimo{{ $listaPrecio->IdListaPrecio }}"
                                style="font-size: 0.78rem; color: #ef4444;"
                            ></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label
                                for="PesoMaximo{{ $listaPrecio->IdListaPrecio }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Peso Máximo (kg) <span style="color: #ef4444;">*</span>
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                >
                                    <i class="bi bi-weight"></i>
                                </span>
                                <input
                                    class="form-control border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    type="number"
                                    name="PesoMaximo"
                                    id="PesoMaximo{{ $listaPrecio->IdListaPrecio }}"
                                    step="0.001"
                                    value="{{ $listaPrecio->PesoMaximo }}"
                                    tabindex="2"
                                    required
                                >
                            </div>
                            <div
                                class="form-text mt-1"
                                id="error-PesoMaximo{{ $listaPrecio->IdListaPrecio }}"
                                style="font-size: 0.78rem; color: #ef4444;"
                            ></div>
                        </div>
                    </div>

                    <!-- IVA -->
                    <div class="mb-3">
                        <label
                            for="PorcentajeIva{{ $listaPrecio->IdListaPrecio }}"
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            IVA (%) <span style="color: #ef4444;">*</span>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                            >
                                <i class="bi bi-percent"></i>
                            </span>
                            <input
                                class="form-control border-start-0"
                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                type="number"
                                name="PorcentajeIva"
                                id="PorcentajeIva{{ $listaPrecio->IdListaPrecio }}"
                                step="0.01"
                                value="{{ $listaPrecio->PorcentajeIva }}"
                                tabindex="3"
                                required
                            >
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-PorcentajeIva{{ $listaPrecio->IdListaPrecio }}"
                            style="font-size: 0.78rem; color: #ef4444;"
                        ></div>
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
                    <i class="bi bi-x"></i>
                    Cerrar
                </button>
                <button
                    type="submit"
                    class="btn d-flex align-items-center gap-1"
                    style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    onmouseover="this.style.background='linear-gradient(135deg, #0f172a 0%, #1e293b 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(30, 41, 59, 0.3)'"
                    onmouseout="this.style.background='linear-gradient(135deg, #1e293b 0%, #334155 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                >
                    <i class="bi bi-pencil"></i>
                    Guardar Cambios
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
