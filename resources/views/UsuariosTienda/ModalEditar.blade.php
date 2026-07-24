<!-- Modal Editar Usuario Tienda -->
<div
    class="modal fade"
    id="ModalEditar{{ $usuarioTienda->IdUsuarioTienda }}"
    tabindex="-1"
    aria-labelledby="ModalEditar{{ $usuarioTienda->IdUsuarioTienda }}Label"
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
                    id="ModalEditar{{ $usuarioTienda->IdUsuarioTienda }}Label"
                >
                    <div class="d-flex align-items-center gap-3 pb-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.15); width: 32px; height: 32px;"
                        >
                            @include('components.icons.user')
                        </div>
                        <span>Editar Usuario: {{ $usuarioTienda->NomUsuario }}</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="/EditarUsuarioTienda/{{ $usuarioTienda->IdUsuarioTienda }}"
                    method="POST"
                    id="formEditar{{ $usuarioTienda->IdUsuarioTienda }}"
                >
                    @csrf

                    <!-- Seleccionar Opción -->
                    <div class="mb-3">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            Seleccione una opción <span style="color: #ef4444;">*</span>
                        </label>

                        <div class="form-check mb-2">
                            <input
                                {{ $usuarioTienda->Todas == 0 ? 'checked' : '' }}
                                class="form-check-input-modern"
                                type="radio"
                                name="radioEdit"
                                id="radioTodasEdit{{ $usuarioTienda->IdUsuarioTienda }}"
                                value="todas"
                                onclick="OpcionesEdit({{ $usuarioTienda->IdUsuarioTienda }})"
                            >
                            <label
                                class="form-check-label"
                                for="radioTodasEdit{{ $usuarioTienda->IdUsuarioTienda }}"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Todas las Tiendas y Plazas
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input
                                {{ $usuarioTienda->IdPlaza != null ? 'checked' : '' }}
                                class="form-check-input-modern"
                                type="radio"
                                name="radioEdit"
                                id="radioPlazaEdit{{ $usuarioTienda->IdUsuarioTienda }}"
                                value="plaza"
                                onclick="OpcionesEdit({{ $usuarioTienda->IdUsuarioTienda }})"
                            >
                            <label
                                class="form-check-label"
                                for="radioPlazaEdit{{ $usuarioTienda->IdUsuarioTienda }}"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Plaza específica
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input
                                {{ $usuarioTienda->IdTienda != null ? 'checked' : '' }}
                                class="form-check-input-modern"
                                type="radio"
                                name="radioEdit"
                                id="radioTiendaEdit{{ $usuarioTienda->IdUsuarioTienda }}"
                                value="tienda"
                                onclick="OpcionesEdit({{ $usuarioTienda->IdUsuarioTienda }})"
                            >
                            <label
                                class="form-check-label"
                                for="radioTiendaEdit{{ $usuarioTienda->IdUsuarioTienda }}"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                Tienda específica
                            </label>
                        </div>
                    </div>

                    <!-- Selector de Tienda -->
                    <div
                        class="mb-3"
                        id="divTiendaEdit{{ $usuarioTienda->IdUsuarioTienda }}"
                        style="display: {{ $usuarioTienda->IdTienda == null ? 'none' : 'block' }};"
                    >
                        <label
                            for="IdTienda{{ $usuarioTienda->IdUsuarioTienda }}"
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
                                id="IdTienda{{ $usuarioTienda->IdUsuarioTienda }}"
                                class="form-select border-start-0"
                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                            >
                                <option value="">Seleccione una tienda</option>
                                @foreach ($tiendas as $tienda)
                                    <option
                                        {{ $tienda->IdTienda == $usuarioTienda->IdTienda ? 'selected' : '' }}
                                        value="{{ $tienda->IdTienda }}"
                                    >
                                        {{ $tienda->NomTienda }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Selector de Plaza -->
                    <div
                        class="mb-3"
                        id="divPlazaEdit{{ $usuarioTienda->IdUsuarioTienda }}"
                        style="display: {{ $usuarioTienda->IdPlaza == null ? 'none' : 'block' }};"
                    >
                        <label
                            for="IdPlaza{{ $usuarioTienda->IdUsuarioTienda }}"
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
                                id="IdPlaza{{ $usuarioTienda->IdUsuarioTienda }}"
                                class="form-select border-start-0"
                                style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 8px 8px 0; padding: 8px 12px; font-size: 0.85rem; cursor: pointer;"
                            >
                                <option value="">Seleccione una plaza</option>
                                @foreach ($plazas as $plaza)
                                    <option
                                        {{ $plaza->IdPlaza == $usuarioTienda->IdPlaza ? 'selected' : '' }}
                                        value="{{ $plaza->IdPlaza }}"
                                    >
                                        {{ $plaza->NomPlaza }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
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
                    @include('components.icons.x')
                    Cancelar
                </button>
                <button
                    type="submit"
                    form="formEditar{{ $usuarioTienda->IdUsuarioTienda }}"
                    class="btn d-flex align-items-center gap-1"
                    style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 0.85rem; font-weight: 500; transition: all 0.3s ease;"
                    onmouseover="this.style.background='linear-gradient(135deg, #0f172a 0%, #1e293b 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(30, 41, 59, 0.3)'"
                    onmouseout="this.style.background='linear-gradient(135deg, #1e293b 0%, #334155 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                >
                    @include('components.icons.send')
                    Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function OpcionesEdit(id) {
        const radioTienda = document.getElementById('radioTiendaEdit' + id);
        const radioPlaza = document.getElementById('radioPlazaEdit' + id);
        const divTienda = document.getElementById('divTiendaEdit' + id);
        const divPlaza = document.getElementById('divPlazaEdit' + id);
        const selectTienda = document.getElementById('IdTienda' + id);
        const selectPlaza = document.getElementById('IdPlaza' + id);

        if (radioTienda && radioTienda.checked) {
            if (divTienda) divTienda.style.display = 'block';
            if (divPlaza) divPlaza.style.display = 'none';
            if (selectTienda) selectTienda.required = true;
            if (selectPlaza) {
                selectPlaza.required = false;
                selectPlaza.value = '';
            }
        } else if (radioPlaza && radioPlaza.checked) {
            if (divTienda) divTienda.style.display = 'none';
            if (divPlaza) divPlaza.style.display = 'block';
            if (selectTienda) {
                selectTienda.required = false;
                selectTienda.value = '';
            }
            if (selectPlaza) selectPlaza.required = true;
        } else {
            if (divTienda) divTienda.style.display = 'none';
            if (divPlaza) divPlaza.style.display = 'none';
            if (selectTienda) {
                selectTienda.required = false;
                selectTienda.value = '';
            }
            if (selectPlaza) {
                selectPlaza.required = false;
                selectPlaza.value = '';
            }
        }
    }
</script>
