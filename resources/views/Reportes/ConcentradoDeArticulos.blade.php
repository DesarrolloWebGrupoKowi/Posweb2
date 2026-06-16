@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Concentrado de Artículos')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <x-page-container>
        <x-card-gradient-header
            icon="graph-up"
            title="Concentrado de Artículos"
            subtitle="Reporte de ventas por artículo"
        >
            <x-slot:buttons>
                <a
                    href="/ExportReporteConcentradoDeArticulos?{{ http_build_query(request()->only(['idTienda', 'fecha1', 'fecha2', 'txtFiltro', 'optionsOnline', 'agrupado', 'agrupadoArticulo', 'codigoInterfaz', 'soloAdeudos'])) }}"
                    class="btn-header-ghost"
                    title="Exportar a Excel"
                    style="background: #f0fdf4; color: #10b981;"
                    onmouseover="this.style.background='#dcfce7'; this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.background='#f0fdf4'; this.style.transform='translateY(0)'"
                >
                    <i class="bi bi-file-earmark-excel"></i> Exportar
                </a>
                <x-header.buttons.refresh-button />
                <x-header.buttons.home-button />
            </x-slot:buttons>

            <!-- Filtros -->
            <div class="border-bottom p-4">
                <form
                    method="GET"
                    action="/ReporteConcentradoDeArticulos"
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
                                name="fecha1"
                                class="form-control"
                                style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                                value="{{ request('fecha1') }}"
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
                                name="fecha2"
                                class="form-control"
                                style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                                value="{{ request('fecha2') }}"
                            >
                        </div>

                        <!-- Articulo -->
                        <div class="col-md-2">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                <i class="bi bi-search me-1"></i>Artículo
                            </label>
                            <input
                                type="text"
                                name="txtFiltro"
                                class="form-control"
                                style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                                placeholder="Código o nombre"
                                value="{{ request('txtFiltro') }}"
                            >
                        </div>

                        <!-- Articulo -->
                        <div class="col-md-3">
                            <x-form.advanced-toggle :active="$filtrosAvanzadosActivos" />
                        </div>
                    </div>

                    {{-- Fila 2: Filtros avanzados --}}
                    <x-form.advanced-panel :active="$filtrosAvanzadosActivos">
                        <x-form.checkbox-filter
                            name="agrupado"
                            label="Agrupar fecha"
                            :checked="request('agrupado') == 'on'"
                        />
                        <x-form.checkbox-filter
                            name="agrupadoArticulo"
                            label="Agrupar artículo"
                            :checked="request('agrupadoArticulo') == 'on'"
                        />
                        @if (Auth::user()->IdTipoUsuario == 2)
                            <x-form.checkbox-filter
                                name="optionsOnline"
                                label="Consulta online"
                                :checked="request('optionsOnline') == 'on'"
                            />
                        @endif
                    </x-form.advanced-panel>
                </form>
            </div>

            <script>
                function toggleFiltrosAvanzados() {
                    const fila = document.getElementById('filaFiltrosAvanzados');
                    const btn = document.getElementById('btnFiltrosAvanzados');

                    if (fila.classList.contains('d-none')) {
                        fila.classList.remove('d-none');
                        btn.style.background = '#e2e8f0';
                    } else {
                        fila.classList.add('d-none');
                        btn.style.background = '#f1f5f9';
                    }
                }
            </script>

            {{-- SECCIÓN 2: KPIs --}}
            <div class="p-4">
                <div class="row g-3">
                    @php
                        $totalPeso = $concentrado->sum('Peso');
                        $totalImporte = $concentrado->sum('Importe');
                        $articulosUnicos = $concentrado->unique('CodArticulo')->count();
                        $precioPromedio = $totalPeso > 0 ? $totalImporte / $totalPeso : 0;
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
                                    >Peso Vendido</span>
                                    <i
                                        class="bi bi-box"
                                        style="color: #3b82f6; font-size: 1.3rem; opacity: 0.7;"
                                    ></i>
                                </div>
                                <h3
                                    class="mb-1"
                                    style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                                >{{ number_format($totalPeso, 2) }} kg</h3>
                                <span style="color: #94a3b8; font-size: 0.78rem;">Kilogramos totales</span>
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
                                    >Artículos</span>
                                    <i
                                        class="bi bi-tags"
                                        style="color: #10b981; font-size: 1.3rem; opacity: 0.7;"
                                    ></i>
                                </div>
                                <h3
                                    class="mb-1"
                                    style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                                >{{ $articulosUnicos }}</h3>
                                <span style="color: #94a3b8; font-size: 0.78rem;">Códigos diferentes</span>
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
                                    >Precio Prom.</span>
                                    <i
                                        class="bi bi-graph-up"
                                        style="color: #f59e0b; font-size: 1.3rem; opacity: 0.7;"
                                    ></i>
                                </div>
                                <h3
                                    class="mb-1"
                                    style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                                >${{ number_format($precioPromedio, 2) }}</h3>
                                <span style="color: #94a3b8; font-size: 0.78rem;">Por kilogramo</span>
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
                                    >Venta Total</span>
                                    <i
                                        class="bi bi-cash-stack"
                                        style="color: #8b5cf6; font-size: 1.3rem; opacity: 0.7;"
                                    ></i>
                                </div>
                                <h3
                                    class="mb-1"
                                    style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                                >${{ number_format($totalImporte, 2) }}</h3>
                                <span style="color: #94a3b8; font-size: 0.78rem;">Monto facturado</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 3: TABLA Y GRÁFICAS --}}
            <div class="px-4 pb-4">
                <div class="row g-4">
                    {{-- TABLA --}}
                    <div class="col-xxl-8">
                        <div
                            class="rounded p-4 shadow-sm"
                            style="background: white; border-radius: 12px;"
                        >
                            <div class="table-responsive">
                                <table class="table-hover table-custom table">
                                    <thead style="position: sticky; top: 0; z-index: 2;">
                                        <tr>
                                            <th><i class="bi bi-building me-1"></i>Ciudad</th>
                                            <th><i class="bi bi-shop me-1"></i>Tienda</th>
                                            @if ($agrupado)
                                                <th><i class="bi bi-calendar3 me-1"></i>Fecha</th>
                                            @endif
                                            <th><i class="bi bi-folder me-1"></i>Grupo</th>
                                            @if (!$agrupadoArticulo)
                                                <th><i class="bi bi-tags me-1"></i>Lista Precios</th>
                                            @endif
                                            <th><i class="bi bi-upc-scan me-1"></i>Código</th>
                                            <th><i class="bi bi-box me-1"></i>Artículo</th>
                                            <th class="text-end"><i class="bi bi-hash me-1"></i>Cantidad</th>
                                            @if (!$agrupadoArticulo)
                                                <th class="text-end"><i class="bi bi-cash me-1"></i>Precio</th>
                                            @endif
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
                                        @forelse ($concentrado as $tConcentrado)
                                            <tr>
                                                <td>{{ $tConcentrado->NomCiudad }}</td>
                                                <td style="font-weight: 500;">{{ $tConcentrado->NomTienda }}</td>
                                                @if ($agrupado)
                                                    <td>{{ \Carbon\Carbon::parse($tConcentrado->FechaVenta)->format('d/m/Y') }}
                                                    </td>
                                                @endif
                                                <td>{{ $tConcentrado->NomGrupo }}</td>
                                                @if (!$agrupadoArticulo)
                                                    <td>{{ $tConcentrado->NomListaPrecio }}</td>
                                                @endif
                                                <td style="font-weight: 600; color: #0f172a;">
                                                    {{ $tConcentrado->CodArticulo }}</td>
                                                <td
                                                    class="text-truncate"
                                                    style="max-width: 180px;"
                                                    title="{{ $tConcentrado->NomArticulo }}"
                                                >{{ $tConcentrado->NomArticulo }}</td>
                                                <td
                                                    class="text-end"
                                                    style="font-weight: 500;"
                                                >{{ number_format($tConcentrado->Peso, 3) }}</td>
                                                @if (!$agrupadoArticulo)
                                                    <td
                                                        class="text-end"
                                                        style="font-weight: 500;"
                                                    >${{ number_format($tConcentrado->PrecioArticulo, 2) }}</td>
                                                @endif
                                                <td
                                                    class="text-end"
                                                    style="font-weight: 500;"
                                                >${{ number_format($tConcentrado->Iva, 2) }}</td>
                                                <td
                                                    class="text-end"
                                                    style="font-weight: 500;"
                                                >${{ number_format($tConcentrado->Importe, 2) }}</td>
                                            </tr>
                                            @php
                                                $totalPeso += $tConcentrado->Peso;
                                                $totalIva += $tConcentrado->Iva;
                                                $totalImporte += $tConcentrado->Importe;
                                            @endphp
                                        @empty
                                            <tr>
                                                <td
                                                    colspan="12"
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
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if (count($concentrado) > 0)
                                        <tfoot>
                                            <tr style="background: #f8fafc; font-weight: 700;">
                                                <td
                                                    colspan="{{ $agrupado ? ($agrupadoArticulo ? 5 : 6) : ($agrupadoArticulo ? 5 : 6) }}"
                                                    class="text-end"
                                                >TOTALES:</td>
                                                @if ($agrupado)
                                                    <td></td>
                                                @endif
                                                <td class="text-end">{{ number_format($totalPeso, 3) }}</td>
                                                @if (!$agrupadoArticulo)
                                                    <td></td>
                                                @endif
                                                <td class="text-end">${{ number_format($totalIva, 2) }}</td>
                                                <td class="text-end">${{ number_format($totalImporte, 2) }}</td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- GRÁFICAS --}}
                    <div class="col-xxl-4">
                        <div class="d-flex flex-column h-100 gap-4">
                            {{-- Top 10 Productos --}}
                            <div
                                class="rounded p-4 shadow-sm"
                                style="background: white; border-radius: 12px; max-height: 380px;"
                            >
                                <h5
                                    class="mb-3"
                                    style="font-weight: 600; color: #0f172a; font-size: 1rem;"
                                >
                                    <i
                                        class="bi bi-trophy me-2"
                                        style="color: #f59e0b;"
                                    ></i>Top 10 Productos por Peso
                                </h5>
                                @if (count($topProductosLabels) > 0)
                                    <div style="height: 280px;">
                                        <canvas id="topProductosChart"></canvas>
                                    </div>
                                @else
                                    <div
                                        class="d-flex justify-content-center align-items-center"
                                        style="height: 280px;"
                                    >
                                        <div class="text-center">
                                            <i
                                                class="bi bi-bar-chart"
                                                style="font-size: 2.5rem; color: #94a3b8;"
                                            ></i>
                                            <p
                                                class="mt-2"
                                                style="color: #64748b;"
                                            >Sin datos</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Ventas por Grupo + Lista (2 columnas) --}}
                            <div
                                class="row g-3"
                                style="min-height: 220px;"
                            >
                                <div class="col-6">
                                    <div
                                        class="h-100 rounded p-3 shadow-sm"
                                        style="background: white; border-radius: 12px;"
                                    >
                                        <h6
                                            class="mb-2"
                                            style="font-weight: 600; color: #0f172a; font-size: 0.85rem;"
                                        >Ventas por Grupo</h6>
                                        @if (count($gruposLabels) > 0)
                                            <div style="height: 160px;">
                                                <canvas id="ventasPorGrupoChart"></canvas>
                                            </div>
                                        @else
                                            <div
                                                class="d-flex justify-content-center align-items-center"
                                                style="height: 160px;"
                                            >
                                                <div class="text-center">
                                                    <i
                                                        class="bi bi-pie-chart"
                                                        style="font-size: 2rem; color: #94a3b8;"
                                                    ></i>
                                                    <p
                                                        class="mt-1"
                                                        style="color: #64748b; font-size: 0.8rem;"
                                                    >Sin datos</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div
                                        class="h-100 rounded p-3 shadow-sm"
                                        style="background: white; border-radius: 12px;"
                                    >
                                        <h6
                                            class="mb-2"
                                            style="font-weight: 600; color: #0f172a; font-size: 0.85rem;"
                                        >Ventas por Lista</h6>
                                        @if (count($listaLabels) > 0)
                                            <div style="height: 160px;">
                                                <canvas id="ventasPorListaChart"></canvas>
                                            </div>
                                        @else
                                            <div
                                                class="d-flex justify-content-center align-items-center"
                                                style="height: 160px;"
                                            >
                                                <div class="text-center">
                                                    <i
                                                        class="bi bi-pie-chart"
                                                        style="font-size: 2rem; color: #94a3b8;"
                                                    ></i>
                                                    <p
                                                        class="mt-1"
                                                        style="color: #64748b; font-size: 0.8rem;"
                                                    >Sin datos</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-card-gradient-header>
    </x-page-container>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const topCanvas = document.getElementById('topProductosChart');
            if (topCanvas && @json(count($topProductosLabels)) > 0) {
                new Chart(topCanvas, {
                    type: 'bar',
                    data: {
                        labels: @json($topProductosLabels),
                        datasets: [{
                            label: 'Peso (kg)',
                            data: @json($topProductosPeso),
                            backgroundColor: '#3b82f6',
                            borderRadius: 6,
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
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: true,
                                    color: 'rgba(0,0,0,0.05)'
                                }
                            },
                            y: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 10
                                    },
                                    autoSkip: false
                                }
                            }
                        }
                    }
                });
            }

            const grupoCanvas = document.getElementById('ventasPorGrupoChart');
            if (grupoCanvas && @json(count($gruposLabels)) > 0) {
                new Chart(grupoCanvas, {
                    type: 'pie',
                    data: {
                        labels: @json($gruposLabels),
                        datasets: [{
                            data: @json($gruposData),
                            backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6',
                                '#64748b'
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: {
                                        size: 10
                                    },
                                    boxWidth: 8
                                }
                            }
                        }
                    }
                });
            }

            const listaCanvas = document.getElementById('ventasPorListaChart');
            if (listaCanvas && @json(count($listaLabels)) > 0) {
                new Chart(listaCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: @json($listaLabels),
                        datasets: [{
                            data: @json($listaData),
                            backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444',
                                '#8b5cf6'
                            ],
                            borderWidth: 2,
                            borderColor: '#fff',
                            cutout: '60%'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: {
                                        size: 10
                                    },
                                    boxWidth: 8
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection
