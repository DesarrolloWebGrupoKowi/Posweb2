@extends('PlantillaBase.masterbladeDashboard')
@section('title', 'Dashboard por Tienda')
@section('dashboardWidth', 'width-95')

@section('contenido')
    <x-page-container>
        <x-card-gradient-header
            icon="shop"
            title="Dashboard por Tienda"
            subtitle="Resumen de ventas y corte por tienda"
        >
            <x-slot:buttons>
                <a
                    href="/GenerarCorteOraclePDF/{{ request('fecha_fin') }}/{{ request('tienda_id') }}/0"
                    class="btn-header-ghost"
                    target="_blank"
                    title="Descargar corte"
                    style="background: #f0fdf4; color: #10b981;"
                    onmouseover="this.style.background='#dcfce7'; this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.background='#f0fdf4'; this.style.transform='translateY(0)'"
                >
                    <i class="bi bi-file-text"></i> Descargar corte
                </a>
                <x-header.buttons.refresh-button />
                <x-header.buttons.home-button />
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
                        text="Filtrar"
                        icon="funnel"
                        class="flex-grow-1"
                    />
                    <x-form.clear />
                </div>
            </x-form.form>

            {{-- SECCIÓN 2: KPIs --}}
            <div class="p-4">
                <div class="row g-3">
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
                                    >Solic. Facturas</span>
                                    <i
                                        class="bi bi-file-text"
                                        style="color: #8b5cf6; font-size: 1.3rem; opacity: 0.7;"
                                    ></i>
                                </div>
                                <h3
                                    class="mb-1"
                                    style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                                >{{ $kpis['solicitudes_factura'] ?? 0 }}</h3>
                                <span
                                    style="color: {{ ($kpis['facturas_pendientes'] ?? 0) > 0 ? '#ef4444' : '#10b981' }}; font-size: 0.78rem;"
                                >{{ $kpis['facturas_pendientes'] ?? 0 }} pendientes</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12">
                        <div
                            class="kpi-card"
                            style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-radius: 12px; padding: 20px; position: relative; overflow: hidden;"
                        >
                            <div
                                style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(20, 184, 166, 0.08); border-radius: 50%;">
                            </div>
                            <div style="position: relative; z-index: 1;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span
                                        style="color: #0d9488; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                    >Tickets</span>
                                    <i
                                        class="bi bi-receipt"
                                        style="color: #14b8a6; font-size: 1.3rem; opacity: 0.7;"
                                    ></i>
                                </div>
                                <h3
                                    class="mb-1"
                                    style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                                >{{ $kpis['tickets'] ?? 0 }}</h3>
                                <span style="color: #94a3b8; font-size: 0.78rem;">Prom:
                                    ${{ number_format($kpis['promedio_ticket'] ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12">
                        <div
                            class="kpi-card"
                            style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border-radius: 12px; padding: 20px; position: relative; overflow: hidden;"
                        >
                            <div
                                style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(249, 115, 22, 0.08); border-radius: 50%;">
                            </div>
                            <div style="position: relative; z-index: 1;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span
                                        style="color: #ea580c; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                    >Kilos</span>
                                    <i
                                        class="bi bi-box"
                                        style="color: #f97316; font-size: 1.3rem; opacity: 0.7;"
                                    ></i>
                                </div>
                                <h3
                                    class="mb-1"
                                    style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                                >{{ number_format($kpis['kilos_hoy'] ?? 0, 1) }} kg</h3>
                                <span
                                    style="color: #94a3b8; font-size: 0.78rem;">{{ number_format(($kpis['kilos_promedio'] ?? 0) / max($kpis['tickets'] ?? 1, 1), 2) }}
                                    kg/ticket</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12">
                        <div
                            class="kpi-card"
                            style="background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); border-radius: 12px; padding: 20px; position: relative; overflow: hidden;"
                        >
                            <div
                                style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(99, 102, 241, 0.08); border-radius: 50%;">
                            </div>
                            <div style="position: relative; z-index: 1;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span
                                        style="color: #4f46e5; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                    >Ventas Hoy</span>
                                    <i
                                        class="bi bi-cash-stack"
                                        style="color: #6366f1; font-size: 1.3rem; opacity: 0.7;"
                                    ></i>
                                </div>
                                <h3
                                    class="mb-1"
                                    style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                                >${{ number_format($kpis['ventas_hoy'] ?? 0, 2) }}</h3>
                                <div class="d-flex align-items-center gap-1">
                                    <span
                                        style="color: {{ ($kpis['ventas_vs_ayer'] ?? 0) >= 0 ? '#10b981' : '#ef4444' }}; font-size: 0.78rem; font-weight: 600;"
                                    >
                                        <i
                                            class="bi bi-arrow-{{ ($kpis['ventas_vs_ayer'] ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                        {{ abs($kpis['ventas_vs_ayer'] ?? 0) }}%
                                    </span>
                                    <span style="color: #94a3b8; font-size: 0.75rem;">vs ayer</span>
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
                    {{-- TABLA: Corte Tienda (FUNCIONALIDAD COMPLETA) --}}
                    <div class="col-xxl-8">
                        <div
                            class="rounded p-4 shadow-sm"
                            style="background: white; border-radius: 12px;"
                        >
                            @php $allowedUserTypes = [1, 4, 9, 11]; @endphp
                            <div
                                class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                                <div>
                                    <h5
                                        class="mb-1"
                                        style="font-weight: 600; color: #0f172a; font-size: 1rem;"
                                    >CORTE TIENDA {{ $tiendaActual->NomTienda ?? '' }}</h5>
                                    @if ($fechaActual)
                                        <small
                                            style="color: #64748b; font-size: 0.85rem;">{{ \Carbon\Carbon::parse($fechaActual)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</small>
                                    @endif
                                </div>
                                <div class="d-flex gap-2">
                                    @if (in_array(Auth::user()->IdTipoUsuario, $allowedUserTypes))
                                        <x-dashboard-buttons-procesar
                                            :corteTienda="$corteTienda"
                                            :corteTiendaSolicitudes="$corteTiendaSolicitudes"
                                        />
                                    @endif
                                    <button
                                        class="btn btn-sm d-flex align-items-center btn-animated gap-1"
                                        onclick="toggleExpandirTabla()"
                                        id="btnExpandir"
                                        style="background: #f1f5f9; color: #475569; border: none; border-radius: 8px; padding: 8px 12px; font-size: 0.8rem;"
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
                                                $sourceTransactionNumber =
                                                    $oracleData->Source_Transaction_Number ?? null;
                                                $sourceIdentifier = $item->Source_Transaction_Identifier ?? null;
                                            @endphp
                                            <tr id="row-{{ $sourceIdentifier ?? 'temp-' . $loop->index }}">
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div
                                                            class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                                            style="background: #eff6ff; width: 28px; height: 28px;"
                                                        >
                                                            <i
                                                                class="bi bi-box"
                                                                style="color: #3b82f6; font-size: 0.75rem;"
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
                                                            @if ($status === 'PROCESADO' && $sourceIdentifier)
                                                                @php $POS = substr($sourceIdentifier, 0, 3) . '_' . substr($sourceIdentifier, 3); @endphp
                                                                <a
                                                                    href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Pdf?Orden={{ $POS }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm d-flex align-items-center btn-animated gap-1"
                                                                    style="background: #eff6ff; color: #3b82f6; border: none; border-radius: 6px; padding: 4px 8px; font-size: 0.75rem;"
                                                                    title="PDF"
                                                                >
                                                                    <i class="bi bi-download"></i> PDF
                                                                </a>
                                                                <a
                                                                    href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Xml?Orden={{ $POS }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm d-flex align-items-center btn-animated gap-1"
                                                                    style="background: #f1f5f9; color: #475569; border: none; border-radius: 6px; padding: 4px 8px; font-size: 0.75rem;"
                                                                    title="XML"
                                                                >
                                                                    <i class="bi bi-download"></i> XML
                                                                </a>
                                                            @elseif ($sourceIdentifier && $status !== 'PROCESADO')
                                                                <button
                                                                    type="button"
                                                                    id="btnEnviarPedido{{ $sourceIdentifier }}"
                                                                    class="btn btn-sm btn-enviar d-flex align-items-center btn-animated gap-1"
                                                                    style="background: #fffbeb; color: #f59e0b; border: none; border-radius: 6px; padding: 4px 8px; font-size: 0.75rem;"
                                                                    title="Enviar pedido a Oracle"
                                                                    data-pedido="{{ $sourceIdentifier }}"
                                                                    data-row-id="row-{{ $sourceIdentifier }}"
                                                                    data-original-status="{{ $status }}"
                                                                    data-original-mensaje="{{ $mensajeError ?? '' }}"
                                                                >
                                                                    <i class="bi bi-send"></i> ENVIAR
                                                                </button>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
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
                                                $sourceTransactionNumber =
                                                    $oracleData->Source_Transaction_Number ?? null;
                                                $sourceIdentifier = $item->Source_Transaction_Identifier ?? null;
                                            @endphp
                                            <tr id="row-{{ $sourceIdentifier ?? 'temp-' . $loop->index }}">
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div
                                                            class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                                            style="background: #eff6ff; width: 28px; height: 28px;"
                                                        >
                                                            <i
                                                                class="bi bi-person"
                                                                style="color: #3b82f6; font-size: 0.75rem;"
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
                                                                    class="btn btn-sm d-flex align-items-center btn-animated gap-1"
                                                                    style="background: #eff6ff; color: #3b82f6; border: none; border-radius: 6px; padding: 4px 8px; font-size: 0.75rem;"
                                                                >PDF</a>
                                                                <a
                                                                    href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Xml?Orden={{ $POS }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm d-flex align-items-center btn-animated gap-1"
                                                                    style="background: #f1f5f9; color: #475569; border: none; border-radius: 6px; padding: 4px 8px; font-size: 0.75rem;"
                                                                >XML</a>
                                                            @endif
                                                            @if ($item->UUID ?? false)
                                                                <a
                                                                    href="https://timbradokowirest.kowi.com.mx/api/Timbrar/DownloadPdfCte?Uuid={{ $item->UUID }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm d-flex align-items-center btn-animated gap-1"
                                                                    style="background: #eff6ff; color: #3b82f6; border: none; border-radius: 6px; padding: 4px 8px; font-size: 0.75rem;"
                                                                > <i class="bi bi-download"></i> PDF
                                                                </a>
                                                                <a
                                                                    href="https://timbradokowirest.kowi.com.mx/api/Timbrar/DownloadXmlCte?Uuid={{ $item->UUID }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm d-flex align-items-center btn-animated gap-1"
                                                                    style="background: #f1f5f9; color: #475569; border: none; border-radius: 6px; padding: 4px 8px; font-size: 0.75rem;"
                                                                ><i class="bi bi-download"></i> XML
                                                                </a>
                                                            @endif
                                                            @if ($sourceIdentifier && $status != 'PROCESADO')
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-sm btn-enviar d-flex align-items-center btn-animated gap-1"
                                                                    style="background: #fffbeb; color: #f59e0b; border: none; border-radius: 6px; padding: 4px 8px; font-size: 0.75rem;"
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
                                                                class="btn btn-sm d-flex align-items-center btn-animated gap-1"
                                                                style="background: #f1f5f9; color: #475569; border: none; border-radius: 6px; padding: 4px 8px; font-size: 0.75rem;"
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
                                            <tr style="background: #f8fafc;">
                                                <td
                                                    colspan="{{ in_array(Auth::user()->IdTipoUsuario, $allowedUserTypes) ? 4 : 3 }}"
                                                    class="fw-bold text-end"
                                                >TOTALES:</td>
                                                <td class="fw-bold text-end">{{ number_format($totalKilos, 2) }} kg</td>
                                                <td class="fw-bold text-end">${{ number_format($totalVentas, 2) }}</td>
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
                                                        style="font-size: 2.5rem; color: #94a3b8;"
                                                    ></i>
                                                    <p
                                                        class="mt-2"
                                                        style="color: #64748b; font-size: 0.85rem;"
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
                            <div class="col-12">
                                <div
                                    class="rounded p-4 shadow-sm"
                                    style="background: white; border-radius: 12px;"
                                >
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5
                                            class="mb-0"
                                            style="font-weight: 600; color: #0f172a; font-size: 1rem;"
                                        >Ventas por Hora</h5>
                                        @if (!empty($graficaVentas['data']) && array_sum($graficaVentas['data']) != 0)
                                            <div class="btn-group">
                                                <button
                                                    type="button"
                                                    class="btn btn-sm periodo-btn active"
                                                    data-periodo="hoy"
                                                    style="background: #1e293b; color: white; border: none; border-radius: 6px 0 0 6px; padding: 4px 10px; font-size: 0.75rem;"
                                                >Hoy</button>
                                                <button
                                                    type="button"
                                                    class="btn btn-sm periodo-btn"
                                                    data-periodo="7d"
                                                    style="background: #f1f5f9; color: #475569; border: none; padding: 4px 10px; font-size: 0.75rem;"
                                                >7d</button>
                                                <button
                                                    type="button"
                                                    class="btn btn-sm periodo-btn"
                                                    data-periodo="30d"
                                                    style="background: #f1f5f9; color: #475569; border: none; border-radius: 0 6px 6px 0; padding: 4px 10px; font-size: 0.75rem;"
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
                                                <div class="text-center"><i
                                                        class="bi bi-bar-chart"
                                                        style="font-size: 2.5rem; color: #94a3b8;"
                                                    ></i>
                                                    <p
                                                        class="mt-2"
                                                        style="color: #64748b;"
                                                    >Sin datos</p>
                                                </div>
                                            </div>
                                        @else
                                            <canvas id="ventasChart"></canvas>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div
                                    class="rounded p-4 shadow-sm"
                                    style="background: white; border-radius: 12px;"
                                >
                                    <h5
                                        class="mb-3"
                                        style="font-weight: 600; color: #0f172a; font-size: 1rem;"
                                    >Distribución de Pagos</h5>
                                    <div
                                        class="position-relative"
                                        style="height: 160px;"
                                    >
                                        @if (empty($graficaDistribucionPagos['data']) || array_sum($graficaDistribucionPagos['data']) == 0)
                                            <div class="d-flex justify-content-center align-items-center h-100">
                                                <div class="text-center"><i
                                                        class="bi bi-pie-chart"
                                                        style="font-size: 2.5rem; color: #94a3b8;"
                                                    ></i>
                                                    <p
                                                        class="mt-2"
                                                        style="color: #64748b;"
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
    </x-page-container>
@endsection

@section('scripts')
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
                            item.innerHTML = `<span class="tags-green">${estatusUnicos[0]}</span>`;
                            let estatusPedido = estatusUnicos[0];
                            const contenedor = document.querySelector(`.buttons-oracle-${pedido}`);
                            if (estatusPedido == 'Awaiting Shipping') {
                                if (contenedor) {
                                    contenedor.innerHTML = '';
                                    botonDespachoInventario(contenedor, pedido, uuidLocal);
                                }
                            }
                            if (estatusPedido == 'Awaiting Billing') {
                                if (contenedor) {
                                    contenedor.innerHTML = '';
                                    botonGenerarFactura(contenedor, pedido, uuidLocal);
                                }
                            }
                            if (estatusPedido == 'Closed' && type == 'sf') {
                                fetchBuscarUUID(pedido, uuidLocal, item, 'sf');
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
                        const contenedor = document.querySelector(`.buttons-oracle-${pedido}`);
                        if ((!uuidOracle || uuidOracle.trim() === '') && type == 'sf') {
                            if (contenedor) {
                                contenedor.innerHTML = '';
                                botonEnviarUuid(contenedor, pedido, uuidLocal, item);
                            }
                        } else {
                            if (contenedor) contenedor.innerHTML = '';
                            if (type == 'sf') item.innerHTML = `<span class="tags-green">Closed & UUID</span>`;
                            else item.innerHTML = `<span class="tags-green">Closed</span>`;
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
                                setTimeout(() => getStatusLoop(pedido, uuidLocal, estatusSiguiente), 3000);
                            }
                        } else {
                            setTimeout(() => getStatusLoop(pedido, uuidLocal, estatusSiguiente), 3000);
                        }
                    } else {
                        setTimeout(() => getStatusLoop(pedido, uuidLocal, estatusSiguiente), 3000);
                    }
                })
                .catch(() => setTimeout(() => getStatusLoop(pedido, uuidLocal, estatusSiguiente), 3000));
        }

        function botonDespachoInventario(contenedor, pedido, uuidLocal) {
            const btn = document.createElement('button');
            btn.className = 'btn btn-sm d-flex align-items-center gap-1 btn-animated';
            btn.style.cssText =
                'background:#fffbeb;color:#f59e0b;border:none;border-radius:6px;padding:4px 8px;font-size:0.75rem;white-space:nowrap;';
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
            btn.className = 'btn btn-sm d-flex align-items-center gap-1 btn-animated';
            btn.style.cssText =
                'background:#eff6ff;color:#3b82f6;border:none;border-radius:6px;padding:4px 8px;font-size:0.75rem;white-space:nowrap;';
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
            btn.className = 'btn btn-sm d-flex align-items-center gap-1 btn-animated';
            btn.style.cssText =
                'background:#f0fdf4;color:#10b981;border:none;border-radius:6px;padding:4px 8px;font-size:0.75rem;white-space:nowrap;';
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
                    `
                    <a href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Pdf?Orden=${resultado.dato.sourceTransactionNumber}" target="_blank" class="btn btn-sm d-flex align-items-center gap-1" style="background:#eff6ff;color:#3b82f6;border:none;border-radius:6px;padding:4px 8px;font-size:0.75rem;">PDF</a>
                    <a href="https://oraclefacturasrest.kowi.com.mx/api/Documentos/Zip?Orden=${resultado.dato.sourceTransactionNumber}" target="_blank" class="btn btn-sm d-flex align-items-center gap-1" style="background:#f1f5f9;color:#475569;border:none;border-radius:6px;padding:4px 8px;font-size:0.75rem;">ZIP</a>`;
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
                // GUARDAR estado original
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

                // EXPANDIR a full screen
                contenedorCard.style.position = 'fixed';
                contenedorCard.style.top = '52px'; // Debajo del nav
                contenedorCard.style.left = '0';
                contenedorCard.style.width = '100vw';
                contenedorCard.style.height = 'calc(100vh - 52px)';
                contenedorCard.style.zIndex = '1050';
                contenedorCard.style.margin = '0';
                contenedorCard.style.borderRadius = '0';
                contenedorCard.style.maxWidth = '100vw';
                contenedorCard.style.overflow = 'auto';
                contenedorCard.style.background = '#f8fafc';
                contenedorCard.style.padding = '24px';
                contenedorCard.style.transition = 'all 0.3s ease';

                // Mover al body para evitar problemas de z-index con el layout
                document.body.appendChild(contenedorCard);

                // Actualizar botón
                btnTexto.textContent = 'Contraer';
                btnExpandir.style.background = '#1e293b';
                btnExpandir.style.color = 'white';
                icono.className = 'bi bi-arrows-collapse';

                tablaExpandida = true;

                // Cerrar con ESC
                document.addEventListener('keydown', cerrarConEsc);
            } else {
                // CONTRAER a estado original
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

                // Devolver a su posición original
                if (tablaOriginalParent && tablaOriginalNextSibling) {
                    tablaOriginalParent.insertBefore(contenedorCard, tablaOriginalNextSibling);
                } else if (tablaOriginalParent) {
                    tablaOriginalParent.appendChild(contenedorCard);
                }

                // Restaurar botón
                btnTexto.textContent = 'Expandir';
                btnExpandir.style.background = '#f1f5f9';
                btnExpandir.style.color = '#475569';
                icono.className = 'bi bi-arrows-fullscreen';

                tablaExpandida = false;

                // Remover evento ESC
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
            if (ctxV) {
                ventasChart = new Chart(ctxV, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($graficaVentas['labels'] ?? []) !!},
                        datasets: [{
                            label: 'Ventas',
                            data: {!! json_encode($graficaVentas['data'] ?? []) !!},
                            borderColor: '#1e293b',
                            backgroundColor: 'rgba(30,41,59,0.1)',
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
                                '#64748b', // Slate
                                '#84a98c', // Sage Green
                                '#7b9acc', // Soft Blue
                                '#9d8ac7', // Soft Purple
                                '#6fa8a3', // Muted Teal
                                '#8b93d6', // Soft Indigo
                                '#8fc4d7', // Soft Cyan
                                '#c9a0b8', // Dusty Pink
                                '#a3b8cc', // Blue Gray
                                '#a8c3a0', // Light Olive
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
                $('.periodo-btn').css({
                    background: '#f1f5f9',
                    color: '#475569'
                });
                $(this).css({
                    background: '#1e293b',
                    color: 'white'
                });
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
@endsection
