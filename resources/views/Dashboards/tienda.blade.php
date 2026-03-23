@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Dashboard por Tienda')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <div class="container-fluid width-95 d-flex flex-column gap-4 pt-4">

        <!-- HEADER CON FILTROS Y KPIs -->
        <div class="card border-0 p-4"
            style="border-radius: 10px; background-color: white;">
            <div class="row mb-4 gap-4">
                <div class="col-12 col-lg-auto d-flex align-items-center gap-3">
                    @include('components.title', ['titulo' => 'Dashboard por Tienda'])
                </div>

                <!-- Filtro de Fecha -->
                <x-dashboard-filters :tiendas="$tiendas"
                    :showReporte="true"
                    :showTodasTiendas="true"
                    :tiendaSeleccionada="request()->get('tienda_id')" />
            </div>

            <x-dashboard-notificacion-procesar-tienda :tiendaActual="$tiendaActual" />

            <!-- KPIs PRINCIPALES POR TIENDA -->
            <div class="row g-4 mb-4">

                <!-- Solicitudes Facturas -->
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-subtitle text-muted fw-500 mb-2">Solicitudes Facturas</h6>
                                    <h3 class="card-title mb-0 text-gray-800">
                                        {{ $kpis['solicitudes_factura'] ?? 0 }}
                                    </h3>
                                    <small class="d-block text-muted mt-1">
                                        <span
                                            class="{{ $kpis['facturas_pendientes'] ?? 0 > 0 ? 'text-warning' : 'text-success' }}">
                                            {{ $kpis['facturas_pendientes'] ?? 0 }} pendientes de ligar
                                        </span>
                                    </small>
                                </div>
                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-purple-50"
                                    style="background-color: rgba(124, 58, 237, 0.1); min-width: 44px; height: 44px;">
                                    <div style="color: #7c3aed;"
                                        class="d-flex justify-content-center">
                                        @include('components.icons.file-text')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tickets -->
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="card h-100 border-0 shadow-sm"
                        style="border: 1px solid #e5e7eb; background: white;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-subtitle text-muted fw-500 mb-2">Tickets</h6>
                                    <h3 class="card-title mb-0 text-gray-800">{{ $kpis['tickets'] ?? 0 }}</h3>
                                    <small class="d-block text-muted mt-1">
                                        Ticket promedio: ${{ number_format($kpis['promedio_ticket'] ?? 0, 2) }}
                                    </small>
                                </div>
                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-purple-50"
                                    style="background-color: rgba(3, 84, 63, 0.1); min-width: 44px; height: 44px;">
                                    <div style="color: #03543f;"
                                        class="d-flex justify-content-center">
                                        @include('components.icons.credit-card')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kilos Vendidos -->
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="card h-100 border-0 shadow-sm"
                        style="border: 1px solid #e5e7eb; background: white;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-subtitle text-muted fw-500 mb-2">Kilos Vendidos</h6>
                                    <h3 class="card-title mb-0 text-gray-800">
                                        {{ number_format($kpis['kilos_hoy'] ?? 0, 1) }} kg</h3>
                                    <small class="d-block text-muted mt-1">
                                        {{ $kpis['kilos_promedio'] ? number_format($kpis['kilos_promedio'] / $kpis['tickets'] ?? 0, 2) : 0 }}
                                        kg/transacción
                                    </small>
                                </div>
                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-purple-50"
                                    style="background-color: rgba(114, 59, 19, 0.1); min-width: 44px; height: 44px;">
                                    <div style="color: #723b13;"
                                        class="d-flex justify-content-center">
                                        @include('components.icons.box')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ventas Diarias -->
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="card h-100 border-0 shadow-sm"
                        style="border: 1px solid #e5e7eb; background: white;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-subtitle text-muted fw-500 mb-2">Ventas Hoy</h6>
                                    <h3 class="card-title mb-0 text-gray-800">
                                        ${{ number_format($kpis['ventas_hoy'] ?? 0, 2) }}</h3>
                                    <small class="d-block text-muted mt-1">
                                        {{ $kpis['ventas_vs_ayer'] ?? 0 }}% vs día anterior
                                    </small>
                                </div>
                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-purple-50"
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

            </div>

            @include('Alertas.Alertas')
        </div>

        <!-- GRÁFICAS Y TABLAS POR TIENDA -->
        <div class="row g-4">
            <!-- Corte tienda -->
            <div class="col-xxl-7">
                <div class="card border-0 p-4"
                    style="border-radius: 10px; background-color: white; border: 1px solid #e5e7eb;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 text-gray-800">CORTE TIENDA {{ $tiendaActual->NomTienda ?? 'Tienda' }}
                        </h5>
                        <x-dashboard-buttons-procesar :corteTienda="$corteTienda"
                            :corteTiendaSolicitudes="$corteTiendaSolicitudes" />
                    </div>

                    @if (count($corteTienda) == 0 && count($corteTiendaSolicitudes) == 0)
                        <!-- Estado vacío - Sin datos -->
                        <div class="py-5 text-center">
                            <div class="mb-4">
                                <div class="empty-state-icon mx-auto mb-3">
                                    <!-- Icono -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-100 w-100"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                </div>
                                <h5 class="mb-2 text-gray-500">No hay ventas registradas</h5>
                                <p class="text-muted mb-4">
                                    @if (request()->get('fecha_fin', date('Y-m-d')) == date('Y-m-d'))
                                        Hoy no se han registrado ventas para esta tienda
                                    @else
                                        No hay ventas registradas para el
                                        {{ \Carbon\Carbon::parse(request()->get('fecha_fin', date('Y-m-d')))->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
                                    @endif
                                </p>

                                @if (request()->get('fecha_fin', date('Y-m-d')) != date('Y-m-d'))
                                    <div class="d-flex justify-content-center gap-3">
                                        <a href="?fecha_fin={{ date('Y-m-d') }}&tienda_id={{ request()->get('tienda_id') }}"
                                            class="btn btn-primary">
                                            Ver ventas de hoy
                                        </a>
                                        <a href="?fecha_fin={{ \Carbon\Carbon::parse(request()->get('fecha_fin', date('Y-m-d')))->subDay()->format('Y-m-d') }}&tienda_id={{ request()->get('tienda_id') }}"
                                            class="btn btn-outline-secondary">
                                            @include('components.icons.arrow-left')Día anterior
                                        </a>
                                    </div>
                                @else
                                    <button type="button"
                                        id="refreshBtn"
                                        class="btn btn-primary">
                                        @include('components.icons.refresh')
                                        Actualizar
                                    </button>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Contenido normal con datos -->
                        <div class="table-responsive content-table-sm">
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

                                    @foreach ($corteTienda as $item)
                                        @php
                                            $totalVentas += $item->total_importe;
                                            $totalKilos += $item->total_cantidad;
                                        @endphp
                                        <tr id="row-{{ $item->Source_Transaction_Identifier ?? 'temp-' . $loop->index }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2 rounded p-1"
                                                        style="background-color: rgba(30, 66, 159, 0.1);">
                                                        <div style="color: #1e429f; width: 16px; height: 16px;">
                                                            @include('components.icons.box')
                                                        </div>
                                                    </div>
                                                    <span class="text-truncate puntitos"
                                                        title="{{ $item->Bill_To }}">
                                                        {{ $item->NomClienteCloud }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="fw-500">
                                                <span
                                                    class="{{ $item->Source_Transaction_Identifier ? 'tags-blue' : 'tags-red' }}">
                                                    {{ $item->Source_Transaction_Number ?? 'SIN PEDIDO' }}
                                                </span>
                                            </td>
                                            <td class="fw-500">
                                                <span id="status-{{ $item->Source_Transaction_Identifier }}"
                                                    class="{{ $item->STATUS === 'PROCESADO' ? 'tags-green' : 'tags-red' }}">
                                                    {{ $item->STATUS && $item->STATUS !== 'NULL' ? $item->STATUS : 'SIN PROCESAR' }}
                                                </span>
                                            </td>
                                            <td class="fw-500 text-end">{{ $item->total_cantidad ?? 0 }} kg</td>
                                            <td class="fw-500 text-end">${{ number_format($item->total_importe, 2) }}</td>
                                            <td class="text-center">
                                                @if ($item->STATUS === 'PROCESADO')
                                                    @php
                                                        $POS =
                                                            substr($item->Source_Transaction_Identifier, 0, 3) .
                                                            '_' .
                                                            substr($item->Source_Transaction_Identifier, 3);
                                                    @endphp
                                                    <a href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Pdf?Orden={{ $POS }}"
                                                        target="_blank"
                                                        class="btn btn-sm btn-outline-primary"
                                                        title="Descargar Factura PDF">
                                                        @include('components.icons.download')
                                                        <span class="d-none d-md-inline">PDF</span>
                                                    </a>
                                                    <a href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Xml?Orden={{ $POS }}"
                                                        target="_blank"
                                                        class="btn btn-sm btn-outline-primary"
                                                        title="Descargar Factura XML">
                                                        @include('components.icons.download')
                                                        <span class="d-none d-md-inline">XML</span>
                                                    </a>
                                                @elseif ($item->Source_Transaction_Identifier && $item->STATUS != 'PROCESADO')
                                                    <button type="button"
                                                        id="btnEnviarPedido{{ $item->Source_Transaction_Identifier }}"
                                                        class="btn btn-sm btn-outline-primary btn-enviar"
                                                        title="Enviar pedido a Oracle"
                                                        data-pedido="{{ $item->Source_Transaction_Identifier }}"
                                                        data-row-id="row-{{ $item->Source_Transaction_Identifier }}"
                                                        data-original-status="{{ $item->STATUS }}"
                                                        data-original-mensaje="{{ $item->MENSAJE_ERROR ?? '' }}">
                                                        @include('components.icons.send')
                                                        <span class="d-none d-md-inline">ENVIAR</span>
                                                    </button>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <!-- Fila de mensaje del POS -->
                                        @if (!empty($item->MENSAJE_ERROR))
                                            <tr id="msg-{{ $item->Source_Transaction_Identifier }}"
                                                class="bg-light">
                                                <td colspan="6"
                                                    class="py-1 ps-5">
                                                    <small
                                                        id="mensaje-container-{{ $item->Source_Transaction_Identifier }}"
                                                        class="{{ $item->STATUS === 'ERROR' ? 'text-danger' : 'text-success' }}">
                                                        <strong
                                                            id="mensaje-titulo-{{ $item->Source_Transaction_Identifier }}">
                                                            {{ $item->STATUS === 'ERROR' ? 'Error:' : 'Mensaje:' }}
                                                        </strong>
                                                        <span
                                                            id="mensaje-texto-{{ $item->Source_Transaction_Identifier }}">
                                                            {{ $item->MENSAJE_ERROR }}
                                                        </span>
                                                    </small>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach

                                    @foreach ($corteTiendaSolicitudes as $item)
                                        @php
                                            $totalVentas += $item->total_importe;
                                            $totalKilos += $item->total_cantidad;
                                        @endphp
                                        <tr id="row-{{ $item->Source_Transaction_Identifier ?? 'temp-' . $loop->index }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2 rounded p-1"
                                                        style="background-color: rgba(30, 66, 159, 0.1);">
                                                        <div style="color: #1e429f; width: 16px; height: 16px;">
                                                            @include('components.icons.user')
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <!-- <div class="fw-medium">{{ $item->Bill_To }}</div> -->
                                                        <div class="text-truncate puntitos"
                                                            title="{{ $item->NomCliente }}">
                                                            {{ $item->NomCliente }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-500">
                                                <span
                                                    class="{{ $item->Source_Transaction_Identifier ? 'tags-blue' : 'tags-red' }}">
                                                    @if ($item->Source_Transaction_Identifier)
                                                        {{ $item->Source_Transaction_Number }}
                                                    @elseif ($item->Editar != null)
                                                        SIN LIGAR
                                                    @else
                                                        SIN PEDIDO
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="fw-500">
                                                <span id="status-{{ $item->Source_Transaction_Identifier }}"
                                                    class="{{ $item->STATUS === 'PROCESADO' ? 'tags-green' : 'tags-red' }}">
                                                    {{ $item->STATUS && $item->STATUS !== 'NULL' ? $item->STATUS : 'SIN PROCESAR' }}
                                                </span>
                                            </td>
                                            <td class="fw-500 text-end">{{ $item->total_cantidad ?? 0 }} kg</td>
                                            <td class="fw-500 text-end">${{ number_format($item->total_importe, 2) }}</td>
                                            <td class="text-center">
                                                @if ($item->STATUS === 'PROCESADO')
                                                    @php
                                                        $POS =
                                                            substr($item->Source_Transaction_Identifier, 0, 3) .
                                                            '_' .
                                                            substr($item->Source_Transaction_Identifier, 3);
                                                    @endphp
                                                    <a href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Pdf?Orden={{ $POS }}"
                                                        target="_blank"
                                                        class="btn btn-sm btn-outline-primary"
                                                        title="Descargar Factura PDF">
                                                        @include('components.icons.download')
                                                        <span class="d-none d-md-inline">PDF</span>
                                                    </a>
                                                    <a href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Xml?Orden={{ $POS }}"
                                                        target="_blank"
                                                        class="btn btn-sm btn-outline-primary"
                                                        title="Descargar Factura XML">
                                                        @include('components.icons.download')
                                                        <span class="d-none d-md-inline">XML</span>
                                                    </a>
                                                @elseif ($item->Source_Transaction_Identifier && $item->STATUS != 'PROCESADO')
                                                    <button type="button"
                                                        id="btnEnviarPedido{{ $item->Source_Transaction_Identifier }}"
                                                        class="btn btn-sm btn-outline-primary btn-enviar"
                                                        title="Enviar pedido a Oracle"
                                                        data-pedido="{{ $item->Source_Transaction_Identifier }}"
                                                        data-row-id="row-{{ $item->Source_Transaction_Identifier }}"
                                                        data-original-status="{{ $item->STATUS }}"
                                                        data-original-mensaje="{{ $item->MENSAJE_ERROR ?? '' }}">
                                                        @include('components.icons.send')
                                                        <span class="d-none d-md-inline">ENVIAR</span>
                                                    </button>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <!-- Fila de mensaje del POS -->
                                        @if (!empty($item->MENSAJE_ERROR))
                                            <tr id="msg-{{ $item->Source_Transaction_Identifier }}"
                                                class="bg-light">
                                                <td colspan="6"
                                                    class="py-1 ps-5">
                                                    <small
                                                        id="mensaje-container-{{ $item->Source_Transaction_Identifier }}"
                                                        class="{{ $item->STATUS === 'ERROR' ? 'text-danger' : 'text-success' }}">
                                                        <strong
                                                            id="mensaje-titulo-{{ $item->Source_Transaction_Identifier }}">
                                                            {{ $item->STATUS === 'ERROR' ? 'Error:' : 'Mensaje:' }}
                                                        </strong>
                                                        <span
                                                            id="mensaje-texto-{{ $item->Source_Transaction_Identifier }}">
                                                            {{ $item->MENSAJE_ERROR }}
                                                        </span>
                                                    </small>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach

                                    <!-- Fila de totales -->
                                    @if (count($corteTienda) > 0 || count($corteTiendaSolicitudes) > 0)
                                        <tr class="table-light">
                                            <td colspan="3"
                                                class="fw-bold text-end">TOTALES:</td>
                                            <td class="fw-bold text-end">{{ number_format($totalKilos, 2) }} kg</td>
                                            <td class="fw-bold text-end">${{ number_format($totalVentas, 2) }}</td>
                                            <td></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- GRAFICAS --}}
            <div class="col-xxl-5">
                <div class="row">
                    <!-- Gráfica de Ventas por Tienda -->
                    <div class="col-12 col-xl-6 mb-xl-0 col-xxl-12 mb-xxl-4 mb-4">
                        <div class="card border-0 p-4"
                            style="border-radius: 10px; height: 350px; background-color: white; border: 1px solid #e5e7eb;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0 text-gray-800">VENTAS DIARIAS POR HORA</h5>
                                <div class="btn-group">
                                    <button type="button"
                                        class="btn btn-sm btn-dark-outline periodo-btn {{ request()->get('fecha_fin', date('Y-m-d')) == date('Y-m-d') ? 'active' : '' }} border-gray-300"
                                        data-periodo="hoy">Hoy</button>
                                    <button type="button"
                                        class="btn btn-sm btn-dark-outline periodo-btn border-gray-300"
                                        data-periodo="7d">7 días</button>
                                    <button type="button"
                                        class="btn btn-sm btn-dark-outline periodo-btn border-gray-300"
                                        data-periodo="30d">30 días</button>
                                </div>
                            </div>
                            <div class="position-relative"
                                style="height: 300px;">
                                @if (empty($graficaVentas['data']) || array_sum($graficaVentas['data']) == 0)
                                    <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                        <div class="mb-3 text-gray-400"
                                            style="font-size: 3rem;">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                        <p class="text-muted mb-0">No hay datos de ventas para mostrar</p>
                                    </div>
                                @else
                                    <canvas id="ventasChart"></canvas>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Distribución de Tipos de Pago -->
                    <div class="col-12 col-xl-6 col-xxl-12">
                        <div class="card border-0 p-4"
                            style="border-radius: 10px; height: 350px; background-color: white; border: 1px solid #e5e7eb;">
                            <h5 class="mb-3 text-gray-800">DISTRIBUCIÓN DE PAGOS</h5>
                            <div class="position-relative"
                                style="height: 250px;">
                                @if (empty($graficaDistribucionPagos['data']) || array_sum($graficaDistribucionPagos['data']) == 0)
                                    <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                        <div class="mb-3 text-gray-400"
                                            style="font-size: 3rem;">
                                            <i class="fas fa-chart-pie"></i>
                                        </div>
                                        <p class="text-muted mb-0">No hay datos de pagos para mostrar</p>
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
    </div>
@endsection

@section('styles')
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
@endsection

@section('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    {{-- <script src="{{ asset('js/cdn.jsdelivr.net.js') }}"></script> --}}
    <script src="{{ asset('js/chart.js') }}"></script>
    <script>
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
