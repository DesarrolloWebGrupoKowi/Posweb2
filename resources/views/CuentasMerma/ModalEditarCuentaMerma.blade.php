<!-- Modal Editar Cuenta Merma -->
<div
    class="modal fade"
    id="ModalEditarCuentaMerma{{ $cuentaMerma->IdCatCuentaMerma }}"
    tabindex="-1"
    aria-labelledby="ModalEditarCuentaMermaLabel{{ $cuentaMerma->IdCatCuentaMerma }}"
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
                    id="ModalEditarCuentaMermaLabel{{ $cuentaMerma->IdCatCuentaMerma }}"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
                        >
                            <i class="fa fa-pencil"></i>
                        </div>
                        <span>Ver Cuenta Merma</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="/EditarCuentaMerma/{{ $idTipoMerma }}"
                    method="POST"
                >
                    @csrf

                    <!-- Información del tipo de merma -->
                    <div
                        class="d-flex align-items-center mb-4 gap-3 pb-3"
                        style="border-bottom: 1px solid #e2e8f0;"
                    >
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="background: #eff6ff; width: 40px; height: 40px;"
                        >
                            <i
                                class="fa fa-tag"
                                style="color: #3b82f6; font-size: 1.1rem;"
                            ></i>
                        </div>
                        <div>
                            <span style="font-weight: 600; color: #0f172a; font-size: 0.95rem;">
                                {{ $cuentaMerma->NomTipoMerma }}
                            </span>
                            <br>
                            <span style="color: #64748b; font-size: 0.8rem;">
                                Tipo de Merma
                            </span>
                        </div>
                    </div>

                    <!-- Fila 1: Libro, Cuenta, Subcuenta -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label
                                for="libro{{ $cuentaMerma->IdCatCuentaMerma }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Libro <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                name="libro"
                                id="libro{{ $cuentaMerma->IdCatCuentaMerma }}"
                                placeholder="Libro"
                                value="{{ $cuentaMerma->Libro }}"
                                tabindex="1"
                                required
                            >
                        </div>
                        <div class="col-md-4 mb-3">
                            <label
                                for="cuenta{{ $cuentaMerma->IdCatCuentaMerma }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Cuenta <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                name="cuenta"
                                id="cuenta{{ $cuentaMerma->IdCatCuentaMerma }}"
                                placeholder="Cuenta"
                                value="{{ $cuentaMerma->Cuenta }}"
                                tabindex="2"
                                required
                            >
                        </div>
                        <div class="col-md-4 mb-3">
                            <label
                                for="subCuenta{{ $cuentaMerma->IdCatCuentaMerma }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Subcuenta <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                name="subCuenta"
                                id="subCuenta{{ $cuentaMerma->IdCatCuentaMerma }}"
                                placeholder="Subcuenta"
                                value="{{ $cuentaMerma->SubCuenta }}"
                                tabindex="3"
                                required
                            >
                        </div>
                    </div>

                    <!-- Fila 2: Intercosto, Futuro -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label
                                for="intercosto{{ $cuentaMerma->IdCatCuentaMerma }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Intercosto <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                name="intercosto"
                                id="intercosto{{ $cuentaMerma->IdCatCuentaMerma }}"
                                placeholder="Intercosto"
                                value="{{ $cuentaMerma->InterCosto }}"
                                tabindex="4"
                                required
                            >
                        </div>
                        <div class="col-md-4 mb-3">
                            <label
                                for="futuro{{ $cuentaMerma->IdCatCuentaMerma }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Futuro <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                name="futuro"
                                id="futuro{{ $cuentaMerma->IdCatCuentaMerma }}"
                                placeholder="Futuro"
                                value="{{ $cuentaMerma->Futuro }}"
                                tabindex="5"
                                required
                            >
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
                    <i class="fa fa-times"></i>
                    Cerrar
                </button>
                {{-- <button
                    type="submit"
                    class="btn d-flex align-items-center gap-1"
                    style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    onmouseover="this.style.background='linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.3)'"
                    onmouseout="this.style.background='linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                >
                    <i class="fa fa-pencil"></i>
                    Guardar Cambios
                </button> --}}
                </form>
            </div>
        </div>
    </div>
</div>
