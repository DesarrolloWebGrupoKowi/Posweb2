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
                style="background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);"
            >
                <h5
                    class="mb-0 text-white"
                    style="font-weight: 600; font-size: 1.1rem;"
                    id="ModalEditarCuentaMermaLabel{{ $cuentaMerma->IdCatCuentaMerma }}"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: var(--card-header-icon-bg); width: 32px; height: 32px;"
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
                        style="border-bottom: 1px solid var(--border-light);"
                    >
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="background: var(--bg-subtle); width: 40px; height: 40px;"
                        >
                            <i
                                class="fa fa-tag"
                                style="color: var(--text-secondary); font-size: 1.1rem;"
                            ></i>
                        </div>
                        <div>
                            <span style="font-weight: 600; color: var(--text-primary); font-size: 0.95rem;">
                                {{ $cuentaMerma->NomTipoMerma }}
                            </span>
                            <br>
                            <span style="color: var(--text-secondary); font-size: 0.8rem;">
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
                                style="color: var(--text-secondary); font-size: 0.85rem;"
                            >
                                Libro <span style="color: var(--danger-color);">*</span>
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                style="border: 1px solid var(--border-input); border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
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
                                style="color: var(--text-secondary); font-size: 0.85rem;"
                            >
                                Cuenta <span style="color: var(--danger-color);">*</span>
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                style="border: 1px solid var(--border-input); border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
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
                                style="color: var(--text-secondary); font-size: 0.85rem;"
                            >
                                Subcuenta <span style="color: var(--danger-color);">*</span>
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                style="border: 1px solid var(--border-input); border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
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
                                style="color: var(--text-secondary); font-size: 0.85rem;"
                            >
                                Intercosto <span style="color: var(--danger-color);">*</span>
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                style="border: 1px solid var(--border-input); border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
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
                                style="color: var(--text-secondary); font-size: 0.85rem;"
                            >
                                Futuro <span style="color: var(--danger-color);">*</span>
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                style="border: 1px solid var(--border-input); border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
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
                    style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                >
                    <i class="fa fa-times"></i>
                    Cerrar
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
