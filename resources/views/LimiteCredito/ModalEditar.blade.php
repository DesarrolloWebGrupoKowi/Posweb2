<!-- Modal Editar Límite Crédito -->
<div
    class="modal fade"
    id="ModalEditar{{ $lCredito->IdCatLimiteCredito }}"
    tabindex="-1"
    aria-labelledby="ModalEditarLabel{{ $lCredito->IdCatLimiteCredito }}"
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
                    id="ModalEditarLabel{{ $lCredito->IdCatLimiteCredito }}"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
                        >
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <span>Editar Tipo Nómina: {{ $lCredito->NomTipoNomina }}</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="/EditarLimiteCredito/{{ $lCredito->TipoNomina }}"
                    method="POST"
                >
                    @csrf

                    <!-- Fila: Límite Crédito y Ventas Diarias -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label
                                for="limiteCredito{{ $lCredito->IdCatLimiteCredito }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Límite Crédito <span style="color: #ef4444;">*</span>
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                >
                                    <i class="bi bi-currency-dollar"></i>
                                </span>
                                <input
                                    type="number"
                                    class="form-control border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    name="limiteCredito"
                                    id="limiteCredito{{ $lCredito->IdCatLimiteCredito }}"
                                    value="{{ $lCredito->Limite }}"
                                    tabindex="1"
                                    required
                                >
                            </div>
                            <div
                                class="form-text mt-1"
                                id="error-limiteCredito{{ $lCredito->IdCatLimiteCredito }}"
                                style="font-size: 0.78rem; color: #ef4444;"
                            ></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label
                                for="totalVentasDiaria{{ $lCredito->IdCatLimiteCredito }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Ventas Diarias <span style="color: #ef4444;">*</span>
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                >
                                    <i class="bi bi-graph-up"></i>
                                </span>
                                <input
                                    type="number"
                                    class="form-control border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    name="totalVentasDiaria"
                                    id="totalVentasDiaria{{ $lCredito->IdCatLimiteCredito }}"
                                    value="{{ $lCredito->TotalVentaDiaria }}"
                                    tabindex="2"
                                    required
                                >
                            </div>
                            <div
                                class="form-text mt-1"
                                id="error-totalVentasDiaria{{ $lCredito->IdCatLimiteCredito }}"
                                style="font-size: 0.78rem; color: #ef4444;"
                            ></div>
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
                    <i class="bi bi-x"></i>
                    Cerrar
                </button>
                <button
                    type="submit"
                    class="btn d-flex align-items-center gap-1"
                    style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    onmouseover="this.style.background='linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.3)'"
                    onmouseout="this.style.background='linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                >
                    <i class="bi bi-pencil"></i>
                    Guardar Cambios
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
