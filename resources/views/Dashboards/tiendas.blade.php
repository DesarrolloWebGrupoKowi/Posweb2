@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Dashboard de Tiendas')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <div class="container-fluid width-95 d-flex flex-column gap-4 pt-4">

        <!-- HEADER CON FILTROS Y KPIs -->
        <div class="card border-0 p-4"
            style="border-radius: 10px; background-color: white;">
            <div class="row mb-4 gap-4">
                <div class="col-12 col-lg-auto d-flex align-items-center gap-3">
                    @include('components.title', ['titulo' => 'Dashboard por Tiendas'])
                </div>

                <!-- Filtro de Fecha -->
                <x-dashboard-filters :tiendas="$tiendasForm"
                    :showReporte="false"
                    :showTodasTiendas="true" />

            </div>

            <!-- KPIs PRINCIPALES POR TIENDA -->
            <div class="row g-4 mb-4">

                <!-- Solicitudes Facturas -->
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-muted fw-500">Ventas Hoy</h6>
                                    <h3 class="card-title mb-0 text-gray-800">
                                        ${{ number_format($kpis['ventas_hoy'] ?? 0, 2) }}</h3>
                                    <small class="d-block mt-1 text-muted">
                                        {{ $kpis['ventas_vs_ayer'] ?? 0 }}% vs día anterior
                                    </small>
                                </div>
                                <div class="bg-purple-50 rounded-circle d-flex align-items-center justify-content-center"
                                    style="background-color: rgba(30, 66, 159, 0.1); min-width: 44px; height: 44px;">
                                    <div style="color: #1e429f;"
                                        class="d-flex justify-content-center">
                                        @include('components.icons.cash')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tiendas Activas -->
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm h-100"
                        style="border: 1px solid #e5e7eb; background: white;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-muted fw-500">Tiendas Activas</h6>
                                    <h3 class="card-title mb-0 text-gray-800">
                                        {{ $kpis['tiendas_activas'] ?? 0 }}/{{ $kpis['total_tiendas'] ?? 0 }}</h3>
                                    <small class="d-block mt-1 text-muted">
                                        {{ $kpis['porcentaje_activas'] ?? 0 }}% activas
                                    </small>
                                </div>
                                <div class="bg-purple-50 rounded-circle d-flex align-items-center justify-content-center"
                                    style="background-color: rgba(3, 84, 63, 0.1); min-width: 44px; height: 44px;">
                                    <div style="color: #03543f;"
                                        class="d-flex justify-content-center">
                                        @include('components.icons.store')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Solicitudes Factura -->
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm h-100"
                        style="border: 1px solid #e5e7eb; background: white;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-muted fw-500">Facturas Pendientes</h6>
                                    <h3 class="card-title mb-0 text-gray-800">{{ $kpis['facturas_pendientes'] ?? 0 }}</h3>
                                    <small class="d-block mt-1 text-muted">
                                        {{ $kpis['facturas_hoy'] ?? 0 }} hoy
                                    </small>
                                </div>
                                <div class="bg-purple-50 rounded-circle d-flex align-items-center justify-content-center"
                                    style="background-color: rgba(114, 59, 19, 0.1); min-width: 44px; height: 44px;">
                                    <div style="color: #723b13;"
                                        class="d-flex justify-content-center">
                                        @include('components.icons.file-text')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kilos Vendidos -->
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm h-100"
                        style="border: 1px solid #e5e7eb; background: white;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-subtitle mb-2 text-muted fw-500">Kilos Vendidos</h6>
                                    <h3 class="card-title mb-0 text-gray-800">
                                        {{ number_format($kpis['kilos_hoy'] ?? 0, 1) }} kg</h3>
                                    <small class="d-block mt-1 text-muted">
                                        {{ $kpis['kilos_promedio'] ?? 0 }} kg/día
                                    </small>
                                </div>
                                <div class="bg-purple-50 rounded-circle d-flex align-items-center justify-content-center"
                                    style="background-color: rgba(124, 58, 237, 0.1); min-width: 44px; height: 44px;">
                                    <div style="color: #7c3aed;"
                                        class="d-flex justify-content-center">
                                        @include('components.icons.box')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            @include('Alertas.Alertas')
        </div>

        <!-- GRÁFICAS Y TABLAS -->
        <div class="row g-4">
            <!-- Gráfica de Ventas -->
            <div class="col-xl-8">
                <div class="card border-0 p-4"
                    style="border-radius: 10px; height: 400px; background-color: white; border: 1px solid #e5e7eb;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 text-gray-800">Ventas Diarias</h5>
                        <div class="btn-group">
                            <button type="button"
                                class="btn btn-sm btn-dark-outline periodo-btn border-gray-300 {{ request()->get('fecha_fin', date('Y-m-d')) == date('Y-m-d') ? 'active' : '' }}"
                                data-periodo="hoy">Hoy</button>
                            <button type="button"
                                class="btn btn-sm btn-dark-outline periodo-btn border-gray-300"
                                data-periodo="7d">7 días</button>
                            <button type="button"
                                class="btn btn-sm btn-dark-outline periodo-btn border-gray-300"
                                data-periodo="30d">30 días</button>
                            <button type="button"
                                class="btn btn-sm btn-dark-outline periodo-btn border-gray-300"
                                data-periodo="90d">90 días</button>
                        </div>
                    </div>
                    <div class="position-relative"
                        style="height: 300px;">
                        <canvas id="ventasChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Productos -->
            <div class="col-xl-4">
                <div class="card border-0 p-4"
                    style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 text-gray-800">Productos Más Vendidos</h5>
                        <span class="badge bg-gray-200 text-gray-800">{{ count($topProductos) }} productos</span>
                    </div>
                    <div class="table-responsive content-table-sm"
                        style="max-height: 320px;">
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
                                                <div class="p-1 rounded me-2"
                                                    style="background-color: rgba(30, 66, 159, 0.1);">
                                                    <div style="color: #1e429f; width: 16px; height: 16px;">
                                                        @include('components.icons.box')
                                                    </div>
                                                </div>
                                                <span class="text-truncate puntitos"
                                                    style="max-width: 150px;"
                                                    title="{{ $producto->NomArticulo }}">
                                                    {{ $producto->NomArticulo }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-end fw-500">${{ number_format($producto->ventas, 2) }}</td>
                                        <td class="text-end fw-500">{{ number_format($producto->kilos, 1) }} kg</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Gráfica Ultimo Mes -->
            <div class="col-xl-8">
                <div class="card border-0 p-4"
                    style="border-radius: 10px; height: 400px; background-color: white; border: 1px solid #e5e7eb;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 text-gray-800">Último mes</h5>
                    </div>
                    <div class="position-relative"
                        style="height: 300px;">
                        <canvas id="ultimoMesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Mermas y Métricas -->
            <div class="col-xl-4">
                <div class="card border-0 p-4"
                    style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 text-gray-800">Productos con Más Mermas</h5>
                        <span class="badge bg-red-100 text-red-800">{{ count($topMermas) }} productos</span>
                    </div>
                    <div class="table-responsive content-table-sm"
                        style="max-height: 250px;">
                        <table class="table">
                            <tbody>
                                @foreach ($topMermas as $merma)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="p-1 rounded me-2"
                                                    style="background-color: rgba(155, 28, 28, 0.1);">
                                                    <div style="color: #9b1c1c; width: 16px; height: 16px;">
                                                        @include('components.icons.trash')
                                                    </div>
                                                </div>
                                                <span class="text-truncate puntitos"
                                                    style="max-width: 120px;">
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

                    <!-- Métricas Adicionales -->
                    {{-- <div class="pt-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="text-center p-2 border rounded">
                                    <div class="fs-4 fw-bold text-gray-800">{{ $metricas['cajas_asignadas'] ?? 0 }}
                                    </div>
                                    <small class="text-muted">Cajas Asignadas</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-2 border rounded">
                                    <div class="fs-4 fw-bold text-gray-800">{{ $metricas['tipos_pago'] ?? 0 }}</div>
                                    <small class="text-muted">Tipos de Pago</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-2 border rounded">
                                    <div class="fs-4 fw-bold text-gray-800">{{ $metricas['listas_precio'] ?? 0 }}
                                    </div>
                                    <small class="text-muted">Listas de Precio</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-2 border rounded">
                                    <div class="fs-4 fw-bold text-gray-800">{{ $metricas['usuarios_activos'] ?? 0 }}
                                    </div>
                                    <small class="text-muted">Usuarios Activos</small>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>

            <!-- Tabla de Tiendas -->
            <div class="col-xl-12">
                <div class="card border-0 p-4"
                    style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 text-gray-800">Tiendas - Rendimiento</h5>
                        <div class="input-group"
                            style="width: 250px;">
                            <input type="text"
                                class="form-control border-gray-300"
                                placeholder="Buscar tienda..."
                                id="searchTienda">
                            <button class="btn btn-dark-outline border-gray-300 border-start-0">
                                @include('components.icons.search')
                            </button>
                        </div>
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
                                @foreach ($tiendas as $tienda)
                                    <tr>
                                        <td class="pb-0">
                                            <div class="d-flex align-items-center">
                                                <div class="p-1 rounded me-2"
                                                    style="background-color: rgba(3, 84, 63, 0.1);">
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
                                        <td class="text-end fw-500 pb-0">{{ $tienda->tickets }}</td>
                                        <td class="text-end fw-500 pb-0">${{ number_format($tienda->promedio_ticket, 2) }}
                                        </td>
                                        <td class="text-end fw-500 pb-0">${{ number_format($tienda->total_ventas, 2) }}
                                        </td>
                                        <td class="text-end fw-500 pb-0">{{ number_format($tienda->total_kilos, 1) }} kg
                                        </td>
                                        <td class="text-end pb-0">
                                            <span class="tag">
                                                {{ $tienda->solicitudes_factura }}
                                            </span>
                                        </td>
                                        <td class="text-end pb-0">
                                            <span class="tags-{{ $tienda->tickets_sin_pedido > 0 ? 'red' : 'green' }}">
                                                {{ $tienda->tickets_sin_pedido }}/{{ $tienda->tickets_sin_bill }}
                                            </span>
                                        </td>
                                        {{-- <td class="pb-0">
                                            @php
                                                $fecha = request()->get('fecha_fin', date('Y-m-d'));
                                            @endphp

                                            <a href="{{ route('DashTienda', ['tienda_id' => $tienda->IdTienda, 'reporte' => 1, 'fecha_fin' => $fecha]) }}"
                                                class="btn-table btn-table-show"
                                                title="Ver corte tienda">
                                                @include('components.icons.eye') Ver corte tienda
                                            </a>

                                            <a href="{{ route('DashTienda', ['tienda_id' => $tienda->IdTienda, 'reporte' => 2, 'fecha_fin' => $fecha]) }}"
                                                class="btn-table btn-table-show"
                                                title="Ver corte detallado">
                                                @include('components.icons.eye') Ver corte detallado
                                            </a>
                                        </td> --}}
                                        <td class="pb-0 text-nowrap">
                                            @php
                                                $fecha = request()->get('fecha_fin', date('Y-m-d'));
                                            @endphp

                                            <a href="{{ route('DashTienda', ['tienda_id' => $tienda->IdTienda, 'reporte' => 1, 'fecha_fin' => $fecha]) }}"
                                                class="btn-table btn-table-icon"
                                                title="Ver corte de tienda">
                                                @include('components.icons.store')
                                            </a>

                                            <a href="{{ route('DashTienda', ['tienda_id' => $tienda->IdTienda, 'reporte' => 2, 'fecha_fin' => $fecha]) }}"
                                                class="btn-table btn-table-icon"
                                                title="Ver corte detallado">
                                                @include('components.icons.list')
                                            </a>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
        }

        .periodo-btn.active {
            background-color: #1e293b;
            color: white;
            border-color: #1e293b !important;
        }

        .bg-gray-100 {
            background-color: #f3f4f6;
        }

        .bg-gray-200 {
            background-color: #e5e7eb;
        }

        .text-gray-800 {
            color: #1f2937;
        }

        .border-gray-300 {
            border-color: #d1d5db !important;
        }

        .bg-blue-50 {
            background-color: rgba(59, 130, 246, 0.1);
        }

        .bg-green-50 {
            background-color: rgba(16, 185, 129, 0.1);
        }

        .bg-yellow-50 {
            background-color: rgba(245, 158, 11, 0.1);
        }

        .bg-purple-50 {
            background-color: rgba(139, 92, 246, 0.1);
        }

        .bg-red-50 {
            background-color: rgba(239, 68, 68, 0.1);
        }

        .bg-red-100 {
            background-color: rgba(254, 202, 202, 0.1);
        }

        .bg-cyan-50 {
            background-color: rgba(6, 182, 212, 0.1);
        }

        .text-red-800 {
            color: #991b1b;
        }

        .badge {
            font-weight: 500;
            padding: 0.25rem 0.75rem;
        }

        h5 {
            color: #374151;
            font-weight: 600;
        }
    </style>
@endsection

@section('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    {{-- <script src="{{ asset('js/cdn.jsdelivr.net.js') }}"></script> --}}
    <script src="{{ asset('js/chart.js') }}"></script>
    <script>
        let ventasChart = null;
        let ultimoMesChart = null;
        $(document).ready(function() {
            // Inicializar gráfica de ventas
            const ctx = document.getElementById('ventasChart').getContext('2d');
            const ultimoMes = document.getElementById('ultimoMesChart').getContext('2d');

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
                    console.log('---------------------------------');
                    console.log(response.periodo);
                    console.log(response.fechaInicio);
                    console.log(response.fechaFin);


                    ventasChart.data.labels = response.labels;
                    ventasChart.data.datasets[0].data = response.data;
                    ventasChart.update();
                }
            });
        }
    </script>
@endsection
