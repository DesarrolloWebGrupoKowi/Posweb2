<!-- Modal Editar Ciudad -->
<div
    class="modal fade"
    id="ModalEditar{{ $ciudad->IdCiudad }}"
    tabindex="-1"
    aria-labelledby="ModalEditarLabel{{ $ciudad->IdCiudad }}"
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
                    id="ModalEditarLabel{{ $ciudad->IdCiudad }}"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: var(--card-header-icon-bg); width: 32px; height: 32px;"
                        >
                            <i class="fa fa-building"></i>
                        </div>
                        <span>Editar Ciudad</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="/EditarCiudad/{{ $ciudad->IdCiudad }}"
                    method="POST"
                >
                    @csrf

                    <!-- Información de la ciudad -->
                    <div
                        class="d-flex align-items-center mb-4 gap-3 pb-3"
                        style="border-bottom: 1px solid var(--border-light);"
                    >
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="background: var(--bg-subtle); width: 40px; height: 40px;"
                        >
                            <i
                                class="fa fa-building"
                                style="color: var(--text-secondary); font-size: 1.2rem;"
                            ></i>
                        </div>
                        <div>
                            <span style="font-weight: 600; color: var(--text-primary); font-size: 0.95rem;">
                                {{ $ciudad->NomCiudad }}
                            </span>
                            <br>
                            <span style="color: var(--text-secondary); font-size: 0.8rem;">
                                ID: {{ $ciudad->IdCiudad }} | Estado: {{ $ciudad->NomEstado }}
                            </span>
                        </div>
                    </div>

                    <!-- Nombre de Ciudad -->
                    <div class="mb-3">
                        <label
                            for="NomCiudad{{ $ciudad->IdCiudad }}"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Nombre de Ciudad <span style="color: var(--danger-color);">*</span>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: var(--bg-light); border: 1px solid var(--border-input); color: var(--text-secondary); border-radius: 8px 0 0 8px;"
                            >
                                <i class="fa fa-font"></i>
                            </span>
                            <input
                                type="text"
                                id="NomCiudad{{ $ciudad->IdCiudad }}"
                                name="NomCiudad"
                                class="form-control border-start-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                tabindex="1"
                                value="{{ $ciudad->NomCiudad }}"
                                onkeyup="mayusculas(this)"
                                required
                            >
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-NomCiudad{{ $ciudad->IdCiudad }}"
                            style="font-size: 0.78rem; color: var(--danger-color);"
                        ></div>
                    </div>

                    <!-- Estado -->
                    <div class="mb-3">
                        <label
                            for="IdEstado{{ $ciudad->IdCiudad }}"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Estado <span style="color: var(--danger-color);">*</span>
                        </label>
                        <select
                            name="IdEstado"
                            id="IdEstado{{ $ciudad->IdCiudad }}"
                            class="form-select"
                            style="border: 1px solid var(--border-input); border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                            tabindex="2"
                        >
                            @foreach ($estados as $estado)
                                <option
                                    {{ $estado->IdEstado == $ciudad->IdEstado ? 'selected' : '' }}
                                    value="{{ $estado->IdEstado }}"
                                >
                                    {{ $estado->NomEstado }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Estatus -->
                    <div class="mb-3">
                        <label
                            for="Status{{ $ciudad->IdCiudad }}"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Estatus
                        </label>
                        <select
                            name="Status"
                            id="Status{{ $ciudad->IdCiudad }}"
                            class="form-select"
                            style="border: 1px solid var(--border-input); border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                            tabindex="3"
                        >
                            <option
                                {{ $ciudad->Status == 0 ? 'selected' : '' }}
                                value="0"
                            >Activo</option>
                            <option
                                {{ $ciudad->Status == 1 ? 'selected' : '' }}
                                value="1"
                            >Inactivo</option>
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
                >
                    <i class="fa fa-times"></i>
                    Cerrar
                </button>
                <button
                    type="submit"
                    class="btn d-flex align-items-center gap-1"
                    style="background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                >
                    <i class="fa fa-pencil"></i>
                    Guardar Cambios
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
