<x-page-container title="Reporte de Ventas por Ticket">
    <x-card-gradient-header
        icon="ticket-perforated"
        title="Reporte de Ventas por Ticket"
        subtitle="Consulta de ventas detalladas por ticket"
    >
        <x-slot:buttons>
            <a
                href="/DashVentaPorTicket/exports?{{ http_build_query(request()->only(['idTienda', 'fecha', 'id_ticket', 'id_encabezado', 'folio', 'status_venta', 'solicitud_fe', 'cancelado'])) }}"
                class="btn-header-ghost"
                title="Exportar a Excel"
                style="background: #f0fdf4; color: #10b981;"
                onmouseover="this.style.background='#dcfce7'; this.style.transform='translateY(-1px)'"
                onmouseout="this.style.background='#f0fdf4'; this.style.transform='translateY(0)'"
            >
                <i class="bi bi-file-earmark-excel"></i> Exportar
            </a>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form action="/DashVentaPorTicket">
            <x-form.group>
                <x-form.select
                    name="idTienda"
                    label="Tienda"
                    icon="shop"
                    col="col-md-3"
                    :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                    :autofocus="true"
                />
                <x-form.date
                    name="fecha"
                    label="Fecha"
                    icon="calendar3"
                    col="col-md-2"
                    :value="request('fecha')"
                />
                <x-form.text
                    name="id_ticket"
                    label="N° Ticket"
                    icon="ticket"
                    placeholder="Número de ticket"
                    col="col-md-2"
                    :value="request('id_ticket')"
                />
                <x-form.text
                    name="id_encabezado"
                    label="ID Encabezado"
                    icon="hash"
                    placeholder="ID del encabezado"
                    col="col-md-2"
                    :value="request('id_encabezado')"
                />
                <div class="col-md-3">
                    <x-form.advanced-toggle :active="$filtrosAvanzadosActivos" />
                </div>
            </x-form.group>
            <x-form.advanced-panel :active="$filtrosAvanzadosActivos">
                <x-form.text
                    name="folio"
                    label="Folio"
                    icon="shield-lock"
                    placeholder="Folio encriptado"
                    col="col-md-2"
                    :value="request('folio')"
                />
                <x-form.checkbox-input
                    name="solicitud_fe"
                    label="Solicitud Factura"
                    icon="file-text"
                    :checked="request('solicitud_fe') == 'on'"
                    col="col-md-2"
                />
            </x-form.advanced-panel>
        </x-form.form>

        <!-- Tabla -->
        <div>
            <div class="d-flex justify-content-between align-items-center flex-wrap px-4 pt-4">
                <div>
                    <h5 class="section-content-title">
                        <i
                            class="bi bi-table me-2"
                            style="color: #64748b;"
                        ></i>Reporte de Ventas por Ticket
                    </h5>
                </div>
                <div class="d-flex gap-2">
                    <!-- Botones de vista -->
                    <div class="btn-group me-2">
                        <button
                            class="btn btn-sm active"
                            id="btnVistaDetallada"
                            onclick="cambiarVista('detallada')"
                            style="background: #1e293b; color: white; border: none; border-radius: 6px 0 0 6px; padding: 6px 12px; font-size: 0.8rem;"
                        >
                            📋 Detallado
                        </button>
                        <button
                            class="btn btn-sm"
                            id="btnVistaResumida"
                            onclick="cambiarVista('resumida')"
                            style="background: #f1f5f9; color: #475569; border: none; border-radius: 0 6px 6px 0; padding: 6px 12px; font-size: 0.8rem;"
                        >
                            🎫 Solo Tickets
                        </button>
                    </div>

                    <!-- Nuevo botón Ver Más -->
                    @if ($idEncabezado && $detalleData && Auth::user()->IdTipoUsuario == 1)
                        <button
                            class="btn btn-sm"
                            id="btnVerMas"
                            onclick="toggleVerMas()"
                            style="background: #f0f9ff; color: #0284c7; border: 1px solid #bae6fd; border-radius: 6px; padding: 6px 14px; font-size: 0.8rem; display: flex; align-items: center; gap: 4px;"
                        >
                            <i class="bi bi-eye"></i> <span id="btnVerMasTexto">Ver más</span>
                        </button>
                    @endif
                </div>
            </div>

            @php
                $totalCantidad = 0;
                $totalIva = 0;
                $totalImporte = 0;
                $totalTicketsActivos = 0;
                $totalTicketsCancelados = 0;

                $groupedByTicket = [];
                foreach ($data as $item) {
                    $ticketId = $item->IdEncabezado;
                    if (!isset($groupedByTicket[$ticketId])) {
                        $groupedByTicket[$ticketId] = [
                            'items' => [],
                            'IdTicket' => $item->IdTicket,
                            'ticket_info' => $item,
                            'totales' => ['cantidad' => 0, 'iva' => 0, 'importe' => 0],
                            'es_cancelado' => false,
                            'solicitudes_factura' => [],
                        ];
                    }
                    if ($item->StatusVenta == 1 && $item->MotivoCancel) {
                        $groupedByTicket[$ticketId]['es_cancelado'] = true;
                    }
                    if ($item->IdSolicitudFactura && $item->SolicitudFE == 0) {
                        $solicitudId = $item->IdSolicitudFactura;
                        if (!isset($groupedByTicket[$ticketId]['solicitudes_factura'][$solicitudId])) {
                            $groupedByTicket[$ticketId]['solicitudes_factura'][$solicitudId] = [
                                'id_solicitud' => $item->IdSolicitudFactura,
                                'nom_cliente' => $item->NomCliente ?? 'N/A',
                                'uuid' => $item->UUID ?? 'N/A',
                                'lineas' => [],
                                'total_importe' => 0,
                            ];
                        }
                        $groupedByTicket[$ticketId]['solicitudes_factura'][$solicitudId]['lineas'][] = $item->Linea;
                        $groupedByTicket[$ticketId]['solicitudes_factura'][$solicitudId]['total_importe'] += floatval(
                            $item->ImporteArticulo,
                        );
                    }
                    $groupedByTicket[$ticketId]['items'][] = $item;
                    $groupedByTicket[$ticketId]['totales']['cantidad'] += floatval($item->CantArticulo);
                    $groupedByTicket[$ticketId]['totales']['iva'] += floatval($item->IvaArticulo);
                    $groupedByTicket[$ticketId]['totales']['importe'] += floatval($item->ImporteArticulo);
                }
                foreach ($groupedByTicket as $ticket) {
                    if ($ticket['es_cancelado']) {
                        $totalTicketsCancelados++;
                    } else {
                        $totalTicketsActivos++;
                        $totalCantidad += $ticket['totales']['cantidad']; // ✅ Agregado
                        $totalIva += $ticket['totales']['iva']; // ✅ Agregado
                        $totalImporte += $ticket['totales']['importe']; // ✅ Agregado
                    }
                }
                $groupedByTicket = collect($groupedByTicket)
                    ->sortBy(function ($ticket) {
                        return $ticket['ticket_info']->IdTicket ?? 0;
                    })
                    ->toArray();
            @endphp

            <div
                class="rounded p-4 shadow-sm"
                style="background: white; border-radius: 12px;"
            >
                <div class="table-responsive">

                    {{-- ============ VISTA DETALLADA ============ --}}
                    <div id="vistaDetallada">
                        {{-- Totales arriba --}}

                        @if (count($data) > 0)
                            <div class="d-flex mb-3 flex-wrap gap-3">
                                <div style="background: #dbeafe; border-radius: 8px; padding: 8px 16px;">
                                    <small style="color: #1d4ed8; font-weight: 600;">Total Cantidad</small>
                                    <div style="font-weight: 700; font-size: 1.1rem; color: #0f172a;">
                                        {{ number_format($totalCantidad, 4) }}</div>
                                </div>
                                <div style="background: #fef3c7; border-radius: 8px; padding: 8px 16px;">
                                    <small style="color: #d97706; font-weight: 600;">Total IVA</small>
                                    <div style="font-weight: 700; font-size: 1.1rem; color: #0f172a;">
                                        ${{ number_format($totalIva, 2) }}</div>
                                </div>
                                <div style="background: #f0fdf4; border-radius: 8px; padding: 8px 16px;">
                                    <small style="color: #059669; font-weight: 600;">Total Importe</small>
                                    <div style="font-weight: 700; font-size: 1.1rem; color: #10b981;">
                                        ${{ number_format($totalImporte, 2) }}</div>
                                </div>
                                <div style="background: #f1f5f9; border-radius: 8px; padding: 8px 16px;">
                                    <small style="color: #64748b; font-weight: 600;">Tickets</small>
                                    <div style="font-weight: 700; font-size: 1.1rem; color: #0f172a;">
                                        {{ count($groupedByTicket) }} <small
                                            style="color: #64748b;">({{ $totalTicketsActivos }} activos)</small></div>
                                </div>
                            </div>
                        @endif
                        <div
                            class="table-responsive"
                            {{-- style="max-height: 650px; overflow-y: auto;" --}}
                        >
                            <table class="table-hover table-custom table">
                                <thead style="position: sticky; top: 0; z-index: 2;">
                                    <tr>
                                        <th><i class="bi bi-upc-scan me-1"></i>Código</th>
                                        <th><i class="bi bi-box me-1"></i>Artículo</th>
                                        <th class="text-end"><i class="bi bi-hash me-1"></i>Cantidad</th>
                                        <th class="text-end"><i class="bi bi-cash me-1"></i>Precio</th>
                                        <th class="text-end"><i class="bi bi-percent me-1"></i>IVA</th>
                                        <th class="text-end"><i class="bi bi-cash-stack me-1"></i>Importe</th>
                                        <th><i class="bi bi-calendar3 me-1"></i>Fecha Venta</th>
                                        <th><i class="bi bi-folder me-1"></i>Pago</th>
                                        <th><i class="bi bi-folder me-1"></i>Grupo</th>
                                        <th><i class="bi bi-folder-symlink me-1"></i>Familia</th>
                                        <th><i class="bi bi-gift me-1"></i>Paquete</th>
                                        <th><i class="bi bi-receipt me-1"></i>Pedido</th>
                                        <th><i class="bi bi-hash me-1"></i>Línea</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($groupedByTicket as $ticketId => $ticketData)
                                        @php
                                            $esCancelado = $ticketData['es_cancelado'];
                                            $tieneFactura = count($ticketData['solicitudes_factura']) > 0;
                                            $ticketInfo = $ticketData['ticket_info'];
                                            $headerBgColor = $esCancelado
                                                ? '#fef2f2'
                                                : ($tieneFactura
                                                    ? '#f0fdf4'
                                                    : '#eff6ff');
                                            $borderColor = $esCancelado
                                                ? '#ef4444'
                                                : ($tieneFactura
                                                    ? '#10b981'
                                                    : '#3b82f6');
                                        @endphp

                                        <!-- Cabecera del ticket -->
                                        <tr>
                                            <td
                                                colspan="13"
                                                class="table-primary"
                                                style="border-left: 4px solid {{ $borderColor }};"
                                            >
                                                <div style=" font-weight: 600; color: #0f172a; font-size: 0.9rem;">
                                                    <i
                                                        class="bi bi-ticket-perforated me-2"
                                                        style="color: #64748b;"
                                                    ></i>
                                                    TICKET #{{ $ticketInfo->IdTicket ?? $ticketId }} -
                                                    {{ $ticketInfo->IdEncabezado }}
                                                    <span style="color: #94a3b8; font-weight: 400;">|</span>
                                                    Folio: {{ Hashids::encode($ticketInfo->IdEncabezado) }}
                                                    <span style="color: #94a3b8; font-weight: 400;">|</span>
                                                    {{ $ticketInfo->NomTienda ?? 'N/A' }}
                                                    <span style="color: #94a3b8; font-weight: 400;">|</span>
                                                    {{ \Carbon\Carbon::parse($ticketInfo->FechaVenta)->format('d/m/Y H:i') }}
                                                    <span style="color: #94a3b8; font-weight: 400;">|</span>
                                                    {{ $ticketInfo->NomUsuario ?? 'N/A' }}
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Cancelación -->
                                        @if ($esCancelado)
                                            <tr style="background: #fffbeb;">
                                                <td
                                                    colspan="13"
                                                    class="py-2 ps-4"
                                                >
                                                    <small style="color: #92400e;">
                                                        <strong>⚠️ TICKET CANCELADO</strong> |
                                                        {{ \Carbon\Carbon::parse($ticketInfo->FechaCancelacion)->format('d/m/Y H:i') }}
                                                        |
                                                        {{ $ticketInfo->MotivoCancel ?? 'No especificado' }}
                                                    </small>
                                                </td>
                                            </tr>
                                        @endif

                                        <!-- Items sin factura -->
                                        @php $itemsSinFactura = array_filter($ticketData['items'], fn($i) => !$i->IdSolicitudFactura || $i->SolicitudFE != 0); @endphp
                                        @foreach ($itemsSinFactura as $item)
                                            <tr
                                                class="{{ $esCancelado ? 'text-decoration-line-through' : '' }}"
                                                style="{{ $esCancelado ? 'background: #fef2f2;' : '' }}"
                                            >
                                                <td style="font-weight: 500;">{{ $item->CodArticulo }}</td>
                                                <td
                                                    class="text-truncate"
                                                    style="max-width: 180px;"
                                                    title="{{ $item->NomArticulo }}"
                                                >{{ $item->NomArticulo }}</td>
                                                <td
                                                    class="text-end"
                                                    style="font-weight: 500;"
                                                >{{ number_format($item->CantArticulo, 4) }}</td>
                                                <td
                                                    class="text-end"
                                                    style="font-weight: 500;"
                                                >${{ number_format($item->PrecioArticulo, 2) }}</td>
                                                <td
                                                    class="text-end"
                                                    style="font-weight: 500;"
                                                >${{ number_format($item->IvaArticulo, 2) }}</td>
                                                <td
                                                    class="text-end"
                                                    style="font-weight: 500;"
                                                >${{ number_format($item->ImporteArticulo, 2) }}</td>
                                                <td style="font-size: 0.85rem;">
                                                    {{ \Carbon\Carbon::parse($item->FechaVenta)->format('d/m/Y H:i') }}
                                                </td>
                                                <td>{{ $item->NomTipoPago ?? 'N/A' }}</td>
                                                <td>{{ $item->NomGrupo ?? 'N/A' }}</td>
                                                <td>{{ $item->NomFamilia ?? 'N/A' }}</td>
                                                <td>{{ $item->NomPaquete ?: '-' }}</td>
                                                <td>{{ $item->Source_Transaction_Identifier ?: '-' }}</td>
                                                <td>{{ $item->Linea }}</td>
                                            </tr>
                                            {{-- @if (!$esCancelado)
                                                @php
                                                    $totalCantidad += floatval($item->CantArticulo);
                                                    $totalIva += floatval($item->IvaArticulo);
                                                    $totalImporte += floatval($item->ImporteArticulo);
                                                @endphp
                                            @endif --}}
                                        @endforeach

                                        <!-- Items con factura -->
                                        @php
                                            $itemsConFactura = [];
                                            foreach ($ticketData['items'] as $item) {
                                                if ($item->IdSolicitudFactura && $item->SolicitudFE == 0) {
                                                    $itemsConFactura[$item->IdSolicitudFactura][] = $item;
                                                }
                                            }
                                        @endphp
                                        @foreach ($itemsConFactura as $solicitudId => $items)
                                            @php $solicitudInfo = $ticketData['solicitudes_factura'][$solicitudId] ?? null; @endphp
                                            <tr style="background: #fef3c7; border-bottom: 2px solid #fde68a;">
                                                <td
                                                    colspan="13"
                                                    class="py-2"
                                                >
                                                    <small>
                                                        <strong>📋 Factura:</strong>
                                                        {{ $solicitudInfo['id_solicitud'] }}
                                                        @if ($solicitudInfo['nom_cliente'] != 'N/A')
                                                            - {{ $solicitudInfo['nom_cliente'] }}
                                                        @endif
                                                        -
                                                        ${{ number_format(array_sum(array_column($items, 'ImporteArticulo')), 2) }}
                                                    </small>
                                                </td>
                                            </tr>
                                            @foreach ($items as $item)
                                                <tr style="background: #fffbeb;">
                                                    <td style="font-weight: 500;">{{ $item->CodArticulo }}</td>
                                                    <td
                                                        class="text-truncate"
                                                        style="max-width: 180px;"
                                                        title="{{ $item->NomArticulo }}"
                                                    >{{ $item->NomArticulo }}</td>
                                                    <td
                                                        class="text-end"
                                                        style="font-weight: 500;"
                                                    >{{ number_format($item->CantArticulo, 4) }}</td>
                                                    <td
                                                        class="text-end"
                                                        style="font-weight: 500;"
                                                    >${{ number_format($item->PrecioArticulo, 2) }}</td>
                                                    <td
                                                        class="text-end"
                                                        style="font-weight: 500;"
                                                    >${{ number_format($item->IvaArticulo, 2) }}</td>
                                                    <td
                                                        class="text-end"
                                                        style="font-weight: 500;"
                                                    >${{ number_format($item->ImporteArticulo, 2) }}</td>
                                                    <td style="font-size: 0.85rem;">
                                                        {{ \Carbon\Carbon::parse($item->FechaVenta)->format('d/m/Y H:i') }}
                                                    </td>
                                                    <td>{{ $item->NomTipoPago ?? 'N/A' }}</td>
                                                    <td>{{ $item->NomGrupo ?? 'N/A' }}</td>
                                                    <td>{{ $item->NomFamilia ?? 'N/A' }}</td>
                                                    <td>{{ $item->NomPaquete ?: '-' }}</td>
                                                    <td>{{ $item->Source_Transaction_Identifier ?: '-' }}</td>
                                                    <td>{{ $item->Linea }}</td>
                                                </tr>
                                                {{-- @php
                                                    $totalCantidad += floatval($item->CantArticulo);
                                                    $totalIva += floatval($item->IvaArticulo);
                                                    $totalImporte += floatval($item->ImporteArticulo);
                                                @endphp --}}
                                            @endforeach
                                        @endforeach

                                        <!-- Subtotal -->
                                        <tr style="background: #f8f9fa; font-weight: 600;">
                                            <td
                                                colspan="2"
                                                style="color: #64748b;"
                                            ><i class="bi bi-bar-chart me-1"></i>Subtotal Ticket</td>
                                            <td class="text-end">
                                                {{ number_format($ticketData['totales']['cantidad'], 4) }}</td>
                                            <td></td>
                                            <td class="text-end">
                                                ${{ number_format($ticketData['totales']['iva'], 2) }}
                                            </td>
                                            <td
                                                class="text-end"
                                                style="color: #10b981;"
                                            >${{ number_format($ticketData['totales']['importe'], 2) }}</td>
                                            <td colspan="7"></td>
                                        </tr>

                                        <!-- Separador -->
                                        <tr>
                                            <td
                                                colspan="13"
                                                style="border-bottom: 3px solid #dee2e6; padding: 5px;"
                                            ></td>
                                        </tr>

                                        <!-- ============ NUEVO: DETALLE DE PAGOS Y FACTURAS ============ -->
                                        @if ($idEncabezado && $detalleData && $ticketInfo->IdEncabezado == $idEncabezado && Auth::user()->IdTipoUsuario == 1)
                                            @php
                                                // Asegurar que $detalleData es una colección o array
                                                $itemsDetalle = is_array($detalleData)
                                                    ? $detalleData
                                                    : (is_object($detalleData)
                                                        ? $detalleData->toArray()
                                                        : []);
                                                $primerItem = !empty($itemsDetalle) ? $itemsDetalle[0] : null;

                                                // Calcular totales del detalle
                                                $totalDetalleCantidad = collect($itemsDetalle)->sum('CantArticulo');
                                                $totalDetalleIva = collect($itemsDetalle)->sum('IvaArticulo');
                                                $totalDetalleImporte = collect($itemsDetalle)->sum('ImporteArticulo');
                                                $totalDetalleSubtotal = collect($itemsDetalle)->sum('SubTotalArticulo');
                                            @endphp

                                            <!-- Contenedor que se oculta/muestra -->
                                <tbody
                                    id="detalleExtra_{{ $ticketId }}"
                                    style="display: none;"
                                >
                                    @if ($primerItem)
                                        <!-- Resumen del ticket - EXTENDIDO -->
                                        <tr style="background: #f0f9ff; border-top: 2px solid #bae6fd;">
                                            <td
                                                colspan="13"
                                                style="padding: 10px 16px;"
                                            >
                                                <div
                                                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; font-size: 0.85rem;">
                                                    <!-- Columna 1: Datos del ticket -->
                                                    <div>
                                                        <div
                                                            style="font-weight: 700; color: #0f172a; border-bottom: 1px solid #bae6fd; padding-bottom: 4px; margin-bottom: 6px;">
                                                            <i class="bi bi-ticket-perforated me-1"></i>Ticket
                                                            #{{ $primerItem->IdTicket ?? 'N/A' }}
                                                        </div>
                                                        <div><span style="color: #64748b;">Encabezado:</span>
                                                            <strong>{{ $primerItem->IdEncabezado ?? 'N/A' }}</strong>
                                                        </div>
                                                        <div><span style="color: #64748b;">Fecha Venta:</span>
                                                            {{ isset($primerItem->FechaVenta) ? \Carbon\Carbon::parse($primerItem->FechaVenta)->format('d/m/Y H:i') : 'N/A' }}
                                                        </div>
                                                        <div><span style="color: #64748b;">Tienda:</span>
                                                            {{ $primerItem->NomTienda ?? 'N/A' }}</div>
                                                        <div><span style="color: #64748b;">Usuario:</span>
                                                            {{ $primerItem->NomUsuario ?? 'N/A' }}</div>
                                                    </div>

                                                    <!-- Columna 2: Empleado -->
                                                    <div>
                                                        <div
                                                            style="font-weight: 700; color: #0f172a; border-bottom: 1px solid #bae6fd; padding-bottom: 4px; margin-bottom: 6px;">
                                                            <i class="bi bi-person me-1"></i>Empleado
                                                        </div>
                                                        <div><span style="color: #64748b;">Nombre:</span>
                                                            <strong>{{ $primerItem->NombreEmpleado ?? 'N/A' }}</strong>
                                                        </div>
                                                        <div><span style="color: #64748b;">Nómina:</span>
                                                            {{ $primerItem->NumNomina ?? 'N/A' }}</div>
                                                        <div><span style="color: #64748b;">Usuario:</span>
                                                            {{ $primerItem->NomUsuario ?? 'N/A' }}</div>
                                                        @if ($primerItem->NombreEmpleadoComprador)
                                                            <div><span style="color: #64748b;">Comprador:</span>
                                                                {{ $primerItem->NombreEmpleadoComprador }}
                                                                {{ $primerItem->ApellidosEmpleadoComprador ?? '' }}
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Columna 3: Totales -->
                                                    <div>
                                                        <div
                                                            style="font-weight: 700; color: #0f172a; border-bottom: 1px solid #bae6fd; padding-bottom: 4px; margin-bottom: 6px;">
                                                            <i class="bi bi-calculator me-1"></i>Totales
                                                        </div>
                                                        <div><span style="color: #64748b;">SubTotal:</span>
                                                            <strong>${{ number_format($primerItem->SubTotal ?? 0, 2) }}</strong>
                                                        </div>
                                                        <div><span style="color: #64748b;">IVA:</span>
                                                            <strong>${{ number_format($primerItem->Iva ?? 0, 2) }}</strong>
                                                        </div>
                                                        <div><span style="color: #64748b; font-weight: 600;">Importe
                                                                Venta:</span> <strong
                                                                style="color: #10b981; font-size: 1.1rem;"
                                                            >${{ number_format($primerItem->ImporteVenta ?? 0, 2) }}</strong>
                                                        </div>
                                                        <div><span style="color: #64748b;">Artículos:</span>
                                                            {{ count($itemsDetalle) }} líneas</div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Tabla de detalles del ticket - EXTENDIDA -->
                                        <tr style="background: #f8fafc;">
                                            <td
                                                colspan="13"
                                                style="padding: 0;"
                                            >
                                                <div style="padding: 10px 16px; background: #f8fafc;">
                                                    <div style="font-weight: 600; color: #0f172a; margin-bottom: 8px;">
                                                        <i class="bi bi-list-ul me-1"></i>Detalle de Artículos
                                                        ({{ count($itemsDetalle) }} líneas)
                                                    </div>
                                                    <table
                                                        style="width: 100%; font-size: 0.8rem; border-collapse: collapse;"
                                                    >
                                                        <thead>
                                                            <tr
                                                                style="border-bottom: 2px solid #e2e8f0; background: #f1f5f9;">
                                                                <th
                                                                    style="text-align: left; padding: 6px 10px; color: #475569; font-weight: 600;">
                                                                    Línea</th>
                                                                <th
                                                                    style="text-align: left; padding: 6px 10px; color: #475569; font-weight: 600;">
                                                                    Código</th>
                                                                <th
                                                                    style="text-align: left; padding: 6px 10px; color: #475569; font-weight: 600;">
                                                                    Artículo</th>
                                                                <th
                                                                    style="text-align: right; padding: 6px 10px; color: #475569; font-weight: 600;">
                                                                    Cantidad</th>
                                                                <th
                                                                    style="text-align: right; padding: 6px 10px; color: #475569; font-weight: 600;">
                                                                    Precio</th>
                                                                <th
                                                                    style="text-align: right; padding: 6px 10px; color: #475569; font-weight: 600;">
                                                                    SubTotal</th>
                                                                <th
                                                                    style="text-align: right; padding: 6px 10px; color: #475569; font-weight: 600;">
                                                                    IVA</th>
                                                                <th
                                                                    style="text-align: right; padding: 6px 10px; color: #475569; font-weight: 600;">
                                                                    Importe</th>
                                                                <th
                                                                    style="text-align: left; padding: 6px 10px; color: #475569; font-weight: 600;">
                                                                    Familia/Grupo</th>
                                                                <th
                                                                    style="text-align: left; padding: 6px 10px; color: #475569; font-weight: 600;">
                                                                    Paquete</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($itemsDetalle as $item)
                                                                <tr
                                                                    style="border-bottom: 1px solid #e2e8f0; {{ isset($item->IdPaquete) && $item->IdPaquete ? 'background: #fefce8;' : '' }}">
                                                                    <td style="padding: 6px 10px; font-weight: 500;">
                                                                        {{ $item->Linea ?? 'N/A' }}</td>
                                                                    <td style="padding: 6px 10px; font-weight: 500;">
                                                                        {{ $item->CodArticulo ?? 'N/A' }}</td>
                                                                    <td
                                                                        style="padding: 6px 10px; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                                                        title="{{ $item->NomArticulo ?? 'N/A' }}"
                                                                    >
                                                                        {{ $item->NomArticulo ?? 'N/A' }}
                                                                    </td>
                                                                    <td
                                                                        style="padding: 6px 10px; text-align: right; font-weight: 500;">
                                                                        {{ number_format($item->CantArticulo ?? 0, 4) }}
                                                                    </td>
                                                                    <td style="padding: 6px 10px; text-align: right;">
                                                                        ${{ number_format($item->PrecioArticulo ?? 0, 2) }}
                                                                    </td>
                                                                    <td style="padding: 6px 10px; text-align: right;">
                                                                        ${{ number_format($item->SubTotalArticulo ?? 0, 2) }}
                                                                    </td>
                                                                    <td
                                                                        style="padding: 6px 10px; text-align: right; color: {{ ($item->IvaArticulo ?? 0) > 0 ? '#64748b' : '#94a3b8' }}">
                                                                        ${{ number_format($item->IvaArticulo ?? 0, 2) }}
                                                                    </td>
                                                                    <td
                                                                        style="padding: 6px 10px; text-align: right; font-weight: 600; color: #10b981;">
                                                                        ${{ number_format($item->ImporteArticulo ?? 0, 2) }}
                                                                    </td>
                                                                    <td style="padding: 6px 10px; font-size: 0.75rem;">
                                                                        <span
                                                                            style="background: #e0e7ff; padding: 1px 8px; border-radius: 4px;"
                                                                        >{{ $item->NomFamilia ?? 'N/A' }}</span>
                                                                        <span
                                                                            style="background: #fce7f3; padding: 1px 8px; border-radius: 4px;"
                                                                        >{{ $item->NomGrupo ?? 'N/A' }}</span>
                                                                    </td>
                                                                    <td style="padding: 6px 10px; font-size: 0.75rem;">
                                                                        @if (isset($item->NomPaquete) && $item->NomPaquete)
                                                                            <span
                                                                                style="background: #fef3c7; color: #92400e; padding: 1px 8px; border-radius: 4px;"
                                                                            >
                                                                                <i
                                                                                    class="bi bi-gift me-1"></i>{{ $item->NomPaquete }}
                                                                            </span>
                                                                        @else
                                                                            <span style="color: #94a3b8;">-</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr
                                                                style="border-top: 2px solid #cbd5e1; font-weight: 600; background: #f1f5f9;">
                                                                <td
                                                                    colspan="3"
                                                                    style="padding: 6px 10px; text-align: right;"
                                                                >TOTALES:</td>
                                                                <td style="padding: 6px 10px; text-align: right;">
                                                                    {{ number_format($totalDetalleCantidad, 4) }}</td>
                                                                <td style="padding: 6px 10px;"></td>
                                                                <td style="padding: 6px 10px; text-align: right;">
                                                                    ${{ number_format($totalDetalleSubtotal, 2) }}</td>
                                                                <td style="padding: 6px 10px; text-align: right;">
                                                                    ${{ number_format($totalDetalleIva, 2) }}</td>
                                                                <td
                                                                    style="padding: 6px 10px; text-align: right; color: #10b981; font-size: 1rem;">
                                                                    ${{ number_format($totalDetalleImporte, 2) }}
                                                                </td>
                                                                <td colspan="2"></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif

                                    <!-- Pagos - EXTENDIDO -->
                                    @if ($pagosData && count($pagosData) > 0)
                                        @php
                                            $totalPagos = collect($pagosData)->sum('Pago');
                                            $totalRestante = count($pagosData) > 0 ? $pagosData[count($pagosData) - 1]->Restante : 0;
                                        @endphp
                                        <tr style="background: #f0fdf4;">
                                            <td
                                                colspan="13"
                                                style="padding: 10px 16px;"
                                            >
                                                <div style="display: flex; flex-direction: column; gap: 8px;">
                                                    <div
                                                        style="display: flex; flex-wrap: wrap; gap: 15px; align-items: center;">
                                                        <span
                                                            style="font-weight: 700; color: #059669; font-size: 0.95rem;"
                                                        >
                                                            <i class="bi bi-credit-card me-1"></i>Pagos
                                                            ({{ count($pagosData) }})
                                                        </span>
                                                        {{-- <span
                                                            style="background: #dcfce7; padding: 2px 12px; border-radius: 12px; font-size: 0.8rem; font-weight: 600; color: #065f46;"
                                                        >
                                                            Total Pagado: ${{ number_format($totalPagos, 2) }}
                                                        </span>
                                                        @if ($totalRestante > 0)
                                                            <span
                                                                style="background: #fef2f2; padding: 2px 12px; border-radius: 12px; font-size: 0.8rem; font-weight: 600; color: #dc2626;"
                                                            >
                                                                <i
                                                                    class="bi bi-exclamation-triangle me-1"></i>Restante:
                                                                ${{ number_format($totalRestante, 2) }}
                                                            </span>
                                                        @endif --}}
                                                    </div>
                                                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                                        @foreach ($pagosData as $pago)
                                                            <div
                                                                style="background: #dcfce7; padding: 8px 14px; border-radius: 8px; font-size: 0.85rem; border-left: 3px solid #10b981; min-width: 150px;">
                                                                <div
                                                                    style="display: flex; justify-content: space-between; gap: 15px;">
                                                                    <span
                                                                        style="font-weight: 600;">{{ $pago->NomTipoPago ?? 'N/A' }}</span>
                                                                    <span
                                                                        style="font-weight: 700; color: #0f172a;">${{ number_format($pago->Pago ?? 0, 2) }}</span>
                                                                </div>
                                                                @if (isset($pago->IdTipoPago) && $pago->IdTipoPago == 5)
                                                                    <div
                                                                        style="font-size: 0.75rem; color: #475569; margin-top: 2px;">
                                                                        <i class="bi bi-credit-card me-1"></i>
                                                                        @if ($pago->IdBanco)
                                                                            Banco: {{ $pago->IdBanco }}
                                                                        @endif
                                                                        @if ($pago->NumTarjeta)
                                                                            Tarjeta: ****{{ $pago->NumTarjeta }}
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                                @if (isset($pago->Restante) && $pago->Restante != 0)
                                                                    <div
                                                                        style="font-size: 0.7rem; color: #ef4444; margin-top: 2px;">
                                                                        <i class="bi bi-arrow-right me-1"></i>Restante:
                                                                        ${{ number_format($pago->Restante, 2) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif

                                    <!-- Facturas - EXTENDIDO -->
                                    @if ($facturasData && count($facturasData) > 0)
                                        <tr style="background: #fef3c7;">
                                            <td
                                                colspan="13"
                                                style="padding: 10px 16px;"
                                            >
                                                <div style="display: flex; flex-direction: column; gap: 8px;">
                                                    <div
                                                        style="display: flex; flex-wrap: wrap; gap: 15px; align-items: center;">
                                                        <span
                                                            style="font-weight: 700; color: #d97706; font-size: 0.95rem;"
                                                        >
                                                            <i class="bi bi-file-text me-1"></i>Facturas
                                                            ({{ count($facturasData) }})
                                                        </span>
                                                    </div>
                                                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                                        @foreach ($facturasData as $factura)
                                                            <div
                                                                style="background: #fef9e7; padding: 8px 14px; border-radius: 8px; font-size: 0.85rem; border: 1px solid #fde68a; min-width: 250px; flex: 1;">
                                                                <div
                                                                    style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 5px;">
                                                                    <span style="font-weight: 700; color: #92400e;">
                                                                        {{ $factura->IdSolicitudFactura ?? 'N/A' }}
                                                                    </span>
                                                                </div>
                                                                <div
                                                                    style="display: grid; grid-template-columns: 1fr 1fr; gap: 2px 15px; margin-top: 4px; font-size: 0.8rem;">
                                                                    <div>
                                                                        <span style="color: #64748b;">Cliente:</span>
                                                                        <span
                                                                            style="font-weight: 500;">{{ $factura->NomCliente ?? 'N/A' }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <span style="color: #64748b;">RFC:</span>
                                                                        <span
                                                                            style="font-weight: 500;">{{ $factura->RFC ?? 'N/A' }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <span style="color: #64748b;">Uso CFDI:</span>
                                                                        <span>{{ $factura->UsoCFDI ?? 'N/A' }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <span style="color: #64748b;">Régimen:</span>
                                                                        <span>{{ $factura->RegimenFiscal ?? 'N/A' }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <span style="color: #64748b;">Método
                                                                            Pago:</span>
                                                                        <span>{{ $factura->MetodoPago ?? 'N/A' }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <span style="color: #64748b;">Fecha:</span>
                                                                        <span>{{ isset($factura->FechaSolicitud) ? \Carbon\Carbon::parse($factura->FechaSolicitud)->format('d/m/Y H:i') : 'N/A' }}</span>
                                                                    </div>
                                                                    @if ($factura->UUID)
                                                                        <div style="grid-column: span 2;">
                                                                            <span style="color: #64748b;">UUID:</span>
                                                                            <span
                                                                                style="font-family: monospace; font-size: 0.7rem; background: #f1f5f9; padding: 1px 6px; border-radius: 4px;"
                                                                            >
                                                                                {{ $factura->UUID }}
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                    @if ($factura->Calle || $factura->Colonia)
                                                                        <div
                                                                            style="grid-column: span 2; color: #64748b; font-size: 0.75rem;">
                                                                            <i class="bi bi-geo-alt me-1"></i>
                                                                            {{ $factura->Calle ?? '' }}
                                                                            {{ $factura->NumExt ?? '' }}
                                                                            {{ $factura->Colonia ? ', Col. ' . $factura->Colonia : '' }}
                                                                            {{ $factura->Ciudad ? ', ' . $factura->Ciudad : '' }}
                                                                            {{ $factura->Estado ? ', ' . $factura->Estado : '' }}
                                                                            CP: {{ $factura->CodigoPostal ?? '' }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                @if ($factura->Email)
                                                                    <div
                                                                        style="font-size: 0.75rem; color: #64748b; margin-top: 4px;">
                                                                        <i
                                                                            class="bi bi-envelope me-1"></i>{{ $factura->Email }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                                @endif
                            @empty
                                <tr>
                                    <td
                                        colspan="13"
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
                                @if (count($data) > 0)
                                    <tfoot style="position: sticky; bottom: -1px; z-index: 3;">
                                        <tr style="background: #f1f5f9; font-weight: 700;">
                                            <td
                                                colspan="2"
                                                class="text-end"
                                                style="color: #0f172a;"
                                            >Totales Generales:</td>
                                            <td class="text-end">{{ number_format($totalCantidad, 4) }}</td>
                                            <td></td>
                                            <td class="text-end">${{ number_format($totalIva, 2) }}</td>
                                            <td
                                                class="text-end"
                                                style="color: #10b981;"
                                            >${{ number_format($totalImporte, 2) }}</td>
                                            <td colspan="7"></td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>

                    {{-- ============ VISTA SOLO TICKETS ============ --}}
                    <div
                        id="vistaResumida"
                        style="display: none;"
                    >
                        <table class="table-hover table-custom table">
                            <thead>
                                <tr>
                                    <th><i class="bi bi-ticket-perforated me-1"></i>Ticket</th>
                                    <th><i class="bi bi-hash me-1"></i>Encabezado</th>
                                    <th><i class="bi bi-shop me-1"></i>Tienda</th>
                                    <th><i class="bi bi-calendar3 me-1"></i>Fecha</th>
                                    <th class="text-center"><i class="bi bi-box me-1"></i>Artículos</th>
                                    <th class="text-center"><i class="bi bi-circle me-1"></i>Estatus</th>
                                    <th class="text-end"><i class="bi bi-cash-stack me-1"></i>Iva</th>
                                    <th class="text-end"><i class="bi bi-cash-stack me-1"></i>Importe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalImporteResumido = 0; @endphp
                                @php $totalIvaResumido = 0; @endphp
                                @forelse ($groupedByTicket as $ticketId => $ticketData)
                                    @php
                                        $esCancelado = $ticketData['es_cancelado'];
                                        $tieneFactura = count($ticketData['solicitudes_factura']) > 0;
                                        $ticketInfo = $ticketData['ticket_info'];
                                        $ivaValue = $ticketData['totales']['iva'];
                                        $tieneIva = $ivaValue > 0;
                                    @endphp
                                    <tr>
                                        <td style="font-weight: 600; color: #0f172a;">
                                            {{ $ticketInfo->IdTicket ?? $ticketId }}</td>
                                        <td style="font-weight: 500;">{{ $ticketInfo->IdEncabezado }}</td>
                                        <td>{{ $ticketInfo->NomTienda ?? 'N/A' }}</td>
                                        <td style="font-size: 0.85rem;">
                                            {{ \Carbon\Carbon::parse($ticketInfo->FechaVenta)->format('d/m/Y H:i') }}
                                        </td>
                                        <td
                                            class="text-center"
                                            style="font-weight: 500;"
                                        >{{ count($ticketData['items']) }}</td>
                                        <td class="text-center">
                                            @if ($esCancelado)
                                                <span
                                                    style="background: #fef2f2; color: #ef4444; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 500;"
                                                >Cancelado</span>
                                            @elseif ($tieneFactura)
                                                <span
                                                    style="background: #f0fdf4; color: #10b981; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 500;"
                                                >Con Factura</span>
                                            @else
                                                <span
                                                    style="background: #eff6ff; color: #3b82f6; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 500;"
                                                >Activo</span>
                                            @endif
                                        </td>
                                        <td
                                            class="text-end"
                                            style="font-weight: 600; color: {{ $tieneIva ? '#64748b' : '#94a3b8' }};"
                                        >
                                            @if ($tieneIva)
                                                ${{ number_format($ivaValue, 2) }}
                                            @else
                                                <span style="font-weight: 400;">$0.00</span>
                                            @endif
                                        </td>
                                        <td
                                            class="text-end"
                                            style="font-weight: 600; color: #10b981;"
                                        >${{ number_format($ticketData['totales']['importe'], 2) }}</td>
                                    </tr>
                                    @php $totalImporteResumido += $ticketData['totales']['importe']; @endphp
                                    @php $totalIvaResumido += $ticketData['totales']['iva']; @endphp
                                @empty
                                    <tr>
                                        <td
                                            colspan="8"
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
                            @if (count($data) > 0)
                                <tfoot>
                                    <tr style="background: #f1f5f9; font-weight: 700;">
                                        <td
                                            colspan="6"
                                            style="color: #0f172a;"
                                        >Total:</td>
                                        <td
                                            class="text-end"
                                            style="color: #64748b; font-weight: 700;"
                                        >
                                            @if ($totalIvaResumido > 0)
                                                ${{ number_format($totalIvaResumido, 2) }}
                                            @else
                                                <span style="color: #94a3b8; font-weight: 400;">$0.00</span>
                                            @endif
                                        </td>
                                        <td
                                            class="text-end"
                                            style="color: #10b981; font-weight: 700;"
                                        >${{ number_format($totalImporteResumido, 2) }}</td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </x-card-gradient-header>

    @section('scripts')
        <script>
            function togglePanel(panelId, buttonId) {
                const panel = document.getElementById(panelId);
                const btn = document.getElementById(buttonId);
                if (panel.classList.contains('d-none')) {
                    panel.classList.remove('d-none');
                    if (btn) btn.style.background = '#e2e8f0';
                } else {
                    panel.classList.add('d-none');
                    if (btn) btn.style.background = '#f1f5f9';
                }
            }

            function toggleVerMas() {
                const btn = document.getElementById('btnVerMas');
                const btnTexto = document.getElementById('btnVerMasTexto');
                const elementosExtra = document.querySelectorAll('[id^="detalleExtra_"]');
                const icono = btn.querySelector('i');

                let visible = false;

                elementosExtra.forEach(el => {
                    if (el.style.display === 'none' || el.style.display === '') {
                        el.style.display = 'table-row-group';
                        visible = true;
                    } else {
                        el.style.display = 'none';
                    }
                });

                if (visible) {
                    btnTexto.textContent = 'Ver menos';
                    btn.style.background = '#fef2f2';
                    btn.style.color = '#dc2626';
                    btn.style.borderColor = '#fecaca';
                    icono.className = 'bi bi-eye-slash';
                } else {
                    btnTexto.textContent = 'Ver más';
                    btn.style.background = '#f0f9ff';
                    btn.style.color = '#0284c7';
                    btn.style.borderColor = '#bae6fd';
                    icono.className = 'bi bi-eye';
                }
            }

            function cambiarVista(vista) {
                const vd = document.getElementById('vistaDetallada');
                const vr = document.getElementById('vistaResumida');
                const bd = document.getElementById('btnVistaDetallada');
                const br = document.getElementById('btnVistaResumida');

                if (vista === 'detallada') {
                    vd.style.display = 'block';
                    vr.style.display = 'none';
                    bd.style.background = '#1e293b';
                    bd.style.color = 'white';
                    br.style.background = '#f1f5f9';
                    br.style.color = '#475569';
                } else {
                    vd.style.display = 'none';
                    vr.style.display = 'block';
                    bd.style.background = '#f1f5f9';
                    bd.style.color = '#475569';
                    br.style.background = '#1e293b';
                    br.style.color = 'white';
                }
            }
        </script>
    @endsection
</x-page-container>
