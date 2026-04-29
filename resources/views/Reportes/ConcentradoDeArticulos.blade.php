@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Concentrado de Artículos')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <x-layout.page-container>

        <!-- SECCIÓN 1: FILTROS -->
        <x-layout.section-card>
            <div class="d-flex justify-content-sm-between align-items-end align-items-sm-start flex-column flex-sm-row mb-2">
                <x-title titulo="Concentrado de Artículos" />
                <div class="d-flex gap-2">
                    <x-filters.buttons.excel-button
                        route="/ExportReporteConcentradoDeArticulos"
                        :params="[
                            'idTienda' => request('idTienda'),
                            'fecha1' => request('fecha1'),
                            'fecha2' => request('fecha2'),
                            'txtFiltro' => request('txtFiltro'),
                            'optionsOnline' => request('optionsOnline'),
                            'agrupado' => request('agrupado'),
                            'agrupadoArticulo' => request('agrupadoArticulo'),
                            'codigoInterfaz' => request('codigoInterfaz'),
                            'soloAdeudos' => request('soloAdeudos'),
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
                        name="fecha1"
                        label="Fecha Inicio"
                        :value="request('fecha1')"
                        :autofocus="true"
                    />
                    <x-filters.inputs.date-input
                        name="fecha2"
                        label="Fecha Fin"
                        :value="request('fecha2')"
                    />
                    <x-filters.inputs.text-input
                        name="txtFiltro"
                        label="Artículo"
                        placeholder="Código o nombre del artículo"
                        :value="request('txtFiltro')"
                    />
                </x-filters.filter-group>

                <!-- Filtros Avanzados -->
                <x-filters.advanced-collapse
                    :active="$filtrosAvanzadosActivos"
                    :showBadge="true"
                >
                    <x-filters.filter-group>
                        <x-filters.inputs.checkbox-input
                            name="agrupado"
                            label="Agrupar fecha"
                            :checked="request('agrupado') == 'on'"
                            helperText="Agrupa ventas por día"
                            compact="true"
                        />
                        <x-filters.inputs.checkbox-input
                            name="agrupadoArticulo"
                            label="Agrupar artículo"
                            :checked="request('agrupadoArticulo') == 'on'"
                            helperText="Agrupa por artículo"
                            compact="true"
                        />
                        @if (Auth::user()->IdTipoUsuario == 2)
                            <x-filters.inputs.checkbox-input
                                name="optionsOnline"
                                label="online"
                                :checked="request('optionsOnline') == 'on'"
                                helperText="Usar conexión remota"
                                compact="true"
                            />
                        @endif
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
                    :value="$concentrado->sum('Peso')"
                    subtitle="Kilogramos totales"
                    color="primary"
                    icon="components.icons.shopping-cart"
                    :decimal="2"
                />

                <!-- Artículos únicos vendidos -->
                <x-kpi.kpi-card
                    title="Artículos Vendidos"
                    :value="$concentrado->unique('CodArticulo')->count()"
                    subtitle="Códigos diferentes"
                    color="info"
                    icon="components.icons.credit-card"
                />

                <!-- Precio promedio por kilogramo -->
                <x-kpi.kpi-card
                    title="Precio Promedio"
                    :value="$concentrado->sum('Importe') /
                        ($concentrado->sum('Peso') > 0 ? $concentrado->sum('Peso') : 1)"
                    subtitle="$ por kilogramo"
                    color="danger"
                    icon="components.icons.dolar"
                    currency="true"
                    :decimal="2"
                />

                <!-- Valor total de ventas -->
                <x-kpi.kpi-card
                    title="Venta Total"
                    :value="$concentrado->sum('Importe')"
                    subtitle="Monto facturado"
                    color="success"
                    icon="components.icons.ticket"
                    currency="true"
                    :decimal="2"
                />
            </div>
        </div>

        <!-- SECCIÓN GRÁFICAS Y TABLAS -->
        <div
            class="flex-grow-1 d-flex gap-4"
            style="min-height: 0;"
        >
            <div
                class="d-flex flex-column"
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
                                    <th>Ciudad</th>
                                    <th>Tienda</th>
                                    @if ($agrupado)
                                        <th>Fecha</th>
                                    @endif
                                    <th>Grupo</th>
                                    <th>Lista precios</th>
                                    <th>Código</th>
                                    <th>Articulo</th>
                                    <th>Cantidad</th>
                                    @if (!$agrupadoArticulo)
                                        <th>Precio</th>
                                    @endif
                                    <th>Iva</th>
                                    <th>Importe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Inicializamos las variables para la suma
                                    $totalPeso = 0;
                                    $totalIva = 0;
                                    $totalImporte = 0;
                                @endphp
                                @forelse ($concentrado as $tConcentrado)
                                    <tr>
                                        <td>{{ $tConcentrado->NomCiudad }}</td>
                                        <td>{{ $tConcentrado->NomTienda }}</td>
                                        @if ($agrupado)
                                            <!-- <td>{{ $tConcentrado->FechaVenta }}</td> -->
                                            <td>{{ \Carbon\Carbon::parse($tConcentrado->FechaVenta)->format('d/m/Y') }}
                                            </td>
                                        @endif
                                        <td>{{ $tConcentrado->NomGrupo }}</td>
                                        <td>{{ $tConcentrado->NomListaPrecio }}</td>
                                        <td>{{ $tConcentrado->CodArticulo }}</td>
                                        <td>{{ $tConcentrado->NomArticulo }}</td>
                                        <td>{{ number_format($tConcentrado->Peso, 3) }}</td>
                                        @if (!$agrupadoArticulo)
                                            <td>{{ number_format($tConcentrado->PrecioArticulo, 2) }}</td>
                                        @endif
                                        <td>{{ number_format($tConcentrado->Iva, 2) }}</td>
                                        <td>{{ number_format($tConcentrado->Importe, 2) }}</td>
                                    </tr>

                                    @php
                                        // Acumulamos los valores
                                        $totalPeso += $tConcentrado->Peso;
                                        $totalIva += $tConcentrado->Iva;
                                        $totalImporte += $tConcentrado->Importe;
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
                                                actionUrl="/ReporteConcentradoDeArticulos"
                                            />
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                            @if (count($concentrado) > 0)
                                <tfoot>
                                    <tr>
                                        <td colspan="5"><strong>Total:</strong></td>
                                        @if ($agrupado)
                                            <td></td>
                                        @endif
                                        <td><strong>{{ number_format($totalPeso, 3) }}</strong></td>
                                        @if (!$agrupadoArticulo)
                                            <td></td>
                                        @endif
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
            <div style="flex: 1; min-width: 0;">
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
                <div class="row mt-4">
                    <div class="col-md-6">
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

                    <div class="col-md-6">
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
            </div>
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

    <script>
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
    </script>
@endsection
