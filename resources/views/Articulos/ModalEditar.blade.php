<!-- Modal Editar Artículo -->
<div
    class="modal fade"
    id="ModalEditar-{{ $articulo->CodArticulo }}"
    tabindex="-1"
    aria-labelledby="ModalEditarLabel-{{ $articulo->CodArticulo }}"
    aria-hidden="true"
>
    <div
        class="modal-dialog modal-lg"
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
                    id="ModalEditarLabel-{{ $articulo->CodArticulo }}"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
                        >
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <span>Editar Artículo: {{ $articulo->NomArticulo }}</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="/EditarArticulo/{{ $articulo->CodArticulo }}"
                    method="POST"
                >
                    @csrf

                    <!-- Información del artículo -->
                    <div class="row mb-3">
                        <div class="col-md-6 mb-md-0 mb-3">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Nombre
                            </label>
                            <div
                                class="rounded p-3"
                                style="background: #f8fafc; border: 1px solid #e2e8f0; font-weight: 600; color: #0f172a; font-size: 0.9rem;"
                            >
                                {{ $articulo->NomArticulo }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Código
                            </label>
                            <div
                                class="rounded p-3"
                                style="background: #f8fafc; border: 1px solid #e2e8f0; font-weight: 600; color: #0f172a; font-size: 0.9rem;"
                            >
                                {{ $articulo->CodArticulo }}
                            </div>
                        </div>
                    </div>

                    <!-- Amece -->
                    <div class="mb-3">
                        <label
                            for="txtCodAmece{{ $articulo->CodArticulo }}"
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            Amece
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                            >
                                <i class="bi bi-qr-code"></i>
                            </span>
                            <input
                                type="text"
                                id="txtCodAmece{{ $articulo->CodArticulo }}"
                                name="txtCodAmece"
                                class="form-control border-start-0"
                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                placeholder="Amece"
                                value="{{ $articulo->Amece }}"
                                maxlength="13"
                                tabindex="1"
                            >
                        </div>
                    </div>

                    <!-- Fila 1: UOM, Peso, Tercero -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label
                                for="txtUOM{{ $articulo->CodArticulo }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Unidad de Medida
                            </label>
                            <select
                                name="txtUOM"
                                id="txtUOM{{ $articulo->CodArticulo }}"
                                class="form-select"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                tabindex="2"
                            >
                                <option
                                    {{ $articulo->UOM == 'KG' ? 'selected' : '' }}
                                    value="KG"
                                >Kilogramo</option>
                                <option
                                    {{ $articulo->UOM == 'LT' ? 'selected' : '' }}
                                    value="LT"
                                >Litro</option>
                                <option
                                    {{ $articulo->UOM == 'PZA' ? 'selected' : '' }}
                                    value="PZA"
                                >Pieza</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label
                                for="txtPeso{{ $articulo->CodArticulo }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Peso
                            </label>
                            <input
                                type="text"
                                id="txtPeso{{ $articulo->CodArticulo }}"
                                name="txtPeso"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                placeholder="Peso"
                                value="{{ $articulo->Peso }}"
                                tabindex="3"
                            >
                        </div>
                        <div class="col-md-4 mb-3">
                            <label
                                for="txtTercero{{ $articulo->CodArticulo }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Tercero
                            </label>
                            <select
                                name="txtTercero"
                                id="txtTercero{{ $articulo->CodArticulo }}"
                                class="form-select"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                tabindex="4"
                            >
                                <option
                                    {{ $articulo->Tercero == 0 ? 'selected' : '' }}
                                    value="0"
                                >Si</option>
                                <option
                                    {{ $articulo->Tercero == 1 ? 'selected' : '' }}
                                    value="1"
                                >No</option>
                            </select>
                        </div>
                    </div>

                    <!-- Fila 2: Precio Recorte, Factor, Tipo -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label
                                for="txtPrecioRecorte{{ $articulo->CodArticulo }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Precio Recorte
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
                                    id="txtPrecioRecorte{{ $articulo->CodArticulo }}"
                                    name="txtPrecioRecorte"
                                    class="form-control border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem;"
                                    placeholder="Precio Recorte"
                                    value="{{ $articulo->PrecioRecorte }}"
                                    tabindex="5"
                                >
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label
                                for="txtFactor{{ $articulo->CodArticulo }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Factor
                            </label>
                            <input
                                type="number"
                                id="txtFactor{{ $articulo->CodArticulo }}"
                                name="txtFactor"
                                class="form-control"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem;"
                                step="any"
                                placeholder="Factor"
                                value="{{ $articulo->Factor }}"
                                tabindex="6"
                            >
                        </div>
                        <div class="col-md-4 mb-3">
                            <label
                                for="idTipoArticulo{{ $articulo->CodArticulo }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Tipo
                            </label>
                            <select
                                name="idTipoArticulo"
                                id="idTipoArticulo{{ $articulo->CodArticulo }}"
                                class="form-select"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                tabindex="7"
                            >
                                <option
                                    {{ empty($articulo->IdTipoArticulo) ? 'selected' : '' }}
                                    value=""
                                >SIN TIPO</option>
                                @foreach ($tiposArticulo as $tipoArticulo)
                                    <option
                                        {{ $articulo->IdTipoArticulo == $tipoArticulo->IdTipoArticulo ? 'selected' : '' }}
                                        value="{{ $tipoArticulo->IdTipoArticulo }}"
                                    >
                                        {{ $tipoArticulo->NomTipoArticulo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Fila 3: Familia, Grupo, IVA -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label
                                for="txtIdFamilia{{ $articulo->CodArticulo }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Familia
                            </label>
                            <select
                                name="txtIdFamilia"
                                id="txtIdFamilia{{ $articulo->CodArticulo }}"
                                class="form-select"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                tabindex="8"
                            >
                                @foreach ($familias as $familia)
                                    <option
                                        {{ $articulo->IdFamilia == $familia->IdFamilia ? 'selected' : '' }}
                                        value="{{ $familia->IdFamilia }}"
                                    >
                                        {{ $familia->NomFamilia }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label
                                for="txtIdGrupo{{ $articulo->CodArticulo }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Grupo
                            </label>
                            <select
                                name="txtIdGrupo"
                                id="txtIdGrupo{{ $articulo->CodArticulo }}"
                                class="form-select"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                tabindex="9"
                            >
                                @foreach ($grupos as $grupo)
                                    <option
                                        {{ $articulo->IdGrupo == $grupo->IdGrupo ? 'selected' : '' }}
                                        value="{{ $grupo->IdGrupo }}"
                                    >
                                        {{ $grupo->NomGrupo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label
                                for="txtIva{{ $articulo->CodArticulo }}"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                IVA
                            </label>
                            <select
                                name="txtIva"
                                id="txtIva{{ $articulo->CodArticulo }}"
                                class="form-select"
                                style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                tabindex="10"
                            >
                                <option
                                    {{ $articulo->Iva == 0 ? 'selected' : '' }}
                                    value="0"
                                >Si</option>
                                <option
                                    {{ $articulo->Iva == 1 ? 'selected' : '' }}
                                    value="1"
                                >No</option>
                            </select>
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
                    onmouseover="this.style.background='linear-gradient(135deg, #0f172a 0%, #1e293b 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(30, 41, 59, 0.3)'"
                    onmouseout="this.style.background='linear-gradient(135deg, #1e293b 0%, #334155 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                >
                    <i class="bi bi-pencil"></i>
                    Guardar Cambios
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
