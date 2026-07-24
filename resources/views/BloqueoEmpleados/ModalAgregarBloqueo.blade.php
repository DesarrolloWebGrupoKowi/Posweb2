<!-- Modal Agregar Bloqueo -->
<div
    class="modal fade"
    id="AgregarBloqueo"
    tabindex="-1"
    aria-labelledby="AgregarBloqueoLabel"
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
                    id="AgregarBloqueoLabel"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
                        >
                            <i class="bi bi-lock"></i>
                        </div>
                        <span>Agregar Nuevo Bloqueo</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <form
                action="/AgregarBloqueoEmpleado"
                method="POST"
            >
                @csrf
                <div class="modal-body p-4">
                    <!-- Nómina -->
                    <div class="mb-3">
                        <label
                            for="numNominaEmpleado"
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            # Nómina
                        </label>
                        <div class="row g-2">
                            <div class="col">
                                <div class="input-group">
                                    <span
                                        class="input-group-text"
                                        style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                    >
                                        <i class="bi bi-hash"></i>
                                    </span>
                                    <input
                                        type="number"
                                        class="form-control border-start-0"
                                        style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                        id="numNominaEmpleado"
                                        placeholder="# Nómina"
                                        name="numNomina"
                                        required
                                    >
                                </div>
                            </div>
                            <div class="col-auto">
                                <button
                                    id="btnBuscarEmpleado"
                                    type="button"
                                    class="btn-modern btn-warning-modern"
                                >
                                    <i class="bi bi-search me-2"></i>Buscar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Resultado de búsqueda -->
                    <div
                        hidden
                        id="divNomEmpleado"
                        class="mb-3"
                    >
                        <div
                            class="py-3 text-center"
                            style="background: #f8fafc; border-radius: 8px;"
                        >
                            <span
                                id="nomEmpleadoFetch"
                                class="badge fs-6"
                            ></span>
                        </div>
                    </div>

                    <!-- Motivo de Bloqueo -->
                    <div
                        hidden
                        id="divMotivoBloqueo"
                        class="mb-0"
                    >
                        <label
                            for="motivoBloqueo"
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            Motivo de Bloqueo
                        </label>
                        <textarea
                            class="form-control"
                            style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                            name="motivoBloqueo"
                            id="motivoBloqueo"
                            cols="30"
                            rows="4"
                            placeholder="Escriba el motivo de bloqueo..."
                            required
                        ></textarea>
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
                        hidden
                        id="btnBloquear"
                        type="submit"
                        class="btn d-flex align-items-center gap-1"
                        style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                        onmouseover="this.style.background='linear-gradient(135deg, #0f172a 0%, #1e293b 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(30, 41, 59, 0.3)'"
                        onmouseout="this.style.background='linear-gradient(135deg, #1e293b 0%, #334155 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                    >
                        <i class="bi bi-lock"></i>
                        Bloquear Empleado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
