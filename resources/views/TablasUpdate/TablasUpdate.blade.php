<x-page-container title="Catálogo de Tablas Actualizables">
    <x-card-gradient-header
        icon="database-gear"
        title="Catálogo de Tablas Actualizables"
        subtitle="Gestione las tablas pendientes por actualizar en cada tienda"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <x-form.form
            action="/TablasUpdate"
            id="formTabla"
            method="GET"
        >
            <x-form.group>
                <x-form.select
                    name="idTienda"
                    label="Tienda"
                    icon="shop"
                    col="col-md-4"
                    placeholder="Seleccione tienda"
                    :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                    :selected="$idTienda ?? '0'"
                    onchange="document.getElementById('formTabla').submit()"
                />
            </x-form.group>
        </x-form.form>

        @if ($idTienda != 0)
            <div class="p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-4 gap-3">
                    <div>
                        <h5 class="section-content-title">
                            <i
                                class="bi bi-table me-2"
                                style="color: var(--text-muted);"
                            ></i>
                            Tablas de {{ $tiendas->where('IdTienda', $idTienda)->first()->NomTienda ?? '' }}
                        </h5>
                        <p class="section-content-subtitle">
                            Configure qué tablas deben descargarse en esta tienda
                        </p>
                    </div>
                    @if ($idTienda != 0)
                        <button
                            class="btn-modern btn-agregar"
                            data-bs-toggle="modal"
                            data-bs-target="#AgregarTablaUpdate"
                        >
                            <i class="bi bi-plus-circle me-2"></i>Agregar Tabla
                        </button>
                    @endif
                </div>

                @if ($tablasActualizables->count() == 0)
                    <div
                        class="card overflow-hidden border-0 shadow-sm"
                        style="border-radius: 16px;"
                    >
                        <div class="card-body p-0">
                            <table class="table-hover table-custom mb-0 table">
                                <thead class="table-light">
                                    <tr>
                                        <th class="rounded-start ps-4">Nombre Tabla</th>
                                        <th>Caja</th>
                                        <th class="text-center">Estado</th>
                                        <th class="rounded-end text-center">
                                            Descargar Todas
                                            <div
                                                class="form-check form-switch d-inline-block ms-3"
                                                style="line-height: 18px"
                                            >
                                                <input
                                                    class="form-check-input-modern"
                                                    type="checkbox"
                                                    role="switch"
                                                    id="descargarTodas"
                                                    {{ $checkedTodas == 0 ? 'checked' : '' }}
                                                >
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4">
                                            <div class="py-5 text-center">
                                                <i class="bi bi-database-slash fs-1 text-muted d-block mb-3"></i>
                                                <h6 class="text-muted">Sin tablas registradas</h6>
                                                <small class="text-muted">No hay tablas configuradas para esta
                                                    tienda</small>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <form
                        id="formActualizarTablas"
                        action="/ActualizarTablas/{{ $idTienda }}"
                        method="POST"
                    >
                        @csrf
                        <div
                            class="card overflow-hidden border-0 shadow-sm"
                            style="border-radius: 16px;"
                        >
                            <div class="card-body p-0">
                                <table class="table-hover table-custom mb-0 table">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="rounded-start ps-4">Nombre Tabla</th>
                                            <th>Caja</th>
                                            <th class="text-center">Estado</th>
                                            <th class="rounded-end text-center">
                                                Descargar Todas
                                                <div
                                                    class="form-check form-switch d-inline-block ms-3"
                                                    style="line-height: 18px"
                                                >
                                                    <input
                                                        class="form-check-input-modern"
                                                        type="checkbox"
                                                        role="switch"
                                                        id="descargarTodas"
                                                        {{ $checkedTodas == 0 ? 'checked' : '' }}
                                                    >
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tablasActualizables as $tActualizable)
                                            <tr class="menu-row">
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        <i
                                                            class="bi bi-grid-3x3-gap me-2"
                                                            style="color: var(--tag-blue-text);"
                                                        ></i>
                                                        <span class="fw-medium">{{ $tActualizable->NombreTabla }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border">
                                                        <i class="bi bi-cash-register me-1"></i>
                                                        Caja {{ $tActualizable->IdCaja }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($tActualizable->Descargar == 1)
                                                        <span
                                                            class="badge bg-success text-success rounded-pill bg-opacity-10 px-3 py-1"
                                                        >
                                                            <i class="bi bi-check-circle me-1"></i>Descargada
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge bg-danger text-danger rounded-pill bg-opacity-10 px-3 py-1"
                                                        >
                                                            <i class="bi bi-clock me-1"></i>Pendiente
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="form-check form-switch d-flex justify-content-center">
                                                        <input
                                                            class="form-check-input-modern descargado-switch"
                                                            type="checkbox"
                                                            role="switch"
                                                            name="descargado[]"
                                                            value="{{ $tActualizable->NombreTabla }}"
                                                            {{ $tActualizable->Descargar == 0 ? 'checked' : '' }}
                                                        >
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer border-top bg-white p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <span id="countSeleccionados">{{ $tablasPorDescargar }}</span> tablas
                                        seleccionadas
                                    </small>
                                    <button
                                        type="submit"
                                        id="btnActualizarTablas"
                                        class="btn-modern btn-warning-modern"
                                    >
                                        <i class="bi bi-save me-2"></i>Guardar Cambios
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        @else
            <div class="p-5 text-center">
                <div class="mb-4">
                    <i
                        class="bi bi-shop display-1"
                        style="color: var(--border-light);"
                    ></i>
                </div>
                <h5 style="color: var(--text-primary);">Seleccione una tienda</h5>
                <p class="text-muted">Elija una tienda del filtro para gestionar sus tablas actualizables</p>
            </div>
        @endif
    </x-card-gradient-header>

    @include('TablasUpdate.AgregarTablaUpdate')
</x-page-container>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const descargarTodas = document.getElementById('descargarTodas');
        const switches = document.querySelectorAll('.descargado-switch');
        const countSeleccionados = document.getElementById('countSeleccionados');
        let totalTablas = switches.length;

        function actualizarContador() {
            const seleccionadas = document.querySelectorAll('.descargado-switch:checked').length;
            if (countSeleccionados) {
                countSeleccionados.textContent = seleccionadas;
                countSeleccionados.style.color = seleccionadas > 0 ? '#f59e0b' : 'var(--text-muted)';
                countSeleccionados.style.fontWeight = seleccionadas > 0 ? '700' : '400';
            }
            if (descargarTodas) {
                descargarTodas.checked = (seleccionadas === totalTablas);
            }
        }

        if (descargarTodas) {
            descargarTodas.addEventListener('change', function() {
                switches.forEach(switchEl => {
                    switchEl.checked = this.checked;
                    toggleRowHighlight(switchEl);
                });
                actualizarContador();
            });
        }

        switches.forEach(switchEl => {
            switchEl.addEventListener('change', function() {
                toggleRowHighlight(this);
                actualizarContador();
            });
            toggleRowHighlight(switchEl);
        });

        function toggleRowHighlight(checkbox) {
            const row = checkbox.closest('tr');
            if (checkbox.checked) {
                row.style.backgroundColor = 'var(--tag-amber-bg, #fffbeb)';
                row.style.borderLeft = '3px solid #f59e0b';
            } else {
                row.style.backgroundColor = '';
                row.style.borderLeft = '';
            }
        }

        actualizarContador();
    });
</script>
