<x-page-container title="Concentrado de Descuentos">

    {{-- SECCIÓN 1: FILTROS --}}
    <x-card-gradient-header
        icon="percent"
        title="Concentrado de Descuentos"
        subtitle="Reporte de descuentos aplicados por artículo"
    >
        <x-slot:buttons>
            <a
                href="/ExportsReporteDescuentos?{{ http_build_query(request()->only(['idTienda', 'fecha_inicio', 'fecha_fin', 'cod_articulo', 'id_familia', 'nom_descuento'])) }}"
                class="btn-header-ghost"
                title="Exportar a Excel"
                style="background: #f0fdf4; color: #10b981;"
                onmouseover="this.style.background='#dcfce7'; this.style.transform='translateY(-1px)'"
                onmouseout="this.style.background='#f0fdf4'; this.style.transform='translateY(0)'"
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
                action="/ReporteDescuentos"
            >
                {{-- Fila 1: Filtros principales + Botones --}}
                <div class="row g-3 align-items-end">
                    <!-- Tienda -->
                    <div class="col-md-3">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            <i class="bi bi-shop me-1"></i>Tienda
                        </label>
                        <select
                            name="idTienda"
                            class="form-select"
                            style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                        >
                            <option value="">Todas las tiendas</option>
                            @foreach ($tiendas as $tienda)
                                <option
                                    value="{{ $tienda->IdTienda }}"
                                    {{ request('idTienda') == $tienda->IdTienda ? 'selected' : '' }}
                                >
                                    {{ $tienda->NomTienda }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fecha Inicio -->
                    <div class="col-md-2">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            <i class="bi bi-calendar3 me-1"></i>Fecha Inicio
                        </label>
                        <input
                            type="date"
                            name="fecha_inicio"
                            class="form-control"
                            style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                            value="{{ request('fecha_inicio') }}"
                            autofocus
                        >
                    </div>

                    <!-- Fecha Fin -->
                    <div class="col-md-2">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            <i class="bi bi-calendar3 me-1"></i>Fecha Fin
                        </label>
                        <input
                            type="date"
                            name="fecha_fin"
                            class="form-control"
                            style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                            value="{{ request('fecha_fin') }}"
                        >
                    </div>

                    <!-- Artículo -->
                    <div class="col-md-2">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            <i class="bi bi-search me-1"></i>Artículo
                        </label>
                        <input
                            type="text"
                            name="cod_articulo"
                            class="form-control"
                            style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                            placeholder="Código o nombre"
                            value="{{ request('cod_articulo') }}"
                        >
                    </div>

                    <!-- Botones -->
                    <div class="col-md-3">
                        <div class="d-flex align-items-center gap-2">
                            <x-form.submit
                                text="Buscar"
                                icon="funnel"
                                class="flex-grow-1"
                            />
                            <x-form.clear url="/ReporteDescuentos" />
                            <button
                                type="button"
                                id="btnFiltrosAvanzados"
                                class="btn btn-sm d-flex align-items-center btn-animated gap-1"
                                style="background: {{ $filtrosAvanzadosActivos ? '#e2e8f0' : '#f1f5f9' }}; color: #475569; border: none; border-radius: 8px; padding: 8px 12px; font-size: 0.85rem; white-space: nowrap;"
                                onclick="togglePanel('filaFiltrosAvanzados', 'btnFiltrosAvanzados')"
                                title="Filtros avanzados"
                            >
                                <i class="bi bi-sliders"></i>
                                @if ($filtrosAvanzadosActivos)
                                    <span
                                        style="background: #3b82f6; color: white; font-size: 0.65rem; padding: 2px 6px; border-radius: 10px;"
                                    >●</span>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Fila 2: Filtros avanzados (ocultos) --}}
                <div
                    id="filaFiltrosAvanzados"
                    class="row g-3 align-items-end {{ $filtrosAvanzadosActivos ? '' : 'd-none' }} mt-3"
                >
                    <!-- Grupo -->
                    <div class="col-md-2">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            <i class="bi bi-folder me-1"></i>Grupo
                        </label>
                        <select
                            name="id_grupo"
                            class="form-select"
                            style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                        >
                            <option value="">Todos</option>
                            @foreach ($grupos as $grupo)
                                <option
                                    value="{{ $grupo->IdGrupo }}"
                                    {{ request('id_grupo') == $grupo->IdGrupo ? 'selected' : '' }}
                                >
                                    {{ $grupo->NomGrupo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Familia -->
                    <div class="col-md-2">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            <i class="bi bi-folder-symlink me-1"></i>Familia
                        </label>
                        <select
                            name="id_familia"
                            class="form-select"
                            style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                        >
                            <option value="">Todas</option>
                            @foreach ($familias as $familia)
                                <option
                                    value="{{ $familia->IdFamilia }}"
                                    {{ request('id_familia') == $familia->IdFamilia ? 'selected' : '' }}
                                >
                                    {{ $familia->NomFamilia }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- ID Descuento -->
                    <div class="col-md-2">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            <i class="bi bi-hash me-1"></i>ID Descuento
                        </label>
                        <input
                            type="text"
                            name="id_enc_descuento"
                            class="form-control"
                            style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                            placeholder="Id del descuento"
                            value="{{ request('id_enc_descuento') }}"
                        >
                    </div>

                    <!-- Nombre Descuento -->
                    <div class="col-md-2">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: #475569; font-size: 0.85rem;"
                        >
                            <i class="bi bi-tag me-1"></i>Nombre Descuento
                        </label>
                        <input
                            type="text"
                            name="nom_descuento"
                            class="form-control"
                            style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                            placeholder="Nombre del descuento"
                            value="{{ request('nom_descuento') }}"
                        >
                    </div>
                </div>
            </form>
        </div>

        {{-- SECCIÓN 2: KPIs --}}
        <div class="p-4">
            <div class="row g-3">
                @php
                    $diferencias = $data->map(function ($item) {
                        $cantidad = floatval($item->CantArticulo);
                        $precioLista = floatval($item->PrecioLista);
                        $precioArticulo = floatval($item->PrecioArticulo);
                        $item->totalLista = $cantidad * $precioLista;
                        $item->totalArticulo = $cantidad * $precioArticulo;
                        $item->diferencia = $item->totalLista - $item->totalArticulo;
                        return $item;
                    });
                    $sumPrecioLista = $diferencias->sum('totalLista');
                    $sumPrecioArticulo = $diferencias->sum('totalArticulo');
                    $ahorroTotal = $sumPrecioLista - $sumPrecioArticulo;
                    $descuentoPromedio = $sumPrecioLista > 0 ? ($ahorroTotal / $sumPrecioLista) * 100 : 0;
                @endphp

                <div class="col-xl-3 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-radius: 12px; padding: 20px; position: relative; overflow: hidden;"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(59, 130, 246, 0.08); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: #1d4ed8; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                >Valor de Lista</span>
                                <i
                                    class="bi bi-tags"
                                    style="color: #3b82f6; font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                            >${{ number_format($sumPrecioLista, 2) }}</h3>
                            <span style="color: #94a3b8; font-size: 0.78rem;">Suma (Cant × Precio Lista)</span>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-radius: 12px; padding: 20px; position: relative; overflow: hidden;"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(16, 185, 129, 0.08); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: #059669; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                >Valor Real</span>
                                <i
                                    class="bi bi-cash-stack"
                                    style="color: #10b981; font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                            >${{ number_format($sumPrecioArticulo, 2) }}</h3>
                            <span style="color: #94a3b8; font-size: 0.78rem;">Suma (Cant × Precio Artículo)</span>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%); border-radius: 12px; padding: 20px; position: relative; overflow: hidden;"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(139, 92, 246, 0.08); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: #7c3aed; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                >Ahorro Total</span>
                                <i
                                    class="bi bi-piggy-bank"
                                    style="color: #8b5cf6; font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                            >${{ number_format($ahorroTotal, 2) }}</h3>
                            <span style="color: #94a3b8; font-size: 0.78rem;">Lo que ahorraron los clientes</span>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-radius: 12px; padding: 20px; position: relative; overflow: hidden;"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(245, 158, 11, 0.08); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: #d97706; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                >Descuento Prom.</span>
                                <i
                                    class="bi bi-percent"
                                    style="color: #f59e0b; font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                            >{{ number_format($descuentoPromedio, 2) }}%</h3>
                            <span style="color: #94a3b8; font-size: 0.78rem;">Sobre el total de ventas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECCIÓN 3: TABLA --}}
        <div class="px-4 pb-4">
            <div
                class="rounded p-4 shadow-sm"
                style="background: white; border-radius: 12px;"
            >
                <div
                    class="table-responsive"
                    style="max-height: 600px; overflow-y: auto;"
                >
                    <table class="table-hover table-custom table">
                        <thead style="position: sticky; top: 0; z-index: 2;">
                            <tr>
                                <th><i class="bi bi-percent me-1"></i>Descuento</th>
                                <th><i class="bi bi-shop me-1"></i>Tienda</th>
                                <th><i class="bi bi-calendar3 me-1"></i>Fecha Venta</th>
                                <th><i class="bi bi-upc-scan me-1"></i>Código</th>
                                <th><i class="bi bi-box me-1"></i>Artículo</th>
                                <th><i class="bi bi-folder me-1"></i>Grupo</th>
                                <th><i class="bi bi-folder-symlink me-1"></i>Familia</th>
                                <th class="text-end"><i class="bi bi-hash me-1"></i>Cantidad</th>
                                <th><i class="bi bi-cash me-1"></i>Precios</th>
                                <th class="text-end"><i class="bi bi-percent me-1"></i>IVA</th>
                                <th class="text-end"><i class="bi bi-cash-stack me-1"></i>Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalPeso = 0;
                                $totalIva = 0;
                                $totalImporte = 0;
                            @endphp
                            @forelse ($data as $item)
                                <tr>
                                    <td>
                                        <span
                                            style="background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 500;"
                                        >
                                            {{ $item->NomDescuento }}
                                        </span>
                                    </td>
                                    <td style="font-weight: 500;">{{ $item->NomTienda }}</td>
                                    <td style="font-size: 0.85rem;">
                                        {{ $item->FechaVenta }}
                                    </td>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $item->CodArticulo }}</td>
                                    <td
                                        class="text-truncate"
                                        style="max-width: 180px;"
                                        title="{{ $item->NomArticulo }}"
                                    >{{ $item->NomArticulo }}</td>
                                    <td>{{ $item->NomGrupo }}</td>
                                    <td>{{ $item->NomFamilia }}</td>
                                    <td
                                        class="text-end"
                                        style="font-weight: 500;"
                                    >{{ number_format($item->CantArticulo, 3) }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <small
                                                style="color: #ef4444; text-decoration: line-through; font-size: 0.75rem;"
                                            >${{ number_format($item->PrecioLista, 2) }}</small>
                                            <span
                                                style="color: #10b981; font-weight: 600; font-size: 0.85rem;">${{ number_format($item->PrecioArticulo, 2) }}</span>
                                        </div>
                                    </td>
                                    <td
                                        class="text-end"
                                        style="font-weight: 500;"
                                    >${{ number_format($item->IvaArticulo, 2) }}</td>
                                    <td
                                        class="text-end"
                                        style="font-weight: 500;"
                                    >${{ number_format($item->ImporteArticulo, 2) }}</td>
                                </tr>
                                @php
                                    $totalPeso += $item->CantArticulo;
                                    $totalIva += $item->IvaArticulo;
                                    $totalImporte += $item->ImporteArticulo;
                                @endphp
                            @empty
                                <tr>
                                    <td
                                        colspan="11"
                                        class="py-5 text-center"
                                    >
                                        <i
                                            class="bi bi-inbox"
                                            style="font-size: 2.5rem; color: #94a3b8;"
                                        ></i>
                                        <p
                                            class="mt-2"
                                            style="color: #64748b; font-size: 0.85rem;"
                                        >Sin datos disponibles</p>
                                        <a
                                            href="/ReporteDescuentos"
                                            class="btn btn-sm d-flex align-items-center mx-auto mt-2 gap-1"
                                            style="background: #f1f5f9; color: #475569; border: none; border-radius: 8px; padding: 8px 16px; width: fit-content;"
                                        >
                                            <i class="bi bi-x-circle"></i> Limpiar filtros
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if (count($data) > 0)
                            <tfoot>
                                <tr style="background: #f8fafc; font-weight: 700;">
                                    <td
                                        colspan="7"
                                        class="text-end"
                                    >TOTALES:</td>
                                    <td class="text-end">{{ number_format($totalPeso, 2) }}</td>
                                    <td></td>
                                    <td class="text-end">${{ number_format($totalIva, 2) }}</td>
                                    <td class="text-end">${{ number_format($totalImporte, 2) }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </x-card-gradient-header>
</x-page-container>
