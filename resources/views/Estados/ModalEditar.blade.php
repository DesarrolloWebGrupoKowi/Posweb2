<!-- Modal Editar Estado -->
<div
    class="modal fade"
    id="ModalEditar{{ $estado->IdEstado }}"
    tabindex="-1"
    aria-labelledby="ModalEditarLabel{{ $estado->IdEstado }}"
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
                    id="ModalEditarLabel{{ $estado->IdEstado }}"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: var(--card-header-icon-bg); width: 32px; height: 32px;"
                        >
                            <i class="fa fa-map-marker"></i>
                        </div>
                        <span>Editar Estado</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="EditarEstado/{{ $estado->IdEstado }}"
                    method="POST"
                >
                    @csrf

                    <!-- Información del estado -->
                    <div
                        class="d-flex align-items-center mb-4 gap-3 pb-3"
                        style="border-bottom: 1px solid var(--border-light);"
                    >
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="background: var(--bg-subtle); width: 40px; height: 40px;"
                        >
                            <i
                                class="fa fa-map-marker"
                                style="color: var(--text-secondary); font-size: 1.2rem;"
                            ></i>
                        </div>
                        <div>
                            <span style="font-weight: 600; color: var(--text-primary); font-size: 0.95rem;">
                                {{ $estado->NomEstado }}
                            </span>
                        </div>
                    </div>

                    <!-- Estatus -->
                    <div class="mb-3">
                        <label
                            for="Status{{ $estado->IdEstado }}"
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            Estatus <span style="color: var(--danger-color);">*</span>
                        </label>
                        <select
                            name="Status"
                            id="Status{{ $estado->IdEstado }}"
                            class="form-select"
                            style="border: 1px solid var(--border-input); border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                            tabindex="1"
                        >
                            <option
                                {{ $estado->Status == 0 ? 'selected' : '' }}
                                value="0"
                            >Activo</option>
                            <option
                                {{ $estado->Status == 1 ? 'selected' : '' }}
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
