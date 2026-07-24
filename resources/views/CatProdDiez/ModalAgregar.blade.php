<!-- Modal Agregar Producto -->
<div
    class="modal fade"
    id="ModalAgregar"
    tabindex="-1"
    aria-labelledby="ModalAgregarLabel"
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
                    id="ModalAgregarLabel"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
                        >
                            <i class="bi bi-plus-circle"></i>
                        </div>
                        <span>Agregar Producto</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <form
                action="/CrearCatProdDiez"
                method="POST"
            >
                @csrf
                <div class="modal-body p-4">
                    <!-- Código de producto -->
                    <div class="mb-3">
                        <label
                            for="CodArticulo"
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            Código de producto <span style="color: #ef4444;">*</span>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                            >
                                <i class="bi bi-upc"></i>
                            </span>
                            <input
                                type="text"
                                name="CodArticulo"
                                id="CodArticulo"
                                class="form-control border-start-0"
                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                placeholder="Escribe el código de producto"
                                tabindex="1"
                                required
                            >
                        </div>
                    </div>

                    <!-- Peso Mínimo -->
                    <div class="mb-3">
                        <label
                            for="PesoMinimo"
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            Peso Mínimo <span style="color: #ef4444;">*</span>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                            >
                                <i class="bi bi-arrow-down"></i>
                            </span>
                            <input
                                type="number"
                                min=".1"
                                step=".1"
                                name="PesoMinimo"
                                id="PesoMinimo"
                                class="form-control border-start-0"
                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                placeholder="Peso mínimo"
                                tabindex="2"
                                required
                            >
                        </div>
                    </div>

                    <!-- Peso Máximo -->
                    <div class="mb-3">
                        <label
                            for="PesoMaximo"
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            Peso Máximo <span style="color: #ef4444;">*</span>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                            >
                                <i class="bi bi-arrow-up"></i>
                            </span>
                            <input
                                type="number"
                                min=".1"
                                step=".1"
                                name="PesoMaximo"
                                id="PesoMaximo"
                                class="form-control border-start-0"
                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                placeholder="Peso máximo"
                                tabindex="3"
                                required
                            >
                        </div>
                    </div>

                    <!-- Lista de precios -->
                    <div class="mb-0">
                        <label
                            for="IdListaPrecio"
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            Lista de precios
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                            >
                                <i class="bi bi-list-ol"></i>
                            </span>
                            <select
                                name="IdListaPrecio"
                                id="IdListaPrecio"
                                class="form-select border-start-0"
                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                            >
                                @foreach ($listaPrecios as $item)
                                    <option value="{{ $item->IdListaPrecio }}">{{ $item->NomListaPrecio }}</option>
                                @endforeach
                            </select>
                        </div>
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
                    <button
                        type="submit"
                        class="btn d-flex align-items-center gap-1"
                        style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                        onmouseover="this.style.background='linear-gradient(135deg, #0f172a 0%, #1e293b 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(30, 41, 59, 0.3)'"
                        onmouseout="this.style.background='linear-gradient(135deg, #1e293b 0%, #334155 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                    >
                        <i class="bi bi-floppy"></i>
                        Agregar Artículo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
