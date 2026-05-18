@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Concentrado de Paquetes')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <x-layout.page-container>

        <!-- SECCIÓN 1: FILTROS -->
        <x-layout.section-card>
            <x-layout.section-title>
                <x-title titulo="Concentrado de Paquetes" />
                <div class="d-flex gap-2">
                    <x-filters.buttons.excel-button
                        route="/ExportsReportePaquetes"
                        :params="[
                            'idTienda' => request('idTienda'),
                            'fecha_inicio' => request('fecha_inicio'),
                            'fecha_fin' => request('fecha_fin'),
                            'id_paquete' => request('id_paquete'),
                            'nom_paquete' => request('nom_paquete'),
                        ]"
                    />
                    <x-filters.buttons.refresh-button />
                    <x-filters.buttons.home-button />
                </div>
            </x-layout.section-title>

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
                    <x-filters.inputs.select-input
                        name="id_paquete"
                        label="Paquete"
                        :options="$paquetes->pluck('NomPaquete', 'IdPaquete')->toArray()"
                    />
                </x-filters.filter-group>

                <!-- Filtros Avanzados -->
                <x-filters.advanced-collapse
                    :active="$filtrosAvanzadosActivos"
                    :showBadge="true"
                >
                    <x-filters.filter-group>
                        {{-- <x-filters.inputs.select-input
                            name="id_grupo"
                            label="Grupo"
                            :options="$grupos->pluck('NomGrupo', 'IdGrupo')->toArray()"
                            compact="true"
                        />
                        <x-filters.inputs.select-input
                            name="id_familia"
                            label="Familia"
                            :options="$familias->pluck('NomFamilia', 'IdFamilia')->toArray()"
                            compact="true"
                        /> --}}
                        {{-- <x-filters.inputs.text-input
                            name="id_paquete"
                            label="ID Paquete"
                            placeholder="Id del paquete"
                            :value="request('id_paquete')"
                            compact="true"
                        /> --}}
                        <x-filters.inputs.text-input
                            name="nom_paquete"
                            label="Nombre Paquete"
                            placeholder="Nombre del paquete"
                            :value="request('nom_paquete')"
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
            @php
                $kpis = $paquetesKPIs ?? [];
            @endphp
            <div class="row g-4">
                <!-- KPI 1: Total de Paquetes Diferentes -->
                <x-kpi.kpi-card
                    title="Paquetes Diferentes"
                    :value="$kpis['total_paquetes'] ?? 0"
                    subtitle="Tipos de paquetes comercializados"
                    color="primary"
                    icon="components.icons.box"
                />

                <!-- KPI 2: Tickets con Paquetes -->
                <x-kpi.kpi-card
                    title="Tickets con Paquetes"
                    :value="$kpis['total_tickets_con_paquetes'] ?? 0"
                    subtitle="Tickets que incluyen al menos un paquete"
                    color="info"
                    icon="components.icons.ticket"
                />

                <!-- KPI 3: Importe Total en Paquetes -->
                <x-kpi.kpi-card
                    title="Importe en Paquetes"
                    :value="$kpis['total_importe_paquetes'] ?? 0"
                    subtitle="Monto total vendido en paquetes"
                    :currency="true"
                    color="success"
                    icon="components.icons.dolar"
                />

                <!-- Paquete más frecuente -->
                @php
                    $paqueteMasFrecuente = $kpis['paquete_mas_frecuente'] ?? null;
                    $nombrePaquete = $paqueteMasFrecuente['nombre'] ?? 'N/A';
                    $vecesVendido = $paqueteMasFrecuente['veces_vendido'] ?? 0;
                @endphp
                <x-kpi.kpi-card
                    title="🏆 Paquete más Vendido (Frecuencia)"
                    :value="$nombrePaquete"
                    :subtitleHtml="'Veces vendido: ' . $vecesVendido . ' tickets'"
                    color="warning"
                    icon="components.icons.shopping-cart"
                />

            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div
                        class=""
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius:10px; padding: 2px;"
                    >
                        <div
                            class="card d-flex flex-column w-100 table-responsive content-table-sm border-0 p-4"
                            style="border-radius: 10px; min-height: 0;"
                        >
                            <table class="table">
                                <thead class="table-head">
                                    <tr>
                                        <th>ID Paquete</th>
                                        <th>Nombre</th>
                                        <th>Veces Vendido</th>
                                        <th>Total Artículos</th>
                                        <th class="text-center">Importe Paquetes</th>
                                        <th class="text-center">Importe Total</th>
                                        <th class="text-center">Ticket Promedio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kpis['paquetes_detalle'] ?? [] as $paquete)
                                        <tr>
                                            <td>{{ $paquete['id'] }}</td>
                                            <td style="text-wrap: nowrap">{{ $paquete['nombre'] }}</td>
                                            <td>
                                                <div
                                                    {{-- class="text-center" --}}
                                                    style="color: #667eea;"
                                                >
                                                    {{ $paquete['veces_vendido'] }}
                                                </div>
                                            </td>
                                            <td
                                                {{-- class="text-center" --}}
                                                style="color: #2d3748;"
                                            >{{ number_format($paquete['total_cantidad'], 2) }}</td>
                                            <td
                                                class="text-end"
                                                style="color: #10b981;"
                                            >${{ number_format($paquete['total_importe'], 2) }}</td>
                                            <td class="text-secondary text-end">
                                                ${{ number_format($paquete['total_importe_todos_articulos'], 2) }}</td>
                                            <td class="text-end">
                                                ${{ number_format($paquete['total_importe_todos_articulos'] / $paquete['veces_vendido'], 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td
                                                colspan="7"
                                                class="text-center"
                                            >No hay datos de paquetes</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN GRÁFICAS Y TABLAS -->
        {{-- <div class="row">
            <div
                class="col-12 col-xxl-8 d-flex flex-column pb-4"
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
                                    <th>Código</th>
                                    <th>Articulo</th>
                                    <th>Cantidad</th>
                                    <th>Precios</th>
                                    <th>Iva</th>
                                    <th>Importe</th>
                                    <th>Fecha venta</th>
                                    <th>NomGrupo</th>
                                    <th>Familia</th>
                                    <th>Ticket</th>
                                    <th>Tienda</th>
                                    <th>Paquete</th>
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
                                        <td>{{ $item->CodArticulo }}</td>
                                        <td>{{ $item->NomArticulo }}</td>
                                        <td>{{ number_format($item->CantArticulo, 3) }}</td>
                                        <td>
                                            ${{ number_format($item->PrecioArticulo, 2) }}
                                        </td>

                                        <td>{{ number_format($item->IvaArticulo, 2) }}</td>
                                        <td>{{ number_format($item->ImporteArticulo, 2) }}</td>
                                        <td>{{ $item->FechaVenta }}</td>
                                        <td>{{ $item->NomGrupo }}</td>
                                        <td>{{ $item->NomFamilia }}</td>
                                        <td>{{ $item->IdEncabezado }}</td>
                                        <td>{{ $item->NomTienda }}</td>
                                        <td>{{ $item->NomPaquete }}</td>
                                    </tr>

                                    @php
                                        // Acumulamos los valores
                                        $totalPeso += $item->CantArticulo;
                                        // $precioLista += $item->PrecioLista;
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
                                                actionUrl="/ReportePaquetes"
                                            />
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                            @if (count($data) > 0)
                                <tfoot>
                                    <tr>
                                        <td colspan="7"><strong>Total:</strong></td>
                                        <td><strong>{{ number_format($totalPeso, 2) }}</strong></td>
                                        <td>
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
        </div> --}}
        <div class="row">
            <div
                class="col-12 col-xxl-8 d-flex flex-column pb-4"
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
                                    <th>Código</th>
                                    <th>Artículo</th>
                                    <th>Cantidad</th>
                                    <th>Precios</th>
                                    <th>Iva</th>
                                    <th>Importe</th>
                                    <th>Fecha venta</th>
                                    <th>Familia</th>
                                    {{-- <th>Ticket</th>
                                    <th>Tienda</th> --}}
                                    <th>Paquete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Inicializamos las variables para la suma
                                    $totalPeso = 0;
                                    $precioDesc = 0;
                                    $totalIva = 0;
                                    $totalImporte = 0;

                                    // Agrupar los datos por IdEncabezado (ticket)
                                    $groupedByTicket = [];
                                    foreach ($data as $item) {
                                        $ticketId = $item->IdEncabezado;
                                        if (!isset($groupedByTicket[$ticketId])) {
                                            $groupedByTicket[$ticketId] = [
                                                'items' => [],
                                                'paquetes' => [],
                                                'ticket_info' => $item,
                                            ];
                                        }

                                        // Si tiene paquete, agrupar por IdPaquete dentro del ticket
                                        if ($item->IdPaquete) {
                                            $paqueteId = $item->IdPaquete;
                                            if (!isset($groupedByTicket[$ticketId]['paquetes'][$paqueteId])) {
                                                $groupedByTicket[$ticketId]['paquetes'][$paqueteId] = [
                                                    'paquete_nombre' => $item->NomPaquete,
                                                    'items' => [],
                                                    'total_cantidad' => 0,
                                                    'total_importe' => 0,
                                                ];
                                            }
                                            $groupedByTicket[$ticketId]['paquetes'][$paqueteId]['items'][] = $item;
                                            $groupedByTicket[$ticketId]['paquetes'][$paqueteId]['total_cantidad'] +=
                                                $item->CantArticulo;
                                            $groupedByTicket[$ticketId]['paquetes'][$paqueteId]['total_importe'] +=
                                                $item->ImporteArticulo;
                                        } else {
                                            // Items sin paquete
                                            $groupedByTicket[$ticketId]['items'][] = $item;
                                        }
                                    }
                                @endphp

                                @forelse ($groupedByTicket as $ticketId => $ticketData)
                                    <!-- Fila de cabecera del ticket -->
                                    <tr
                                        class="table-primary"
                                        style="background-color: #e3f2fd;"
                                    >
                                        <td colspan="9">
                                            <strong>🎫 TICKET #{{ $ticketId }}</strong>
                                            - Tienda: {{ $ticketData['ticket_info']->NomTienda ?? 'N/A' }}
                                            - Fecha: {{ $ticketData['ticket_info']->FechaVenta ?? 'N/A' }}
                                        </td>
                                    </tr>

                                    <!-- Mostrar paquetes dentro del ticket -->
                                    @if (!empty($ticketData['paquetes']))
                                        @foreach ($ticketData['paquetes'] as $paqueteId => $paqueteData)
                                            <!-- Fila de cabecera del paquete -->
                                            {{-- <tr
                                                class="table-warning"
                                                style="background-color: #fff3e0;"
                                            >
                                                <td colspan="9">
                                                    <strong>📦 PAQUETE: {{ $paqueteData['paquete_nombre'] }} (ID:
                                                        {{ $paqueteId }})</strong>
                                                    <span class="badge bg-info ms-2">Total:
                                                        {{ number_format($paqueteData['total_cantidad'], 3) }}
                                                        unidades</span>
                                                    <span class="badge bg-success ms-2">Importe:
                                                        ${{ number_format($paqueteData['total_importe'], 2) }}</span>
                                                </td>
                                            </tr> --}}

                                            <!-- Items del paquete -->
                                            @foreach ($paqueteData['items'] as $item)
                                                <tr style="background-color: #fffaf0;">
                                                    <td>{{ $item->CodArticulo }}</td>
                                                    <td>{{ $item->NomArticulo }}</td>
                                                    <td>{{ number_format($item->CantArticulo, 3) }}</td>
                                                    <td>${{ number_format($item->PrecioArticulo, 2) }}</td>
                                                    <td>{{ number_format($item->IvaArticulo, 2) }}</td>
                                                    <td>{{ number_format($item->ImporteArticulo, 2) }}</td>
                                                    <td>{{ $item->FechaVenta }}</td>
                                                    <td>{{ $item->NomFamilia }}</td>
                                                    {{-- <td>{{ $item->IdEncabezado }}</td> --}}
                                                    {{-- <td>{{ $item->NomTienda }}</td> --}}
                                                    <td>{{ $item->NomPaquete }}</td>
                                                </tr>
                                                @php
                                                    // Acumulamos los valores totales
                                                    $totalPeso += $item->CantArticulo;
                                                    $precioDesc += $item->PrecioArticulo;
                                                    $totalIva += $item->IvaArticulo;
                                                    $totalImporte += $item->ImporteArticulo;
                                                @endphp
                                            @endforeach
                                        @endforeach
                                    @endif

                                    <!-- Items sin paquete del ticket -->
                                    @if (!empty($ticketData['items']))
                                        {{-- <tr
                                            class="table-secondary"
                                            style="background-color: #f8f9fa;"
                                        >
                                            <td colspan="11">
                                                <strong>📝 Items sin paquete</strong>
                                            </td>
                                        </tr> --}}
                                        @foreach ($ticketData['items'] as $item)
                                            <tr>
                                                <td>{{ $item->CodArticulo }}</td>
                                                <td>{{ $item->NomArticulo }}</td>
                                                <td>{{ number_format($item->CantArticulo, 3) }}</td>
                                                <td>${{ number_format($item->PrecioArticulo, 2) }}</td>
                                                <td>{{ number_format($item->IvaArticulo, 2) }}</td>
                                                <td>{{ number_format($item->ImporteArticulo, 2) }}</td>
                                                <td>{{ $item->FechaVenta }}</td>
                                                <td>{{ $item->NomFamilia }}</td>
                                                {{-- <td>{{ $item->IdEncabezado }}</td> --}}
                                                {{-- <td>{{ $item->NomTienda }}</td> --}}
                                                <td>
                                                    @if ($item->NomPaquete)
                                                        {{ \Illuminate\Support\Str::limit($item->NomPaquete, strlen($item->NomPaquete) - 15, '') }}
                                                    @else
                                                        Sin paquete
                                                    @endif
                                                </td>

                                            </tr>
                                            @php
                                                // Acumulamos los valores totales
                                                $totalPeso += $item->CantArticulo;
                                                $precioDesc += $item->PrecioArticulo;
                                                $totalIva += $item->IvaArticulo;
                                                $totalImporte += $item->ImporteArticulo;
                                            @endphp
                                        @endforeach
                                    @endif

                                    <!-- Separador entre tickets -->
                                    <tr>
                                        <td
                                            colspan="11"
                                            style="border-bottom: 2px solid #dee2e6;"
                                        ></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td
                                            colspan="11"
                                            class="py-5 text-center"
                                        >
                                            <x-table-empty-state
                                                title="Sin datos disponibles"
                                                icon="cube"
                                                :message="'No se encontraron resultados con los filtros seleccionados.'"
                                                :suggestion="'Intenta ampliar el rango de fechas o modificar los criterios de búsqueda.'"
                                                action="Limpiar filtros"
                                                actionUrl="/ReportePaquetes"
                                            />
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                            @if (count($data) > 0)
                                <tfoot>
                                    <tr>
                                        <td colspan="2"><strong>Totales Generales:</strong></td>
                                        <td><strong>{{ number_format($totalPeso, 2) }}</strong></td>
                                        <td></td>
                                        <td><strong>{{ number_format($totalIva, 2) }}</strong></td>
                                        <td><strong>{{ number_format($totalImporte, 2) }}</strong></td>
                                        <td colspan="5"></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
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
