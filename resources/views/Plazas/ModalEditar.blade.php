<!-- Modal Editar Plaza -->
<div
    class="modal fade"
    id="ModalEditar{{ $plaza->IdPlaza }}"
    tabindex="-1"
    aria-labelledby="ModalEditarLabel{{ $plaza->IdPlaza }}"
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
                    id="ModalEditarLabel{{ $plaza->IdPlaza }}"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: var(--card-header-icon-bg); width: 32px; height: 32px;"
                        >
                            <i class="fa fa-building"></i>
                        </div>
                        <span>Editar Plaza</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="/EditarPlaza/{{ $plaza->IdPlaza }}"
                    method="POST"
                >
                    @csrf

                    <!-- Nombre de la Plaza -->
                    <div class="mb-3">
                        <label
                            for="NomPlaza{{ $plaza->IdPlaza }}"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Nombre de la Plaza <span style="color: var(--danger-color);">*</span>
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
                                id="NomPlaza{{ $plaza->IdPlaza }}"
                                name="NomPlaza"
                                class="form-control border-start-0"
                                style="border: 1px solid var(--border-input); border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                tabindex="1"
                                value="{{ $plaza->NomPlaza }}"
                                onkeyup="mayusculas(this)"
                                required
                            >
                        </div>
                        <div
                            class="form-text mt-1"
                            id="error-NomPlaza{{ $plaza->IdPlaza }}"
                            style="font-size: 0.78rem; color: var(--danger-color);"
                        ></div>
                    </div>

                    <!-- Ciudad -->
                    <div class="mb-3">
                        <label
                            for="IdCiudad{{ $plaza->IdPlaza }}"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Ciudad <span style="color: var(--danger-color);">*</span>
                        </label>
                        <select
                            name="IdCiudad"
                            id="IdCiudad{{ $plaza->IdPlaza }}"
                            class="form-select"
                            style="border: 1px solid var(--border-input); border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                            tabindex="2"
                        >
                            @foreach ($ciudades as $ciudad)
                                <option
                                    {{ $ciudad->IdCiudad == $plaza->IdCiudad ? 'selected' : '' }}
                                    value="{{ $ciudad->IdCiudad }}"
                                >
                                    {{ $ciudad->NomCiudad }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Estatus -->
                    <div class="mb-3">
                        <label
                            for="Status{{ $plaza->IdPlaza }}"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Estatus
                        </label>
                        <select
                            name="Status"
                            id="Status{{ $plaza->IdPlaza }}"
                            class="form-select"
                            style="border: 1px solid var(--border-input); border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                            tabindex="3"
                        >
                            <option
                                {{ $plaza->Status == 0 ? 'selected' : '' }}
                                value="0"
                            >Activa</option>
                            <option
                                {{ $plaza->Status == 1 ? 'selected' : '' }}
                                value="1"
                            >Inactiva</option>
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
