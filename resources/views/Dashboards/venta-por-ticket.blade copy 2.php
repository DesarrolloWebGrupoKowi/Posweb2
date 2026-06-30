<x-page-container>
    <x-card-gradient-header
        icon="ticket-perforated"
        title="Reporte de Ventas por Ticket"
        subtitle="Consulta de ventas detalladas por ticket"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
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
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form action="/DashVentaPorTicket">
            {{-- Fila 1: Filtros principales --}}
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

            {{-- Fila 2: Filtros avanzados (ocultos) --}}
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
        <div class="p-4">
            <div
                class="rounded p-4 shadow-sm"
                style="background: white; border-radius: 12px;"
            >
                <div class="table-responsive">
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
                                <th><i class="bi bi-folder me-1"></i>Grupo</th>
                                <th><i class="bi bi-folder-symlink me-1"></i>Familia</th>
                                <th><i class="bi bi-gift me-1"></i>Paquete</th>
                                <th><i class="bi bi-receipt me-1"></i>Pedido</th>
                                <th><i class="bi bi-hash me-1"></i>Línea</th>
                            </tr>
                        </thead>
                        <tbody>
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
                                        $groupedByTicket[$ticketId]['solicitudes_factura'][$solicitudId]['lineas'][] =
                                            $item->Linea;
                                        $groupedByTicket[$ticketId]['solicitudes_factura'][$solicitudId][
                                            'total_importe'
                                        ] += floatval($item->ImporteArticulo);
                                    }
                                    $groupedByTicket[$ticketId]['items'][] = $item;
                                    $groupedByTicket[$ticketId]['totales']['cantidad'] += floatval($item->CantArticulo);
                                    $groupedByTicket[$ticketId]['totales']['iva'] += floatval($item->IvaArticulo);
                                    $groupedByTicket[$ticketId]['totales']['importe'] += floatval(
                                        $item->ImporteArticulo,
                                    );
                                }
                                foreach ($groupedByTicket as $ticket) {
                                    $ticket['es_cancelado'] ? $totalTicketsCancelados++ : $totalTicketsActivos++;
                                }
                                $groupedByTicket = collect($groupedByTicket)
                                    ->sortByDesc(function ($ticket) {
                                        return $ticket['ticket_info']->IdTicket ?? 0;
                                    })
                                    ->toArray();
                            @endphp

                            @forelse ($groupedByTicket as $ticketId => $ticketData)
                                @php
                                    $esCancelado = $ticketData['es_cancelado'];
                                    $tieneFactura = count($ticketData['solicitudes_factura']) > 0;
                                    $ticketInfo = $ticketData['ticket_info'];
                                    $headerBgColor = $esCancelado ? '#fef2f2' : ($tieneFactura ? '#f0fdf4' : '#eff6ff');
                                    $borderColor = $esCancelado ? '#ef4444' : ($tieneFactura ? '#10b981' : '#3b82f6');
                                @endphp

                                {{-- Cabecera del ticket --}}
                                <tr
                                    class="table-primary"
                                    style="background-color: {{ $headerBgColor }}; cursor: pointer;"
                                >
                                    <td
                                        colspan="12"
                                        class="p-0"
                                        style="border: none;"
                                    >
                                        <div
                                            style="padding: 10px 16px; border-left: 4px solid {{ $borderColor }}; font-weight: 600; color: #0f172a; font-size: 0.9rem;">
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

                                {{-- Cancelación --}}
                                @if ($esCancelado)
                                    <tr style="background: #fffbeb;">
                                        <td
                                            colspan="12"
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

                                {{-- Items sin factura --}}
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
                                            {{ \Carbon\Carbon::parse($item->FechaVenta)->format('d/m/Y H:i') }}</td>
                                        <td>{{ $item->NomGrupo ?? 'N/A' }}</td>
                                        <td>{{ $item->NomFamilia ?? 'N/A' }}</td>
                                        <td>{{ $item->NomPaquete ?: '-' }}</td>
                                        <td>{{ $item->Source_Transaction_Identifier ?: '-' }}</td>
                                        <td>{{ $item->Linea }}</td>
                                    </tr>
                                    @if (!$esCancelado)
                                        @php
                                            $totalCantidad += floatval($item->CantArticulo);
                                            $totalIva += floatval($item->IvaArticulo);
                                            $totalImporte += floatval($item->ImporteArticulo);
                                        @endphp
                                    @endif
                                @endforeach

                                {{-- Items con factura --}}
                                @php
                                    $itemsAgrupados = [];
                                    foreach ($ticketData['items'] as $item) {
                                        if ($item->IdSolicitudFactura && $item->SolicitudFE == 0) {
                                            $itemsAgrupados[$item->IdSolicitudFactura][] = $item;
                                        }
                                    }
                                @endphp
                                @foreach ($itemsAgrupados as $solicitudId => $items)
                                    @php $solicitudInfo = $ticketData['solicitudes_factura'][$solicitudId] ?? null; @endphp
                                    <tr style="background: #fef3c7; border-bottom: 2px solid #fde68a;">
                                        <td
                                            colspan="12"
                                            class="py-2"
                                        >
                                            <small>
                                                <strong>📋 Factura:</strong> {{ $solicitudInfo['id_solicitud'] }}
                                                @if ($solicitudInfo['nom_cliente'] != 'N/A')
                                                    - {{ $solicitudInfo['nom_cliente'] }}
                                                @endif
                                                -
                                                ${{ number_format(array_sum(array_column($items, 'ImporteArticulo')), 2) }}
                                                @if ($solicitudInfo['uuid'] != 'N/A')
                                                    - {{ $solicitudInfo['uuid'] }}
                                                @endif
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
                                            <td>{{ $item->NomGrupo ?? 'N/A' }}</td>
                                            <td>{{ $item->NomFamilia ?? 'N/A' }}</td>
                                            <td>{{ $item->NomPaquete ?: '-' }}</td>
                                            <td>{{ $item->Source_Transaction_Identifier ?: '-' }}</td>
                                            <td>{{ $item->Linea }}</td>
                                        </tr>
                                        @php
                                            $totalCantidad += floatval($item->CantArticulo);
                                            $totalIva += floatval($item->IvaArticulo);
                                            $totalImporte += floatval($item->ImporteArticulo);
                                        @endphp
                                    @endforeach
                                @endforeach

                                {{-- Subtotal del ticket --}}
                                <tr style="background: #f8f9fa; font-weight: 600;">
                                    <td
                                        colspan="2"
                                        style="color: #64748b;"
                                    >
                                        <i class="bi bi-bar-chart me-1"></i>Subtotal Ticket
                                    </td>
                                    <td class="text-end">{{ number_format($ticketData['totales']['cantidad'], 4) }}
                                    </td>
                                    <td></td>
                                    <td class="text-end">${{ number_format($ticketData['totales']['iva'], 2) }}</td>
                                    <td
                                        class="text-end"
                                        style="color: #10b981;"
                                    >${{ number_format($ticketData['totales']['importe'], 2) }}</td>
                                    <td colspan="6"></td>
                                </tr>

                                {{-- Separador --}}
                                <tr>
                                    <td
                                        colspan="12"
                                        style="border-bottom: 3px solid #dee2e6; padding: 5px;"
                                    ></td>
                                </tr>
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
                        @if (count($data) > 0)
                            <tfoot>
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
                                    <td colspan="6"></td>
                                </tr>
                                <tr>
                                    <td
                                        colspan="12"
                                        class="text-muted small py-2"
                                    >
                                        * {{ count($groupedByTicket) }} tickets | {{ $totalTicketsActivos }} activos |
                                        {{ $totalTicketsCancelados }} cancelados
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
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
        </script>
    @endsection
</x-page-container>
