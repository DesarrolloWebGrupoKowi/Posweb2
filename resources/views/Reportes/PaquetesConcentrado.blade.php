<x-page-container title="Concentrado de Paquetes">

    <x-card-gradient-header
        icon="gift"
        title="Concentrado de Paquetes"
        subtitle="Reporte de paquetes comercializados"
    >
        <x-slot:buttons>
            <a
                href="/ExportarConcentradoPaquetes?{{ http_build_query(request()->only(['idTienda', 'fecha_inicio', 'fecha_fin', 'cod_articulo', 'vista'])) }}"
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
        <x-form.form action="/ConcentradoPaquetes">
            <input
                type="hidden"
                name="vista"
                value="{{ $vista }}"
            >
            <x-form.group>
                <x-form.select
                    name="idTienda"
                    label="Tienda"
                    icon="shop"
                    col="col-md-3"
                    :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                    placeholder="Todas las tiendas"
                />
                <x-form.date
                    name="fecha_inicio"
                    label="Fecha Inicio"
                    icon="calendar3"
                    col="col-md-2"
                    :value="$fechaInicio"
                    :autofocus="true"
                />
                <x-form.date
                    name="fecha_fin"
                    label="Fecha Fin"
                    icon="calendar3"
                    col="col-md-2"
                    :value="$fechaFin"
                />
                <x-form.text
                    name="cod_articulo"
                    label="Código o Artículo"
                    icon="search"
                    placeholder="Código o nombre..."
                    col="col-md-2"
                    :value="$codArticulo"
                />
            </x-form.group>
            <div class="col-md-3 d-flex gap-2">
                <x-form.submit
                    text="Buscar"
                    icon="funnel"
                    class="flex-grow-1"
                />
                <x-form.clear url="/ConcentradoPaquetes" />
            </div>
        </x-form.form>

        <!-- KPIs -->
        <div class="p-4 pb-0">
            <div class="d-flex flex-wrap gap-3">
                <div
                    class="kpi-card"
                    style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); flex: 1; min-width: 160px; padding: 16px 20px;"
                >
                    <div
                        style="position: absolute; top: -15px; right: -15px; width: 60px; height: 60px; background: rgba(59, 130, 246, 0.08); border-radius: 50%;">
                    </div>
                    <div style="position: relative; z-index: 1;">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span
                                style="color: #1d4ed8; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                            >Paquetes</span>
                            <i
                                class="bi bi-gift"
                                style="color: #3b82f6; font-size: 1.1rem; opacity: 0.6;"
                            ></i>
                        </div>
                        <h3
                            class="mb-1"
                            style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.3rem;"
                        >{{ $kpis['total_paquetes'] ?? 0 }}</h3>
                        <span style="color: var(--kpi-sub-color); font-size: 0.75rem;">Tipos de paquetes</span>
                    </div>
                </div>
                <div
                    class="kpi-card"
                    style="background: var(--kpi-purple-bg); flex: 1; min-width: 160px; padding: 16px 20px;"
                >
                    <div
                        style="position: absolute; top: -15px; right: -15px; width: 60px; height: 60px; background: var(--kpi-circle-bg); border-radius: 50%;">
                    </div>
                    <div style="position: relative; z-index: 1;">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span
                                style="color: var(--kpi-purple-text); font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                            >Importe Total</span>
                            <i
                                class="bi bi-cash-stack"
                                style="color: var(--kpi-icon-purple); font-size: 1.1rem; opacity: 0.6;"
                            ></i>
                        </div>
                        <h3
                            class="mb-1"
                            style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.3rem;"
                        >${{ number_format($kpis['total_importe'] ?? 0, 2) }}</h3>
                        <span style="color: var(--kpi-sub-color); font-size: 0.75rem;">Monto total</span>
                    </div>
                </div>
                <div
                    class="kpi-card"
                    style="background: var(--kpi-orange-bg); flex: 1; min-width: 160px; padding: 16px 20px;"
                >
                    <div
                        style="position: absolute; top: -15px; right: -15px; width: 60px; height: 60px; background: var(--kpi-circle-orange-bg); border-radius: 50%;">
                    </div>
                    <div style="position: relative; z-index: 1;">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span
                                style="color: var(--kpi-orange-text); font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                            >Registros</span>
                            <i
                                class="bi bi-list-ul"
                                style="color: var(--kpi-icon-orange); font-size: 1.1rem; opacity: 0.6;"
                            ></i>
                        </div>
                        <h3
                            class="mb-1"
                            style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.3rem;"
                        >{{ $data->count() }}</h3>
                        <span style="color: var(--kpi-sub-color); font-size: 0.75rem;">Líneas</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de vista -->
        <div class="px-4 pt-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="section-content-title">
                    <i
                        class="bi bi-table me-2"
                        style="color: var(--text-secondary);"
                    ></i>
                    {{ $vista === 'agrupada' ? 'Concentrado por Tienda' : 'Concentrado General' }}
                </h5>
                <div class="btn-group">
                    <a
                        href="{{ request()->fullUrlWithQuery(['vista' => 'agrupada']) }}"
                        class="btn btn-sm {{ $vista === 'agrupada' ? 'active' : '' }}"
                        style="border-radius: 6px 0 0 6px; {{ $vista === 'agrupada' ? 'background: var(--gradient-start); color: white;' : 'background: var(--btn-gray-bg); color: var(--btn-gray-text);' }} border: none; padding: 6px 12px; font-size: 0.8rem;"
                    >
                        📋 Por Tienda
                    </a>
                    <a
                        href="{{ request()->fullUrlWithQuery(['vista' => 'general']) }}"
                        class="btn btn-sm {{ $vista === 'general' ? 'active' : '' }}"
                        style="border-radius: 0 6px 6px 0; {{ $vista === 'general' ? 'background: var(--gradient-start); color: white;' : 'background: var(--btn-gray-bg); color: var(--btn-gray-text);' }} border: none; padding: 6px 12px; font-size: 0.8rem;"
                    >
                        🎫 General
                    </a>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="p-4">
            <div class="card-chart rounded p-4 shadow-sm">
                <div
                    class="table-responsive"
                    style="overflow-y: auto;"
                >
                    <table class="table-hover table-custom table">
                        <thead style="position: sticky; top: 0; z-index: 2;">
                            <tr>
                                @if ($vista === 'agrupada')
                                    <th><i class="bi bi-shop me-1"></i>Tienda</th>
                                @endif
                                <th><i class="bi bi-upc-scan me-1"></i>Código</th>
                                <th><i class="bi bi-box me-1"></i>Artículo</th>
                                <th class="text-end"><i class="bi bi-cash me-1"></i>Precio</th>
                                <th class="text-end"><i class="bi bi-hash me-1"></i>Cantidad</th>
                                <th><i class="bi bi-rulers me-1"></i>UOM</th>
                                <th><i class="bi bi-gift me-1"></i>Paquete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalCantidad = 0;
                                $totalImporte = 0;
                            @endphp
                            @forelse ($data as $item)
                                @php
                                    $importe = $item->cantidad * $item->PrecioArticulo;
                                    $totalCantidad += $item->cantidad;
                                    $totalImporte += $importe;
                                @endphp
                                <tr>
                                    @if ($vista === 'agrupada')
                                        <td style="font-weight: 500;">{{ $item->NomTienda }}</td>
                                    @endif
                                    <td style="font-weight: 500;">{{ $item->CodArticulo }}</td>
                                    <td
                                        class="text-truncate"
                                        style="max-width: 200px;"
                                        title="{{ $item->NomArticulo }}"
                                    >
                                        {{ $item->NomArticulo }}
                                    </td>
                                    <td
                                        class="text-end"
                                        style="font-weight: 500;"
                                    >${{ number_format($item->PrecioArticulo, 2) }}</td>
                                    <td
                                        class="text-end"
                                        style="font-weight: 500;"
                                    >{{ number_format($item->cantidad, 2) }}</td>
                                    <td>{{ $item->UOM }}</td>
                                    <td><span
                                            class="tags-blue"
                                            style="font-size: 0.7rem;"
                                        >{{ $item->IdPaquete }} - {{ $item->NomPaquete }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="{{ $vista === 'agrupada' ? 7 : 6 }}"
                                        class="py-5 text-center"
                                    >
                                        <i
                                            class="bi bi-inbox"
                                            style="font-size: 2.5rem; color: var(--text-muted);"
                                        ></i>
                                        <p
                                            class="mt-2"
                                            style="color: var(--text-secondary); font-size: 0.85rem;"
                                        >Sin datos disponibles</p>
                                        <a
                                            href="/ConcentradoPaquetes"
                                            class="btn btn-sm d-flex align-items-center mx-auto mt-2 gap-1"
                                            style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border: none; border-radius: 8px; padding: 8px 16px; width: fit-content;"
                                        >
                                            <i class="bi bi-x-circle"></i> Limpiar filtros
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($data->count() > 0)
                            <tfoot>
                                <tr class="bg-table-totals">
                                    <td
                                        colspan="{{ $vista === 'agrupada' ? 3 : 2 }}"
                                        class="text-end"
                                        style="color: var(--text-primary); font-weight: 700;"
                                    >TOTALES:</td>
                                    <td></td>
                                    <td
                                        class="text-end"
                                        style="color: var(--text-primary); font-weight: 700;"
                                    >{{ number_format($totalCantidad, 2) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </x-card-gradient-header>
</x-page-container>
