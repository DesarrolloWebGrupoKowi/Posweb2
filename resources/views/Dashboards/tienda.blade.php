@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Dashboard por Tienda')
@section('dashboardWidth', 'width-95')
@section('contenido')

    <x-layout.page-container>

        <!-- SECCIÓN 1: TITULO Y FILTROS -->
        <x-layout.section-card>
            <!-- Título y botones principales -->
            <div class="d-flex justify-content-sm-between align-items-end align-items-sm-start flex-column flex-sm-row mb-2">
                <x-title titulo="Dashboard por Tienda" />
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

        <x-dashboard-notificacion-procesar-tienda :tiendaActual="$tiendaActual" />

        <!-- SECCIÓN 2: KPIs -->
        <div class="flex-shrink-0">
            <div class="row g-4">
                <!-- Solicitudes Facturas -->
                <x-kpi.kpi-card
                    title="Solicitudes Facturas"
                    :value="$kpis['solicitudes_factura'] ?? 0"
                    :subtitle="($kpis['facturas_pendientes'] ?? 0) . ' pendientes de ligar'"
                    subtitle-class="{{ ($kpis['facturas_pendientes'] ?? 0) > 0 ? 'text-warning' : 'text-success' }}"
                    color="purple"
                    icon="components.icons.file-text"
                />

                <!-- Tickets -->
                <x-kpi.kpi-card
                    title="Tickets"
                    :value="$kpis['tickets'] ?? 0"
                    :subtitle="'Ticket promedio: $' . number_format($kpis['promedio_ticket'] ?? 0, 2)"
                    color="teal"
                    icon="components.icons.credit-card"
                />

                <!-- Kilos Vendidos -->
                <x-kpi.kpi-card
                    title="Kilos Vendidos"
                    :value="number_format($kpis['kilos_hoy'] ?? 0, 1)"
                    :subtitle="number_format(($kpis['kilos_promedio'] ?? 0) / max($kpis['tickets'] ?? 1, 1), 2) .
                        ' kg/transacción'"
                    color="orange"
                    icon="components.icons.box"
                    suffix=" kg"
                />

                <!-- Ventas Hoy -->
                <x-kpi.kpi-card
                    title="Ventas Hoy"
                    :value="$kpis['ventas_hoy'] ?? 0"
                    :subtitle="abs($kpis['ventas_vs_ayer'] ?? 0) . '% vs día anterior'"
                    subtitle-icon="{{ ($kpis['ventas_vs_ayer'] ?? 0) > 0 ? '↑' : (($kpis['ventas_vs_ayer'] ?? 0) < 0 ? '↓' : '→') }}"
                    subtitle-class="{{ ($kpis['ventas_vs_ayer'] ?? 0) > 0 ? 'text-success' : (($kpis['ventas_vs_ayer'] ?? 0) < 0 ? 'text-danger' : 'text-warning') }}"
                    color="indigo"
                    icon="components.icons.cash"
                    currency="true"
                />
            </div>
        </div>

        <!-- SECCIÓN 3: GRÁFICAS Y TABLAS POR TIENDA -->
        <div
            class="flex-grow-1 d-flex gap-4"
            style="min-height: 0;"
        >
            <!-- TABLA: Corte tienda -->
            <div
                class="d-flex flex-column"
                style="flex: 2; min-width: 0; min-height: 0;"
            >
                <div
                    class="card d-flex flex-column border-0 p-4"
                    style="border-radius: 10px; min-height: 0;"
                >
                    <!--Header tabla-->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="flex-column">
                            <h5 class="mb-0 text-gray-800">CORTE TIENDA {{ $tiendaActual->NomTienda ?? '' }}
                            </h5>
                            @if ($fechaActual)
                                <h6
                                    class="fw-semibold text-muted m-0"
                                    style="font-size: 0.9rem;"
                                >
                                    {{ \Carbon\Carbon::parse($fechaActual)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
                                </h6>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <x-dashboard-buttons-procesar
                                :corteTienda="$corteTienda"
                                :corteTiendaSolicitudes="$corteTiendaSolicitudes"
                            />
                            <!-- Botón Expandir/Contraer -->
                            <button
                                class="btn btn-sm btn-outline-dark"
                                onclick="toggleExpandirTabla()"
                                id="btnExpandir"
                                title="Expandir/Contraer tabla"
                            >
                                <span class="d-flex align-items-center gap-1">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="14"
                                        height="14"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"
                                        />
                                    </svg>
                                    <span id="btnExpandirTexto">Expandir</span>
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Contenido normal con datos -->
                    <div
                        id="vistaTabla"
                        class="table-responsive content-table-sm"
                    >
                        <table class="table">
                            <thead class="table-head">
                                <tr>
                                    <th>Cliente</th>
                                    <th>Pedido</th>
                                    <th>Estatus</th>
                                    <th class="text-end">Cantidad</th>
                                    <th class="text-end">Ventas</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalVentas = 0;
                                    $totalKilos = 0;
                                @endphp

                                <!-- SECCION: Corte de contado -->
                                @foreach ($corteTienda as $item)
                                    @php
                                        $totalVentas += $item->total_importe;
                                        $totalKilos += $item->total_cantidad;

                                        // Obtener datos de Oracle del objeto anidado
                                        $oracleData = $item->OracleData ?? null;
                                        $status = $oracleData->STATUS ?? null;
                                        $mensajeError = $oracleData->MENSAJE_ERROR ?? null;
                                        $transactionOn = $oracleData->Transaction_On ?? null;
                                        $sourceTransactionNumber = $oracleData->Source_Transaction_Number ?? null;
                                        $sourceIdentifier = $item->Source_Transaction_Identifier ?? null;
                                    @endphp

                                    <tr id="row-{{ $sourceIdentifier ?? 'temp-' . $loop->index }}">
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
                                                    title="{{ $item->Bill_To }}"
                                                >
                                                    {{ $item->NomClienteCloud }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="fw-500">
                                            <span class="{{ $sourceIdentifier ? 'tags-blue' : 'tags-red' }}">
                                                {{ $sourceTransactionNumber ?? ($sourceIdentifier ? 'SIN NÚMERO' : 'SIN PEDIDO') }}
                                            </span>
                                        </td>
                                        <td class="fw-500">
                                            <span
                                                id="status-{{ $sourceIdentifier }}"
                                                class="{{ $status === 'PROCESADO' ? 'tags-green' : ($status === 'ERROR' ? 'tags-red' : 'tags-yellow') }}"
                                            >
                                                {{ $status && $status !== 'NULL' ? $status : 'SIN PROCESAR' }}
                                            </span>
                                        </td>
                                        <td class="fw-500 text-end">{{ number_format($item->total_cantidad, 3) }} kg</td>
                                        <td class="fw-500 text-end">${{ number_format($item->total_importe, 2) }}</td>
                                        <td class="text-center">
                                            @if ($status === 'PROCESADO' && $sourceIdentifier)
                                                @php
                                                    $POS =
                                                        substr($sourceIdentifier, 0, 3) .
                                                        '_' .
                                                        substr($sourceIdentifier, 3);
                                                @endphp
                                                <a
                                                    href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Pdf?Orden={{ $POS }}"
                                                    target="_blank"
                                                    class="btn btn-sm btn-outline-primary"
                                                    title="Descargar Factura PDF"
                                                >
                                                    @include('components.icons.download')
                                                    <span class="d-none d-md-inline">PDF</span>
                                                </a>
                                                <a
                                                    href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Xml?Orden={{ $POS }}"
                                                    target="_blank"
                                                    class="btn btn-sm btn-outline-primary"
                                                    title="Descargar Factura XML"
                                                >
                                                    @include('components.icons.download')
                                                    <span class="d-none d-md-inline">XML</span>
                                                </a>
                                            @elseif ($sourceIdentifier && $status !== 'PROCESADO')
                                                <button
                                                    type="button"
                                                    id="btnEnviarPedido{{ $sourceIdentifier }}"
                                                    class="btn btn-sm btn-outline-primary btn-enviar"
                                                    title="Enviar pedido a Oracle"
                                                    data-pedido="{{ $sourceIdentifier }}"
                                                    data-row-id="row-{{ $sourceIdentifier }}"
                                                    data-original-status="{{ $status }}"
                                                    data-original-mensaje="{{ $mensajeError ?? '' }}"
                                                >
                                                    @include('components.icons.send')
                                                    <span class="d-none d-md-inline">ENVIAR</span>
                                                </button>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Fila de mensaje del POS -->
                                    @if (!empty($mensajeError))
                                        <tr
                                            id="msg-{{ $sourceIdentifier }}"
                                            class="bg-light"
                                        >
                                            <td
                                                colspan="6"
                                                class="py-1 ps-5"
                                            >
                                                <small
                                                    id="mensaje-container-{{ $sourceIdentifier }}"
                                                    class="{{ $status === 'ERROR' ? 'text-danger' : 'text-success' }}"
                                                >
                                                    <strong id="mensaje-titulo-{{ $sourceIdentifier }}">
                                                        @if ($transactionOn)
                                                            {{ $transactionOn }} -
                                                        @endif
                                                        {{ $status === 'ERROR' ? 'Error:' : 'Mensaje:' }}
                                                    </strong>
                                                    <span id="mensaje-texto-{{ $sourceIdentifier }}">
                                                        {{ $mensajeError }}
                                                    </span>
                                                </small>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach

                                <!-- SECCION: Corte de solicitudes de factura -->
                                @foreach ($corteTiendaSolicitudes as $item)
                                    @php
                                        $totalVentas += $item->total_importe;
                                        $totalKilos += $item->total_cantidad;

                                        // Obtener datos de Oracle del objeto anidado
                                        $oracleData = $item->OracleData ?? null;
                                        $status = $oracleData->STATUS ?? null;
                                        $mensajeError = $oracleData->MENSAJE_ERROR ?? null;
                                        $transactionOn = $oracleData->Transaction_On ?? null;
                                        $sourceTransactionNumber = $oracleData->Source_Transaction_Number ?? null;
                                        $sourceIdentifier = $item->Source_Transaction_Identifier ?? null;
                                    @endphp

                                    <tr id="row-{{ $sourceIdentifier ?? 'temp-' . $loop->index }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="me-2 rounded p-1"
                                                    style="background-color: rgba(30, 66, 159, 0.1);"
                                                >
                                                    <div style="color: #1e429f; width: 16px; height: 16px;">
                                                        @include('components.icons.user')
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <div
                                                        class="text-truncate puntitos"
                                                        title="{{ $item->NomCliente }}"
                                                    >
                                                        {{ $item->NomCliente }}
                                                    </div>
                                                    @if ($item->UUID ?? false)
                                                        <small>Facturación en Línea</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-500">
                                            <span class="{{ $sourceIdentifier ? 'tags-blue' : 'tags-red' }}">
                                                @if ($sourceIdentifier)
                                                    {{ $sourceTransactionNumber }}
                                                @elseif ($item->Editar != null)
                                                    SIN LIGAR
                                                @else
                                                    SIN PEDIDO
                                                @endif
                                            </span>
                                        </td>
                                        <td class="fw-500">
                                            <span
                                                id="status-{{ $sourceIdentifier }}"
                                                class="{{ $status === 'PROCESADO' ? 'tags-green' : ($status === 'ERROR' ? 'tags-red' : 'tags-yellow') }}"
                                            >
                                                {{ $status && $status !== 'NULL' ? $status : 'SIN PROCESAR' }}
                                            </span>
                                        </td>
                                        <td class="fw-500 text-end">{{ number_format($item->total_cantidad, 3) }} kg</td>
                                        <td class="fw-500 text-end">${{ number_format($item->total_importe, 2) }}</td>
                                        <td class="text-center">
                                            @if ($sourceIdentifier && $status != 'PROCESADO')
                                                <button
                                                    type="button"
                                                    id="btnEnviarPedido{{ $sourceIdentifier }}"
                                                    class="btn btn-sm btn-outline-primary btn-enviar"
                                                    title="Enviar pedido a Oracle"
                                                    data-pedido="{{ $sourceIdentifier }}"
                                                    data-row-id="row-{{ $sourceIdentifier }}"
                                                    data-original-status="{{ $status }}"
                                                    data-original-mensaje="{{ $mensajeError ?? '' }}"
                                                >
                                                    @include('components.icons.send')
                                                    <span class="d-none d-md-inline">ENVIAR</span>
                                                </button>
                                            @endif

                                            @if ($status === 'PROCESADO' && !($item->UUID ?? false))
                                                @php
                                                    $POS =
                                                        substr($sourceIdentifier, 0, 3) .
                                                        '_' .
                                                        substr($sourceIdentifier, 3);
                                                @endphp
                                                <a
                                                    href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Pdf?Orden={{ $POS }}"
                                                    target="_blank"
                                                    class="btn btn-sm btn-outline-primary"
                                                    title="Descargar Factura PDF"
                                                >
                                                    @include('components.icons.download')
                                                    <span class="d-none d-md-inline">PDF</span>
                                                </a>
                                                <a
                                                    href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Xml?Orden={{ $POS }}"
                                                    target="_blank"
                                                    class="btn btn-sm btn-outline-primary"
                                                    title="Descargar Factura XML"
                                                >
                                                    @include('components.icons.download')
                                                    <span class="d-none d-md-inline">XML</span>
                                                </a>
                                            @endif

                                            @if ($item->UUID ?? false)
                                                <a
                                                    href="http://oraclefacturasrest.kowi.com.mx/api/Documentos/Pdftest?UUID={{ $item->UUID }}"
                                                    target="_blank"
                                                    class="btn btn-sm btn-outline-primary"
                                                    title="Descargar Factura PDF"
                                                >
                                                    @include('components.icons.download')
                                                    <span class="d-none d-md-inline">PDF</span>
                                                </a>
                                                <a
                                                    href="http://oraclefacturasrest.kowi.com.mx/api/Documentos/xmltest?UUID={{ $item->UUID }}"
                                                    target="_blank"
                                                    class="btn btn-sm btn-outline-primary"
                                                    title="Descargar Factura XML"
                                                >
                                                    @include('components.icons.download')
                                                    <span class="d-none d-md-inline">XML</span>
                                                </a>
                                            @endif

                                            {{-- @if (!$status && !($item->UUID ?? false))
                                                <span class="text-muted">-</span>
                                            @endif --}}
                                            {{-- @dump($item) --}}
                                            <a
                                                href="/SolicitudesFactura/{{ $item->IdSolicitudFactura }}"
                                                target="_blank"
                                                class="btn btn-sm btn-outline-primary"
                                                title="Descargar Factura XML"
                                            >
                                                @include('components.icons.arrow-up-right')
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Fila de mensaje del POS -->
                                    @if (!empty($mensajeError))
                                        <tr
                                            id="msg-{{ $sourceIdentifier }}"
                                            class="bg-light"
                                        >
                                            <td
                                                colspan="6"
                                                class="py-1 ps-5"
                                            >
                                                <small
                                                    id="mensaje-container-{{ $sourceIdentifier }}"
                                                    class="{{ $status === 'ERROR' ? 'text-danger' : 'text-success' }}"
                                                >
                                                    <strong id="mensaje-titulo-{{ $sourceIdentifier }}">
                                                        @if ($transactionOn)
                                                            {{ $transactionOn }} -
                                                        @endif
                                                        {{ $status === 'ERROR' ? 'Error:' : 'Mensaje:' }}
                                                    </strong>
                                                    <span id="mensaje-texto-{{ $sourceIdentifier }}">
                                                        {{ $mensajeError }}
                                                    </span>
                                                    <br>
                                                    @if ($item->UUID ?? false)
                                                        <strong>UUID:</strong>
                                                        <span>{{ $item->UUID }}</span>
                                                    @endif
                                                </small>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach

                                @if (count($corteTienda) == 0 && count($corteTiendaSolicitudes) == 0)
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
                                                action="Ver reporte de hoy"
                                                actionUrl="/DashTienda"
                                            />
                                        </td>

                                    </tr>
                                @endif

                                <!-- Fila de totales -->
                                @if (count($corteTienda) > 0 || count($corteTiendaSolicitudes) > 0)
                                    <tr class="table-light">
                                        <td
                                            colspan="3"
                                            class="fw-bold text-end"
                                        >TOTALES:</td>
                                        <td class="fw-bold text-end">{{ number_format($totalKilos, 2) }} kg</td>
                                        <td class="fw-bold text-end">${{ number_format($totalVentas, 2) }}</td>
                                        <td></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- GRAFICAS -->
            <div style="flex: 1; min-width: 0;">
                <div class="row">
                    <!-- Gráfica de Ventas por Tienda -->
                    <div class="col-12 col-xl-6 mb-xl-0 col-xxl-12 mb-xxl-4 mb-4">
                        <div
                            class="card border-0 p-4"
                            style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;"
                        >
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-semibold mb-3">📈 Ventas diarias por hora</h6>
                                @if (!empty($graficaVentas['data']) || array_sum($graficaVentas['data']) != 0)
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
                                    </div>
                                @endif
                            </div>
                            <div
                                class="position-relative"
                                style="height: 200px;"
                            >
                                @if (empty($graficaVentas['data']) || array_sum($graficaVentas['data']) == 0)
                                    <div style="min-height: 200px;">
                                        <x-table-empty-state
                                            title="Sin datos para mostrar"
                                            icon="credit-card"
                                            :message="'No se encontraron ventas realizadas por empleados en el período seleccionado.'"
                                        />
                                    </div>
                                @else
                                    <canvas id="ventasChart"></canvas>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Distribución de Tipos de Pago -->
                    <div class="col-12 col-xl-6 col-xxl-12">
                        <div
                            class="card border-0 p-4"
                            style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;"
                        >
                            <h6 class="fw-semibold mb-3">💳 Distribución de Pagos</h6>
                            <div
                                class="position-relative"
                                style="height: 200px;"
                            >
                                @if (empty($graficaDistribucionPagos['data']) || array_sum($graficaDistribucionPagos['data']) == 0)
                                    <div style="min-height: 200px;">
                                        <x-table-empty-state
                                            title="Sin datos para mostrar"
                                            icon="credit-card"
                                            :message="'No se encontraron ventas realizadas por empleados en el período seleccionado.'"
                                        />
                                    </div>
                                @else
                                    <canvas id="pagosChart"></canvas>
                                @endif
                            </div>
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

        /* Estilos para modo expandido */
        .modo-expandido {
            position: fixed !important;
            top: 60px !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            z-index: 1050 !important;
            background: white !important;
            margin: 0 !important;
            border-radius: 0 !important;
            padding: 1rem !important;
            /* padding-top: 90px !important; */
            width: 100% !important;
            height: calc(100vh - 60px) !important;
            overflow: auto !important;
        }

        .modo-expandido .table-responsive {
            height: calc(100vh - 120px) !important;
        }

        .btn-expandido {
            background-color: #dc3545 !important;
            color: white !important;
            border-color: #dc3545 !important;
        }

        .btn-expandido:hover {
            background-color: #bb2d3b !important;
        }
    </style>
@endsection

{{-- @section('styles')
    <style>
        .empty-state-icon {
            width: 80px;
            height: 80px;
            color: #d1d5db;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 0.6;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0.6;
            }
        }

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

        .bg-indigo-50 {
            background-color: rgba(67, 56, 202, 0.1);
        }

        .bg-pink-50 {
            background-color: rgba(190, 24, 93, 0.1);
        }

        .bg-emerald-50 {
            background-color: rgba(5, 150, 105, 0.1);
        }

        .tags-red {
            background-color: rgba(190, 24, 93, 0.1);
            color: #be185d;
        }


        .tags-blue {
            background-color: rgba(30, 66, 159, 0.1);
            color: #1e429f;
        }

        .tags-green {
            background-color: rgba(3, 84, 63, 0.1);
            color: #03543f;
        }

        .tags-purple {
            background-color: rgba(124, 58, 237, 0.1);
            color: #7c3aed;
        }
    </style>
@endsection --}}

@section('scripts')
    <script>
        let tablaExpandida = false;
        let contenedorOriginal = null;
        let siguienteHermano = null;

        function toggleExpandirTabla() {
            const contenedorTabla = document.querySelector('#vistaTabla, #vistaTickets').closest(
                '.card.d-flex.flex-column');
            const btnExpandir = document.getElementById('btnExpandir');
            const btnTexto = document.getElementById('btnExpandirTexto');

            if (!tablaExpandida) {
                // Expandir
                contenedorOriginal = contenedorTabla.parentNode;
                siguienteHermano = contenedorTabla.nextSibling;

                // Guardar posición original
                contenedorTabla.style.position = 'relative';

                // Mover al body
                document.body.appendChild(contenedorTabla);
                contenedorTabla.classList.add('modo-expandido');

                // Cambiar botón
                btnExpandir.classList.add('btn-expandido');
                btnTexto.innerHTML = 'Contraer';
                btnExpandir.title = 'Contraer tabla';

                // Cambiar ícono
                btnExpandir.querySelector('svg').innerHTML =
                    '<path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/><line x1="4" y1="4" x2="20" y2="20"/><line x1="20" y1="4" x2="4" y2="20"/>';

                tablaExpandida = true;

                // Agregar evento para cerrar con ESC
                document.addEventListener('keydown', cerrarConEsc);
            } else {
                // Contraer
                contenedorTabla.classList.remove('modo-expandido');

                // Regresar a su posición original
                if (contenedorOriginal && siguienteHermano) {
                    contenedorOriginal.insertBefore(contenedorTabla, siguienteHermano);
                } else if (contenedorOriginal) {
                    contenedorOriginal.appendChild(contenedorTabla);
                }

                // Restaurar botón
                btnExpandir.classList.remove('btn-expandido');
                btnTexto.innerHTML = 'Expandir';
                btnExpandir.title = 'Expandir tabla';

                // Restaurar ícono
                btnExpandir.querySelector('svg').innerHTML =
                    '<path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>';

                tablaExpandida = false;

                // Remover evento de ESC
                document.removeEventListener('keydown', cerrarConEsc);
            }
        }

        function cerrarConEsc(event) {
            if (event.key === 'Escape' && tablaExpandida) {
                toggleExpandirTabla();
            }
        }

        let ventasChart = null;
        let pagosChart = null;

        $(document).ready(function() {
            // Inicializar gráfica de ventas
            const ctxVentas = document?.getElementById('ventasChart')?.getContext('2d');
            ventasChart = new Chart(ctxVentas, {
                type: 'line',
                data: {
                    labels: {!! json_encode($graficaVentas['labels'] ?? []) !!},
                    datasets: [{
                        label: 'Ventas',
                        data: {!! json_encode($graficaVentas['data'] ?? []) !!},
                        borderColor: '#1e429f',
                        backgroundColor: 'rgba(30, 66, 159, 0.1)',
                        borderWidth: 2,
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
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString('es-MX');
                                }
                            }
                        }
                    }
                }
            });

            // Inicializar gráfica de pagos
            const ctxPagos = document?.getElementById('pagosChart')?.getContext('2d');
            pagosChart = new Chart(ctxPagos, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($graficaDistribucionPagos['labels'] ?? []) !!},
                    datasets: [{
                        data: {!! json_encode($graficaDistribucionPagos['data'] ?? []) !!},
                        backgroundColor: [
                            '#1e429f', '#03543f', '#7c3aed',
                            '#9b1c1c', '#d97706', '#059669'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });

            // Botones de período
            $('.periodo-btn').click(function() {
                $('.periodo-btn').removeClass('active');
                $(this).addClass('active');
                actualizarGrafica($(this).data('periodo'));
            });

            document.querySelectorAll('.btn-enviar').forEach(button => {
                button.addEventListener('click', async function() {
                    const pedidoId = this.getAttribute('data-pedido');
                    const rowId = this.getAttribute('data-row-id');
                    const button = this;

                    // Deshabilitar el botón mientras se procesa
                    button.disabled = true;
                    const originalHTML = button.innerHTML;
                    button.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...';

                    try {
                        // Construir la URL para el POST
                        // const pos = pedidoId.substring(0, 3) + '_' + pedidoId.substring(3);
                        const pos = pedidoId;
                        const urlPost = `/DashTienda/enviar-pedido/${pos}`;
                        // `http://oracleordenrest.kowi.com.mx/api/SalesOrder/PostSales?OrdenVta=${pos}&Origen=POS`;

                        console.log('Enviando POST a:', urlPost);
                        console.log('CSRF Token:', document.querySelector(
                            'meta[name="csrf-token"]').getAttribute('content'));

                        // Hacer la petición POST
                        const response = await fetch(urlPost, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        });

                        if (!response.ok) {
                            throw new Error(
                                `Error HTTP: ${response.status} ${response.statusText}`);
                        }

                        const result = await response.json();
                        console.log('Respuesta recibida:', result);

                        // Actualizar la interfaz según la respuesta
                        actualizarFilaConRespuesta(pedidoId, result, button);

                    } catch (error) {
                        console.error('Error:', error);

                        // Crear un objeto de resultado de error para mantener consistencia
                        const errorResult = {
                            ok: false,
                            status: 'Error',
                            message: error.message
                        };

                        actualizarFilaConRespuesta(pedidoId, errorResult, button);

                        // Restaurar el botón en caso de error de red
                        button.disabled = false;
                        button.innerHTML = originalHTML;
                    }
                });
            });

            function actualizarFilaConRespuesta(pedidoId, resultado, button) {
                // Determinar el nuevo estado basado en la respuesta
                let nuevoEstado = resultado.ok ? 'PROCESADO' : 'ERROR';
                let mensaje = resultado.message || (resultado.ok ? 'Procesado correctamente' : 'Error desconocido');
                let esExito = resultado.ok;

                // 1. Actualizar el estado en la tabla
                const spanEstado = document.getElementById(`status-${pedidoId}`);
                if (spanEstado) {
                    spanEstado.className = nuevoEstado === 'PROCESADO' ? 'tags-green' : 'tags-red';
                    spanEstado.textContent = nuevoEstado;
                }

                // 2. Actualizar o crear la fila de mensaje
                const trMensajeId = `msg-${pedidoId}`;
                const trMensajeExistente = document.getElementById(trMensajeId);
                const filaPrincipal = document.getElementById(`row-${pedidoId}`);

                // Si ya existe un mensaje, actualizarlo
                if (trMensajeExistente) {
                    const smallElement = trMensajeExistente.querySelector('small');
                    const spanMensaje = trMensajeExistente.querySelector('#mensaje-texto-' + pedidoId);

                    if (smallElement) {
                        smallElement.className = nuevoEstado === 'ERROR' ? 'text-danger' : 'text-success';
                        // IMPORTANTE: Cambiar "Mensaje:" por "Error:" cuando hay error
                        smallElement.querySelector('strong').textContent = nuevoEstado === 'ERROR' ? 'Error:' :
                            'Mensaje:';
                    }

                    if (spanMensaje) {
                        spanMensaje.textContent = mensaje;
                    }
                } else if (mensaje) {
                    // Si no existe pero hay mensaje, crear nueva fila
                    const nuevaFilaMensaje = document.createElement('tr');
                    nuevaFilaMensaje.id = trMensajeId;
                    nuevaFilaMensaje.className = 'bg-light';

                    // Determinar el texto del strong según si es error o éxito
                    const textoStrong = nuevoEstado === 'ERROR' ? 'Error:' : 'Mensaje:';
                    const claseColor = nuevoEstado === 'ERROR' ? 'text-danger' : 'text-success';

                    nuevaFilaMensaje.innerHTML = `
                        <td colspan="6" class="py-1 ps-5">
                            <small class="${claseColor}">
                                <strong>${textoStrong}</strong>
                                <span id="mensaje-texto-${pedidoId}">${mensaje}</span>
                            </small>
                        </td>
                    `;

                    // Insertar después de la fila principal
                    if (filaPrincipal && filaPrincipal.parentNode) {
                        filaPrincipal.parentNode.insertBefore(nuevaFilaMensaje, filaPrincipal.nextSibling);
                    }
                } else if (esExito && !mensaje) {
                    // Si fue exitoso pero no hay mensaje, eliminar la fila de mensaje si existe
                    if (trMensajeExistente) {
                        trMensajeExistente.remove();
                    }
                }

                console.log('llego a actualizar fila con respuesta');
                console.log(esExito);
                console.log(resultado.dato);
                console.log(resultado.dato.sourceTransactionNumber);

                // 3. Actualizar los botones si el pedido fue procesado exitosamente
                if (esExito && resultado.dato && resultado.dato.sourceTransactionNumber) {
                    // if (esExito && resultado.dato) {
                    // Mantener el formato del POS para las URLs de descarga
                    const posFormat = resultado.dato.sourceTransactionNumber;
                    const tdAcciones = button.parentNode;

                    tdAcciones.innerHTML = `
                        <a
                            href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Pdf?Orden=${posFormat}"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary"
                            title="Descargar Factura PDF"
                        >
                            @include('components.icons.download')
                            <span class="d-none d-md-inline">PDF</span>
                        </a>
                        <a
                            href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Zip?Orden=${posFormat}"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary"
                            title="Descargar Factura Zip"
                        >
                            @include('components.icons.download')
                            <span class="d-none d-md-inline">ZIP</span>
                        </a>
                    `;
                } else {
                    // Si hubo error, restaurar el botón para que pueda intentar de nuevo
                    const originalHTML = `
                        @include('components.icons.send')
                        <span class="d-none d-md-inline">ENVIAR</span>
                    `;

                    button.disabled = false;
                    button.innerHTML = originalHTML;
                }
            }

        });

        function actualizarGrafica(periodo) {
            const tiendaId = $('input[name="tienda_id"]').val();
            const fechaFin = $('input[name="fecha_fin"]').val();

            // console.log('Actualizando grafica');
            // console.log(tiendaId);
            $.ajax({
                url: '{{ route('DashTienda.grafica') }}',
                type: 'GET',
                data: {
                    periodo: periodo,
                    tienda_id: tiendaId,
                    fecha_fin: fechaFin
                },
                success: function(response) {
                    // console.log(response);
                    // console.log(response.labels);
                    // console.log(response.data);

                    ventasChart.data.labels = response.labels;
                    ventasChart.data.datasets[0].data = response.data;
                    ventasChart.update();
                }
            });

        }

        function exportarReporte() {
            const tiendaId = $('input[name="tienda_id"]').val();
            const fechaFin = $('input[name="fecha_fin"]').val();

            window.open(`/exportar-reporte-tienda?tienda_id=${tiendaId}&fecha_fin=${fechaFin}`, '_blank');
        }
    </script>
@endsection
