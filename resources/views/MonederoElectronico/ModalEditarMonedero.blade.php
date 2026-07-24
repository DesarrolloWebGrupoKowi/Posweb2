<!-- Modal Editar Monedero Electrónico -->
<div
    class="modal fade"
    id="ModalEditar{{ $monedero->IdCatMonederoElectronico }}"
    tabindex="-1"
    aria-labelledby="ModalEditar{{ $monedero->IdCatMonederoElectronico }}Label"
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
                    id="ModalEditar{{ $monedero->IdCatMonederoElectronico }}Label"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
                        >
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <span>Editar Monedero Electrónico</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <form
                action="/EditarMonederoElectronico/{{ $monedero->IdCatMonederoElectronico }}"
                method="POST"
            >
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Máximo Acumulado ($)
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                >
                                    <i class="bi bi-cash-stack"></i>
                                </span>
                                <input
                                    type="number"
                                    class="form-control border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    name="maximoAcumulado"
                                    value="{{ $monedero->MaximoAcumulado }}"
                                    step="0.01"
                                >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Múltiplo ($)
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                >
                                    <i class="bi bi-stack"></i>
                                </span>
                                <input
                                    type="number"
                                    class="form-control border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    name="multiplo"
                                    value="{{ $monedero->MonederoMultiplo }}"
                                    step="0.01"
                                >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Pesos por Múltiplo ($)
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
                                    name="pesosPorMultiplo"
                                    value="{{ $monedero->PesosPorMultiplo }}"
                                    step="0.01"
                                >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Vigencia (días)
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                >
                                    <i class="bi bi-calendar-check"></i>
                                </span>
                                <input
                                    type="number"
                                    class="form-control border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    name="vigencia"
                                    value="{{ $monedero->VigenciaMonedero }}"
                                >
                            </div>
                        </div>
                        <div class="col-12">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Grupo Funcional
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                >
                                    <i class="bi bi-collection"></i>
                                </span>
                                <select
                                    name="idGrupo"
                                    class="form-select border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                >
                                    @foreach ($grupos as $grupo)
                                        <option
                                            {{ $grupo->IdGrupo == $monedero->IdGrupo ? 'selected' : '' }}
                                            value="{{ $grupo->IdGrupo }}"
                                        >
                                            {{ $grupo->NomGrupo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
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
                        <i class="bi bi-x-lg"></i> Cerrar
                    </button>
                    <button
                        type="submit"
                        class="btn d-flex align-items-center gap-1"
                        style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                        onmouseover="this.style.background='linear-gradient(135deg, #0f172a 0%, #1e293b 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(30, 41, 59, 0.3)'"
                        onmouseout="this.style.background='linear-gradient(135deg, #1e293b 0%, #334155 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                    >
                        <i class="bi bi-floppy"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
