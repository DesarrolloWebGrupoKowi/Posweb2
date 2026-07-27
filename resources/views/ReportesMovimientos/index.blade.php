<x-page-container title="Reporte de Movimientos de Inventario">
    <x-card-gradient-header
        icon="box-arrow-in-down"
        title="Reporte de Movimientos de Inventario"
        subtitle="Consulta de movimientos de productos en tiendas"
    >
        <x-slot:buttons>
            <a
                href="/ReporteMovimientosInventario/exports?{{ http_build_query(request()->only(['idTienda', 'fecha_inicio', 'fecha_fin', 'fecha', 'cod_articulo', 'nom_articulo', 'id_movimiento', 'usuario', 'num_nomina', 'referencia', 'id_caja'])) }}"
                class="btn-header-ghost"
                title="Exportar a Excel"
                style="background: var(--btn-green-bg); color: var(--btn-green-text);"
                onmouseover="this.style.background='var(--btn-green-hover)'; this.style.transform='translateY(-1px)'"
                onmouseout="this.style.background='var(--btn-green-bg)'; this.style.transform='translateY(0)'"
            >
                <i class="bi bi-file-earmark-excel"></i> Exportar
            </a>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <div class="border-bottom p-4">
            <form
                method="GET"
                action="/ReporteMovimientosInventario"
            >
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            <i class="bi bi-shop me-1"></i>Tienda
                        </label>
                        <select
                            name="idTienda"
                            class="form-select"
                            style="border-radius: 8px; border: 1px solid var(--border-input); padding: 8px 12px; font-size: 0.85rem;"
                        >
                            <option value="">Todas las tiendas</option>
                            @foreach ($tiendas as $tienda)
                                <option
                                    value="{{ $tienda->IdTienda }}"
                                    {{ request('idTienda') == $tienda->IdTienda ? 'selected' : '' }}
                                >{{ $tienda->NomTienda }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            <i class="bi bi-calendar3 me-1"></i>Fecha Única
                        </label>
                        <input
                            type="date"
                            name="fecha"
                            class="form-control"
                            style="border-radius: 8px; border: 1px solid var(--border-input); padding: 8px 12px; font-size: 0.85rem;"
                            value="{{ request('fecha') }}"
                            autofocus
                        >
                    </div>
                    <div class="col-md-2">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            <i class="bi bi-calendar3 me-1"></i>Fecha Inicio
                        </label>
                        <input
                            type="date"
                            name="fecha_inicio"
                            class="form-control"
                            style="border-radius: 8px; border: 1px solid var(--border-input); padding: 8px 12px; font-size: 0.85rem;"
                            value="{{ request('fecha_inicio') }}"
                        >
                    </div>
                    <div class="col-md-2">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        >
                            <i class="bi bi-calendar3 me-1"></i>Fecha Fin
                        </label>
                        <input
                            type="date"
                            name="fecha_fin"
                            class="form-control"
                            style="border-radius: 8px; border: 1px solid var(--border-input); padding: 8px 12px; font-size: 0.85rem;"
                            value="{{ request('fecha_fin') }}"
                        >
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center gap-2">
                            <x-form.submit
                                text="Buscar"
                                icon="funnel"
                                class="flex-grow-1"
                            />
                            <x-form.clear url="/ReporteMovimientosInventario" />
                            <button
                                type="button"
                                id="btnFiltrosAvanzados"
                                class="btn btn-sm d-flex align-items-center btn-animated gap-1"
                                style="background: {{ $filtrosAvanzadosActivos ? 'var(--btn-gray-hover)' : 'var(--btn-gray-bg)' }}; color: var(--btn-gray-text); border: none; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; white-space: nowrap;"
                                onclick="togglePanel('filaFiltrosAvanzados', 'btnFiltrosAvanzados')"
                                title="Filtros avanzados"
                            >
                                <i class="bi bi-sliders"></i>
                                @if ($filtrosAvanzadosActivos)
                                    <span
                                        style="background: var(--btn-blue-text); color: white; font-size: 0.65rem; padding: 2px 6px; border-radius: 10px;"
                                    >●</span>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    id="filaFiltrosAvanzados"
                    class="row g-3 align-items-end {{ $filtrosAvanzadosActivos ? '' : 'd-none' }} mt-3"
                >
                    <div class="col-md-2">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        ><i class="bi bi-upc-scan me-1"></i>Código Artículo</label>
                        <input
                            type="text"
                            name="cod_articulo"
                            class="form-control"
                            style="border-radius: 8px; border: 1px solid var(--border-input); padding: 8px 12px; font-size: 0.85rem;"
                            placeholder="Buscar por código"
                            value="{{ request('cod_articulo') }}"
                        >
                    </div>
                    <div class="col-md-2">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        ><i class="bi bi-search me-1"></i>Nombre Artículo</label>
                        <input
                            type="text"
                            name="nom_articulo"
                            class="form-control"
                            style="border-radius: 8px; border: 1px solid var(--border-input); padding: 8px 12px; font-size: 0.85rem;"
                            placeholder="Buscar por nombre"
                            value="{{ request('nom_articulo') }}"
                        >
                    </div>
                    <div class="col-md-2">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        ><i class="bi bi-arrow-left-right me-1"></i>Tipo Movimiento</label>
                        <select
                            name="id_movimiento"
                            class="form-select"
                            style="border-radius: 8px; border: 1px solid var(--border-input); padding: 8px 12px; font-size: 0.85rem;"
                        >
                            <option value="">Todos</option>
                            @foreach ($movimientosProducto as $mov)
                                <option
                                    value="{{ $mov->IdMovimiento }}"
                                    {{ request('id_movimiento') == $mov->IdMovimiento ? 'selected' : '' }}
                                >{{ $mov->NomMovimiento }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        ><i class="bi bi-person me-1"></i>Usuario</label>
                        <input
                            type="text"
                            name="usuario"
                            class="form-control"
                            style="border-radius: 8px; border: 1px solid var(--border-input); padding: 8px 12px; font-size: 0.85rem;"
                            placeholder="Nombre o usuario"
                            value="{{ request('usuario') }}"
                        >
                    </div>
                    <div class="col-md-2">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        ><i class="bi bi-person-badge me-1"></i>Nómina</label>
                        <input
                            type="text"
                            name="num_nomina"
                            class="form-control"
                            style="border-radius: 8px; border: 1px solid var(--border-input); padding: 8px 12px; font-size: 0.85rem;"
                            placeholder="Número de nómina"
                            value="{{ request('num_nomina') }}"
                        >
                    </div>
                    <div class="col-md-2">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        ><i class="bi bi-link me-1"></i>Referencia</label>
                        <input
                            type="text"
                            name="referencia"
                            class="form-control"
                            style="border-radius: 8px; border: 1px solid var(--border-input); padding: 8px 12px; font-size: 0.85rem;"
                            placeholder="Buscar por referencia"
                            value="{{ request('referencia') }}"
                        >
                    </div>
                    <div class="col-md-2">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        ><i class="bi bi-hash me-1"></i>ID Caja</label>
                        <input
                            type="text"
                            name="id_caja"
                            class="form-control"
                            style="border-radius: 8px; border: 1px solid var(--border-input); padding: 8px 12px; font-size: 0.85rem;"
                            placeholder="ID de caja"
                            value="{{ request('id_caja') }}"
                        >
                    </div>
                </div>
            </form>
        </div>

        <!-- TABLA -->
        <div class="p-4">
            <div
                class="table-responsive"
                style="max-height: 600px; overflow-y: auto;"
            >
                <table class="table-hover table-custom table">
                    <thead style="position: sticky; top: 0; z-index: 2;">
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>ID</th>
                            <th><i class="bi bi-shop me-1"></i>Tienda</th>
                            <th><i class="bi bi-upc-scan me-1"></i>Código</th>
                            <th><i class="bi bi-box me-1"></i>Artículo</th>
                            <th class="text-end"><i class="bi bi-hash me-1"></i>Cantidad</th>
                            <th><i class="bi bi-rulers me-1"></i>UOM</th>
                            <th><i class="bi bi-calendar3 me-1"></i>Fecha Mov.</th>
                            <th><i class="bi bi-link me-1"></i>Referencia</th>
                            <th><i class="bi bi-arrow-left-right me-1"></i>Movimiento</th>
                            <th><i class="bi bi-person me-1"></i>Usuario</th>
                            <th><i class="bi bi-person-badge me-1"></i>Nómina</th>
                            <th><i class="bi bi-person me-1"></i>Empleado</th>
                            <th><i class="bi bi-hash me-1"></i>ID Caja</th>
                            <th><i class="bi bi-link-45deg me-1"></i>Ref. ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $movimiento)
                            <tr>
                                <td style="font-weight: 600; color: var(--text-primary);">
                                    {{ $movimiento->IdDatHistorialMovimientos }}</td>
                                <td style="font-weight: 500;">{{ $movimiento->NomTienda }}</td>
                                <td>{{ $movimiento->CodArticulo }}</td>
                                <td
                                    class="text-truncate"
                                    style="max-width: 180px;"
                                    title="{{ $movimiento->NomArticulo }}"
                                >{{ $movimiento->NomArticulo }}</td>
                                <td
                                    class="text-end"
                                    style="font-weight: 500;"
                                >{{ number_format($movimiento->CantArticulo, 2) }}</td>
                                <td>{{ $movimiento->UOM }}</td>
                                <td style="font-size: 0.8rem;">
                                    {{ \Carbon\Carbon::parse($movimiento->FechaMovimiento)->format('d/m/Y H:i') }}</td>
                                <td>{{ $movimiento->Referencia }}</td>
                                <td><span class="tags-blue">{{ $movimiento->NomMovimiento }}</span></td>
                                <td>{{ $movimiento->NomUsuario }}</td>
                                <td>{{ $movimiento->NumNomina }}</td>
                                <td>{{ $movimiento->NombreEmpleado }}</td>
                                <td>{{ $movimiento->IDCAJA }}</td>
                                <td>{{ $movimiento->ReferenciaId }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="14"
                                    class="py-5 text-center"
                                >
                                    <i
                                        class="bi bi-inbox"
                                        style="font-size: 2.5rem; color: var(--text-muted);"
                                    ></i>
                                    <p
                                        class="mt-2"
                                        style="color: var(--text-secondary); font-size: 0.85rem;"
                                    >No hay movimientos de productos</p>
                                    <a
                                        href="{{ request()->fullUrlWithQuery(['fecha' => now()->toDateString()]) }}"
                                        class="btn btn-sm d-flex align-items-center mx-auto mt-2 gap-1"
                                        style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border: none; border-radius: 8px; padding: 8px 16px; width: fit-content;"
                                    >
                                        <i class="bi bi-calendar3"></i> Ver movimientos de hoy
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @include('components.paginate', ['items' => $data])
        </div>
    </x-card-gradient-header>
</x-page-container>

@section('scripts')
    <script>
        function togglePanel(panelId, buttonId) {
            const panel = document.getElementById(panelId);
            const btn = document.getElementById(buttonId);
            if (panel.classList.contains('d-none')) {
                panel.classList.remove('d-none');
                if (btn) btn.style.background = 'var(--btn-gray-hover)';
            } else {
                panel.classList.add('d-none');
                if (btn) btn.style.background = 'var(--btn-gray-bg)';
            }
        }
    </script>
@endsection
