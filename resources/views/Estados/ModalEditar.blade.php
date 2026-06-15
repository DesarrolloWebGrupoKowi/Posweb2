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
                style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);"
            >
                <h5
                    class="mb-0 text-white"
                    style="font-weight: 600; font-size: 1.1rem;"
                    id="ModalEditarLabel{{ $estado->IdEstado }}"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
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
                        style="border-bottom: 1px solid #e2e8f0;"
                    >
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="background: #f1f5f9; width: 40px; height: 40px;"
                        >
                            <i
                                class="fa fa-map-marker"
                                style="color: #64748b; font-size: 1.2rem;"
                            ></i>
                        </div>
                        <div>
                            <span style="font-weight: 600; color: #0f172a; font-size: 0.95rem;">
                                {{ $estado->NomEstado }}
                            </span>
                        </div>
                    </div>

                    <!-- Estatus -->
                    <div class="mb-3">
                        <label
                            for="Status{{ $estado->IdEstado }}"
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            Estatus <span style="color: #ef4444;">*</span>
                        </label>
                        <select
                            name="Status"
                            id="Status{{ $estado->IdEstado }}"
                            class="form-select"
                            style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
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
                    style="background: #f1f5f9; color: #475569; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    onmouseover="this.style.background='#e2e8f0'; this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.background='#f1f5f9'; this.style.transform='translateY(0)'"
                >
                    <i class="fa fa-times"></i>
                    Cerrar
                </button>
                <button
                    type="submit"
                    class="btn d-flex align-items-center gap-1"
                    style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    onmouseover="this.style.background='linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.3)'"
                    onmouseout="this.style.background='linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                >
                    <i class="fa fa-pencil"></i>
                    Guardar Cambios
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
