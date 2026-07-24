<x-page-container title="Transacciones por Tienda">
    <x-card-gradient-header
        icon="arrow-left-right"
        title="Transacciones por Tienda"
        subtitle="Gestione las transacciones asignadas a cada tienda"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <x-form.form
            action="/TransaccionesTienda"
            id="formTransacciones"
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
                    :selected="$idTienda ?? ''"
                    onchange="document.getElementById('formTransacciones').submit()"
                />
            </x-form.group>
        </x-form.form>

        @if (!empty($idTienda))
            <div class="p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-4 gap-3">
                    <div>
                        <h5 class="section-content-title">
                            <i
                                class="bi bi-arrow-left-right me-2"
                                style="color: #64748b;"
                            ></i>
                            Transacciones de {{ $tiendas->where('IdTienda', $idTienda)->first()->NomTienda ?? '' }}
                        </h5>
                        <p class="section-content-subtitle">
                            Agregue o elimine tiendas para transacciones
                        </p>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Tiendas Agregadas -->
                    <div class="col-md-6">
                        <form
                            action="/EliminarTransaccionTienda/{{ $idTienda }}"
                            method="POST"
                        >
                            @csrf
                            <div class="card-modern">
                                <div
                                    class="card-header border-bottom p-3"
                                    style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);"
                                >
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6
                                                class="fw-bold mb-0"
                                                style="color: #0f172a;"
                                            >
                                                <i
                                                    class="bi bi-shop fs-5 me-2"
                                                    style="color: #ef4444;"
                                                ></i>
                                                Tiendas Agregadas
                                            </h6>
                                            <small class="text-muted ms-4">{{ $tiendasAgregadas->count() }}
                                                tiendas</small>
                                        </div>
                                        <span class="badge bg-danger text-danger rounded-pill bg-opacity-10 px-3 py-2">
                                            <i class="bi bi-collection me-1"></i>{{ $tiendasAgregadas->count() }}
                                        </span>
                                    </div>
                                </div>

                                <div
                                    class="card-body p-0"
                                    style="max-height: 400px; overflow-y: auto; overflow-x: hidden;"
                                >
                                    <table class="table-hover table-custom mb-0 table">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th class="rounded-start ps-4">Tienda</th>
                                                <th
                                                    width="80"
                                                    class="rounded-end text-center"
                                                >Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($tiendasAgregadas->count() == 0)
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="py-5 text-center">
                                                            <i class="bi bi-shop fs-1 text-muted d-block mb-3"></i>
                                                            <h6 class="text-muted">Sin tiendas agregadas</h6>
                                                            <small class="text-muted">No hay tiendas
                                                                configuradas</small>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @else
                                                @foreach ($tiendasAgregadas as $tAgregada)
                                                    <tr class="menu-row">
                                                        <td class="ps-4">
                                                            <div class="d-flex align-items-center">
                                                                <i
                                                                    class="bi bi-building me-2"
                                                                    style="color: #ef4444;"
                                                                ></i>
                                                                <span
                                                                    class="fw-medium">{{ $tAgregada->NomTienda }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <input
                                                                class="form-check-input-modern chk-eliminar"
                                                                type="checkbox"
                                                                name="chkEliminar[]"
                                                                value="{{ $tAgregada->IdTienda }}"
                                                            >
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                @if ($tiendasAgregadas->count() > 0)
                                    <div class="card-footer border-top bg-white p-3">
                                        <div class="d-flex justify-content-end">
                                            <button
                                                type="submit"
                                                class="btn-modern btn-remover btn-disabled"
                                                id="btnEliminar"
                                                disabled
                                            >
                                                <i class="bi bi-trash me-2"></i>Eliminar
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Tiendas por Agregar -->
                    <div class="col-md-6">
                        <form
                            action="/AgregarTransaccionTienda/{{ $idTienda }}"
                            method="POST"
                        >
                            @csrf
                            <div class="card-modern">
                                <div
                                    class="card-header border-bottom p-3"
                                    style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);"
                                >
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6
                                                class="fw-bold mb-0"
                                                style="color: #0f172a;"
                                            >
                                                <i
                                                    class="bi bi-shop fs-5 me-2"
                                                    style="color: #10b981;"
                                                ></i>
                                                Tiendas por Agregar
                                            </h6>
                                            <small class="text-muted ms-4">{{ $tiendasPorAgregar->count() }}
                                                tiendas</small>
                                        </div>
                                        <span
                                            class="badge bg-success text-success rounded-pill bg-opacity-10 px-3 py-2">
                                            <i class="bi bi-collection me-1"></i>{{ $tiendasPorAgregar->count() }}
                                        </span>
                                    </div>
                                </div>

                                <div
                                    class="card-body p-0"
                                    style="max-height: 400px; overflow-y: auto; overflow-x: hidden;"
                                >
                                    <table class="table-hover table-custom mb-0 table">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th class="rounded-start ps-4">Tienda</th>
                                                <th
                                                    width="80"
                                                    class="rounded-end text-center"
                                                >Agregar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($tiendasPorAgregar->count() == 0)
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="py-5 text-center">
                                                            <i
                                                                class="bi bi-check-circle fs-1 text-muted d-block mb-3"></i>
                                                            <h6 class="text-muted">Sin tiendas disponibles</h6>
                                                            <small class="text-muted">Todas las tiendas están
                                                                agregadas</small>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @else
                                                @foreach ($tiendasPorAgregar as $tPorAgregar)
                                                    <tr class="menu-row">
                                                        <td class="ps-4">
                                                            <div class="d-flex align-items-center">
                                                                <i
                                                                    class="bi bi-building me-2"
                                                                    style="color: #10b981;"
                                                                ></i>
                                                                <span
                                                                    class="fw-medium">{{ $tPorAgregar->NomTienda }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <input
                                                                class="form-check-input-modern chk-agregar"
                                                                type="checkbox"
                                                                name="chkAgregar[]"
                                                                value="{{ $tPorAgregar->IdTienda }}"
                                                            >
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                @if ($tiendasPorAgregar->count() > 0)
                                    <div class="card-footer border-top bg-white p-3">
                                        <div class="d-flex justify-content-end">
                                            <button
                                                type="submit"
                                                class="btn-modern btn-agregar btn-disabled"
                                                id="btnAgregar"
                                                disabled
                                            >
                                                <i class="bi bi-plus-circle me-2"></i>Agregar
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="p-5 text-center">
                <div class="mb-4">
                    <i
                        class="bi bi-shop display-1"
                        style="color: #cbd5e1;"
                    ></i>
                </div>
                <h5 style="color: #0f172a;">Seleccione una tienda</h5>
                <p class="text-muted">Elija una tienda del filtro para gestionar sus transacciones</p>
            </div>
        @endif
    </x-card-gradient-header>
</x-page-container>

<script>
    document.getElementById('idTienda').addEventListener('change', (e) => {
        document.getElementById('formTransacciones').submit();
    });

    // Control de botones según checkboxes
    document.addEventListener('DOMContentLoaded', function() {
        const chkEliminar = document.querySelectorAll('.chk-eliminar');
        const chkAgregar = document.querySelectorAll('.chk-agregar');
        const btnEliminar = document.getElementById('btnEliminar');
        const btnAgregar = document.getElementById('btnAgregar');

        function actualizarBoton(checkboxes, boton) {
            if (!boton) return;
            const algunoSeleccionado = Array.from(checkboxes).some(cb => cb.checked);
            boton.disabled = !algunoSeleccionado;
            if (algunoSeleccionado) {
                boton.classList.remove('btn-disabled');
            } else {
                boton.classList.add('btn-disabled');
            }
        }

        if (chkEliminar.length > 0) {
            chkEliminar.forEach(cb => {
                cb.addEventListener('change', () => actualizarBoton(chkEliminar, btnEliminar));
            });
        }

        if (chkAgregar.length > 0) {
            chkAgregar.forEach(cb => {
                cb.addEventListener('change', () => actualizarBoton(chkAgregar, btnAgregar));
            });
        }
    });
</script>
