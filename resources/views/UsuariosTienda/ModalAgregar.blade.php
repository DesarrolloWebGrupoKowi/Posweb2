<!-- Modal Agregar Usuario Tienda -->
<div
    class="modal fade"
    id="ModalAgregar"
    tabindex="-1"
    aria-labelledby="ModalAgregar"
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
                    id="ModalAgregar"
                >
                    <div class="d-flex align-items-center gap-2">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.2); width: 32px; height: 32px;"
                        >
                            @include('components.icons.user')
                        </div>
                        <span>Agregar Usuario Tienda</span>
                    </div>
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form
                    action="/CrearUsuarioTienda"
                    method="POST"
                    id="formAgregarUsuarioTienda"
                >
                    @csrf

                    @if (empty($usuarios))
                        <div class="py-4 text-center">
                            <div
                                class="d-flex align-items-center justify-content-center mx-auto mb-3"
                                style="width: 64px; height: 64px; background-color: rgba(220, 38, 38, 0.1); border-radius: 50%;"
                            >
                                <x-icons.alert-box
                                    :width="24"
                                    :height="24"
                                    color="#dc2626"
                                />
                            </div>
                            <p class="fs-6 fw-medium m-0 text-gray-700">
                                No hay usuarios disponibles
                            </p>
                            <p class="fs-6 text-muted mt-2">
                                Todos los usuarios ya han sido asignados
                            </p>
                        </div>
                    @else
                        <!-- Seleccionar Usuario -->
                        <div class="mb-4">
                            <label
                                for="IdUsuario"
                                class="form-label fw-500 mb-2 text-gray-700"
                            >
                                <div class="d-flex align-items-center gap-2">
                                    <span>Usuario</span>
                                </div>
                            </label>
                            <div class="input-group">
                                <span
                                    class="input-group-text"
                                    style="background-color: rgba(30, 41, 59, 0.1); border-color: #e5e7eb; color: #1e293b"
                                >
                                    @include('components.icons.user')
                                </span>
                                <select
                                    name="IdUsuario"
                                    id="IdUsuario"
                                    class="form-select border-start-0"
                                    style="border-color: #e5e7eb; border-radius: 0 6px 6px 0; line-height: 18px;"
                                    required
                                >
                                    @foreach ($usuarios as $usuario)
                                        <option value="{{ $usuario->IdUsuario }}">{{ $usuario->NomUsuario }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Seleccionar Opción -->
                        <div class="mb-4">
                            <label class="form-label fw-500 mb-2 text-gray-700">
                                <div class="d-flex align-items-center gap-2">
                                    <span>Seleccione Opción</span>
                                </div>
                            </label>

                            <div class="form-check mb-2">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="radio"
                                    id="radioTodas"
                                    value="todas"
                                    onclick="Opciones()"
                                >
                                <label
                                    class="form-check-label text-gray-700"
                                    for="radioTodas"
                                >
                                    Todas las Tiendas y Plazas
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="radio"
                                    id="radioPlaza"
                                    value="plaza"
                                    onclick="Opciones()"
                                >
                                <label
                                    class="form-check-label text-gray-700"
                                    for="radioPlaza"
                                >
                                    Plaza específica
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="radio"
                                    id="radioTienda"
                                    value="tienda"
                                    onclick="Opciones()"
                                >
                                <label
                                    class="form-check-label text-gray-700"
                                    for="radioTienda"
                                >
                                    Tienda específica
                                </label>
                            </div>
                        </div>

                        <!-- Selector de Tienda (oculto por defecto) -->
                        <div
                            class="mb-4"
                            id="divTienda"
                            style="display: none;"
                        >
                            <label
                                for="IdTienda"
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
                                    id="IdTienda"
                                    class="form-select border-start-0"
                                    style="border-color: #e5e7eb; border-radius: 0 6px 6px 0; line-height: 18px;"
                                >
                                    <option value="">Seleccione una tienda</option>
                                    @foreach ($tiendas as $tienda)
                                        <option value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Selector de Plaza (oculto por defecto) -->
                        <div
                            class="mb-4"
                            id="divPlaza"
                            style="display: none;"
                        >
                            <label
                                for="IdPlaza"
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
                                    id="IdPlaza"
                                    class="form-select border-start-0"
                                    style="border-color: #e5e7eb; border-radius: 0 6px 6px 0; line-height: 18px;"
                                >
                                    <option value="">Seleccione una plaza</option>
                                    @foreach ($plazas as $plaza)
                                        <option value="{{ $plaza->IdPlaza }}">{{ $plaza->NomPlaza }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
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
                    form="formAgregarUsuarioTienda"
                    class="btn btn-primary"
                    {{ $usuarios == null ? 'disabled' : '' }}
                    style="border-radius: 6px; padding: 6px 16px; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none; transition: all 0.2s ease;"
                    onmouseover="this.style.background='linear-gradient(135deg, #0f172a 0%, #1e293b 100%)'"
                    onmouseout="this.style.background='linear-gradient(135deg, #1e293b 0%, #334155 100%)'"
                >
                    <span class="d-flex align-items-center gap-1">
                        @include('components.icons.send')
                        Asignar Usuario
                    </span>
                </button>
            </div>
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

        if (radioTienda.checked) {
            divTienda.style.display = 'block';
            divPlaza.style.display = 'none';
            document.getElementById('IdTienda').required = true;
            document.getElementById('IdPlaza').required = false;
        } else if (radioPlaza.checked) {
            divTienda.style.display = 'none';
            divPlaza.style.display = 'block';
            document.getElementById('IdTienda').required = false;
            document.getElementById('IdPlaza').required = true;
        } else {
            divTienda.style.display = 'none';
            divPlaza.style.display = 'none';
            document.getElementById('IdTienda').required = false;
            document.getElementById('IdPlaza').required = false;
        }
    }
</script>
