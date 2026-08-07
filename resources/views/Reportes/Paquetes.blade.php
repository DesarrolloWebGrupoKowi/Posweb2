<x-page-container title="Reporte de Paquetes">

    <!-- SECCIÓN 1: FILTROS -->
    <x-card-gradient-header
        icon="gift"
        title="Reporte de Paquetes"
        subtitle="Reporte de paquetes comercializados por ticket"
    >
        <x-slot:buttons>
            <a
                href="/ExportsReportePaquetes?{{ http_build_query(request()->only(['idTienda', 'fecha_inicio', 'fecha_fin', 'id_paquete', 'nom_paquete'])) }}"
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
                action="/ReportePaquetes"
            >
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        ><i class="bi bi-shop me-1"></i>Tienda</label>
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
                        ><i class="bi bi-calendar3 me-1"></i>Fecha Inicio</label>
                        <input
                            type="date"
                            name="fecha_inicio"
                            class="form-control"
                            style="border-radius: 8px; border: 1px solid var(--border-input); padding: 8px 12px; font-size: 0.85rem;"
                            value="{{ request('fecha_inicio') }}"
                            autofocus
                        >
                    </div>
                    <div class="col-md-2">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        ><i class="bi bi-calendar3 me-1"></i>Fecha Fin</label>
                        <input
                            type="date"
                            name="fecha_fin"
                            class="form-control"
                            style="border-radius: 8px; border: 1px solid var(--border-input); padding: 8px 12px; font-size: 0.85rem;"
                            value="{{ request('fecha_fin') }}"
                        >
                    </div>
                    <div class="col-md-2">
                        <label
                            class="form-label fw-medium mb-2"
                            style="color: var(--text-secondary); font-size: 0.85rem;"
                        ><i class="bi bi-gift me-1"></i>Paquete</label>
                        <select
                            name="id_paquete"
                            class="form-select"
                            style="border-radius: 8px; border: 1px solid var(--border-input); padding: 8px 12px; font-size: 0.85rem;"
                        >
                            <option value="">Todos</option>
                            @foreach ($paquetes as $paquete)
                                <option
                                    value="{{ $paquete->IdPaquete }}"
                                    {{ request('id_paquete') == $paquete->IdPaquete ? 'selected' : '' }}
                                >{{ $paquete->NomPaquete }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center gap-2">
                            <x-form.submit
                                text="Buscar"
                                icon="funnel"
                                class="flex-grow-1"
                            />
                            <x-form.clear url="/ReportePaquetes" />
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
                        ><i class="bi bi-search me-1"></i>Nombre Paquete</label>
                        <input
                            type="text"
                            name="nom_paquete"
                            class="form-control"
                            style="border-radius: 8px; border: 1px solid var(--border-input); padding: 8px 12px; font-size: 0.85rem;"
                            placeholder="Nombre del paquete"
                            value="{{ request('nom_paquete') }}"
                        >
                    </div>
                </div>
            </form>
        </div>

        <!-- SECCIÓN 2: KPIs -->
        @php $kpis = $paquetesKPIs ?? []; @endphp
        <div class="p-4">
            <div class="row g-3">
                <div class="col-xl-3 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(59, 130, 246, 0.08); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: #1d4ed8; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                >Paquetes Dif.</span>
                                <i
                                    class="bi bi-gift"
                                    style="color: #3b82f6; font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.5rem;"
                            >{{ $kpis['total_paquetes'] ?? 0 }}</h3>
                            <span style="color: var(--kpi-sub-color); font-size: 0.78rem;">Tipos de paquetes</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
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
                                    style="color: var(--kpi-green-text-icon); font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                >Tickets c/Paq.</span>
                                <i
                                    class="bi bi-receipt"
                                    style="color: var(--kpi-icon-green); font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.5rem;"
                            >{{ $kpis['total_tickets_con_paquetes'] ?? 0 }}</h3>
                            <span style="color: var(--kpi-sub-color); font-size: 0.78rem;">Tickets con paquetes</span>
                        </div>
                    </div>
                </div>
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
                                    style="color: var(--kpi-purple-text); font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                >Importe Paq.</span>
                                <i
                                    class="bi bi-cash-stack"
                                    style="color: var(--kpi-icon-purple); font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.5rem;"
                            >${{ number_format($kpis['total_importe_paquetes'] ?? 0, 2) }}</h3>
                            <span style="color: var(--kpi-sub-color); font-size: 0.78rem;">Monto total en
                                paquetes</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
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
                                    style="color: var(--kpi-orange-text); font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                >Más Vendido</span>
                                <i
                                    class="bi bi-trophy"
                                    style="color: var(--kpi-icon-orange); font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.2rem;"
                            >{{ \Illuminate\Support\Str::limit($kpis['paquete_mas_frecuente']['nombre'] ?? 'N/A', 20) }}
                            </h3>
                            <span
                                style="color: var(--kpi-sub-color); font-size: 0.78rem;">{{ $kpis['paquete_mas_frecuente']['veces_vendido'] ?? 0 }}
                                veces vendido</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de detalle de paquetes -->
            @if (!empty($kpis['paquetes_detalle']))
                <div class="mt-4">
                    <div
                        class="card-chart rounded p-4 shadow-sm"
                        style="border: 1px solid var(--border-input);"
                    >
                        <h5
                            class="mb-3"
                            style="font-weight: 600; color: var(--text-primary); font-size: 1rem;"
                        >
                            <i
                                class="bi bi-bar-chart me-2"
                                style="color: var(--text-secondary);"
                            ></i>Resumen por Paquete
                        </h5>
                        <div class="table-responsive">
                            <table class="table-hover table-custom table">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-hash me-1"></i>ID</th>
                                        <th><i class="bi bi-gift me-1"></i>Nombre</th>
                                        <th class="text-center"><i class="bi bi-cart me-1"></i>Veces</th>
                                        <th class="text-end"><i class="bi bi-box me-1"></i>Artículos</th>
                                        <th class="text-end"><i class="bi bi-cash me-1"></i>Importe Paq.</th>
                                        <th class="text-end"><i class="bi bi-cash-stack me-1"></i>Importe Total</th>
                                        <th class="text-end"><i class="bi bi-receipt me-1"></i>Ticket Prom.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kpis['paquetes_detalle'] as $paquete)
                                        <tr>
                                            <td style="font-weight: 600; color: var(--text-primary);">
                                                {{ $paquete['id'] }}</td>
                                            <td style="font-weight: 500;">{{ $paquete['nombre'] }}</td>
                                            <td
                                                class="text-center"
                                                style="font-weight: 500;"
                                            >{{ $paquete['veces_vendido'] }}</td>
                                            <td class="text-end">{{ number_format($paquete['total_cantidad'], 2) }}
                                            </td>
                                            <td
                                                class="text-end"
                                                style="color: var(--success-color); font-weight: 500;"
                                            >${{ number_format($paquete['total_importe'], 2) }}</td>
                                            <td class="text-end">
                                                ${{ number_format($paquete['total_importe_todos_articulos'], 2) }}</td>
                                            <td class="text-end">
                                                ${{ number_format($paquete['total_importe_todos_articulos'] / $paquete['veces_vendido'], 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- SECCIÓN 3: TABLA DETALLADA POR TICKET -->
        <div class="mt-4 px-4 pb-4">
            <h5
                class="mb-3"
                style="font-weight: 600; color: var(--text-primary); font-size: 1rem;"
            >
                <i
                    class="bi bi-ticket-detailed me-2"
                    style="color: var(--text-secondary);"
                ></i>Detalle por Ticket
            </h5>
            <div
                class="table-responsive"
                style="overflow-y: auto;"
            >
                <table class="table-hover table-custom table">
                    <thead style="position: sticky; top: 0; z-index: 2;">
                        <tr>
                            <th><i class="bi bi-upc-scan me-1"></i>Código</th>
                            <th><i class="bi bi-box me-1"></i>Artículo</th>
                            <th class="text-end"><i class="bi bi-hash me-1"></i>Cantidad</th>
                            <th><i class="bi bi-cash me-1"></i>Precio</th>
                            <th class="text-end"><i class="bi bi-percent me-1"></i>IVA</th>
                            <th class="text-end"><i class="bi bi-cash-stack me-1"></i>Importe</th>
                            <th><i class="bi bi-calendar3 me-1"></i>Fecha</th>
                            <th><i class="bi bi-folder me-1"></i>Familia</th>
                            <th><i class="bi bi-gift me-1"></i>Paquete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalPeso = 0;
                            $totalIva = 0;
                            $totalImporte = 0;
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
                                if ($item->IdPaquete) {
                                    $paqueteId = $item->IdPaquete;
                                    if (!isset($groupedByTicket[$ticketId]['paquetes'][$paqueteId])) {
                                        $groupedByTicket[$ticketId]['paquetes'][$paqueteId] = [
                                            'nombre' => $item->NomPaquete,
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
                                    $groupedByTicket[$ticketId]['items'][] = $item;
                                }
                            }
                        @endphp

                        @forelse ($groupedByTicket as $ticketId => $ticketData)
                            <!-- Cabecera del ticket -->
                            <tr style="background: var(--table-head-secondary-bg);">
                                <td colspan="9">
                                    <i
                                        class="bi bi-ticket-perforated me-2"
                                        style="color: var(--text-subtle);"
                                    ></i>
                                    <strong style="color: var(--text-primary);">TICKET #{{ $ticketId }}</strong>
                                    <span style="color: var(--text-muted);">|</span>
                                    <span
                                        style="color: var(--text-subtle);">{{ $ticketData['ticket_info']->NomTienda ?? 'N/A' }}</span>
                                    <span style="color: var(--text-muted);">|</span>
                                    {{ $ticketData['ticket_info']->FechaVenta }}
                                </td>
                            </tr>

                            <!-- Paquetes del ticket -->
                            @foreach ($ticketData['paquetes'] as $paqueteId => $paqueteData)
                                @foreach ($paqueteData['items'] as $item)
                                    <tr style="background: var(--btn-amber-bg);">
                                        <td style="font-weight: 500;">{{ $item->CodArticulo }}</td>
                                        <td
                                            class="text-truncate"
                                            style="max-width: 180px;"
                                            title="{{ $item->NomArticulo }}"
                                        >{{ $item->NomArticulo }}</td>
                                        <td
                                            class="text-end"
                                            style="font-weight: 500;"
                                        >{{ number_format($item->CantArticulo, 3) }}</td>
                                        <td>${{ number_format($item->PrecioArticulo, 2) }}</td>
                                        <td
                                            class="text-end"
                                            style="font-weight: 500;"
                                        >${{ number_format($item->IvaArticulo, 2) }}</td>
                                        <td
                                            class="text-end"
                                            style="font-weight: 500;"
                                        >${{ number_format($item->ImporteArticulo, 2) }}</td>
                                        <td style="font-size: 0.85rem;">{{ $item->FechaVenta }}</td>
                                        <td>{{ $item->NomFamilia }}</td>
                                        <td><span class="tags-yellow">{{ $item->NomPaquete }}</span></td>
                                    </tr>
                                    @php
                                        $totalPeso += $item->CantArticulo;
                                        $totalIva += $item->IvaArticulo;
                                        $totalImporte += $item->ImporteArticulo;
                                    @endphp
                                @endforeach
                            @endforeach

                            <!-- Items sin paquete -->
                            @foreach ($ticketData['items'] as $item)
                                <tr>
                                    <td style="font-weight: 500;">{{ $item->CodArticulo }}</td>
                                    <td
                                        class="text-truncate"
                                        style="max-width: 180px;"
                                        title="{{ $item->NomArticulo }}"
                                    >{{ $item->NomArticulo }}</td>
                                    <td
                                        class="text-end"
                                        style="font-weight: 500;"
                                    >{{ number_format($item->CantArticulo, 3) }}</td>
                                    <td>${{ number_format($item->PrecioArticulo, 2) }}</td>
                                    <td
                                        class="text-end"
                                        style="font-weight: 500;"
                                    >${{ number_format($item->IvaArticulo, 2) }}</td>
                                    <td
                                        class="text-end"
                                        style="font-weight: 500;"
                                    >${{ number_format($item->ImporteArticulo, 2) }}</td>
                                    <td style="font-size: 0.85rem;">{{ $item->FechaVenta }}</td>
                                    <td>{{ $item->NomFamilia }}</td>
                                    <td>
                                        @if ($item->NomPaquete)
                                            <span
                                                class="tags-blue">{{ \Illuminate\Support\Str::limit($item->NomPaquete, 20) }}</span>
                                        @else
                                            <span style="color: var(--text-muted); font-size: 0.8rem;">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @php
                                    $totalPeso += $item->CantArticulo;
                                    $totalIva += $item->IvaArticulo;
                                    $totalImporte += $item->ImporteArticulo;
                                @endphp
                            @endforeach

                            <!-- Separador entre tickets -->
                            <tr>
                                <td
                                    colspan="9"
                                    style="border-bottom: 2px solid var(--border-medium); padding: 0;"
                                ></td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="9"
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
                                        href="/ReportePaquetes"
                                        class="btn btn-sm d-flex align-items-center mx-auto mt-2 gap-1"
                                        style="background: var(--btn-gray-bg); color: var(--btn-gray-text); border: none; border-radius: 8px; padding: 8px 16px; width: fit-content;"
                                    >
                                        <i class="bi bi-x-circle"></i> Limpiar filtros
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if (count($data) > 0)
                        <tfoot>
                            <tr class="bg-table-totals">
                                <td
                                    colspan="2"
                                    class="text-end"
                                    style="color: var(--text-primary);"
                                >TOTALES GENERALES:</td>
                                <td
                                    class="text-end"
                                    style="color: var(--text-primary);"
                                >{{ number_format($totalPeso, 2) }}</td>
                                <td></td>
                                <td
                                    class="text-end"
                                    style="color: var(--text-primary);"
                                >${{ number_format($totalIva, 2) }}</td>
                                <td
                                    class="text-end"
                                    style="color: var(--text-primary);"
                                >${{ number_format($totalImporte, 2) }}</td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
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
