<!-- Modal Agregar Lista de Precio -->
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
                style="background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);"
            >
                <h5
                    class="mb-0 text-white"
                    style="font-weight: 600; font-size: 1.1rem;"
                    id="ModalAgregarLabel"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: var(--card-header-icon-bg); width: 32px; height: 32px;"
                        >
                            <i class="bi bi-tags"></i>
                        </div>
                        <span>Agregar Lista de Precio</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="/CrearListaPrecio"
                    method="POST"
                >
                    @csrf

                    <!-- Nombre Lista de Precio -->
                    <div class="mb-3">
                        <label
                            for="NomListaPrecio"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Lista de Precio <span style="color: var(--danger-color);">*</span>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-secondary); border-radius: 8px 0 0 8px;"
                            >
                                <i class="bi bi-tag"></i>
                            </span>
                            <input
                                type="text"
                                id="NomListaPrecio"
                                name="NomListaPrecio"
                                class="form-control border-start-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                onkeyup="mayusculas(this)"
                                placeholder="Nombre de la lista de precio"
                                tabindex="1"
                                required
                            >
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-NomListaPrecio"
                            style="font-size: 0.78rem; color: var(--danger-color);"
                        ></div>
                    </div>

                    <!-- Fila: Peso Mínimo y Peso Máximo -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label
                                for="PesoMinimo"
                                class="form-label fw-medium mb-2"
                                style="color: var(--text-secondary); font-size: 0.85rem;"
                            >
                                Peso Mínimo (kg) <span style="color: var(--danger-color);">*</span>
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-secondary); border-radius: 8px 0 0 8px;"
                                >
                                    <i class="bi bi-weight"></i>
                                </span>
                                <input
                                    class="form-control border-start-0"
                                    style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    type="number"
                                    name="PesoMinimo"
                                    id="PesoMinimo"
                                    step="0.01"
                                    placeholder="0.00"
                                    tabindex="2"
                                    required
                                >
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label
                                for="PesoMaximo"
                                class="form-label fw-medium mb-2"
                                style="color: var(--text-secondary); font-size: 0.85rem;"
                            >
                                Peso Máximo (kg) <span style="color: var(--danger-color);">*</span>
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-secondary); border-radius: 8px 0 0 8px;"
                                >
                                    <i class="bi bi-weight"></i>
                                </span>
                                <input
                                    class="form-control border-start-0"
                                    style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    type="number"
                                    name="PesoMaximo"
                                    id="PesoMaximo"
                                    step="0.01"
                                    placeholder="0.00"
                                    tabindex="3"
                                    required
                                >
                            </div>
                        </div>
                    </div>

                    <!-- IVA -->
                    <div class="mb-3">
                        <label
                            for="PorcentajeIva"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            IVA (%) <span style="color: var(--danger-color);">*</span>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-secondary); border-radius: 8px 0 0 8px;"
                            >
                                <i class="bi bi-percent"></i>
                            </span>
                            <input
                                class="form-control border-start-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                type="number"
                                name="PorcentajeIva"
                                id="PorcentajeIva"
                                step="0.01"
                                placeholder="0.00"
                                tabindex="4"
                                required
                            >
                        </div>
                    </div>

                    <!-- Línea divisoria -->
                    <hr style="border-color: var(--border-light); margin: 1.25rem 0;">

                    <!-- Checkbox: Crear a partir de existente -->
                    <div class="mb-3">
                        <div
                            class="form-check rounded p-3"
                            style="background: var(--bg-light); border: 1px solid var(--border-input); border-radius: 8px;"
                        >
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="checkExistente"
                                id="checkExistente"
                                style="cursor: pointer; border-color: var(--border-medium);"
                                tabindex="5"
                            >
                            <label
                                class="form-check-label"
                                for="checkExistente"
                                style="color: var(--text-secondary); font-size: 0.85rem; font-weight: 500; cursor: pointer;"
                            >
                                <i class="bi bi-files me-1"></i>Crear a partir de una lista de precios existente
                            </label>
                        </div>
                    </div>

                    <!-- Select: Lista de Precio Existente -->
                    <div
                        id="divSelectListaPrecio"
                        class="mb-3"
                        style="display: none;"
                    >
                        <label
                            for="selectListaPrecio"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Seleccionar Lista de Precio Base
                        </label>
                        <select
                            class="form-select"
                            name="selectListaPrecio"
                            id="selectListaPrecio"
                            style="border: 1px solid var(--border-input); border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                            tabindex="6"
                        >
                            <option value="">-- Selecciona una lista --</option>
                            @foreach ($selectListaPrecio as $listaPrecio)
                                <option value="{{ $listaPrecio->IdListaPrecio }}">
                                    {{ $listaPrecio->NomListaPrecio }} (IVA: {{ $listaPrecio->PorcentajeIva }}%)
                                </option>
                            @endforeach
                        </select>
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
                <button
                    type="submit"
                    class="btn d-flex align-items-center gap-1"
                    style="background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    onmouseover="this.style.background='linear-gradient(135deg, var(--gradient-end) 0%, var(--gradient-start) 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px var(--btn-gradient-shadow)'"
                    onmouseout="this.style.background='linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                >
                    <i class="bi bi-check-lg"></i> Crear
                </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('checkExistente').addEventListener('change',
        function() {
            const
                divSelect =
                document.getElementById('divSelectListaPrecio');
            if (this.checked) {
                divSelect.style.display =
                    'block';
            } else {
                divSelect.style.display =
                    'none';
            }
        });
</script>
