<x-page-container title="Dashboard por Tienda">
    <x-card-gradient-header
        icon="shop"
        title="Dashboard por Tienda"
        subtitle="Resumen de ventas y corte por tienda"
    >
        <x-slot:buttons>
            <a
                href="/GenerarCorteOraclePDF/{{ request('fecha_fin') }}/{{ request('tienda_id') }}/0"
                class="btn-green"
                target="_blank"
                title="Descargar corte"
            >
                <i class="bi bi-file-text"></i> Descargar corte
            </a>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form action="{{ route('DashTienda') }}">
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

        {{-- SECCIÓN 2: KPIs --}}
        <div class="p-4">
            <div class="row g-3">
                {{-- KPI 1: Solic. Facturas --}}
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
                                >Solic. Facturas</span>
                                <i
                                    class="bi bi-file-text"
                                    style="color: var(--kpi-icon-purple); font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.5rem;"
                            >{{ $kpis['solicitudes_factura'] ?? 0 }}</h3>
                            <span
                                style="color: {{ ($kpis['facturas_pendientes'] ?? 0) > 0 ? '#ef4444' : '#10b981' }}; font-size: 0.78rem;"
                            >{{ $kpis['facturas_pendientes'] ?? 0 }} pendientes</span>
                        </div>
                    </div>
                </div>

                {{-- KPI 2: Tickets --}}
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
                                >Tickets</span>
                                <i
                                    class="bi bi-receipt"
                                    style="color: var(--kpi-icon-green); font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.5rem;"
                            >{{ $kpis['tickets'] ?? 0 }}</h3>
                            <span style="color: var(--kpi-sub-color); font-size: 0.78rem;">Prom:
                                ${{ number_format($kpis['promedio_ticket'] ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- KPI 3: Kilos --}}
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
                                >Kilos</span>
                                <i
                                    class="bi bi-box"
                                    style="color: var(--kpi-icon-orange); font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: var(--kpi-value-color); font-size: 1.5rem;"
                            >{{ number_format($kpis['kilos_hoy'] ?? 0, 1) }} kg</h3>
                            <span
                                style="color: var(--kpi-sub-color); font-size: 0.78rem;">{{ number_format(($kpis['kilos_promedio'] ?? 0) / max($kpis['tickets'] ?? 1, 1), 2) }}
                                kg/ticket</span>
                        </div>
                    </div>
                </div>

                {{-- KPI 4: Ventas Hoy --}}
                <div class="col-xl-3 col-md-6 col-12">
                    <div
                        class="kpi-card"
                        style="background: var(--kpi-indigo-bg);"
                    >
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: var(--kpi-circle-indigo-bg); border-radius: 50%;">
                        </div>
                        <div style="position: relative; z-index: 1;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span
                                    style="color: var(--kpi-indigo-text); font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                >Ventas Hoy</span>
                                <i
                                    class="bi bi-cash-stack"
                                    style="color: var(--kpi-icon-indigo); font-size: 1.3rem; opacity: 0.7;"
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
                                <span style="color: var(--kpi-sub-color); font-size: 0.75rem;">vs ayer</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notificación de procesar tienda --}}
        <x-dashboard-notificacion-procesar-tienda :tiendaActual="$tiendaActual" />

        {{-- SECCIÓN 3: TABLA Y GRÁFICAS --}}
        <div class="px-4 pb-4">
            <div class="row g-4">
                {{-- TABLA: Corte Tienda --}}
                <div class="col-xxl-8">
                    <div class="card-chart rounded p-4 shadow-sm">
                        @php $allowedUserTypes = [1, 4, 9, 11]; @endphp
                        <div
                            class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                            <div>
                                <h5
                                    class="mb-1"
                                    style="font-weight: 600; color: var(--text-primary); font-size: 1rem;"
                                >CORTE TIENDA {{ $tiendaActual->NomTienda ?? '' }}</h5>
                                @if ($fechaActual)
                                    <small
                                        style="color: var(--text-secondary); font-size: 0.85rem;">{{ \Carbon\Carbon::parse($fechaActual)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</small>
                                @endif
                            </div>
                            <div class="d-flex gap-2">
                                @if (in_array(Auth::user()->IdTipoUsuario, $allowedUserTypes) && $tiendaActual?->procesarcorte == 0)
                                    <x-dashboard-buttons-procesar
                                        :corteTienda="$corteTienda"
                                        :corteTiendaSolicitudes="$corteTiendaSolicitudes"
                                    />
                                @endif
                                <button
                                    class="btn btn-sm d-flex align-items-center btn-animated btn-expand gap-1"
                                    onclick="toggleExpandirTabla()"
                                    id="btnExpandir"
                                >
                                    <i class="bi bi-arrows-fullscreen"></i>
                                    <span id="btnExpandirTexto">Expandir</span>
                                </button>
                            </div>
                        </div>

                        <div
                            id="vistaTabla"
                            class="table-responsive"
                        >
                            <table class="table-hover table-custom table">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-person me-1"></i>Cliente</th>
                                        <th><i class="bi bi-receipt me-1"></i>Pedido</th>
                                        <th><i class="bi bi-circle me-1"></i>Estatus</th>
                                        <th><i class="bi bi-database me-1"></i>Oracle</th>
                                        <th class="text-end"><i class="bi bi-box me-1"></i>Cantidad</th>
                                        <th class="text-end"><i class="bi bi-cash me-1"></i>Ventas</th>
                                        @if (in_array(Auth::user()->IdTipoUsuario, $allowedUserTypes))
                                            <th class="text-center"><i class="bi bi-gear me-1"></i>Acciones</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalVentas = 0;
                                        $totalKilos = 0;
                                    @endphp

                                    {{-- CORTE DE CONTADO --}}
                                    @foreach ($corteTienda as $item)
                                        @php
                                            $totalVentas += $item->total_importe;
                                            $totalKilos += $item->total_cantidad;
                                            $oracleData = $item->OracleData ?? null;
                                            $status = $oracleData->STATUS ?? null;
                                            $mensajeError = $oracleData->MENSAJE_ERROR ?? null;
                                            $transactionOn = $oracleData->Transaction_On ?? null;
                                            $sourceTransactionNumber = $oracleData->Source_Transaction_Number ?? null;
                                            $sourceIdentifier = $item->Source_Transaction_Identifier ?? null;
                                        @endphp
                                        <tr id="row-{{ $sourceIdentifier ?? 'temp-' . $loop->index }}">
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div
                                                        class="rounded-circle d-flex align-items-center justify-content-center icon-circle-blue flex-shrink-0">
                                                        <i
                                                            class="bi bi-box"
                                                            style="color: var(--btn-blue-text); font-size: 0.75rem;"
                                                        ></i>
                                                    </div>
                                                    <span
                                                        class="text-truncate"
                                                        style="max-width: 270px; display: inline-block;"
                                                        title="{{ $item->NomClienteCloud }}"
                                                    >{{ $item->NomClienteCloud }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="{{ $sourceIdentifier ? 'tags-blue' : 'tags-red' }}">
                                                    {{ $sourceTransactionNumber ?? ($sourceIdentifier ? 'SIN NÚMERO' : 'SIN PEDIDO') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    id="status-{{ $sourceIdentifier }}"
                                                    class="{{ $status === 'PROCESADO' ? 'tags-green' : ($status === 'ERROR' ? 'tags-red' : 'tags-yellow') }}"
                                                    style="text-wrap: nowrap"
                                                >
                                                    {{ $status && $status !== 'NULL' ? $status : 'SIN PROCESAR' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    id="status-oracle-{{ $sourceTransactionNumber }}"
                                                    class="{{ $status === 'PROCESADO' ? 'status-oracle' : '' }} text-muted"
                                                    data-pedido="{{ $sourceTransactionNumber }}"
                                                    data-uuid-local="{{ $item->UUID ?? '' }}"
                                                >-</span>
                                            </td>
                                            <td
                                                class="text-end"
                                                style="font-weight: 500;"
                                            >{{ number_format($item->total_cantidad, 3) }} kg</td>
                                            <td
                                                class="text-end"
                                                style="font-weight: 500;"
                                            >${{ number_format($item->total_importe, 2) }}</td>
                                            @if (in_array(Auth::user()->IdTipoUsuario, $allowedUserTypes))
                                                <td class="text-center">
                                                    <div
                                                        class="d-flex align-items-center justify-content-center gap-1">
                                                        {{-- @if ($status === 'PROCESADO' && $sourceIdentifier)
                                                            @php $POS = substr($sourceIdentifier, 0, 3) . '_' . substr($sourceIdentifier, 3); @endphp
                                                            <a
                                                                href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Pdf?Orden={{ $POS }}"
                                                                target="_blank"
                                                                class="btn-blue"
                                                                title="PDF"
                                                            >
                                                                <i class="bi bi-download"></i> PDF
                                                            </a>
                                                            <a
                                                                href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Xml?Orden={{ $POS }}"
                                                                target="_blank"
                                                                class="btn-gray"
                                                                title="XML"
                                                            >
                                                                <i class="bi bi-download"></i> XML
                                                            </a>
                                                        @else --}}
                                                        @if ($sourceIdentifier && $status !== 'PROCESADO')
                                                            <button
                                                                type="button"
                                                                id="btnEnviarPedido{{ $sourceIdentifier }}"
                                                                class="btn-amber btn-enviar"
                                                                title="Enviar pedido a Oracle"
                                                                data-pedido="{{ $sourceIdentifier }}"
                                                                data-row-id="row-{{ $sourceIdentifier }}"
                                                                data-original-status="{{ $status }}"
                                                                data-original-mensaje="{{ $mensajeError ?? '' }}"
                                                            >
                                                                <i class="bi bi-send"></i> ENVIAR
                                                            </button>
                                                        @endif
                                                        @if ($status === 'PROCESADO')
                                                            <div
                                                                class="acciones-oracle buttons-oracle-{{ $sourceTransactionNumber }} d-inline-block"
                                                                data-pedido="{{ $sourceTransactionNumber }}"
                                                                data-uuid-local="{{ $item->UUID ?? '' }}"
                                                            >
                                                                <span class="text-muted">-</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                        @if (!empty($mensajeError))
                                            <tr
                                                id="msg-{{ $sourceIdentifier }}"
                                                class="bg-light"
                                            >
                                                <td
                                                    colspan="7"
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
                                                        <span
                                                            id="mensaje-texto-{{ $sourceIdentifier }}">{{ $mensajeError }}</span>
                                                    </small>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach

                                    {{-- CORTE DE SOLICITUDES DE FACTURA --}}
                                    @foreach ($corteTiendaSolicitudes as $item)
                                        @php
                                            $totalVentas += $item->total_importe;
                                            $totalKilos += $item->total_cantidad;
                                            $oracleData = $item->OracleData ?? null;
                                            $status = $oracleData->STATUS ?? null;
                                            $mensajeError = $oracleData->MENSAJE_ERROR ?? null;
                                            $transactionOn = $oracleData->Transaction_On ?? null;
                                            $sourceTransactionNumber = $oracleData->Source_Transaction_Number ?? null;
                                            $sourceIdentifier = $item->Source_Transaction_Identifier ?? null;
                                        @endphp
                                        <tr id="row-{{ $sourceIdentifier ?? 'temp-' . $loop->index }}">
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div
                                                        class="rounded-circle d-flex align-items-center justify-content-center icon-circle-blue flex-shrink-0">
                                                        <i
                                                            class="bi bi-person"
                                                            style="color: var(--btn-blue-text); font-size: 0.75rem;"
                                                        ></i>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span
                                                            class="text-truncate"
                                                            style="max-width: 300px; display: inline-block;"
                                                            title="{{ $item->NomCliente }}"
                                                        >{{ $item->NomCliente }}</span>
                                                        @if ($item->UUID ?? false)
                                                            <small>Facturación en Línea</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
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
                                            <td>
                                                <span
                                                    id="status-{{ $sourceIdentifier }}"
                                                    class="{{ $status === 'PROCESADO' ? 'tags-green' : ($status === 'ERROR' ? 'tags-red' : 'tags-yellow') }}"
                                                    style="text-wrap: nowrap"
                                                >
                                                    {{ $status && $status !== 'NULL' ? $status : 'SIN PROCESAR' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    id="status-oracle-{{ $sourceTransactionNumber }}"
                                                    class="{{ $status === 'PROCESADO' ? 'status-oracle' : '' }} text-muted"
                                                    data-pedido="{{ $sourceTransactionNumber }}"
                                                    data-uuid-local="{{ $item->UUID ?? '' }}"
                                                    data-type="sf"
                                                >-</span>
                                            </td>
                                            <td
                                                class="text-end"
                                                style="font-weight: 500;"
                                            >{{ number_format($item->total_cantidad, 3) }} kg</td>
                                            <td
                                                class="text-end"
                                                style="font-weight: 500;"
                                            >${{ number_format($item->total_importe, 2) }}</td>
                                            @if (in_array(Auth::user()->IdTipoUsuario, $allowedUserTypes))
                                                <td class="text-center">
                                                    <div
                                                        class="d-flex align-items-center justify-content-center gap-1">
                                                        @if ($status === 'PROCESADO' && !($item->UUID ?? false))
                                                            @php $POS = substr($sourceIdentifier, 0, 3) . '_' . substr($sourceIdentifier, 3); @endphp
                                                            <a
                                                                href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Pdf?Orden={{ $POS }}"
                                                                target="_blank"
                                                                class="btn-blue"
                                                            >PDF</a>
                                                            <a
                                                                href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Xml?Orden={{ $POS }}"
                                                                target="_blank"
                                                                class="btn-gray"
                                                            >XML</a>
                                                        @endif
                                                        @if ($item->UUID ?? false)
                                                            <a
                                                                href="https://timbradokowirest.kowi.com.mx/api/Timbrar/DownloadPdfCte?Uuid={{ $item->UUID }}"
                                                                target="_blank"
                                                                class="btn-blue"
                                                            > <i class="bi bi-download"></i> PDF</a>
                                                            <a
                                                                href="https://timbradokowirest.kowi.com.mx/api/Timbrar/DownloadXmlCte?Uuid={{ $item->UUID }}"
                                                                target="_blank"
                                                                class="btn-gray"
                                                            ><i class="bi bi-download"></i> XML</a>
                                                        @endif
                                                        @if ($sourceIdentifier && $status != 'PROCESADO')
                                                            <button
                                                                type="button"
                                                                class="btn-amber btn-enviar"
                                                                data-pedido="{{ $sourceIdentifier }}"
                                                                data-row-id="row-{{ $sourceIdentifier }}"
                                                                data-original-status="{{ $status }}"
                                                                data-original-mensaje="{{ $mensajeError ?? '' }}"
                                                            >
                                                                <i class="bi bi-send"></i> ENVIAR
                                                            </button>
                                                        @endif
                                                        <a
                                                            href="/SolicitudesFactura/{{ $item->IdSolicitudFactura }}"
                                                            target="_blank"
                                                            class="btn-gray"
                                                        >
                                                            <i class="bi bi-box-arrow-up-right"></i>
                                                        </a>
                                                        @if ($status === 'PROCESADO')
                                                            <div
                                                                class="acciones-oracle buttons-oracle-{{ $sourceTransactionNumber }} d-inline-block"
                                                                data-pedido="{{ $sourceTransactionNumber }}"
                                                                data-uuid-local="{{ $item->UUID ?? '' }}"
                                                            ></div>
                                                        @endif
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                        @if (!empty($mensajeError) || ($item->UUID ?? false))
                                            <tr
                                                id="msg-{{ $sourceIdentifier }}"
                                                class="bg-light"
                                            >
                                                <td
                                                    colspan="7"
                                                    class="py-1 ps-5"
                                                >
                                                    <small
                                                        id="mensaje-container-{{ $sourceIdentifier }}"
                                                        class="{{ $status === 'ERROR' ? 'text-danger' : 'text-success' }}"
                                                    >
                                                        @if ($mensajeError)
                                                            <strong id="mensaje-titulo-{{ $sourceIdentifier }}">
                                                                @if ($transactionOn)
                                                                    {{ $transactionOn }} -
                                                                @endif
                                                                {{ $status === 'ERROR' ? 'Error:' : 'Mensaje:' }}
                                                            </strong>
                                                            <span
                                                                id="mensaje-texto-{{ $sourceIdentifier }}">{{ $mensajeError }}</span><br>
                                                        @endif
                                                        @if ($item->UUID ?? false)
                                                            <strong>UUID:</strong> <span>{{ $item->UUID }}</span>
                                                        @endif
                                                    </small>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach

                                    {{-- Totales --}}
                                    @if (count($corteTienda) > 0 || count($corteTiendaSolicitudes) > 0)
                                        <tr class="bg-table-totals">
                                            <td
                                                colspan="{{ in_array(Auth::user()->IdTipoUsuario, $allowedUserTypes) ? 4 : 3 }}"
                                                class="fw-bold text-end"
                                                style="color: var(--text-primary);"
                                            >TOTALES:</td>
                                            <td
                                                class="fw-bold text-end"
                                                style="color: var(--text-primary);"
                                            >{{ number_format($totalKilos, 2) }} kg</td>
                                            <td
                                                class="fw-bold text-end"
                                                style="color: var(--text-primary);"
                                            >${{ number_format($totalVentas, 2) }}</td>
                                            @if (in_array(Auth::user()->IdTipoUsuario, $allowedUserTypes))
                                                <td></td>
                                            @endif
                                        </tr>
                                    @endif

                                    @if (count($corteTienda) == 0 && count($corteTiendaSolicitudes) == 0)
                                        <tr>
                                            <td
                                                colspan="{{ in_array(Auth::user()->IdTipoUsuario, $allowedUserTypes) ? 7 : 6 }}"
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
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- GRÁFICAS --}}
                <div class="col-xxl-4">
                    <div class="row g-4">
                        {{-- Ventas por Hora --}}
                        <div class="col-12">
                            <div class="card-chart rounded p-4 shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5
                                        class="mb-0"
                                        style="font-weight: 600; color: var(--text-primary); font-size: 1rem;"
                                    >Ventas por Hora</h5>
                                    @if (!empty($graficaVentas['data']) && array_sum($graficaVentas['data']) != 0)
                                        <div class="btn-group">
                                            <button
                                                type="button"
                                                class="periodo-btn active"
                                                data-periodo="hoy"
                                            >Hoy</button>
                                            <button
                                                type="button"
                                                class="periodo-btn"
                                                data-periodo="7d"
                                            >7d</button>
                                            <button
                                                type="button"
                                                class="periodo-btn"
                                                data-periodo="30d"
                                            >30d</button>
                                        </div>
                                    @endif
                                </div>
                                <div
                                    class="position-relative"
                                    style="height: 200px;"
                                >
                                    @if (empty($graficaVentas['data']) || array_sum($graficaVentas['data']) == 0)
                                        <div class="d-flex justify-content-center align-items-center h-100">
                                            <div class="text-center">
                                                <i
                                                    class="bi bi-bar-chart"
                                                    style="font-size: 2.5rem; color: var(--text-muted);"
                                                ></i>
                                                <p
                                                    class="mt-2"
                                                    style="color: var(--text-secondary);"
                                                >Sin datos</p>
                                            </div>
                                        </div>
                                    @else
                                        <canvas id="ventasChart"></canvas>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Distribución de Pagos --}}
                        <div class="col-12">
                            <div class="card-chart rounded p-4 shadow-sm">
                                <h5
                                    class="mb-3"
                                    style="font-weight: 600; color: var(--text-primary); font-size: 1rem;"
                                >Distribución de Pagos</h5>
                                <div
                                    class="position-relative"
                                    style="height: 160px;"
                                >
                                    @if (empty($graficaDistribucionPagos['data']) || array_sum($graficaDistribucionPagos['data']) == 0)
                                        <div class="d-flex justify-content-center align-items-center h-100">
                                            <div class="text-center">
                                                <i
                                                    class="bi bi-pie-chart"
                                                    style="font-size: 2.5rem; color: var(--text-muted);"
                                                ></i>
                                                <p
                                                    class="mt-2"
                                                    style="color: var(--text-secondary);"
                                                >Sin datos</p>
                                            </div>
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
    </x-card-gradient-header>

    <script>
        // ====================================================================================================
        // FUNCIONALIDAD COMPLETA DE ORACLE (STATUS, BOTONES, ENVÍO DE PEDIDOS)
        // ====================================================================================================
        function fetchStatusOracle(item, btnReload = null) {
            const pedido = item.dataset.pedido;
            const uuidLocal = item.dataset.uuidLocal;
            const type = item.dataset.type;
            const apiUrl = `https://oracleordenrest.kowi.com.mx/api/SalesOrder/GetSalesOracle?OrdenVta=${pedido}`;

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.ok && data.dato?.lines) {
                        const estatusLineas = data.dato.lines.map(line => line.status);
                        const estatusUnicos = [...new Set(estatusLineas)];
                        if (estatusUnicos.length >= 1) item.innerHTML =
                            `<span class="tags-green">${estatusUnicos.join(', ')}</span>`;
                        if (estatusUnicos.length == 1) {
                            item.innerHTML =
                                `<span class="tags-green" style="text-wrap:nowrap">${estatusUnicos[0]}</span>`;
                            let estatusPedido = estatusUnicos[0];
                            const contenedor = document.querySelector(`.buttons-oracle-${pedido}`);
                            if (estatusPedido == 'Awaiting Shipping') {
                                if (contenedor) {
                                    contenedor.innerHTML = '';
                                    botonDespachoInventario(contenedor, pedido, uuidLocal, type);
                                }
                            }
                            if (estatusPedido == 'Awaiting Billing') {
                                if (contenedor) {
                                    contenedor.innerHTML = '';
                                    botonGenerarFactura(contenedor, pedido, uuidLocal, type);
                                }
                            }
                            if (estatusPedido == 'Closed') {
                                fetchBuscarUUID(pedido, uuidLocal, item, type);
                            }
                        }
                    } else {
                        item.innerHTML = '<span class="tags-red">Sin datos</span>';
                    }
                    if (btnReload) {
                        btnReload.innerHTML = `<span>@include('components.icons.refresh')</span>`;
                        btnReload.disabled = false;
                    }
                });
        }

        function fetchBuscarUUID(pedido, uuidLocal, item, type) {
            const apiUrl = `https://oraclefacturasrest.kowi.com.mx/api/Documentos/FacturaOracle?Orden=${pedido}`;
            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        const uuidOracle = data.dato?.uUid;
                        const uuidApex = data.dato?.uUidApex;
                        const contenedor = document.querySelector(`.buttons-oracle-${pedido}`);
                        contenedor.innerHTML = ''; //Limpiamos el contenedor de botones

                        // Verificando que este timbrada la factura de contado yponemos los botones para descargar los archivos
                        if (!type && uuidApex) {
                            var innerSpan = item.querySelector('span');
                            if (innerSpan) {
                                innerSpan.textContent = innerSpan.textContent + ' & Timbrado';
                            }
                            if (contenedor) {
                                contenedor.innerHTML = `
                                    <a
                                        href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Pdf?Orden=${pedido}"
                                        target="_blank"
                                        class="btn-blue"
                                        title="PDF"
                                    >
                                        <i class="bi bi-download"></i> PDF
                                    </a>
                                    <a
                                        href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Xml?Orden=${pedido}"
                                        target="_blank"
                                        class="btn-gray"
                                        title="XML"
                                    >
                                        <i class="bi bi-download"></i> XML
                                    </a>
                                `;
                            }
                        }

                        // Verificaciones para las que son con solicitudes de factura
                        if (uuidLocal != uuidOracle || uuidLocal != uuidApex) {
                            if (type == 'sf')
                                botonEnviarUuid(contenedor, pedido, uuidLocal, item);
                        } else {
                            if (type == 'sf') {
                                item.innerHTML = `<span class="tags-green">Closed & UUID</span>`;
                            }
                        }

                        if (uuidOracle && uuidLocal != uuidOracle && type == 'sf') {
                            item.innerHTML = `<span class="tags-red">Closed & !UUID Oracle </span>`;
                        }
                        if (uuidApex && uuidLocal != uuidApex && type == 'sf') {
                            let mensajeError = item?.parentNode?.parentNode?.nextElementSibling?.querySelector('small');
                            mensajeError.innerHTML += `
                                <br>
                                <strong class='text-danger'>UUID Apex:</strong>
                                <span class='text-danger'>${uuidApex}</span>
                            `;
                            item.innerHTML = `<span class="tags-red">Closed & !UUID Apex </span>`;
                        }
                    }
                });
        }

        function actualizarFilasOracle() {
            document.querySelectorAll('.status-oracle').forEach(item => fetchStatusOracle(item));
        }

        function getStatusLoop(pedido, uuidLocal, estatusSiguiente, type) {
            const item = document.getElementById(`status-oracle-${pedido}`);
            const apiUrl = `https://oracleordenrest.kowi.com.mx/api/SalesOrder/GetSalesOracle?OrdenVta=${pedido}`;
            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.ok && data.dato?.lines) {
                        const estatusLineas = data.dato.lines.map(line => line.status);
                        const estatusUnicos = [...new Set(estatusLineas)];
                        if (estatusUnicos.length == 1) {
                            let estatusPedido = estatusUnicos[0];
                            item.innerHTML = `<span class="tags-green">${estatusPedido}</span>`;
                            const contenedor = document.querySelector(`.buttons-oracle-${pedido}`);
                            if (estatusPedido == 'Awaiting Billing' && estatusSiguiente == 'Awaiting Billing') {
                                if (contenedor) {
                                    contenedor.innerHTML = '';
                                    botonGenerarFactura(contenedor, pedido, uuidLocal, type);
                                }
                            } else if (estatusPedido == 'Closed' && estatusSiguiente == 'Closed') {
                                fetchBuscarUUID(pedido, uuidLocal, item, type);
                            } else {
                                setTimeout(() => getStatusLoop(pedido, uuidLocal, estatusSiguiente, type), 3000);
                            }
                        } else {
                            setTimeout(() => getStatusLoop(pedido, uuidLocal, estatusSiguiente, type), 3000);
                        }
                    } else {
                        setTimeout(() => getStatusLoop(pedido, uuidLocal, estatusSiguiente, type), 3000);
                    }
                })
                .catch(() => setTimeout(() => getStatusLoop(pedido, uuidLocal, estatusSiguiente, type), 3000));
        }

        function botonDespachoInventario(contenedor, pedido, uuidLocal) {
            const btn = document.createElement('button');
            btn.className = 'btn-amber';
            btn.style.whiteSpace = 'nowrap';
            btn.innerHTML = '<i class="bi bi-send"></i> DESPACHO';
            btn.addEventListener('click', () => {
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Despachando...';
                btn.disabled = true;
                fetch(`https://oracledespachorest.kowi.com.mx/api/PickWave/Despacho?Orden=${pedido}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.ok) getStatusLoop(pedido, uuidLocal, 'Awaiting Billing');
                        else btn.innerHTML = '❌ Error';
                    }).catch(() => btn.innerHTML = '❌ Error');
            });
            contenedor.appendChild(btn);
        }

        function botonGenerarFactura(contenedor, pedido, uuidLocal, type) {
            const btn = document.createElement('button');
            btn.className = 'btn-blue';
            btn.style.whiteSpace = 'nowrap';
            btn.innerHTML = '<i class="bi bi-send"></i> GENERAR FACTURA';
            btn.addEventListener('click', () => {
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Generando...';
                btn.disabled = true;
                fetch(`https://oraclefacturasrest.kowi.com.mx/api/Documentos/Factura?Orden=${pedido}`, {
                        method: 'POST'
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.ok) getStatusLoop(pedido, uuidLocal, 'Closed', type);
                        else btn.innerHTML = '❌ Error';
                    }).catch(() => btn.innerHTML = '❌ Error');
            });
            contenedor.appendChild(btn);
        }

        function botonEnviarUuid(contenedor, pedido, uuidLocal, item) {
            const btn = document.createElement('button');
            btn.className = 'btn-green';
            btn.style.whiteSpace = 'nowrap';
            btn.innerHTML = '<i class="bi bi-send"></i> ENVIAR UUID';
            btn.addEventListener('click', () => {
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Enviando...';
                btn.disabled = true;
                fetch(`https://oraclefacturasrest.kowi.com.mx/api/Documentos/UUID?Orden=${pedido}&UUID=${uuidLocal}`, {
                        method: 'POST'
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.ok) fetchBuscarUUID(pedido, uuidLocal, item, 'sf');
                        else {
                            btn.innerHTML = '❌ Error';
                            btn.disabled = false;
                        }
                    }).catch(() => {
                        btn.innerHTML = '❌ Error';
                        btn.disabled = false;
                    });
            });
            contenedor.appendChild(btn);
        }

        document.addEventListener('DOMContentLoaded', () => actualizarFilasOracle());

        // ====================================================================================================
        // ENVÍO DE PEDIDOS (BTN-ENVIAR)
        // ====================================================================================================
        document.querySelectorAll('.btn-enviar').forEach(button => {
            button.addEventListener('click', async function() {
                const pedidoId = this.getAttribute('data-pedido');
                const rowId = this.getAttribute('data-row-id');
                const btn = this;
                btn.disabled = true;
                const origHTML = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Enviando...';
                try {
                    const response = await fetch(`/DashTienda/enviar-pedido/${pedidoId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    });
                    const result = await response.json();
                    actualizarFilaConRespuesta(pedidoId, result, btn);
                } catch (error) {
                    actualizarFilaConRespuesta(pedidoId, {
                        ok: false,
                        status: 'Error',
                        message: error.message
                    }, btn);
                    btn.disabled = false;
                    btn.innerHTML = origHTML;
                }
            });
        });

        function actualizarFilaConRespuesta(pedidoId, resultado, button) {
            let nuevoEstado = resultado.ok ? 'PROCESADO' : 'ERROR';
            let mensaje = resultado.message || (resultado.ok ? 'Procesado correctamente' : 'Error desconocido');
            let esExito = resultado.ok;
            const spanEstado = document.getElementById(`status-${pedidoId}`);
            if (spanEstado) {
                spanEstado.className = nuevoEstado === 'PROCESADO' ? 'tags-green' : 'tags-red';
                spanEstado.textContent = nuevoEstado;
            }
            const trMensajeId = `msg-${pedidoId}`;
            const trMensajeExistente = document.getElementById(trMensajeId);
            const filaPrincipal = document.getElementById(`row-${pedidoId}`);
            if (trMensajeExistente) {
                const small = trMensajeExistente.querySelector('small');
                const spanMsg = trMensajeExistente.querySelector('#mensaje-texto-' + pedidoId);
                if (small) {
                    small.className = nuevoEstado === 'ERROR' ? 'text-danger' : 'text-success';
                    small.querySelector('strong').textContent = nuevoEstado === 'ERROR' ? 'Error:' : 'Mensaje:';
                }
                if (spanMsg) spanMsg.textContent = mensaje;
            } else if (mensaje) {
                const nuevaFila = document.createElement('tr');
                nuevaFila.id = trMensajeId;
                nuevaFila.className = 'bg-light';
                nuevaFila.innerHTML =
                    `<td colspan="7" class="py-1 ps-5"><small class="${nuevoEstado === 'ERROR' ? 'text-danger' : 'text-success'}"><strong>${nuevoEstado === 'ERROR' ? 'Error:' : 'Mensaje:'}</strong><span id="mensaje-texto-${pedidoId}">${mensaje}</span></small></td>`;
                if (filaPrincipal?.parentNode) filaPrincipal.parentNode.insertBefore(nuevaFila, filaPrincipal.nextSibling);
            }
            if (esExito && resultado.dato?.sourceTransactionNumber) {
                button.parentNode.innerHTML =
                    `<a href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Pdf?Orden=${resultado.dato.sourceTransactionNumber}" target="_blank" class="btn-blue">PDF</a>
                     <a href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Zip?Orden=${resultado.dato.sourceTransactionNumber}" target="_blank" class="btn-gray">ZIP</a>`;
            } else {
                button.disabled = false;
                button.innerHTML = '<i class="bi bi-send"></i> ENVIAR';
            }
        }

        // ====================================================================================================
        // EXPANDIR/CONTRACTAR TABLA (FULL SCREEN)
        // ====================================================================================================
        let tablaExpandida = false;
        let tablaOriginalParent = null;
        let tablaOriginalNextSibling = null;
        let tablaOriginalStyles = {};

        function toggleExpandirTabla() {
            const contenedorCard = document.getElementById('vistaTabla').closest('.rounded');
            const btnTexto = document.getElementById('btnExpandirTexto');
            const btnExpandir = document.getElementById('btnExpandir');
            const icono = btnExpandir.querySelector('i');

            if (!tablaExpandida) {
                tablaOriginalParent = contenedorCard.parentNode;
                tablaOriginalNextSibling = contenedorCard.nextSibling;
                tablaOriginalStyles = {
                    position: contenedorCard.style.position,
                    top: contenedorCard.style.top,
                    left: contenedorCard.style.left,
                    width: contenedorCard.style.width,
                    height: contenedorCard.style.height,
                    zIndex: contenedorCard.style.zIndex,
                    margin: contenedorCard.style.margin,
                    borderRadius: contenedorCard.style.borderRadius,
                    maxWidth: contenedorCard.style.maxWidth,
                    overflow: contenedorCard.style.overflow,
                    background: contenedorCard.style.background,
                    padding: contenedorCard.style.padding,
                    transition: contenedorCard.style.transition
                };

                contenedorCard.style.position = 'fixed';
                contenedorCard.style.top = '52px';
                contenedorCard.style.left = '0';
                contenedorCard.style.width = '100vw';
                contenedorCard.style.height = 'calc(100vh - 52px)';
                contenedorCard.style.zIndex = '1050';
                contenedorCard.style.margin = '0';
                contenedorCard.style.borderRadius = '0';
                contenedorCard.style.maxWidth = '100vw';
                contenedorCard.style.overflow = 'auto';
                contenedorCard.style.background = 'var(--card-bg)';
                contenedorCard.style.padding = '24px';
                contenedorCard.style.transition = 'all 0.3s ease';

                document.body.appendChild(contenedorCard);

                btnTexto.textContent = 'Contraer';
                btnExpandir.classList.add('expanded');
                icono.className = 'bi bi-arrows-collapse';

                tablaExpandida = true;
                document.addEventListener('keydown', cerrarConEsc);
            } else {
                contenedorCard.style.position = tablaOriginalStyles.position || '';
                contenedorCard.style.top = tablaOriginalStyles.top || '';
                contenedorCard.style.left = tablaOriginalStyles.left || '';
                contenedorCard.style.width = tablaOriginalStyles.width || '';
                contenedorCard.style.height = tablaOriginalStyles.height || '';
                contenedorCard.style.zIndex = tablaOriginalStyles.zIndex || '';
                contenedorCard.style.margin = tablaOriginalStyles.margin || '';
                contenedorCard.style.borderRadius = tablaOriginalStyles.borderRadius || '';
                contenedorCard.style.maxWidth = tablaOriginalStyles.maxWidth || '';
                contenedorCard.style.overflow = tablaOriginalStyles.overflow || '';
                contenedorCard.style.background = tablaOriginalStyles.background || '';
                contenedorCard.style.padding = tablaOriginalStyles.padding || '';

                if (tablaOriginalParent && tablaOriginalNextSibling) {
                    tablaOriginalParent.insertBefore(contenedorCard, tablaOriginalNextSibling);
                } else if (tablaOriginalParent) {
                    tablaOriginalParent.appendChild(contenedorCard);
                }

                btnTexto.textContent = 'Expandir';
                btnExpandir.classList.remove('expanded');
                icono.className = 'bi bi-arrows-fullscreen';

                tablaExpandida = false;
                document.removeEventListener('keydown', cerrarConEsc);
            }
        }

        function cerrarConEsc(event) {
            if (event.key === 'Escape' && tablaExpandida) {
                toggleExpandirTabla();
            }
        }

        let ventasChart = null,
            pagosChart = null;
        $(document).ready(function() {
            const ctxV = document.getElementById('ventasChart')?.getContext('2d');
            const ctxP = document.getElementById('pagosChart')?.getContext('2d');

            var gradientStart = getComputedStyle(document.documentElement).getPropertyValue('--gradient-start')
                .trim() || '#1e293b';
            var chartFill = getComputedStyle(document.documentElement).getPropertyValue('--chart-fill').trim() ||
                'rgba(30,41,59,0.1)';

            if (ctxV) {
                ventasChart = new Chart(ctxV, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($graficaVentas['labels'] ?? []) !!},
                        datasets: [{
                            label: 'Ventas',
                            data: {!! json_encode($graficaVentas['data'] ?? []) !!},
                            borderColor: gradientStart,
                            backgroundColor: chartFill,
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
                                    callback: v => '$' + v.toLocaleString('es-MX')
                                }
                            }
                        }
                    }
                });
            }
            if (ctxP) {
                pagosChart = new Chart(ctxP, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($graficaDistribucionPagos['labels'] ?? []) !!},
                        datasets: [{
                            data: {!! json_encode($graficaDistribucionPagos['data'] ?? []) !!},
                            backgroundColor: [
                                '#64748b', '#84a98c', '#7b9acc', '#9d8ac7', '#6fa8a3',
                                '#8b93d6', '#8fc4d7', '#c9a0b8', '#a3b8cc', '#a8c3a0'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    font: {
                                        size: 10
                                    }
                                }
                            }
                        }
                    }
                });
            }
            $('.periodo-btn').click(function() {
                $('.periodo-btn').removeClass('active');
                $(this).addClass('active');
                $.ajax({
                    url: '{{ route('DashTienda.grafica') }}',
                    data: {
                        periodo: $(this).data('periodo'),
                        tienda_id: $('select[name="tienda_id"]').val(),
                        fecha_fin: $('input[name="fecha_fin"]').val()
                    },
                    success: function(r) {
                        if (ventasChart) {
                            ventasChart.data.labels = r.labels;
                            ventasChart.data.datasets[0].data = r.data;
                            ventasChart.update();
                        }
                    }
                });
            });
        });
    </script>
</x-page-container>
