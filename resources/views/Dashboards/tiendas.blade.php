@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Dashboard de Tiendas')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <!--CORTE DIARIO DE TIENDA-->
    <x-layout.page-container>

        <!-- SECCIÓN 1: FILTROS -->
        <x-layout.section-card>
            <!-- Título y botones principales -->
            <div class="d-flex justify-content-sm-between align-items-end align-items-sm-start flex-column flex-sm-row mb-2">
                <x-title titulo="Dashboard de Tiendas" />
                <div class="d-flex gap-2">
                    <x-filters.buttons.refresh-button />
                    <x-filters.buttons.home-button />
                </div>
            </div>

            <!-- Formulario de filtros -->
            <x-filters.filter-form>
                <!-- Filtros Básicos -->
                <x-filters.filter-group>
                    <x-filters.inputs.select-input
                        name="tienda_id"
                        label="Tienda"
                        :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                    />
                    <x-filters.inputs.date-input
                        name="fecha_fin"
                        label="Fecha"
                        :value="request('fecha_fin')"
                        :autofocus="true"
                    />
                    <x-filters.inputs.text-input
                        name="pos"
                        label="Pedido"
                        placeholder="Buscar por Pedido POS_000000"
                    />
                    <x-filters.inputs.checkbox-input
                        name="detallado"
                        label="Detallado"
                        :checked="request('detallado') == 'on'"
                        helperText="Ver detallado"
                    />
                </x-filters.filter-group>
                <x-slot:buttons>
                    <x-filters.buttons.clear-button />
                    {{-- <x-filters.buttons.advanced-button
                        :active="$filtrosAvanzadosActivos"
                        :hasBadge="true"
                    /> --}}
                    <x-filters.buttons.submit-button />
                </x-slot:buttons>
            </x-filters.filter-form>
        </x-layout.section-card>

        <!-- SECCIÓN 2: KPIs -->
        <div class="flex-shrink-0">
            <div class="row g-4">
                <!-- Ventas Hoy -->
                <x-kpi.kpi-card
                    title="Ventas Hoy"
                    :value="$kpis['ventas_hoy'] ?? 0"
                    :subtitle="($kpis['ventas_vs_ayer'] ?? 0) . '% vs día anterior'"
                    color="purple"
                    icon="components.icons.cash"
                    currency="true"
                    colClass="col-xl-3 col-md-4 col-sm-6 col-6"
                />

                <!-- Tiendas Activas -->
                @php
                    $tiendasActivas =
                        !empty($kpis['tiendas_activas']) && !empty($kpis['total_tiendas'])
                            ? $kpis['tiendas_activas'] . '/' . $kpis['total_tiendas']
                            : '0/0';
                @endphp
                <x-kpi.kpi-card
                    title="Tiendas Activas"
                    :value="$tiendasActivas"
                    :subtitle="($kpis['porcentaje_activas'] ?? 0) . '% activas'"
                    color="success"
                    icon="components.icons.store"
                    colClass="col-xl-2 col-md-4 col-sm-6 col-6"
                />

                <!-- Promedio Tickets -->
                <x-kpi.kpi-card
                    title="Promedio Tickets"
                    :value="'$' . ($kpis['promedio_tickets'] ?? 0)"
                    :subtitle="($kpis['tickets_hoy'] ?? 0) . ' tickets'"
                    color="warning"
                    icon="components.icons.ticket"
                    colClass="col-xl-2 col-md-4 col-sm-6 col-6"
                />

                <!-- Facturas Pendientes -->
                <x-kpi.kpi-card
                    title="Facturas"
                    :value="$kpis['facturas_hoy'] ?? 0"
                    :subtitle="($kpis['facturas_pendientes'] ?? 0) . ' pendientes'"
                    color="orange"
                    icon="components.icons.file-text"
                    colClass="col-xl-2 col-md-4 col-sm-6 col-6"
                />

                <!-- Kilos Vendidos -->
                @php
                    $kilosVendidos = number_format($kpis['kilos_hoy'] ?? 0, 1) . ' kg';
                @endphp
                <x-kpi.kpi-card
                    title="Kilos Vendidos"
                    :value="$kilosVendidos"
                    :subtitle="($kpis['kilos_promedio'] ?? 0) . ' kg/día'"
                    color="purple"
                    icon="components.icons.box"
                    colClass="col-xl-3 col-md-4 col-sm-6 col-6"
                />
            </div>
        </div>

        <!-- SECCIÓN 3: GRÁFICAS Y TABLAS -->
        <div class="row g-4 pb-4">
            <!-- Gráfica de Ventas -->
            <div class="col-xl-8">
                <div
                    class="card border-0 p-4"
                    style="border-radius: 10px; height: 400px; background-color: white; border: 1px solid #e5e7eb;"
                >
                    @php
                        $hasData =
                            isset($graficaVentas['labels'], $graficaVentas['data']) &&
                            is_array($graficaVentas['labels']) &&
                            is_array($graficaVentas['data']) &&
                            count($graficaVentas['labels']) > 0 &&
                            count($graficaVentas['data']) > 0;
                    @endphp
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 text-gray-800">Ventas Diarias</h5>
                        @if ($hasData)
                            <div class="btn-group">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-dark-outline periodo-btn {{ request()->get('fecha_fin', date('Y-m-d')) == date('Y-m-d') ? 'active' : '' }} border-gray-300"
                                    data-periodo="hoy"
                                >Hoy</button>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-dark-outline periodo-btn border-gray-300"
                                    data-periodo="7d"
                                >7 días</button>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-dark-outline periodo-btn border-gray-300"
                                    data-periodo="30d"
                                >30 días</button>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-dark-outline periodo-btn border-gray-300"
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
                                <x-table-empty-state
                                    title="No hay registros de corte diario"
                                    icon="ticket"
                                    :message="'No se encontraron ventas registradas en el corte diario para el período seleccionado.'"
                                    :suggestion="'Modifica la fecha o los filtros de búsqueda para ver otros cortes diarios.'"
                                />
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            <!-- Top Productos -->
            <div class="col-xl-4">
                <div
                    class="card h-100 border-0 p-4"
                    style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;"
                >
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-semibold">📦 Productos más Vendidos</h6>
                        <span class="bg-gray-200 text-gray-800">{{ count($topProductos) }} productos</span>
                    </div>
                    @if (empty($topProductos) || count($topProductos) == 0)
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <x-table-empty-state
                                title="Sin datos para mostrar"
                                icon="ticket"
                                :message="'No se encontraron ventas realizadas por empleados en el período seleccionado.'"
                            />
                        </div>
                    @else
                        <div
                            class="table-responsive content-table-sm"
                            {{-- style="max-height: 320px;" --}}
                        >
                            <table class="table">
                                <thead class="table-head">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-end">Ventas</th>
                                        <th class="text-end">Kilos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topProductos as $index => $producto)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="me-2 rounded p-1"
                                                        style="background-color: rgba(30, 66, 159, 0.1);"
                                                    >
                                                        <div style="color: #1e429f; width: 16px; height: 16px;">
                                                            @include('components.icons.box')
                                                        </div>
                                                    </div>
                                                    <span
                                                        class="text-truncate puntitos"
                                                        style="max-width: 150px;"
                                                        title="{{ $producto->NomArticulo }}"
                                                    >
                                                        {{ $producto->NomArticulo }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="fw-500 text-end">${{ number_format($producto->ventas, 2) }}</td>
                                            <td class="fw-500 text-end">{{ number_format($producto->kilos, 1) }} kg</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Gráfica Ultimo Mes -->
            <div class="col-xl-8">
                <div
                    class="card border-0 p-4"
                    style="border-radius: 10px; height: 400px; background-color: white; border: 1px solid #e5e7eb;"
                >
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 text-gray-800">Último mes</h5>
                    </div>
                    <div
                        class="position-relative"
                        style="height: 300px;"
                    >
                        @php
                            $hasData =
                                isset($graficaUltimoMes['labels'], $graficaUltimoMes['data']) &&
                                is_array($graficaUltimoMes['labels']) &&
                                is_array($graficaUltimoMes['data']) &&
                                count($graficaUltimoMes['labels']) > 0 &&
                                count($graficaUltimoMes['data']) > 0;
                        @endphp

                        @if ($hasData)
                            <canvas id="ultimoMesChart"></canvas>
                        @else
                            <div class="d-flex justify-content-center align-items-center h-100">
                                <x-table-empty-state
                                    title="No hay registros de corte diario"
                                    icon="ticket"
                                    :message="'No se encontraron ventas registradas en el corte diario para el período seleccionado.'"
                                    :suggestion="'Modifica la fecha o los filtros de búsqueda para ver otros cortes diarios.'"
                                />
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Mermas y Métricas -->
            <div class="col-xl-4">
                <div
                    class="card h-100 border-0 p-4"
                    style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;"
                >
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-semibold">🥫 Productos con más Mermas</h6>
                        <span class="bg-gray-200 text-gray-800">{{ count($topMermas) }} productos</span>
                    </div>
                    @if (empty($topProductos) || count($topProductos) == 0)
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <x-table-empty-state
                                title="Sin datos para mostrar"
                                icon="ticket"
                                :message="'No se encontraron ventas realizadas por empleados en el período seleccionado.'"
                            />
                        </div>
                    @else
                        <div
                            class="table-responsive content-table-sm"
                            style="max-height: 250px;"
                        >
                            <table class="table">
                                <tbody>
                                    @foreach ($topMermas as $merma)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="me-2 rounded p-1"
                                                        style="background-color: rgba(155, 28, 28, 0.1);"
                                                    >
                                                        <div style="color: #9b1c1c; width: 16px; height: 16px;">
                                                            @include('components.icons.trash')
                                                        </div>
                                                    </div>
                                                    <span
                                                        class="text-truncate puntitos"
                                                        style="max-width: 120px;"
                                                    >
                                                        {{ $merma->NomArticulo }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <span class="tags-red">
                                                    {{ number_format($merma->kilos_merma, 1) }}kg
                                                </span>
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
            <div class="col-xl-12">
                <div
                    class="card border-0 p-4"
                    style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;"
                >
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 text-gray-800">Tiendas - Rendimiento</h5>
                        {{-- <div
                            class="input-group"
                            style="width: 250px;"
                        >
                            <input
                                type="text"
                                class="form-control border-gray-300"
                                placeholder="Buscar tienda..."
                                id="searchTienda"
                            >
                            <button class="btn btn-dark-outline border-start-0 border-gray-300">
                                @include('components.icons.search')
                            </button>
                        </div> --}}
                    </div>
                    <div class="table-responsive content-table">
                        <table class="table">
                            <thead class="table-head">
                                <tr>
                                    <th>Tienda</th>
                                    <th class="text-end">Tickets</th>
                                    <th class="text-end">Ticket Prom.</th>
                                    <th class="text-end">Ventas Hoy</th>
                                    <th class="text-end">Kilos Hoy</th>
                                    <th class="text-end">Facturas</th>
                                    <th class="text-end">Sin Ped.</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tiendasRendimiento as $tienda)
                                    <tr>
                                        <td class="pb-0">
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="me-2 rounded p-1"
                                                    style="background-color: rgba(3, 84, 63, 0.1);"
                                                >
                                                    <div
                                                        style="color: #03543f; width: 16px; height: 16px; line-height: 16px">
                                                        @include('components.icons.store')
                                                    </div>
                                                </div>
                                                <div>
                                                    <strong class="text-gray-800">{{ $tienda->NomTienda }}</strong>
                                                    {{-- <small class="d-block text-muted">{{ $tienda->Ciudad ?? 'Sin ubicación' }}</small> --}}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-500 pb-0 text-end">{{ $tienda->tickets }}</td>
                                        <td class="fw-500 pb-0 text-end">${{ number_format($tienda->promedio_ticket, 2) }}
                                        </td>
                                        <td class="fw-500 pb-0 text-end">${{ number_format($tienda->total_ventas, 2) }}
                                        </td>
                                        <td class="fw-500 pb-0 text-end">{{ number_format($tienda->total_kilos, 1) }} kg
                                        </td>
                                        <td class="pb-0 text-end">
                                            <span class="tag">
                                                {{ $tienda->solicitudes_factura }}
                                            </span>
                                        </td>
                                        <td class="pb-0 text-end">
                                            <span class="tags-{{ $tienda->tickets_sin_pedido > 0 ? 'red' : 'green' }}">
                                                {{ $tienda->tickets_sin_pedido }}/{{ $tienda->tickets_sin_bill }}
                                            </span>
                                        </td>
                                        <td class="text-nowrap pb-0">
                                            @php
                                                $fecha = request()->get('fecha_fin', date('Y-m-d'));
                                            @endphp

                                            <a
                                                href="{{ route('DashTienda', ['tienda_id' => $tienda->IdTienda, 'fecha_fin' => $fecha]) }}"
                                                class="btn-table btn-table-icon"
                                                title="Ver corte de tienda"
                                            >
                                                @include('components.icons.store')
                                            </a>

                                            <a
                                                href="{{ route('DashCorte', ['tienda_id' => $tienda->IdTienda, 'detallado' => 'on', 'fecha_fin' => $fecha]) }}"
                                                class="btn-table btn-table-icon"
                                                title="Ver corte detallado"
                                            >
                                                @include('components.icons.list')
                                            </a>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td
                                            colspan="12"
                                            class="py-5 text-center"
                                        >
                                            <x-table-empty-state
                                                title="No hay registros de corte diario"
                                                icon="credit-card"
                                                :message="'No se encontraron ventas registradas en el corte diario para el período seleccionado.'"
                                                :suggestion="'Modifica la fecha o los filtros de búsqueda para ver otros cortes diarios.'"
                                            />
                                        </td>

                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </x-layout.page-container>
@endsection

@section('scripts')
    <script>
        let ventasChart = null;
        let ultimoMesChart = null;
        $(document).ready(function() {
            // Inicializar gráfica de ventas
            const ctx = document.getElementById('ventasChart')?.getContext('2d');
            const ultimoMes = document.getElementById('ultimoMesChart')?.getContext('2d');

            ventasChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($graficaVentas['labels'] ?? []) !!},
                    datasets: [{
                        label: 'Ventas',
                        data: {!! json_encode($graficaVentas['data'] ?? []) !!},
                        borderColor: '#1e293b',
                        backgroundColor: 'rgba(30, 41, 59, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#1e293b',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
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
                            backgroundColor: '#1e293b',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#374151',
                            borderWidth: 1,
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

            ultimoMesChart = new Chart(ultimoMes, {
                type: 'line',
                data: {
                    labels: {!! json_encode($graficaUltimoMes['labels'] ?? []) !!},
                    datasets: [{
                        label: 'Ventas',
                        data: {!! json_encode($graficaUltimoMes['data'] ?? []) !!},
                        borderColor: '#1e293b',
                        backgroundColor: 'rgba(30, 41, 59, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#1e293b',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
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
                            backgroundColor: '#1e293b',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#374151',
                            borderWidth: 1,
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

            // Botones de período
            $('.periodo-btn').click(function() {
                $('.periodo-btn').removeClass('active');
                $(this).addClass('active');

                const periodo = $(this).data('periodo');
                actualizarGrafica(periodo);
            });

            // Buscar tienda
            $('#searchTienda').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                $('table tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });

        function actualizarGrafica(periodo) {
            // AJAX para actualizar la gráfica según el período
            $.ajax({
                url: '{{ route('DashTiendas.grafica') }}',
                type: 'GET',
                data: {
                    periodo: periodo,
                    fecha_inicio: $('input[name="fecha_inicio"]').val(),
                    fecha_fin: $('input[name="fecha_fin"]').val(),
                },
                success: function(response) {
                    // console.log({
                    //     periodo: periodo,
                    //     fecha_inicio: $('input[name="fecha_inicio"]').val(),
                    //     fecha_fin: $('input[name="fecha_fin"]').val(),
                    // });
                    // console.log(response.labels);
                    // console.log(response.data);
                    // console.log('---------------------------------');
                    // console.log(response.periodo);
                    // console.log(response.fechaInicio);
                    // console.log(response.fechaFin);


                    ventasChart.data.labels = response.labels;
                    ventasChart.data.datasets[0].data = response.data;
                    ventasChart.update();
                }
            });
        }
    </script>
@endsection
