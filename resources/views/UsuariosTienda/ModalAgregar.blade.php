<!-- Modal Agregar Usuario Tienda -->
<div
    class="modal fade"
    id="ModalAgregar"
    tabindex="-1"
    aria-labelledby="ModalAgregarLabel"
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
                    id="ModalAgregarLabel"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
                        >
                            @include('components.icons.user')
                        </div>
                        <span>Agregar Usuario Tienda</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                @if (empty($usuarios))
                    <div class="py-5 text-center">
                        <div
                            class="d-flex align-items-center justify-content-center mx-auto mb-3"
                            style="width: 64px; height: 64px; background-color: #fef2f2; border-radius: 50%;"
                        >
                            <i
                                class="bi bi-exclamation-triangle fs-3"
                                style="color: #ef4444;"
                            ></i>
                        </div>
                        <h6 style="color: #0f172a;">No hay usuarios disponibles</h6>
                        <p
                            class="text-muted"
                            style="font-size: 0.85rem;"
                        >Todos los usuarios ya han sido asignados</p>
                    </div>
                @else
                    <form
                        action="/CrearUsuarioTienda"
                        method="POST"
                        id="formAgregarUsuarioTienda"
                    >
                        @csrf

                        <!-- Seleccionar Usuario -->
                        <div class="mb-3">
                            <label
                                for="IdUsuario"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Usuario
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                >
                                    @include('components.icons.user')
                                </span>
                                <select
                                    name="IdUsuario"
                                    id="IdUsuario"
                                    class="form-select border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                    required
                                >
                                    @foreach ($usuarios as $usuario)
                                        <option value="{{ $usuario->IdUsuario }}">{{ $usuario->NomUsuario }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Seleccionar Opción -->
                        <div class="mb-3">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Seleccione Opción
                            </label>

                            <div class="form-check mb-2">
                                <input
                                    class="form-check-input-modern"
                                    type="radio"
                                    name="radio"
                                    id="radioTodas"
                                    value="todas"
                                    onclick="Opciones()"
                                    checked
                                >
                                <label
                                    class="form-check-label"
                                    for="radioTodas"
                                    style="color: #475569; font-size: 0.85rem;"
                                >
                                    Todas las Tiendas y Plazas
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input
                                    class="form-check-input-modern"
                                    type="radio"
                                    name="radio"
                                    id="radioPlaza"
                                    value="plaza"
                                    onclick="Opciones()"
                                >
                                <label
                                    class="form-check-label"
                                    for="radioPlaza"
                                    style="color: #475569; font-size: 0.85rem;"
                                >
                                    Plaza específica
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input
                                    class="form-check-input-modern"
                                    type="radio"
                                    name="radio"
                                    id="radioTienda"
                                    value="tienda"
                                    onclick="Opciones()"
                                >
                                <label
                                    class="form-check-label"
                                    for="radioTienda"
                                    style="color: #475569; font-size: 0.85rem;"
                                >
                                    Tienda específica
                                </label>
                            </div>
                        </div>

                        <!-- Selector de Tienda -->
                        <div
                            class="mb-3"
                            id="divTienda"
                            style="display: none;"
                        >
                            <label
                                for="IdTienda"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Tienda
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                >
                                    @include('components.icons.store')
                                </span>
                                <select
                                    name="IdTienda"
                                    id="IdTienda"
                                    class="form-select border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                >
                                    <option value="">Seleccione una tienda</option>
                                    @foreach ($tiendas as $tienda)
                                        <option value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Selector de Plaza -->
                        <div
                            class="mb-3"
                            id="divPlaza"
                            style="display: none;"
                        >
                            <label
                                for="IdPlaza"
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Plaza
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px 0 0 8px;"
                                >
                                    @include('components.icons.building-plus')
                                </span>
                                <select
                                    name="IdPlaza"
                                    id="IdPlaza"
                                    class="form-select border-start-0"
                                    style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                                >
                                    <option value="">Seleccione una plaza</option>
                                    @foreach ($plazas as $plaza)
                                        <option value="{{ $plaza->IdPlaza }}">{{ $plaza->NomPlaza }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                @endif
            </div>

            <!-- Modal Footer -->
            @if (!empty($usuarios))
                <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                    <button
                        type="button"
                        class="btn d-flex align-items-center gap-1"
                        data-bs-dismiss="modal"
                        style="background: #f1f5f9; color: #475569; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                        onmouseover="this.style.background='#e2e8f0'; this.style.transform='translateY(-1px)'"
                        onmouseout="this.style.background='#f1f5f9'; this.style.transform='translateY(0)'"
                    >
                        @include('components.icons.x')
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        form="formAgregarUsuarioTienda"
                        class="btn d-flex align-items-center gap-1"
                        style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                        onmouseover="this.style.background='linear-gradient(135deg, #0f172a 0%, #1e293b 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(30, 41, 59, 0.3)'"
                        onmouseout="this.style.background='linear-gradient(135deg, #1e293b 0%, #334155 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                    >
                        @include('components.icons.send')
                        Asignar Usuario
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function Opciones() {
        const radioTodas = document.getElementById('radioTodas');
        const radioPlaza = document.getElementById('radioPlaza');
        const radioTienda = document.getElementById('radioTienda');
        const divTienda = document.getElementById('divTienda');
        const divPlaza = document.getElementById('divPlaza');
        const selectTienda = document.getElementById('IdTienda');
        const selectPlaza = document.getElementById('IdPlaza');

        if (radioTienda.checked) {
            divTienda.style.display = 'block';
            divPlaza.style.display = 'none';
            selectTienda.required = true;
            selectPlaza.required = false;
            selectPlaza.value = '';
        } else if (radioPlaza.checked) {
            divTienda.style.display = 'none';
            divPlaza.style.display = 'block';
            selectTienda.required = false;
            selectPlaza.required = true;
            selectTienda.value = '';
        } else {
            divTienda.style.display = 'none';
            divPlaza.style.display = 'none';
            selectTienda.required = false;
            selectPlaza.required = false;
            selectTienda.value = '';
            selectPlaza.value = '';
        }
    }
</script>
