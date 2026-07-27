<x-page-container title="Dashboard de Tiendas">

    <!-- SECCIÓN 1: FILTROS -->
    <x-card-gradient-header
        icon="speedometer2"
        title="Dashboard de Tiendas"
        subtitle="Resumen de ventas y rendimiento por tienda"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form action="{{ route('DashTiendas') }}">
            <x-form.group>
                <x-form.select
                    name="tienda_id"
                    label="Tienda"
                    icon="shop"
                    col="col-md-3"
                    placeholder="Todas las tiendas"
                    :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                />
                <x-form.date
                    name="fecha_fin"
                    label="Fecha"
                    icon="calendar3"
                    col="col-md-2"
                    :autofocus="true"
                />
                <x-form.text
                    name="pos"
                    label="Pedido"
                    icon="search"
                    placeholder="POS_000000"
                    col="col-md-2"
                />
                <x-form.checkbox-input
                    name="detallado"
                    label="Detalle"
                    :checked="request('detallado') == 'on'"
                />
            </x-form.group>
            <div class="col-md-3 d-flex gap-2">
                <x-form.submit
                    text="Buscar"
                    icon="funnel"
                    class="flex-grow-1"
                />
                <x-form.clear />
            </div>
        </x-form.form>

        <!-- SECCIÓN 2: KPIs -->
        <div class="p-4">
            <div class="row g-3 mb-4">

                <!-- Ventas Hoy -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: var(--kpi-purple-bg);"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: var(--kpi-circle-bg); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: var(--kpi-purple-text); font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;"
                                >Ventas Hoy</span>
                                <i
                                    class="bi bi-cash-stack"
                                    style="color: var(--kpi-icon-purple); font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.5rem;"
                            >${{ number_format($kpis['ventas_hoy'] ?? 0, 2) }}</h3>
                            <div class="d-flex align-items-center gap-1">
                                <span
                                    style="color: {{ ($kpis['ventas_vs_ayer'] ?? 0) >= 0 ? '#10b981' : '#ef4444' }}; font-size: 0.78rem; font-weight: 600;"
                                >
                                    <i
                                        class="bi bi-arrow-{{ ($kpis['ventas_vs_ayer'] ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                    {{ abs($kpis['ventas_vs_ayer'] ?? 0) }}%
                                </span>
                                <span style="color: var(--kpi-sub-color); font-size: 0.75rem;">vs día anterior</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tiendas Activas -->
                <div class="col-xl-2 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: var(--kpi-green-bg);"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: var(--kpi-circle-green-bg); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: var(--kpi-green-text-icon); font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;"
                                >Tiendas</span>
                                <i
                                    class="bi bi-shop"
                                    style="color: var(--kpi-icon-green); font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.5rem;"
                            >
                                {{ $kpis['tiendas_activas'] ?? 0 }}<span
                                    style="font-size: 0.9rem; color: var(--kpi-sub-color);"
                                >/{{ $kpis['total_tiendas'] ?? 0 }}</span>
                            </h3>
                            <span
                                style="color: #10b981; font-size: 0.78rem; font-weight: 500;">{{ $kpis['porcentaje_activas'] ?? 0 }}%
                                activas</span>
                        </div>
                    </div>
                </div>

                <!-- Promedio Tickets -->
                <div class="col-xl-2 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: var(--kpi-orange-bg);"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: var(--kpi-circle-orange-bg); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: var(--kpi-orange-text); font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;"
                                >Prom. Ticket</span>
                                <i
                                    class="bi bi-receipt"
                                    style="color: var(--kpi-icon-orange); font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.5rem;"
                            >${{ number_format($kpis['promedio_tickets'] ?? 0, 2) }}</h3>
                            <span
                                style="color: var(--kpi-sub-color); font-size: 0.78rem;">{{ $kpis['tickets_hoy'] ?? 0 }}
                                tickets</span>
                        </div>
                    </div>
                </div>

                <!-- Facturas -->
                <div class="col-xl-2 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: var(--kpi-orange-bg);"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: var(--kpi-circle-orange-bg); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: var(--kpi-orange-text); font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;"
                                >Facturas</span>
                                <i
                                    class="bi bi-file-text"
                                    style="color: var(--kpi-icon-orange); font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.5rem;"
                            >{{ $kpis['facturas_hoy'] ?? 0 }}</h3>
                            <span
                                style="color: {{ ($kpis['facturas_pendientes'] ?? 0) > 0 ? '#ef4444' : '#10b981' }}; font-size: 0.78rem; font-weight: 500;"
                            >
                                {{ $kpis['facturas_pendientes'] ?? 0 }} pendientes
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Kilos Vendidos -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: var(--kpi-purple-bg);"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: var(--kpi-circle-bg); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: var(--text-subtle); font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;"
                                >Kilos Vendidos</span>
                                <i
                                    class="bi bi-box"
                                    style="color: var(--text-secondary); font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.5rem;"
                            >{{ number_format($kpis['kilos_hoy'] ?? 0, 1) }} kg</h3>
                            <span
                                style="color: var(--kpi-sub-color); font-size: 0.78rem;">{{ $kpis['kilos_promedio'] ?? 0 }}
                                kg/día</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 3: GRÁFICAS Y TABLAS -->
        <div class="px-4 pb-4">
            <div class="row g-4">

                <!-- Gráfica de Ventas -->
                <div class="col-xl-8">
                    <div class="card-chart rounded p-4 shadow-sm">
                        @php
                            $hasData =
                                isset($graficaVentas['labels'], $graficaVentas['data']) &&
                                is_array($graficaVentas['labels']) &&
                                is_array($graficaVentas['data']) &&
                                count($graficaVentas['labels']) > 0 &&
                                count($graficaVentas['data']) > 0;
                        @endphp
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5
                                class="mb-0"
                                style="font-weight: 600; color: var(--text-primary); font-size: 1rem;"
                            >Ventas Diarias</h5>
                            @if ($hasData)
                                <div class="btn-group">
                                    <button
                                        type="button"
                                        class="btn btn-sm periodo-btn active"
                                        data-periodo="hoy"
                                    >Hoy</button>
                                    <button
                                        type="button"
                                        class="btn btn-sm periodo-btn"
                                        data-periodo="7d"
                                    >7 días</button>
                                    <button
                                        type="button"
                                        class="btn btn-sm periodo-btn"
                                        data-periodo="30d"
                                    >30 días</button>
                                    <button
                                        type="button"
                                        class="btn btn-sm periodo-btn"
                                        data-periodo="90d"
                                    >90 días</button>
                                </div>
                            @endif
                        </div>
                        <div
                            class="position-relative"
                            style="height: 300px;"
                        >
                            @if ($hasData)
                                <canvas id="ventasChart"></canvas>
                            @else
                                <div class="d-flex justify-content-center align-items-center h-100">
                                    <div class="text-center">
                                        <i
                                            class="fa fa-bar-chart"
                                            style="font-size: 3rem; color: var(--text-muted);"
                                        ></i>
                                        <p
                                            class="mt-2"
                                            style="color: var(--text-secondary); font-size: 0.85rem;"
                                        >No hay registros de ventas para el período</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Top Productos -->
                <div class="col-xl-4">
                    <div class="card-chart rounded p-4 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5
                                class="mb-0"
                                style="font-weight: 600; color: var(--text-primary); font-size: 1rem;"
                            >
                                <i
                                    class="fa fa-star me-2"
                                    style="color: #f59e0b;"
                                ></i>Productos más Vendidos
                            </h5>
                            <span
                                style="background: var(--bg-subtle); color: var(--text-subtle); padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 500;"
                            >
                                {{ count($topProductos) }} prod.
                            </span>
                        </div>
                        @if (empty($topProductos) || count($topProductos) == 0)
                            <div class="d-flex justify-content-center align-items-center h-75">
                                <div class="text-center">
                                    <i
                                        class="fa fa-inbox"
                                        style="font-size: 3rem; color: var(--text-muted);"
                                    ></i>
                                    <p
                                        class="mt-2"
                                        style="color: var(--text-secondary); font-size: 0.85rem;"
                                    >Sin datos para mostrar</p>
                                </div>
                            </div>
                        @else
                            <div
                                class="table-responsive"
                                style="max-height: 300px; overflow-y: auto;"
                            >
                                <table class="table-hover table-custom table">
                                    <thead>
                                        <tr>
                                            <th style="font-size: 0.75rem;">Producto</th>
                                            <th
                                                class="text-end"
                                                style="font-size: 0.75rem;"
                                            >Ventas</th>
                                            <th
                                                class="text-end"
                                                style="font-size: 0.75rem;"
                                            >Kilos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topProductos as $producto)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div
                                                            class="rounded-circle d-flex align-items-center justify-content-center icon-circle-blue">
                                                            <i
                                                                class="fa fa-cube"
                                                                style="color: var(--btn-blue-text); font-size: 0.7rem;"
                                                            ></i>
                                                        </div>
                                                        <span
                                                            class="text-truncate"
                                                            style="max-width: 120px; font-size: 0.8rem;"
                                                            title="{{ $producto->NomArticulo }}"
                                                        >
                                                            {{ $producto->NomArticulo }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td
                                                    class="text-end"
                                                    style="font-weight: 500; font-size: 0.8rem;"
                                                >${{ number_format($producto->ventas, 2) }}</td>
                                                <td
                                                    class="text-end"
                                                    style="font-weight: 500; font-size: 0.8rem;"
                                                >{{ number_format($producto->kilos, 1) }} kg</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Gráfica Último Mes -->
                <div class="col-xl-8">
                    <div class="card-chart rounded p-4 shadow-sm">
                        @php
                            $hasDataMes =
                                isset($graficaUltimoMes['labels'], $graficaUltimoMes['data']) &&
                                is_array($graficaUltimoMes['labels']) &&
                                is_array($graficaUltimoMes['data']) &&
                                count($graficaUltimoMes['labels']) > 0 &&
                                count($graficaUltimoMes['data']) > 0;
                        @endphp
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5
                                class="mb-0"
                                style="font-weight: 600; color: var(--text-primary); font-size: 1rem;"
                            >Último Mes</h5>
                        </div>
                        <div
                            class="position-relative"
                            style="height: 300px;"
                        >
                            @if ($hasDataMes)
                                <canvas id="ultimoMesChart"></canvas>
                            @else
                                <div class="d-flex justify-content-center align-items-center h-100">
                                    <div class="text-center">
                                        <i
                                            class="fa fa-line-chart"
                                            style="font-size: 3rem; color: var(--text-muted);"
                                        ></i>
                                        <p
                                            class="mt-2"
                                            style="color: var(--text-secondary); font-size: 0.85rem;"
                                        >No hay registros para el período</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Mermas -->
                <div class="col-xl-4">
                    <div class="card-chart rounded p-4 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5
                                class="mb-0"
                                style="font-weight: 600; color: var(--text-primary); font-size: 1rem;"
                            >
                                <i
                                    class="fa fa-exclamation-triangle me-2"
                                    style="color: #ef4444;"
                                ></i>Productos con más Mermas
                            </h5>
                            <span
                                style="background: var(--bg-subtle); color: var(--text-subtle); padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 500;"
                            >
                                {{ count($topMermas) }} prod.
                            </span>
                        </div>
                        @if (empty($topMermas) || count($topMermas) == 0)
                            <div class="d-flex justify-content-center align-items-center h-75">
                                <div class="text-center">
                                    <i
                                        class="fa fa-inbox"
                                        style="font-size: 3rem; color: var(--text-muted);"
                                    ></i>
                                    <p
                                        class="mt-2"
                                        style="color: var(--text-secondary); font-size: 0.85rem;"
                                    >Sin datos para mostrar</p>
                                </div>
                            </div>
                        @else
                            <div
                                class="table-responsive"
                                style="max-height: 300px; overflow-y: auto;"
                            >
                                <table class="table-hover table-custom table">
                                    <thead>
                                        <tr>
                                            <th style="font-size: 0.75rem;">Producto</th>
                                            <th
                                                class="text-end"
                                                style="font-size: 0.75rem;"
                                            >Kilos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topMermas as $merma)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div
                                                            class="rounded-circle d-flex align-items-center justify-content-center"
                                                            style="background: var(--tag-red-bg); width: 28px; height: 28px;"
                                                        >
                                                            <i
                                                                class="fa fa-trash"
                                                                style="color: var(--tag-red-text); font-size: 0.7rem;"
                                                            ></i>
                                                        </div>
                                                        <span
                                                            class="text-truncate"
                                                            style="max-width: 150px; font-size: 0.8rem;"
                                                        >
                                                            {{ $merma->NomArticulo }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <span class="tags-red">{{ number_format($merma->kilos_merma, 1) }}
                                                        kg</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tabla de Tiendas -->
                <div class="col-12">
                    <div class="card-chart rounded p-4 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5
                                class="mb-0"
                                style="font-weight: 600; color: var(--text-primary); font-size: 1rem;"
                            >
                                <i
                                    class="bi bi-shop me-2"
                                    style="color: var(--text-secondary);"
                                ></i>Tiendas - Rendimiento
                            </h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table-hover table-custom table">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-shop me-1"></i>Tienda</th>
                                        <th class="text-end"><i class="bi bi-receipt me-1"></i>Tickets</th>
                                        <th class="text-end"><i class="bi bi-cash me-1"></i>Ticket Prom.</th>
                                        <th class="text-end"><i class="bi bi-cash-stack me-1"></i>Ventas Hoy</th>
                                        <th class="text-end"><i class="bi bi-box me-1"></i>Kilos Hoy</th>
                                        <th class="text-end"><i class="bi bi-file-text me-1"></i>Facturas</th>
                                        <th class="text-end"><i class="bi bi-exclamation-triangle me-1"></i>Sin Ped.
                                        </th>
                                        <th><i class="bi bi-gear me-1"></i>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tiendasRendimiento as $tienda)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div
                                                        class="rounded-circle d-flex align-items-center justify-content-center"
                                                        style="background: var(--kpi-circle-green-bg); width: 28px; height: 28px;"
                                                    >
                                                        <i
                                                            class="bi bi-shop"
                                                            style="color: var(--kpi-icon-green); font-size: 0.75rem;"
                                                        ></i>
                                                    </div>
                                                    <span
                                                        style="font-weight: 600; color: var(--text-primary); font-size: 0.85rem;"
                                                    >{{ $tienda->NomTienda }}</span>
                                                </div>
                                            </td>
                                            <td
                                                class="text-end"
                                                style="font-weight: 500;"
                                            >{{ $tienda->tickets }}</td>
                                            <td
                                                class="text-end"
                                                style="font-weight: 500;"
                                            >${{ number_format($tienda->promedio_ticket, 2) }}</td>
                                            <td
                                                class="text-end"
                                                style="font-weight: 500;"
                                            >${{ number_format($tienda->total_ventas, 2) }}</td>
                                            <td
                                                class="text-end"
                                                style="font-weight: 500;"
                                            >{{ number_format($tienda->total_kilos, 1) }} kg</td>
                                            <td class="text-end">
                                                <span class="tags-blue">{{ $tienda->solicitudes_factura }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span
                                                    class="{{ $tienda->tickets_sin_pedido > 0 ? 'tags-red' : 'tags-green' }}"
                                                >
                                                    {{ $tienda->tickets_sin_pedido }}/{{ $tienda->tickets_sin_bill }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    @php $fecha = request()->get('fecha_fin', date('Y-m-d')); @endphp
                                                    <a
                                                        href="{{ route('DashTienda', ['tienda_id' => $tienda->IdTienda, 'fecha_fin' => $fecha]) }}"
                                                        class="btn-blue"
                                                        title="Ver corte de tienda"
                                                    >
                                                        <i class="bi bi-eye"></i> Corte
                                                    </a>
                                                    <a
                                                        href="{{ route('DashCorte', ['tienda_id' => $tienda->IdTienda, 'detallado' => 'on', 'fecha_fin' => $fecha]) }}"
                                                        class="btn-gray"
                                                        title="Ver corte detallado"
                                                    >
                                                        <i class="bi bi-list"></i> Detalle
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td
                                                colspan="8"
                                                class="py-5 text-center"
                                            >
                                                <i
                                                    class="bi bi-inbox"
                                                    style="font-size: 2.5rem; color: var(--text-muted);"
                                                ></i>
                                                <p
                                                    class="mt-2"
                                                    style="color: var(--text-secondary); font-size: 0.85rem;"
                                                >No hay registros de corte diario</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-card-gradient-header>

    @section('scripts')
        <script>
            let ventasChart = null;
            let ultimoMesChart = null;

            $(document).ready(function() {
                const ctx = document.getElementById('ventasChart')?.getContext('2d');
                const ultimoMes = document.getElementById('ultimoMesChart')?.getContext('2d');

                var gradientStart = getComputedStyle(document.documentElement).getPropertyValue('--gradient-start')
                    .trim() || '#1e293b';
                var chartFill = getComputedStyle(document.documentElement).getPropertyValue('--chart-fill').trim() ||
                    'rgba(30,41,59,0.1)';

                if (ctx) {
                    ventasChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($graficaVentas['labels'] ?? []) !!},
                            datasets: [{
                                label: 'Ventas',
                                data: {!! json_encode($graficaVentas['data'] ?? []) !!},
                                backgroundColor: chartFill,
                                borderColor: gradientStart,
                                borderWidth: 2,
                                borderRadius: 6,
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: gradientStart,
                                    callbacks: {
                                        label: function(context) {
                                            return '$' + context.parsed.y.toLocaleString('es-MX');
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)'
                                    },
                                    ticks: {
                                        color: '#6b7280',
                                        callback: function(value) {
                                            return '$' + value.toLocaleString('es-MX');
                                        }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: '#6b7280'
                                    }
                                }
                            }
                        }
                    });
                }

                if (ultimoMes) {
                    ultimoMesChart = new Chart(ultimoMes, {
                        type: 'line',
                        data: {
                            labels: {!! json_encode($graficaUltimoMes['labels'] ?? []) !!},
                            datasets: [{
                                label: 'Ventas',
                                data: {!! json_encode($graficaUltimoMes['data'] ?? []) !!},
                                borderColor: gradientStart,
                                backgroundColor: chartFill,
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: gradientStart,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: gradientStart,
                                    callbacks: {
                                        label: function(context) {
                                            return '$' + context.parsed.y.toLocaleString('es-MX');
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)'
                                    },
                                    ticks: {
                                        color: '#6b7280',
                                        callback: function(value) {
                                            return '$' + value.toLocaleString('es-MX');
                                        }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: '#6b7280'
                                    }
                                }
                            }
                        }
                    });
                }

                // Botones de período
                $('.periodo-btn').click(function() {
                    const periodo = $(this).data('periodo');
                    actualizarGrafica(periodo);
                });
            });

            function actualizarGrafica(periodo) {
                $.ajax({
                    url: '{{ route('DashTiendas.grafica') }}',
                    type: 'GET',
                    data: {
                        periodo: periodo,
                        fecha_inicio: $('input[name="fecha_inicio"]').val(),
                        fecha_fin: $('input[name="fecha_fin"]').val(),
                    },
                    success: function(response) {
                        if (ventasChart) {
                            ventasChart.data.labels = response.labels;
                            ventasChart.data.datasets[0].data = response.data;
                            ventasChart.update();
                        }
                    }
                });
            }
        </script>
    @endsection
</x-page-container>
