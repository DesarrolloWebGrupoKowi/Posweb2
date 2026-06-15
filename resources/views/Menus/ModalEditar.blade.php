<!-- Modal Editar Menú Posweb -->
<div
    class="modal fade"
    id="ModalEditar{{ $menuPosweb->cmpIdMenu }}"
    tabindex="-1"
    aria-labelledby="ModalEditarLabel{{ $menuPosweb->cmpIdMenu }}"
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
                    id="ModalEditarLabel{{ $menuPosweb->cmpIdMenu }}"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
                        >
                            <i class="fa fa-bars"></i>
                        </div>
                        <span>Editar Menú</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="EditarMenu/{{ $menuPosweb->cmpIdMenu }}"
                    method="POST"
                >
                    @csrf

                    <!-- Fila 1: Nombre y Tipo Menú -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label
                                for="NomMenu{{ $menuPosweb->cmpIdMenu }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Nombre <span style="color: #ef4444;">*</span>
                            </label>
                            <input
                                type="text"
                                id="NomMenu{{ $menuPosweb->cmpIdMenu }}"
                                name="NomMenu"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                tabindex="1"
                                value="{{ $menuPosweb->cmpNomMenu }}"
                                onkeyup="mayusculas(this)"
                                required
                            >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label
                                for="IdTipoMenu{{ $menuPosweb->cmpIdMenu }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Tipo de Menú <span style="color: #ef4444;">*</span>
                            </label>
                            <select
                                class="form-select"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                name="IdTipoMenu"
                                id="IdTipoMenu{{ $menuPosweb->cmpIdMenu }}"
                                tabindex="2"
                            >
                                @foreach ($tipoMenus as $tipoMenu)
                                    <option
                                        {{ $menuPosweb->cmpIdTipoMenu == $tipoMenu->IdTipoMenu ? 'selected' : '' }}
                                        value="{{ $tipoMenu->IdTipoMenu }}"
                                    >
                                        {{ $tipoMenu->NomTipoMenu }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Link -->
                    <div class="mb-3">
                        <label
                            for="Link{{ $menuPosweb->cmpIdMenu }}"
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            Link <span style="color: #ef4444;">*</span>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                            >
                                <i class="fa fa-link"></i>
                            </span>
                            <input
                                type="text"
                                id="Link{{ $menuPosweb->cmpIdMenu }}"
                                name="Link"
                                class="form-control border-start-0"
                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                tabindex="3"
                                value="{{ $menuPosweb->cmpLink }}"
                                required
                            >
                        </div>
                    </div>

                    <!-- Fila 2: Icono y Background Color -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label
                                for="Icono{{ $menuPosweb->cmpIdMenu }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Icono <span style="color: #ef4444;">*</span>
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                >
                                    <i class="fa fa-star"></i>
                                </span>
                                <input
                                    class="form-control border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    type="text"
                                    name="Icono"
                                    id="Icono{{ $menuPosweb->cmpIdMenu }}"
                                    placeholder="fa fa-icons"
                                    value="{{ $menuPosweb->cmpIcono }}"
                                    tabindex="4"
                                    required
                                >
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label
                                for="BgColor{{ $menuPosweb->cmpIdMenu }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Background Color <span style="color: #ef4444;">*</span>
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                >
                                    <i class="fa fa-paint-brush"></i>
                                </span>
                                <input
                                    class="form-control border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    type="text"
                                    name="BgColor"
                                    id="BgColor{{ $menuPosweb->cmpIdMenu }}"
                                    placeholder="bg-orange"
                                    value="{{ $menuPosweb->cmpBgColor }}"
                                    tabindex="5"
                                    required
                                >
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
