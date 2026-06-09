<!-- Modal Editar Usuario Tienda -->
<div
    class="modal fade"
    id="ModalEditar{{ $usuarioTienda->IdUsuarioTienda }}"
    tabindex="-1"
    aria-labelledby="ModalEditar{{ $usuarioTienda->IdUsuarioTienda }}"
    aria-hidden="true"
>
    <div
        class="modal-dialog"
        style="margin-top: 10vh;"
    >
        <div
            class="modal-content border-0 shadow"
            style="border-radius: 10px;"
        >
            <!-- Modal Header -->
            <div
                class="modal-header border-bottom-0 pb-0"
                style="background: linear-gradient(135deg, #1e293b 0%, #1e293b 100%); border-radius: 10px 10px 0 0;"
            >
                <h5
                    class="text-white"
                    id="ModalEditar{{ $usuarioTienda->IdUsuarioTienda }}"
                >
                    <div class="d-flex align-items-center gap-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.2); width: 32px; height: 32px;"
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
                    action="EditarUsuarioTienda/{{ $usuarioTienda->IdUsuarioTienda }}"
                    method="POST"
                    id="formEditar{{ $usuarioTienda->IdUsuarioTienda }}"
                >
                    @csrf

                    <!-- Seleccionar Opción -->
                    <div class="mb-4">
                        <label class="form-label fw-500 mb-2 text-gray-700">
                            <div class="d-flex align-items-center gap-2">
                                <span>Seleccione Una Opción</span>
                                <span class="text-danger">*</span>
                            </div>
                        </label>

                        <div class="form-check mb-2">
                            <input
                                {{ $usuarioTienda->Todas == 0 ? 'checked' : '' }}
                                class="form-check-input"
                                type="radio"
                                name="radioEdit"
                                id="radioTodasEdit{{ $usuarioTienda->IdUsuarioTienda }}"
                                value="todas"
                                onclick="OpcionesEdit({{ $usuarioTienda->IdUsuarioTienda }})"
                            >
                            <label
                                class="form-check-label text-gray-700"
                                for="radioTodasEdit{{ $usuarioTienda->IdUsuarioTienda }}"
                            >
                                Todas las Tiendas y Plazas
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input
                                {{ $usuarioTienda->IdPlaza != null ? 'checked' : '' }}
                                class="form-check-input"
                                type="radio"
                                name="radioEdit"
                                id="radioPlazaEdit{{ $usuarioTienda->IdUsuarioTienda }}"
                                value="plaza"
                                onclick="OpcionesEdit({{ $usuarioTienda->IdUsuarioTienda }})"
                            >
                            <label
                                class="form-check-label text-gray-700"
                                for="radioPlazaEdit{{ $usuarioTienda->IdUsuarioTienda }}"
                            >
                                Plaza específica
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input
                                {{ $usuarioTienda->IdTienda != null ? 'checked' : '' }}
                                class="form-check-input"
                                type="radio"
                                name="radioEdit"
                                id="radioTiendaEdit{{ $usuarioTienda->IdUsuarioTienda }}"
                                value="tienda"
                                onclick="OpcionesEdit({{ $usuarioTienda->IdUsuarioTienda }})"
                            >
                            <label
                                class="form-check-label text-gray-700"
                                for="radioTiendaEdit{{ $usuarioTienda->IdUsuarioTienda }}"
                            >
                                Tienda específica
                            </label>
                        </div>
                    </div>

                    <!-- Selector de Tienda (oculto por defecto) -->
                    <div
                        class="mb-4"
                        id="divTiendaEdit{{ $usuarioTienda->IdUsuarioTienda }}"
                        style="display: {{ $usuarioTienda->IdTienda == null ? 'none' : 'block' }};"
                    >
                        <label
                            for="IdTienda{{ $usuarioTienda->IdUsuarioTienda }}"
                            class="form-label fw-500 mb-2 text-gray-700"
                        >
                            <div class="d-flex align-items-center gap-2">
                                <span>Tienda</span>
                            </div>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background-color: rgba(30, 41, 59, 0.1); border-color: #e5e7eb; color: #1e293b"
                            >
                                @include('components.icons.store')
                            </span>
                            <select
                                name="IdTienda"
                                id="IdTienda{{ $usuarioTienda->IdUsuarioTienda }}"
                                class="form-select border-start-0"
                                style="border-color: #e5e7eb; border-radius: 0 6px 6px 0; line-height: 18px;"
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

                    <!-- Selector de Plaza (oculto por defecto) -->
                    <div
                        class="mb-4"
                        id="divPlazaEdit{{ $usuarioTienda->IdUsuarioTienda }}"
                        style="display: {{ $usuarioTienda->IdPlaza == null ? 'none' : 'block' }};"
                    >
                        <label
                            for="IdPlaza{{ $usuarioTienda->IdUsuarioTienda }}"
                            class="form-label fw-500 mb-2 text-gray-700"
                        >
                            <div class="d-flex align-items-center gap-2">
                                <span>Plaza</span>
                            </div>
                        </label>
                        <div class="input-group">
                            <span
                                class="input-group-text"
                                style="background-color: rgba(30, 41, 59, 0.1); border-color: #e5e7eb; color: #1e293b"
                            >
                                @include('components.icons.building-plus')
                            </span>
                            <select
                                name="IdPlaza"
                                id="IdPlaza{{ $usuarioTienda->IdUsuarioTienda }}"
                                class="form-select border-start-0"
                                style="border-color: #e5e7eb; border-radius: 0 6px 6px 0; line-height: 18px;"
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
            <div class="modal-footer border-top-0 pt-0">
                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal"
                    style="border-radius: 6px; padding: 6px 16px;"
                >
                    <span class="d-flex align-items-center gap-1">
                        @include('components.icons.x')
                        Cancelar
                    </span>
                </button>
                <button
                    type="submit"
                    form="formEditar{{ $usuarioTienda->IdUsuarioTienda }}"
                    class="btn btn-primary"
                    style="border-radius: 6px; padding: 6px 16px; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; transition: all 0.2s ease;"
                    onmouseover="this.style.background='linear-gradient(135deg, #0f172a 0%, #1e293b 100%)'"
                    onmouseout="this.style.background='linear-gradient(135deg, #1e293b 0%, #334155 100%)'"
                >
                    <span class="d-flex align-items-center gap-1">
                        @include('components.icons.send')
                        Editar Usuario
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function OpcionesEdit(id) {
        const radioTodas = document.getElementById('radioTodasEdit' + id);
        const radioPlaza = document.getElementById('radioPlazaEdit' + id);
        const radioTienda = document.getElementById('radioTiendaEdit' + id);
        const divTienda = document.getElementById('divTiendaEdit' + id);
        const divPlaza = document.getElementById('divPlazaEdit' + id);
        const selectTienda = document.getElementById('IdTienda' + id);
        const selectPlaza = document.getElementById('IdPlaza' + id);

        if (radioTienda && radioTienda.checked) {
            if (divTienda) divTienda.style.display = 'block';
            if (divPlaza) divPlaza.style.display = 'none';
            if (selectTienda) selectTienda.required = true;
            if (selectPlaza) selectPlaza.required = false;
        } else if (radioPlaza && radioPlaza.checked) {
            if (divTienda) divTienda.style.display = 'none';
            if (divPlaza) divPlaza.style.display = 'block';
            if (selectTienda) selectTienda.required = false;
            if (selectPlaza) selectPlaza.required = true;
        } else {
            if (divTienda) divTienda.style.display = 'none';
            if (divPlaza) divPlaza.style.display = 'none';
            if (selectTienda) selectTienda.required = false;
            if (selectPlaza) selectPlaza.required = false;
        }
    }
</script>
