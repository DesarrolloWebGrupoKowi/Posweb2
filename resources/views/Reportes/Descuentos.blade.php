@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Concentrado de Descuentos')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <x-layout.page-container>

        <!-- SECCIÓN 1: FILTROS -->
        <x-layout.section-card>
            <div
                class="d-flex justify-content-sm-between align-items-start align-items-sm-start flex-column flex-md-row mb-2">
                <x-title titulo="Concentrado de Descuentos" />
                <div class="d-flex gap-2">
                    <x-filters.buttons.excel-button
                        route="/ExportsReporteDescuentos"
                        :params="[
                            'idTienda' => request('idTienda'),
                            'fecha_inicio' => request('fecha_inicio'),
                            'fecha_fin' => request('fecha_fin'),
                            'cod_articulo' => request('cod_articulo'),
                            'id_familia' => request('id_familia'),
                            'nom_descuento' => request('nom_descuento'),
                        ]"
                    />
                    <x-filters.buttons.refresh-button />
                    <x-filters.buttons.home-button />
                </div>
            </div>

            <!-- Formulario de filtros -->
            <x-filters.filter-form>
                <!-- Filtros Básicos -->
                <x-filters.filter-group>
                    <x-filters.inputs.select-input
                        name="idTienda"
                        label="Tienda"
                        :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                    />
                    <x-filters.inputs.date-input
                        name="fecha_inicio"
                        label="Fecha Inicio"
                        :value="request('fecha_inicio')"
                        :autofocus="true"
                    />
                    <x-filters.inputs.date-input
                        name="fecha_fin"
                        label="Fecha Fin"
                        :value="request('fecha_fin')"
                    />
                    <x-filters.inputs.text-input
                        name="cod_articulo"
                        label="Artículo"
                        placeholder="Código o nombre del artículo"
                        :value="request('cod_articulo')"
                    />
                </x-filters.filter-group>

                <!-- Filtros Avanzados -->
                <x-filters.advanced-collapse
                    :active="$filtrosAvanzadosActivos"
                    :showBadge="true"
                >
                    <x-filters.filter-group>
                        <x-filters.inputs.select-input
                            name="id_familia"
                            label="Familia"
                            :options="$familias->pluck('NomFamilia', 'IdFamilia')->toArray()"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="nom_descuento"
                            label="Artículo"
                            placeholder="Código o nombre del artículo"
                            :value="request('nom_descuento')"
                            compact="true"
                        />
                    </x-filters.filter-group>
                </x-filters.advanced-collapse>

                <x-slot:buttons>
                    <x-filters.buttons.clear-button />
                    <x-filters.buttons.advanced-button
                        :active="$filtrosAvanzadosActivos"
                        :hasBadge="true"
                    />
                    <x-filters.buttons.submit-button />
                </x-slot:buttons>
            </x-filters.filter-form>
        </x-layout.section-card>

        <!-- SECCIÓN 2: KPIs -->
        <div class="flex-shrink-0">
            <div class="row g-4">
                <!-- Total de artículos vendidos (suma de pesos) -->
                <x-kpi.kpi-card
                    title="Total de Peso Vendido"
                    :value="$data->sum('CantArticulo')"
                    subtitle="Kilogramos totales"
                    color="primary"
                    icon="components.icons.shopping-cart"
                    :decimal="2"
                />

                {{-- KPI: Producto con Menor Descuento --}}
                <x-kpi.kpi-card
                    title="Menor Descuento por Unidad"
                    value="$4.00"
                    subtitle="SALCHICHA DE PAVO TURI"
                    subtitleHtml="Precio Lista: $65.00 → Precio Final: $61.00<br>Descuento: 6.15%"
                    color="warning"
                    {{-- icon="components.icons.trending-down" --}}
                />

                {{-- KPI: Producto con Mayor Descuento --}}
                <x-kpi.kpi-card
                    title="Mayor Descuento por Unidad"
                    value="$25.00"
                    subtitle="CARNE DESHEBRADA 300 GR."
                    subtitleHtml="Precio Lista: $150.00 → Precio Final: $125.00<br>Descuento: 16.67%"
                    color="success"
                    {{-- icon="components.icons.trending-up" --}}
                />

                <!-- Valor total de ventas -->
                <x-kpi.kpi-card
                    title="Venta Total"
                    :value="$data->sum('ImporteArticulo')"
                    subtitle="Monto facturado"
                    color="success"
                    icon="components.icons.ticket"
                    currency="true"
                    :decimal="2"
                />
            </div>
        </div>

        <!-- SECCIÓN GRÁFICAS Y TABLAS -->
        <div class="row">
            <div
                class="col-12 col-xxl-8 d-flex flex-column"
                style="flex: 2; min-width: 0; min-height: 0;"
            >
                <div
                    class="card d-flex flex-column w-100 border-0 p-4"
                    style="border-radius: 10px; min-height: 0;"
                >
                    <div
                        id="vistaTabla"
                        class="flex-grow-1 table-responsive content-table-sm overflow-auto"
                        style="min-height: 0;"
                    >
                        <table class="table">
                            <thead class="table-head">
                                <tr>
                                    <th>Descuento</th>
                                    <th>Tienda</th>
                                    <th>Fecha venta</th>
                                    <th>Código</th>
                                    <th>Articulo</th>
                                    <th>Familia</th>
                                    <th>Cantidad</th>
                                    <th>Precios</th>
                                    <th>Iva</th>
                                    <th>Importe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Inicializamos las variables para la suma
                                    $totalPeso = 0;
                                    $precioLista = 0;
                                    $precioDesc = 0;
                                    $totalIva = 0;
                                    $totalImporte = 0;
                                @endphp
                                @forelse ($data as $item)
                                    <tr>
                                        <td>{{ $item->NomDescuento }}</td>
                                        <td>{{ $item->NomTienda }}</td>
                                        <td>{{ $item->FechaVenta }}</td>
                                        <td>{{ $item->CodArticulo }}</td>
                                        <td>{{ $item->NomArticulo }}</td>
                                        <td>{{ $item->NomFamilia }}</td>
                                        <td>{{ number_format($item->CantArticulo, 3) }}</td>
                                        <td>
                                            <span
                                                class="tags-red text-muted">${{ number_format($item->PrecioLista, 2) }}</span>
                                            /
                                            <span class="tags-green">${{ number_format($item->PrecioArticulo, 2) }}</span>
                                        </td>

                                        <td>{{ number_format($item->IvaArticulo, 2) }}</td>
                                        <td>{{ number_format($item->ImporteArticulo, 2) }}</td>
                                    </tr>

                                    @php
                                        // Acumulamos los valores
                                        $totalPeso += $item->CantArticulo;
                                        $precioLista += $item->PrecioLista;
                                        $precioDesc += $item->PrecioArticulo;
                                        $totalIva += $item->IvaArticulo;
                                        $totalImporte += $item->ImporteArticulo;
                                    @endphp
                                @empty
                                    <tr>
                                        <td
                                            colspan="14"
                                            class="py-5 text-center"
                                        >
                                            <x-table-empty-state
                                                title="Sin datos disponibles"
                                                icon="cube"
                                                :message="'No se encontraron resultados con los filtros seleccionados.'"
                                                :suggestion="'Intenta ampliar el rango de fechas o modificar los criterios de búsqueda.'"
                                                action="Limpiar filtros"
                                                actionUrl="/ReporteConcentradoDeArticulosYListaPrecios"
                                            />
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                            @if (count($data) > 0)
                                <tfoot>
                                    <tr>
                                        <td colspan="6"><strong>Total:</strong></td>
                                        <td><strong>{{ number_format($totalPeso, 2) }}</strong></td>
                                        <td>
                                            {{-- <span class="tags-red text-muted">
                                                <b>${{ number_format($precioLista, 2) }}</b>
                                            </span>
                                            /
                                            <span class="tags-green"><b>${{ number_format($precioDesc, 2) }}</b></span> --}}
                                        </td>
                                        <td><strong>{{ number_format($totalIva, 2) }}</strong></td>
                                        <td><strong>{{ number_format($totalImporte, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN Graficas -->
            {{-- <div class="col-xxl-4 py-xxl-0 py-4">
                <!-- Top 10 Productos por Peso -->
                <div class="row">
                    <div class="col-12">
                        <div
                            class="card border-0 p-4"
                            style="border-radius: 10px"
                        >
                            <h6 class="fw-semibold mb-3">🏆 Top 10 Productos por Peso Vendido</h6>
                            @php
                                // Agrupar por producto sumando pesos
                                $productosAgrupados = $concentrado
                                    ->groupBy('CodArticulo')
                                    ->map(function ($items) {
                                        $first = $items->first();
                                        return (object) [
                                            'CodArticulo' => $first->CodArticulo,
                                            'NomArticulo' => $first->NomArticulo,
                                            'Peso' => $items->sum(function ($item) {
                                                return floatval($item->Peso);
                                            }),
                                            'Importe' => $items->sum(function ($item) {
                                                return floatval($item->Importe);
                                            }),
                                        ];
                                    })
                                    ->sortByDesc('Peso')
                                    ->take(10);

                                $topProductosLabels = $productosAgrupados->pluck('NomArticulo')->toArray();
                                $topProductosPeso = $productosAgrupados->pluck('Peso')->toArray();
                            @endphp

                            @if (count($topProductosLabels) > 0)
                                <canvas
                                    id="topProductosChart"
                                    height="250"
                                ></canvas>
                            @else
                                <div class="py-5 text-center">
                                    <p class="text-muted">No hay datos para mostrar</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Gráficas de distribución -->
                <div class="row g-4 mt-xxl-0 mt-2">
                    <div class="col-6">
                        <div
                            class="card border-0 p-4"
                            style="border-radius: 10px"
                        >
                            <h6 class="fw-semibold mb-3">📊 Ventas por Grupo</h6>
                            @php
                                $ventasPorGrupo = $concentrado
                                    ->groupBy('NomGrupo')
                                    ->map(function ($items) {
                                        return $items->sum(function ($item) {
                                            return floatval($item->Importe);
                                        });
                                    })
                                    ->sortDesc();

                                $gruposLabels = $ventasPorGrupo->keys()->toArray();
                                $gruposData = $ventasPorGrupo->values()->toArray();
                            @endphp

                            @if (count($gruposLabels) > 0)
                                <canvas
                                    id="ventasPorGrupoChart"
                                    height="150"
                                ></canvas>
                            @else
                                <div class="py-5 text-center">
                                    <p class="text-muted">No hay datos para mostrar</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-6">
                        <div
                            class="card border-0 p-4"
                            style="border-radius: 10px"
                        >
                            <h6 class="fw-semibold mb-3">💰 Ventas por Lista de Precio</h6>
                            @php
                                $ventasPorLista = $concentrado
                                    ->groupBy('NomListaPrecio')
                                    ->map(function ($items) {
                                        return $items->sum(function ($item) {
                                            return floatval($item->Importe);
                                        });
                                    })
                                    ->sortDesc();

                                $listaLabels = $ventasPorLista->keys()->toArray();
                                $listaData = $ventasPorLista->values()->toArray();
                            @endphp

                            @if (count($listaLabels) > 0)
                                <canvas
                                    id="ventasPorListaChart"
                                    height="150"
                                ></canvas>
                            @else
                                <div class="py-5 text-center">
                                    <p class="text-muted">No hay datos para mostrar</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </x-layout.page-container>

    <style>
        .table thead th {
            position: sticky;
            top: 0;
            background: rgb(30, 41, 59);
            z-index: 2;
        }
    </style>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gráfica 1: Top 10 Productos (Barras horizontales)
            const topProductosCanvas = document.getElementById('topProductosChart');
            if (topProductosCanvas && @json(count($topProductosLabels)) > 0) {
                new Chart(topProductosCanvas, {
                    type: 'bar',
                    data: {
                        labels: @json($topProductosLabels),
                        datasets: [{
                            label: 'Peso (kg)',
                            data: @json($topProductosPeso),
                            backgroundColor: '#4e73df',
                            borderRadius: 5,
                            barPercentage: 0.7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        indexAxis: 'y',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `Peso: ${context.raw.toFixed(2)} kg`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Peso (kg)',
                                    font: {
                                        size: 11
                                    }
                                },
                                grid: {
                                    display: true
                                },
                                ticks: {
                                    callback: function(value) {
                                        return value.toFixed(2) + ' kg';
                                    }
                                }
                            },
                            y: {
                                ticks: {
                                    font: {
                                        size: 10
                                    },
                                    maxRotation: 0,
                                    autoSkip: false
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // Gráfica 2: Ventas por Grupo (Pie)
            const grupoCanvas = document.getElementById('ventasPorGrupoChart');
            if (grupoCanvas && @json(count($gruposLabels)) > 0) {
                new Chart(grupoCanvas, {
                    type: 'pie',
                    data: {
                        labels: @json($gruposLabels),
                        datasets: [{
                            data: @json($gruposData),
                            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                                '#858796'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: {
                                        size: 11
                                    },
                                    boxWidth: 10
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return `${label}: $${value.toFixed(2)} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Gráfica 3: Ventas por Lista de Precio (Doughnut)
            const listaCanvas = document.getElementById('ventasPorListaChart');
            if (listaCanvas && @json(count($listaLabels)) > 0) {
                new Chart(listaCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: @json($listaLabels),
                        datasets: [{
                            data: @json($listaData),
                            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e',
                                '#e74a3b'
                            ],
                            borderWidth: 0,
                            cutout: '60%'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: {
                                        size: 11
                                    },
                                    boxWidth: 10
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return `${label}: $${value.toFixed(2)} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script> --}}
@endsection
