@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Reporte de Ventas por Ticket')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <x-layout.page-container>
        <!-- SECCIÓN 1: TITULO Y FILTROS -->
        <x-layout.section-card>
            <!-- Título y botones principales -->
            <x-layout.section-title>
                <x-title titulo="Reporte de Ventas por Ticket" />
                <div class="d-flex gap-2">
                    <x-filters.buttons.excel-button
                        route="/ReporteMovimientosInventario/exports"
                        :params="[
                            'idTienda' => request('idTienda'),
                            'fecha_inicio' => request('fecha_inicio'),
                            'fecha_fin' => request('fecha_fin'),
                            'fecha' => request('fecha'),
                            'cod_articulo' => request('cod_articulo'),
                            'nom_articulo' => request('nom_articulo'),
                            'id_movimiento' => request('id_movimiento'),
                            'usuario' => request('usuario'),
                            'num_nomina' => request('num_nomina'),
                            'referencia' => request('referencia'),
                            'id_caja' => request('id_caja'),
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
                        :value="request('idTienda')"
                    />
                    <x-filters.inputs.date-input
                        name="fecha"
                        label="Fecha Única"
                        :value="request('fecha')"
                        :autofocus="true"
                    />
                    <x-filters.inputs.text-input
                        name="id_ticket"
                        label="N° Ticket"
                        placeholder="Número de ticket"
                        :value="request('id_ticket')"
                    />
                    <x-filters.inputs.text-input
                        name="id_encabezado"
                        label="ID Encabezado"
                        placeholder="ID del encabezado"
                        :value="request('id_encabezado')"
                    />
                    {{-- <x-filters.inputs.date-input
                        name="fecha_inicio"
                        label="Fecha Inicio"
                        :value="request('fecha_inicio')"
                    />
                    <x-filters.inputs.date-input
                        name="fecha_fin"
                        label="Fecha Fin"
                        :value="request('fecha_fin')"
                    /> --}}
                </x-filters.filter-group>

                <!-- Filtros Avanzados -->
                <x-filters.advanced-collapse
                    :active="$filtrosAvanzadosActivos"
                    :showBadge="true"
                >
                    <!-- Estado de Venta - Grupo 2 -->
                    <x-filters.filter-group>
                        <x-filters.inputs.select-input
                            name="status_venta"
                            label="Estado Venta"
                            :options="[
                                '' => 'Todos',
                                '1' => 'Activa',
                                '2' => 'Cancelada',
                            ]"
                            :value="request('status_venta')"
                            compact="true"
                        />
                        <x-filters.inputs.checkbox-input
                            name="solicitud_fe"
                            label="Solicitud Factura"
                            :checked="request('solicitud_fe') == 'on'"
                            helperText="Con solicitud"
                            compact="true"
                        />
                        <x-filters.inputs.checkbox-input
                            name="recorte"
                            label="Con Recorte"
                            :value="request('recorte')"
                            true-value="1"
                            false-value="0"
                            compact="true"
                        />
                        <x-filters.inputs.checkbox-input
                            name="cancelado"
                            label="Incluir Cancelados"
                            :value="request('cancelado')"
                            true-value="1"
                            false-value="0"
                            helper-text="Mostrar también tickets cancelados"
                            compact="true"
                        />
                    </x-filters.filter-group>

                    <!-- Artículos y Tickets -->
                    <x-filters.filter-group>
                        <x-filters.inputs.text-input
                            name="cod_articulo"
                            label="Código Artículo"
                            placeholder="Buscar por código"
                            :value="request('cod_articulo')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="nom_articulo"
                            label="Nombre Artículo"
                            placeholder="Buscar por nombre"
                            :value="request('nom_articulo')"
                            compact="true"
                        />
                        {{-- <x-filters.inputs.text-input
                            name="id_ticket"
                            label="Folio Ticket"
                            placeholder="Número de ticket"
                            :value="request('id_ticket')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="id_encabezado"
                            label="ID Encabezado"
                            placeholder="ID del encabezado"
                            :value="request('id_encabezado')"
                            compact="true"
                        /> --}}
                    </x-filters.filter-group>

                    <!-- Usuario y Cliente -->
                    <x-filters.filter-group>
                        <x-filters.inputs.text-input
                            name="usuario"
                            label="Vendedor"
                            placeholder="Nombre de usuario"
                            :value="request('usuario')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="num_nomina"
                            label="Nómina Cliente"
                            placeholder="Número de nómina"
                            :value="request('num_nomina')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="nombre_cliente"
                            label="Cliente"
                            placeholder="Nombre del cliente"
                            :value="request('nombre_cliente')"
                            compact="true"
                        />
                        <x-filters.inputs.select-input
                            name="id_lista_precio"
                            label="Lista de Precio"
                            :options="$listasPrecio ?? []"
                            :value="request('id_lista_precio')"
                            compact="true"
                        />
                    </x-filters.filter-group>

                    <!-- Categorías -->
                    <x-filters.filter-group>
                        <x-filters.inputs.text-input
                            name="familia"
                            label="Familia"
                            placeholder="Nombre de familia"
                            :value="request('familia')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="grupo"
                            label="Grupo"
                            placeholder="Nombre de grupo"
                            :value="request('grupo')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="id_paquete"
                            label="ID Paquete"
                            placeholder="ID del paquete"
                            :value="request('id_paquete')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="id_descuento"
                            label="ID Descuento"
                            placeholder="ID del descuento"
                            :value="request('id_descuento')"
                            compact="true"
                        />
                    </x-filters.filter-group>

                    <!-- Rangos de Monto -->
                    <x-filters.filter-group>
                        <x-filters.inputs.number-input
                            name="subtotal_min"
                            label="Subtotal Mínimo"
                            placeholder="Mínimo"
                            :value="request('subtotal_min')"
                            compact="true"
                        />
                        <x-filters.inputs.number-input
                            name="subtotal_max"
                            label="Subtotal Máximo"
                            placeholder="Máximo"
                            :value="request('subtotal_max')"
                            compact="true"
                        />
                        <x-filters.inputs.number-input
                            name="importe_min"
                            label="Importe Mínimo"
                            placeholder="Mínimo"
                            :value="request('importe_min')"
                            compact="true"
                        />
                        <x-filters.inputs.number-input
                            name="importe_max"
                            label="Importe Máximo"
                            placeholder="Máximo"
                            :value="request('importe_max')"
                            compact="true"
                        />
                    </x-filters.filter-group>

                    <!-- Filtros adicionales con checkbox -->
                    <x-filters.filter-group>
                        <x-filters.inputs.checkbox-input
                            name="solo_con_descuento"
                            label="Solo con Descuento"
                            :value="request('solo_con_descuento')"
                            true-value="1"
                            false-value="0"
                            compact="true"
                        />
                        <x-filters.inputs.checkbox-input
                            name="solo_paquetes"
                            label="Solo Paquetes"
                            :value="request('solo_paquetes')"
                            true-value="1"
                            false-value="0"
                            compact="true"
                        />
                        <x-filters.inputs.checkbox-input
                            name="solo_recorte"
                            label="Solo Recorte"
                            :value="request('solo_recorte')"
                            true-value="1"
                            false-value="0"
                            compact="true"
                        />
                        <x-filters.inputs.checkbox-input
                            name="solo_empleados"
                            label="Solo Compras Empleados"
                            :value="request('solo_empleados')"
                            true-value="1"
                            false-value="0"
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

            {{-- <x-filters.filter-form>
                <!-- Filtros Básicos -->
                <x-filters.filter-group>
                    <x-filters.inputs.select-input
                        name="idTienda"
                        label="Tienda"
                        :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                        :value="request('idTienda')"
                    />
                    <x-filters.inputs.date-input
                        name="fecha"
                        label="Fecha Única"
                        :value="request('fecha')"
                        :autofocus="true"
                    />
                    <x-filters.inputs.date-input
                        name="fecha_inicio"
                        label="Fecha Inicio"
                        :value="request('fecha_inicio')"
                    />
                    <x-filters.inputs.date-input
                        name="fecha_fin"
                        label="Fecha Fin"
                        :value="request('fecha_fin')"
                    />
                    <x-filters.inputs.select-input
                        name="status_venta"
                        label="Estado Venta"
                        :options="[
                            '' => 'Todos',
                            '1' => 'Activa',
                            '0' => 'Cancelada',
                        ]"
                        :value="request('status_venta')"
                    />
                </x-filters.filter-group>

                <!-- Filtros Avanzados -->
                <x-filters.advanced-collapse
                    :active="$filtrosAvanzadosActivos"
                    :showBadge="true"
                >
                    <x-filters.filter-group>
                        <x-filters.inputs.text-input
                            name="cod_articulo"
                            label="Código Artículo"
                            placeholder="Buscar por código"
                            :value="request('cod_articulo')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="nom_articulo"
                            label="Nombre Artículo"
                            placeholder="Buscar por nombre"
                            :value="request('nom_articulo')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="id_ticket"
                            label="Folio Ticket"
                            placeholder="Número de ticket"
                            :value="request('id_ticket')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="id_encabezado"
                            label="ID Encabezado"
                            placeholder="ID del encabezado"
                            :value="request('id_encabezado')"
                            compact="true"
                        />
                    </x-filters.filter-group>

                    <x-filters.filter-group>
                        <x-filters.inputs.text-input
                            name="usuario"
                            label="Vendedor"
                            placeholder="Nombre de usuario"
                            :value="request('usuario')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="num_nomina"
                            label="Nómina Cliente"
                            placeholder="Número de nómina"
                            :value="request('num_nomina')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="nombre_cliente"
                            label="Cliente"
                            placeholder="Nombre del cliente"
                            :value="request('nombre_cliente')"
                            compact="true"
                        />
                    </x-filters.filter-group>

                    <x-filters.filter-group>
                        <x-filters.inputs.select-input
                            name="solicitud_fe"
                            label="Solicitud Factura"
                            :options="[
                                '' => 'Todos',
                                '1' => 'Solicitado',
                                '0' => 'No solicitado',
                            ]"
                            :value="request('solicitud_fe')"
                            compact="true"
                        />
                        <x-filters.inputs.select-input
                            name="recorte"
                            label="Recorte"
                            :options="[
                                '' => 'Todos',
                                '1' => 'Con recorte',
                                '0' => 'Sin recorte',
                            ]"
                            :value="request('recorte')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="familia"
                            label="Familia"
                            placeholder="Nombre de familia"
                            :value="request('familia')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="grupo"
                            label="Grupo"
                            placeholder="Nombre de grupo"
                            :value="request('grupo')"
                            compact="true"
                        />
                    </x-filters.filter-group>

                    <x-filters.filter-group>
                        <x-filters.inputs.select-input
                            name="id_lista_precio"
                            label="Lista de Precio"
                            :options="$listasPrecio ?? []"
                            :value="request('id_lista_precio')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="id_paquete"
                            label="ID Paquete"
                            placeholder="ID del paquete"
                            :value="request('id_paquete')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="id_descuento"
                            label="ID Descuento"
                            placeholder="ID del descuento"
                            :value="request('id_descuento')"
                            compact="true"
                        />
                    </x-filters.filter-group>

                    <!-- Filtros de rangos -->
                    <x-filters.filter-group>
                        <x-filters.inputs.number-input
                            name="subtotal_min"
                            label="Subtotal Mínimo"
                            placeholder="Mínimo"
                            :value="request('subtotal_min')"
                            compact="true"
                        />
                        <x-filters.inputs.number-input
                            name="subtotal_max"
                            label="Subtotal Máximo"
                            placeholder="Máximo"
                            :value="request('subtotal_max')"
                            compact="true"
                        />
                        <x-filters.inputs.number-input
                            name="importe_min"
                            label="Importe Mínimo"
                            placeholder="Mínimo"
                            :value="request('importe_min')"
                            compact="true"
                        />
                        <x-filters.inputs.number-input
                            name="importe_max"
                            label="Importe Máximo"
                            placeholder="Máximo"
                            :value="request('importe_max')"
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
            </x-filters.filter-form> --}}

            {{-- <x-filters.filter-form>
                <!-- Filtros Básicos -->
                <x-filters.filter-group>
                    <x-filters.inputs.select-input
                        name="idTienda"
                        label="Tienda"
                        :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                        :value="request('idTienda')"
                    />
                    <x-filters.inputs.date-input
                        name="fecha"
                        label="Fecha Única"
                        :value="request('fecha')"
                        :autofocus="true"
                    />
                    <x-filters.inputs.date-input
                        name="fecha_inicio"
                        label="Fecha Inicio"
                        :value="request('fecha_inicio')"
                    />
                    <x-filters.inputs.date-input
                        name="fecha_fin"
                        label="Fecha Fin"
                        :value="request('fecha_fin')"
                    />
                </x-filters.filter-group>


                <!-- Filtros Avanzados -->
                <x-filters.advanced-collapse
                    :active="$filtrosAvanzadosActivos"
                    :showBadge="true"
                >
                    <x-filters.filter-group>
                        <x-filters.inputs.text-input
                            name="cod_articulo"
                            label="Código Artículo"
                            placeholder="Buscar por código"
                            :value="request('cod_articulo')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="nom_articulo"
                            label="Nombre Artículo"
                            placeholder="Buscar por nombre"
                            :value="request('nom_articulo')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="usuario"
                            label="Usuario"
                            placeholder="Nombre o usuario"
                            :value="request('usuario')"
                            compact="true"
                        />
                    </x-filters.filter-group>

                    <x-filters.filter-group>
                        <x-filters.inputs.text-input
                            name="num_nomina"
                            label="Nómina"
                            placeholder="Número de nómina"
                            :value="request('num_nomina')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="referencia"
                            label="Referencia"
                            placeholder="Buscar por referencia"
                            :value="request('referencia')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="id_caja"
                            label="ID Caja"
                            placeholder="ID de caja"
                            :value="request('id_caja')"
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
            </x-filters.filter-form> --}}
        </x-layout.section-card>

        <!-- SECCIÓN: TABLAS -->
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
                                    <th>Precio</th>
                                    <th>Iva</th>
                                    <th>Importe</th>
                                    <th>Fecha venta</th>
                                    <th>Grupo</th>
                                    <th>Familia</th>
                                    <th>Paquete</th>
                                    <th>Pedido</th>
                                    <th>Línea</th>
                            </thead>
                            </thead>

                            <tbody>
                                @php
                                    // Inicializamos las variables para la suma
                                    $totalCantidad = 0;
                                    $totalIva = 0;
                                    $totalImporte = 0;
                                    $totalTicketsActivos = 0;
                                    $totalTicketsCancelados = 0;

                                    // Agrupar los datos por IdEncabezado (ticket)
                                    $groupedByTicket = [];
                                    foreach ($data as $item) {
                                        $ticketId = $item->IdEncabezado;
                                        if (!isset($groupedByTicket[$ticketId])) {
                                            $groupedByTicket[$ticketId] = [
                                                'items' => [],
                                                'ticket_info' => $item,
                                                'totales' => [
                                                    'cantidad' => 0,
                                                    'iva' => 0,
                                                    'importe' => 0,
                                                ],
                                                'es_cancelado' => false,
                                                'solicitudes_factura' => [],
                                            ];
                                        }

                                        // Detectar si el ticket está cancelado
                                        if ($item->StatusVenta == 1 && $item->MotivoCancel) {
                                            $groupedByTicket[$ticketId]['es_cancelado'] = true;
                                        }

                                        // Agrupar solicitudes de factura
                                        if ($item->IdSolicitudFactura && $item->SolicitudFE == 0) {
                                            $solicitudId = $item->IdSolicitudFactura;
                                            if (
                                                !isset($groupedByTicket[$ticketId]['solicitudes_factura'][$solicitudId])
                                            ) {
                                                $groupedByTicket[$ticketId]['solicitudes_factura'][$solicitudId] = [
                                                    'id_solicitud' => $item->IdSolicitudFactura,
                                                    'nom_cliente' => $item->NomCliente ?? 'N/A',
                                                    'uuid' => $item->UUID ?? 'N/A',
                                                    'lineas' => [],
                                                    'total_importe' => 0,
                                                ];
                                            }
                                            $groupedByTicket[$ticketId]['solicitudes_factura'][$solicitudId][
                                                'lineas'
                                            ][] = $item->Linea;
                                            $groupedByTicket[$ticketId]['solicitudes_factura'][$solicitudId][
                                                'total_importe'
                                            ] += floatval($item->ImporteArticulo);
                                        }

                                        $groupedByTicket[$ticketId]['items'][] = $item;
                                        $groupedByTicket[$ticketId]['totales']['cantidad'] += floatval(
                                            $item->CantArticulo,
                                        );
                                        $groupedByTicket[$ticketId]['totales']['iva'] += floatval($item->IvaArticulo);
                                        $groupedByTicket[$ticketId]['totales']['importe'] += floatval(
                                            $item->ImporteArticulo,
                                        );
                                    }

                                    foreach ($groupedByTicket as $ticket) {
                                        if ($ticket['es_cancelado']) {
                                            $totalTicketsCancelados++;
                                        } else {
                                            $totalTicketsActivos++;
                                        }
                                    }

                                    krsort($groupedByTicket);
                                @endphp

                                @forelse ($groupedByTicket as $ticketId => $ticketData)
                                    @php
                                        $esCancelado = $ticketData['es_cancelado'];
                                        $tieneFactura = count($ticketData['solicitudes_factura']) > 0;
                                        $ticketInfo = $ticketData['ticket_info'];
                                        $headerBgColor = $esCancelado
                                            ? '#f8d7da'
                                            : ($tieneFactura
                                                ? '#d4edda'
                                                : '#e3f2fd');
                                        $headerTextColor = $esCancelado ? '#721c24' : '#000000';
                                        $badgeClass = $esCancelado
                                            ? 'bg-danger'
                                            : ($tieneFactura
                                                ? 'bg-info'
                                                : 'bg-success');
                                        $badgeText = $esCancelado
                                            ? 'CANCELADO'
                                            : ($tieneFactura
                                                ? 'CON FACTURA'
                                                : 'ACTIVO');
                                    @endphp

                                    <!-- Cabecera del ticket -->
                                    <tr
                                        class="table-primary"
                                        style="background-color: {{ $headerBgColor }}; cursor: pointer; color: {{ $headerTextColor }}; border-left: 4px solid {{ $esCancelado ? '#dc3545' : ($tieneFactura ? '#28a745' : '#0d6efd') }};"
                                        onclick="toggleTicket('ticket-{{ $ticketId }}')"
                                    >
                                        <td colspan="12">
                                            <strong>🎫 TICKET
                                                #{{ $ticketInfo->IdTicket ?? $ticketId }} -
                                                {{ $ticketInfo->IdEncabezado }}</strong>
                                            {{-- <span class="badge {{ $badgeClass }} ms-2">{{ $badgeText }}</span> --}}
                                            {{-- @if ($tieneFactura)
                                                <span
                                                    class="badge bg-secondary ms-1">{{ count($ticketData['solicitudes_factura']) }}
                                                    solicitud(es)</span>
                                            @endif --}}
                                            - Tienda: {{ $ticketInfo->NomTienda ?? 'N/A' }}
                                            - Fecha:
                                            {{ \Carbon\Carbon::parse($ticketInfo->FechaVenta)->format('d/m/Y H:i:s') }}
                                            - Vendedor: {{ $ticketInfo->NomUsuario ?? 'N/A' }}
                                            - Cliente:
                                            {{ $ticketInfo->NombreEmpleadoComprador ? $ticketInfo->NombreEmpleadoComprador . ' ' . $ticketInfo->ApellidosEmpleadoComprador : 'PÚBLICO GENERAL' }}
                                            </strong>
                                        </td>
                                    </tr>

                                    <!-- Cancelación -->
                                    @if ($esCancelado)
                                        <tr style="background-color: #fff3cd;">
                                            <td
                                                colspan="12"
                                                class="py-2 ps-4"
                                            >
                                                <small class="text-danger">
                                                    <strong>⚠️ TICKET CANCELADO</strong><br>
                                                    <strong>Fecha Cancelación:</strong>
                                                    {{ \Carbon\Carbon::parse($ticketInfo->FechaCancelacion)->format('d/m/Y H:i:s') }}<br>
                                                    <strong>Usuario Cancelación:</strong>
                                                    {{ $ticketInfo->NombreUsuarioCancelacion . ' ' . $ticketInfo->ApellidoUsuarioCancelacion ?? 'N/A' }}<br>
                                                    <strong>Motivo:</strong>
                                                    {{ $ticketInfo->MotivoCancel ?? 'No especificado' }}
                                                </small>
                                            </td>
                                        </tr>
                                    @endif

                                    <!-- Agrupar items por solicitud de factura -->
                                    @php
                                        // Organizar items por grupo: sin factura y por cada solicitud
                                        $itemsAgrupados = [];
                                        $itemsSinFactura = [];

                                        // Items sin factura
                                        foreach ($ticketData['items'] as $item) {
                                            if (!$item->IdSolicitudFactura || $item->SolicitudFE != 0) {
                                                $itemsSinFactura[] = $item;
                                            } else {
                                                $solicitudId = $item->IdSolicitudFactura;
                                                if (!isset($itemsAgrupados[$solicitudId])) {
                                                    $itemsAgrupados[$solicitudId] = [];
                                                }
                                                $itemsAgrupados[$solicitudId][] = $item;
                                            }
                                        }
                                    @endphp

                                    <!-- Mostrar items sin factura primero -->
                                    @if (count($itemsSinFactura) > 0)
                                        @foreach ($itemsSinFactura as $item)
                                            <tr
                                                class="{{ $esCancelado ? 'text-muted' : '' }}"
                                                style="{{ $esCancelado ? 'background-color: #fff5f5; text-decoration: line-through;' : '' }}"
                                            >
                                                <td>{{ $item->CodArticulo }}</td>
                                                <td>{{ $item->NomArticulo }}</td>
                                                <td>{{ number_format($item->CantArticulo, 4) }}</td>
                                                <td>${{ number_format($item->PrecioArticulo, 2) }}</td>
                                                <td>{{ number_format($item->IvaArticulo, 2) }}</td>
                                                <td>${{ number_format($item->ImporteArticulo, 2) }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->FechaVenta)->format('d/m/Y H:i:s') }}
                                                </td>
                                                <td>{{ $item->NomGrupo ?? 'N/A' }}</td>
                                                <td>{{ $item->NomFamilia ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($item->NomPaquete)
                                                        {{ $item->NomPaquete }}
                                                    @else
                                                        <span class="text-muted">---</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->Source_Transaction_Identifier }}</td>
                                                <td>{{ $item->Linea }}</td>
                                            </tr>
                                            @php
                                                if (!$esCancelado) {
                                                    $totalCantidad += floatval($item->CantArticulo);
                                                    $totalIva += floatval($item->IvaArticulo);
                                                    $totalImporte += floatval($item->ImporteArticulo);
                                                }
                                            @endphp
                                        @endforeach
                                    @endif

                                    <!-- Mostrar items agrupados por cada solicitud de factura -->
                                    @foreach ($itemsAgrupados as $solicitudId => $items)
                                        @php
                                            $solicitudInfo = $ticketData['solicitudes_factura'][$solicitudId] ?? null;
                                            // $colores = ['#e3f2fd', '#e8f5e9', '#f3e5f5', '#fff3e0'];
                                            $colores = ['#fff3e0'];
                                            $colorIndex = $loop->index % count($colores);
                                            $bgColorGrupo = $colores[$colorIndex];
                                        @endphp

                                        <!-- Cabecera del grupo de factura -->
                                        <tr
                                            class="{{ $esCancelado ? 'text-muted text-decoration-line-through' : '' }}"
                                            style="background-color: {{ $bgColorGrupo }}; border-bottom: 2px solid lightgray;"
                                        >
                                            <td
                                                colspan="12"
                                                class="py-2"
                                            >
                                                <small>
                                                    <strong>📋 Factura:</strong>
                                                    <span class="me-3">
                                                        📄 <strong>{{ $solicitudInfo['id_solicitud'] }}</strong>
                                                        @if ($solicitudInfo['nom_cliente'] != 'N/A')
                                                            - {{ $solicitudInfo['nom_cliente'] }}
                                                        @endif
                                                        -
                                                        ${{ number_format(array_sum(array_column($items, 'ImporteArticulo')), 2) }}
                                                        - {{ $solicitudInfo['uuid'] }}
                                                    </span>
                                                </small>
                                            </td>
                                        </tr>

                                        <!-- Items de esta factura -->
                                        @foreach ($items as $item)
                                            <tr
                                                {{-- style="background-color: {{ $bgColorGrupo }};" --}}
                                                class="{{ $esCancelado ? 'text-muted text-decoration-line-through' : '' }}"
                                                style="{{ $esCancelado ? 'background-color: #fff5f5;' : 'background-color: #fffaf2' }}"
                                            >
                                                <td>{{ $item->CodArticulo }}</td>
                                                <td>{{ $item->NomArticulo }}</td>
                                                <td>{{ number_format($item->CantArticulo, 4) }}</td>
                                                <td>${{ number_format($item->PrecioArticulo, 2) }}</td>
                                                <td>{{ number_format($item->IvaArticulo, 2) }}</td>
                                                <td>${{ number_format($item->ImporteArticulo, 2) }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->FechaVenta)->format('d/m/Y H:i:s') }}
                                                </td>
                                                <td>{{ $item->NomGrupo ?? 'N/A' }}</td>
                                                <td>{{ $item->NomFamilia ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($item->NomPaquete)
                                                        {{ $item->NomPaquete }}
                                                    @else
                                                        <span class="text-muted">---</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->Source_Transaction_Identifier }}</td>
                                                <td>{{ $item->Linea }}</td>
                                            </tr>
                                            @php
                                                if (!$esCancelado) {
                                                    $totalCantidad += floatval($item->CantArticulo);
                                                    $totalIva += floatval($item->IvaArticulo);
                                                    $totalImporte += floatval($item->ImporteArticulo);
                                                }
                                            @endphp
                                        @endforeach
                                    @endforeach

                                    <!-- Fila de totales del ticket -->
                                    <tr
                                        class="table-secondary"
                                        style="background-color: {{ $esCancelado ? '#f8d7da' : ($tieneFactura ? '#d4edda' : '#f8f9fa') }};"
                                    >
                                        <td colspan="1"><strong>Totales</strong></td>
                                        <td colspan="1">{{ count($ticketData['items']) }} artículos</td>
                                        <td colspan="1">
                                            <strong>{{ number_format($ticketData['totales']['cantidad'], 4) }}</strong>
                                        </td>
                                        <td colspan="1"></td>
                                        <td colspan="1">
                                            <strong>{{ number_format($ticketData['totales']['iva'], 2) }}</strong>
                                        </td>
                                        <td colspan="1">
                                            <strong>${{ number_format($ticketData['totales']['importe'], 2) }}</strong>
                                        </td>
                                        <td colspan="6"></td>
                                    </tr>


                                    <!-- Separador entre tickets -->
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
                                    <tr style="background-color: #e9ecef;">
                                        <td colspan="2"><strong>Totales Generales:</strong></td>
                                        <td colspan="1"><strong>{{ number_format($totalCantidad, 4) }}</strong></td>
                                        <td colspan="1"></td>
                                        <td colspan="1"><strong>{{ number_format($totalIva, 2) }}</strong></td>
                                        <td colspan="1"><strong>${{ number_format($totalImporte, 2) }}</strong></td>
                                        <td colspan="6"></td>
                                    </tr>
                                    <tr>
                                        <td
                                            colspan="12"
                                            class="text-muted small"
                                        >
                                            * Total de tickets: {{ count($groupedByTicket) }} |
                                            Total de tickets activos: {{ $totalTicketsActivos }} |
                                            Total de tickets cancelados: {{ $totalTicketsCancelados }} |
                                            Total de artículos vendidos (activos): {{ number_format($totalCantidad, 4) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let currentUUID = '';

            function toggleTicket(ticketId) {
                // Esta función puede ser implementada si quieres colapsar/expandir
                // Por ahora solo es para mantener la funcionalidad del clic
                console.log('Ticket clicked: ' + ticketId);
            }

            function copiarUUID(uuid) {
                navigator.clipboard.writeText(uuid).then(function() {
                    // Pequeña notificación visual
                    alert('✅ UUID copiado: ' + uuid);
                }).catch(function() {
                    alert('❌ Error al copiar el UUID');
                });
            }
        </script>

        <style>
            .table-primary {
                transition: all 0.3s ease;
            }

            .table-primary:hover {
                filter: brightness(0.98);
            }

            .btn-outline-primary {
                transition: all 0.2s;
            }

            .btn-outline-primary:hover {
                transform: scale(1.05);
            }

            /* Diferentes colores para cada grupo de factura */
            .table tbody tr {
                transition: background-color 0.2s;
            }
        </style>

        <!-- VERSION CON MODAL -->
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
                                    <th>Artículo</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Iva</th>
                                    <th>Importe</th>
                                    <th>Fecha venta</th>
                                    <th>Grupo</th>
                                    <th>Familia</th>
                                    <th>Paquete</th>
                                    <th>Línea</th>
                                    <th>Factura</th>
                            </thead>
                            </thead>

                            <tbody>
                                @php
                                    // Inicializamos las variables para la suma
                                    $totalCantidad = 0;
                                    $totalIva = 0;
                                    $totalImporte = 0;
                                    $totalTicketsActivos = 0;
                                    $totalTicketsCancelados = 0;

                                    // Agrupar los datos por IdEncabezado (ticket)
                                    $groupedByTicket = [];
                                    foreach ($data as $item) {
                                        $ticketId = $item->IdEncabezado;
                                        if (!isset($groupedByTicket[$ticketId])) {
                                            $groupedByTicket[$ticketId] = [
                                                'items' => [],
                                                'ticket_info' => $item,
                                                'totales' => [
                                                    'cantidad' => 0,
                                                    'iva' => 0,
                                                    'importe' => 0,
                                                ],
                                                'es_cancelado' => false,
                                                'solicitudes_factura' => [], // Array para múltiples solicitudes
                                            ];
                                        }

                                        // Detectar si el ticket está cancelado
                                        if ($item->StatusVenta == 1 && $item->MotivoCancel) {
                                            $groupedByTicket[$ticketId]['es_cancelado'] = true;
                                        }

                                        // Agrupar solicitudes de factura por IdSolicitudFactura
                                        if ($item->IdSolicitudFactura && $item->SolicitudFE == 0) {
                                            $solicitudId = $item->IdSolicitudFactura;
                                            if (
                                                !isset($groupedByTicket[$ticketId]['solicitudes_factura'][$solicitudId])
                                            ) {
                                                $groupedByTicket[$ticketId]['solicitudes_factura'][$solicitudId] = [
                                                    'id_solicitud' => $item->IdSolicitudFactura,
                                                    'nom_cliente' => $item->NomCliente ?? 'N/A',
                                                    'uuid' => $item->UUID ?? 'N/A',
                                                    'lineas' => [],
                                                    'total_importe' => 0,
                                                ];
                                            }
                                            // Agregar línea a esta solicitud
                                            $groupedByTicket[$ticketId]['solicitudes_factura'][$solicitudId][
                                                'lineas'
                                            ][] = $item->Linea;
                                            $groupedByTicket[$ticketId]['solicitudes_factura'][$solicitudId][
                                                'total_importe'
                                            ] += floatval($item->ImporteArticulo);
                                        }

                                        // Agregar item al ticket
                                        $groupedByTicket[$ticketId]['items'][] = $item;

                                        // Acumular totales del ticket
                                        $groupedByTicket[$ticketId]['totales']['cantidad'] += floatval(
                                            $item->CantArticulo,
                                        );
                                        $groupedByTicket[$ticketId]['totales']['iva'] += floatval($item->IvaArticulo);
                                        $groupedByTicket[$ticketId]['totales']['importe'] += floatval(
                                            $item->ImporteArticulo,
                                        );
                                    }

                                    // Contar tickets activos y cancelados
                                    foreach ($groupedByTicket as $ticket) {
                                        if ($ticket['es_cancelado']) {
                                            $totalTicketsCancelados++;
                                        } else {
                                            $totalTicketsActivos++;
                                        }
                                    }

                                    // Ordenar tickets por fecha (más reciente primero)
                                    krsort($groupedByTicket);
                                @endphp

                                @forelse ($groupedByTicket as $ticketId => $ticketData)
                                    @php
                                        $esCancelado = $ticketData['es_cancelado'];
                                        $tieneFactura = count($ticketData['solicitudes_factura']) > 0;
                                        $ticketInfo = $ticketData['ticket_info'];
                                        $headerBgColor = $esCancelado
                                            ? '#f8d7da'
                                            : ($tieneFactura
                                                ? '#d4edda'
                                                : '#e3f2fd');
                                        $headerTextColor = $esCancelado ? '#721c24' : '#000000';
                                        $badgeClass = $esCancelado
                                            ? 'bg-danger'
                                            : ($tieneFactura
                                                ? 'bg-info'
                                                : 'bg-success');
                                        $badgeText = $esCancelado
                                            ? 'CANCELADO'
                                            : ($tieneFactura
                                                ? 'CON FACTURA'
                                                : 'ACTIVO');
                                    @endphp

                                    <!-- Fila de cabecera del ticket -->
                                    <tr
                                        class="table-primary"
                                        style="background-color: {{ $headerBgColor }}; cursor: pointer; color: {{ $headerTextColor }}; border-left: 4px solid {{ $esCancelado ? '#dc3545' : ($tieneFactura ? '#28a745' : '#0d6efd') }};"
                                        onclick="toggleTicket('ticket-{{ $ticketId }}')"
                                    >
                                        <td colspan="12">
                                            <strong>🎫 TICKET #{{ $ticketInfo->IdTicket ?? $ticketId }}</strong>
                                            <span class="badge {{ $badgeClass }} ms-2">{{ $badgeText }}</span>
                                            @if ($tieneFactura)
                                                <span
                                                    class="badge bg-secondary ms-1">{{ count($ticketData['solicitudes_factura']) }}
                                                    solicitud(es)</span>
                                            @endif
                                            - Tienda: {{ $ticketInfo->NomTienda ?? 'N/A' }}
                                            - Fecha:
                                            {{ \Carbon\Carbon::parse($ticketInfo->FechaVenta)->format('d/m/Y H:i:s') }}
                                            - Vendedor: {{ $ticketInfo->NomUsuario ?? 'N/A' }}
                                            - Cliente:
                                            {{ $ticketInfo->NombreEmpleadoComprador ? $ticketInfo->NombreEmpleadoComprador . ' ' . $ticketInfo->ApellidosEmpleadoComprador : 'PÚBLICO GENERAL' }}
                                        </td>
                                    </tr>

                                    <!-- Mensaje de cancelación si el ticket está cancelado -->
                                    @if ($esCancelado)
                                        <tr style="background-color: #fff3cd;">
                                            <td
                                                colspan="12"
                                                class="py-2 ps-4"
                                            >
                                                <small class="text-danger">
                                                    <strong>⚠️ TICKET CANCELADO</strong><br>
                                                    <strong>Fecha Cancelación:</strong>
                                                    {{ \Carbon\Carbon::parse($ticketInfo->FechaCancelacion)->format('d/m/Y H:i:s') }}<br>
                                                    <strong>Usuario Cancelación:</strong>
                                                    {{ $ticketInfo->NombreUsuarioCancelacion . ' ' . $ticketInfo->ApellidoUsuarioCancelacion ?? 'N/A' }}<br>
                                                    <strong>Motivo:</strong>
                                                    {{ $ticketInfo->MotivoCancel ?? 'No especificado' }}
                                                </small>
                                            </td>
                                        </tr>
                                    @endif

                                    <!-- Items del ticket -->
                                    @foreach ($ticketData['items'] as $item)
                                        @php
                                            // Verificar a qué solicitud pertenece este item
                                            $solicitudInfo = null;
                                            $solicitudId = null;
                                            if ($item->IdSolicitudFactura && $item->SolicitudFE == 0) {
                                                $solicitudId = $item->IdSolicitudFactura;
                                                $solicitudInfo =
                                                    $ticketData['solicitudes_factura'][$solicitudId] ?? null;
                                            }

                                            $bgColorItem = $esCancelado ? '#fef5f5' : ($solicitudInfo ? '#f0f8ff' : '');
                                            $borderLeft = $solicitudInfo ? '3px solid #007bff' : '';
                                        @endphp
                                        <tr
                                            style="background-color: {{ $bgColorItem }}; border-left: {{ $borderLeft }};">
                                            <td>{{ $item->CodArticulo }}</td>
                                            <td>{{ $item->NomArticulo }}</td>
                                            <td>{{ number_format($item->CantArticulo, 4) }}</td>
                                            <td>${{ number_format($item->PrecioArticulo, 2) }}</td>
                                            <td>{{ number_format($item->IvaArticulo, 2) }}</td>
                                            <td>${{ number_format($item->ImporteArticulo, 2) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->FechaVenta)->format('d/m/Y H:i:s') }}</td>
                                            <td>{{ $item->NomGrupo ?? 'N/A' }}</td>
                                            <td>{{ $item->NomFamilia ?? 'N/A' }}</td>
                                            <td>
                                                @if ($item->NomPaquete)
                                                    {{ $item->NomPaquete }}
                                                @else
                                                    <span class="text-muted">---</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->Linea }}</td>
                                            <td>
                                                @if ($solicitudInfo)
                                                    <span
                                                        class="badge bg-info"
                                                        style="cursor: pointer;"
                                                        onclick="mostrarDetalleFactura('{{ $solicitudInfo['id_solicitud'] }}', '{{ addslashes($solicitudInfo['nom_cliente']) }}', '{{ $solicitudInfo['uuid'] }}', '{{ implode(', ', $solicitudInfo['lineas']) }}', '{{ number_format($solicitudInfo['total_importe'], 2) }}')"
                                                        title="Ver detalles de la factura"
                                                    >
                                                        📄 Factura {{ $loop->parent->iteration }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">---</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @php
                                            // Solo acumular totales si no es cancelado
                                            if (!$esCancelado) {
                                                $totalCantidad += floatval($item->CantArticulo);
                                                $totalIva += floatval($item->IvaArticulo);
                                                $totalImporte += floatval($item->ImporteArticulo);
                                            }
                                        @endphp
                                    @endforeach

                                    <!-- Fila de totales del ticket -->
                                    <tr
                                        class="table-secondary"
                                        style="background-color: {{ $esCancelado ? '#f8d7da' : ($tieneFactura ? '#d4edda' : '#f8f9fa') }};"
                                    >
                                        <td colspan="2"><strong>Totales del Ticket</strong></td>
                                        <td><strong>{{ number_format($ticketData['totales']['cantidad'], 4) }}</strong>
                                        </td>
                                        <td colspan="2"></td>
                                        <td><strong>${{ number_format($ticketData['totales']['importe'], 2) }}</strong>
                                        </td>
                                        <td colspan="6"></td>
                                    </tr>

                                    <!-- Resumen compacto de facturas en una sola fila -->
                                    @if ($tieneFactura && !$esCancelado)
                                        <tr style="background-color: #e7f3ff;">
                                            <td
                                                colspan="12"
                                                class="py-2"
                                            >
                                                <small>
                                                    <strong>📋 Resumen de facturas:</strong>
                                                    @foreach ($ticketData['solicitudes_factura'] as $solicitudId => $solicitud)
                                                        <span class="me-3">
                                                            📄 <strong>{{ $solicitud['id_solicitud'] }}</strong>
                                                            ({{ count($solicitud['lineas']) }} líneas -
                                                            ${{ number_format($solicitud['total_importe'], 2) }})
                                                            @if ($solicitud['nom_cliente'] != 'N/A')
                                                                - {{ $solicitud['nom_cliente'] }}
                                                            @endif
                                                        </span>
                                                    @endforeach
                                                </small>
                                            </td>
                                        </tr>
                                    @endif

                                    <!-- Separador entre tickets -->
                                    <tr>
                                        <td
                                            colspan="12"
                                            style="border-bottom: 2px solid #dee2e6;"
                                        ></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td
                                            colspan="12"
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
                                    <tr style="background-color: #e9ecef;">
                                        <td colspan="2"><strong>Totales Generales:</strong></td>
                                        <td><strong>{{ number_format($totalCantidad, 4) }}</strong></td>
                                        <td colspan="2"></td>
                                        <td><strong>${{ number_format($totalImporte, 2) }}</strong></td>
                                        <td colspan="6"></td>
                                    </tr>
                                    <tr>
                                        <td
                                            colspan="12"
                                            class="text-muted small"
                                        >
                                            * Total de tickets: {{ count($groupedByTicket) }} |
                                            Total de tickets activos: {{ $totalTicketsActivos }} |
                                            Total de tickets cancelados: {{ $totalTicketsCancelados }} |
                                            Total de artículos vendidos (activos): {{ number_format($totalCantidad, 4) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para detalles de factura (opcional) -->
        <div
            class="modal fade"
            id="modalFactura"
            tabindex="-1"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">📄 Detalles de Factura</h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Folio Solicitud:</strong> <span id="modal_solicitud_id"></span></p>
                        <p><strong>Cliente:</strong> <span id="modal_cliente"></span></p>
                        <p><strong>Líneas asociadas:</strong> <span id="modal_lineas"></span></p>
                        <p><strong>Total:</strong> $<span id="modal_total"></span></p>
                        <p><strong>UUID:</strong> <code id="modal_uuid"></code></p>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >Cerrar</button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            onclick="copiarUUIDModal()"
                        >Copiar UUID</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script>
            let currentUUID = '';

            function toggleTicket(ticketId) {
                var items = document.querySelectorAll('.ticket-item-' + ticketId);
                var isHidden = items.length > 0 && items[0].style.display === 'none';
                items.forEach(function(item) {
                    item.style.display = isHidden ? '' : 'none';
                });
            }

            function mostrarDetalleFactura(idSolicitud, cliente, uuid, lineas, total) {
                document.getElementById('modal_solicitud_id').textContent = idSolicitud;
                document.getElementById('modal_cliente').textContent = cliente;
                document.getElementById('modal_lineas').textContent = lineas;
                document.getElementById('modal_total').textContent = total;
                document.getElementById('modal_uuid').textContent = uuid || 'No disponible';
                currentUUID = uuid;

                // Mostrar modal (Bootstrap 5)
                var modal = new bootstrap.Modal(document.getElementById('modalFactura'));
                modal.show();
            }

            function copiarUUIDModal() {
                if (currentUUID && currentUUID !== 'No disponible') {
                    navigator.clipboard.writeText(currentUUID).then(function() {
                        alert('UUID copiado: ' + currentUUID);
                    });
                } else {
                    alert('No hay UUID disponible para copiar');
                }
            }

            function copiarUUID(uuid) {
                navigator.clipboard.writeText(uuid).then(function() {
                    alert('UUID copiado: ' + uuid);
                });
            }
        </script>

        <style>
            .ticket-item-row {
                transition: all 0.3s ease;
            }

            .badge {
                cursor: pointer;
                transition: all 0.2s;
            }

            .badge:hover {
                transform: scale(1.05);
            }

            .table-primary {
                transition: all 0.3s ease;
            }

            .table-primary:hover {
                filter: brightness(0.98);
            }

            /* Tooltip personalizado */
            [title] {
                cursor: help;
            }
        </style> --}}

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
                                    <th>Artículo</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Iva</th>
                                    <th>Importe</th>
                                    <th>Fecha venta</th>
                                    <th>Grupo</th>
                                    <th>Familia</th>
                                    <th>Paquete</th>
                                    <th>Linea</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    // Inicializamos las variables para la suma
                                    $totalCantidad = 0;
                                    $totalIva = 0;
                                    $totalImporte = 0;
                                    $totalTicketsActivos = 0;
                                    $totalTicketsCancelados = 0;

                                    // Agrupar los datos por IdEncabezado (ticket)
                                    $groupedByTicket = [];
                                    foreach ($data as $item) {
                                        $ticketId = $item->IdEncabezado;
                                        if (!isset($groupedByTicket[$ticketId])) {
                                            $groupedByTicket[$ticketId] = [
                                                'items' => [],
                                                'ticket_info' => $item,
                                                'totales' => [
                                                    'cantidad' => 0,
                                                    'iva' => 0,
                                                    'importe' => 0,
                                                ],
                                                'es_cancelado' => false,
                                                'solicitudes_factura' => [], // Array para múltiples solicitudes
                                            ];
                                        }

                                        // Detectar si el ticket está cancelado
                                        if ($item->StatusVenta == 1 && $item->MotivoCancel) {
                                            $groupedByTicket[$ticketId]['es_cancelado'] = true;
                                        }

                                        // Agrupar solicitudes de factura por IdSolicitudFactura
                                        if ($item->IdSolicitudFactura && $item->SolicitudFE == 0) {
                                            $solicitudId = $item->IdSolicitudFactura;
                                            if (
                                                !isset($groupedByTicket[$ticketId]['solicitudes_factura'][$solicitudId])
                                            ) {
                                                $groupedByTicket[$ticketId]['solicitudes_factura'][$solicitudId] = [
                                                    'id_solicitud' => $item->IdSolicitudFactura,
                                                    'nom_cliente' => $item->NomCliente ?? 'N/A',
                                                    'uuid' => $item->UUID ?? 'N/A',
                                                    'lineas' => [],
                                                    'total_importe' => 0,
                                                ];
                                            }
                                            // Agregar línea a esta solicitud
                                            $groupedByTicket[$ticketId]['solicitudes_factura'][$solicitudId][
                                                'lineas'
                                            ][] = $item->Linea;
                                            $groupedByTicket[$ticketId]['solicitudes_factura'][$solicitudId][
                                                'total_importe'
                                            ] += floatval($item->ImporteArticulo);
                                        }

                                        // Agregar item al ticket
                                        $groupedByTicket[$ticketId]['items'][] = $item;

                                        // Acumular totales del ticket
                                        $groupedByTicket[$ticketId]['totales']['cantidad'] += floatval(
                                            $item->CantArticulo,
                                        );
                                        $groupedByTicket[$ticketId]['totales']['iva'] += floatval($item->IvaArticulo);
                                        $groupedByTicket[$ticketId]['totales']['importe'] += floatval(
                                            $item->ImporteArticulo,
                                        );
                                    }

                                    // Contar tickets activos y cancelados
                                    foreach ($groupedByTicket as $ticket) {
                                        if ($ticket['es_cancelado']) {
                                            $totalTicketsCancelados++;
                                        } else {
                                            $totalTicketsActivos++;
                                        }
                                    }

                                    // Ordenar tickets por fecha (más reciente primero)
                                    krsort($groupedByTicket);
                                @endphp

                                @forelse ($groupedByTicket as $ticketId => $ticketData)
                                    @php
                                        $esCancelado = $ticketData['es_cancelado'];
                                        $tieneFactura = count($ticketData['solicitudes_factura']) > 0;
                                        $ticketInfo = $ticketData['ticket_info'];
                                        $headerBgColor = $esCancelado
                                            ? '#f8d7da'
                                            : ($tieneFactura
                                                ? '#d4edda'
                                                : '#e3f2fd');
                                        $headerTextColor = $esCancelado ? '#721c24' : '#000000';
                                        $badgeClass = $esCancelado
                                            ? 'bg-danger'
                                            : ($tieneFactura
                                                ? 'bg-info'
                                                : 'bg-success');
                                        $badgeText = $esCancelado
                                            ? 'CANCELADO'
                                            : ($tieneFactura
                                                ? 'CON FACTURA'
                                                : 'ACTIVO');
                                    @endphp

                                    <!-- Fila de cabecera del ticket -->
                                    <tr
                                        class="table-primary"
                                        style="background-color: {{ $headerBgColor }}; cursor: pointer; color: {{ $headerTextColor }}; border-left: 4px solid {{ $esCancelado ? '#dc3545' : ($tieneFactura ? '#28a745' : '#0d6efd') }};"
                                        onclick="toggleTicket('ticket-{{ $ticketId }}')"
                                    >
                                        <td colspan="11">
                                            <strong>🎫 TICKET #{{ $ticketInfo->IdTicket ?? $ticketId }}</strong>
                                            <span class="badge {{ $badgeClass }} ms-2">{{ $badgeText }}</span>
                                            @if ($tieneFactura)
                                                <span
                                                    class="badge bg-secondary ms-1">{{ count($ticketData['solicitudes_factura']) }}
                                                    solicitud(es)</span>
                                            @endif
                                            - Tienda: {{ $ticketInfo->NomTienda ?? 'N/A' }}
                                            - Fecha:
                                            {{ \Carbon\Carbon::parse($ticketInfo->FechaVenta)->format('d/m/Y H:i:s') }}
                                            - Vendedor: {{ $ticketInfo->NomUsuario ?? 'N/A' }}
                                            - Cliente:
                                            {{ $ticketInfo->NombreEmpleadoComprador ? $ticketInfo->NombreEmpleadoComprador . ' ' . $ticketInfo->ApellidosEmpleadoComprador : 'PÚBLICO GENERAL' }}
                                        </td>
                                    </tr>

                                    <!-- Mensaje de cancelación si el ticket está cancelado -->
                                    @if ($esCancelado)
                                        <tr style="background-color: #fff3cd;">
                                            <td
                                                colspan="11"
                                                class="py-2 ps-4"
                                            >
                                                <small class="text-danger">
                                                    <strong>⚠️ TICKET CANCELADO</strong><br>
                                                    <strong>Fecha Cancelación:</strong>
                                                    {{ \Carbon\Carbon::parse($ticketInfo->FechaCancelacion)->format('d/m/Y H:i:s') }}<br>
                                                    <strong>Usuario Cancelación:</strong>
                                                    {{ $ticketInfo->NombreUsuarioCancelacion . ' ' . $ticketInfo->ApellidoUsuarioCancelacion ?? 'N/A' }}<br>
                                                    <strong>Motivo:</strong>
                                                    {{ $ticketInfo->MotivoCancel ?? 'No especificado' }}
                                                </small>
                                            </td>
                                        </tr>
                                    @endif

                                    <!-- Mostrar todas las solicitudes de factura del ticket -->
                                    @if ($tieneFactura && !$esCancelado)
                                        @foreach ($ticketData['solicitudes_factura'] as $solicitudId => $solicitud)
                                            <tr style="background-color: #d1ecf1;">
                                                <td
                                                    colspan="11"
                                                    class="py-2 ps-4"
                                                >
                                                    <small class="text-primary">
                                                        <strong>📄 SOLICITUD DE FACTURA
                                                            #{{ $loop->iteration }}</strong><br>
                                                        <strong>Folio Solicitud:</strong>
                                                        {{ $solicitud['id_solicitud'] }}<br>
                                                        <strong>Cliente:</strong> {{ $solicitud['nom_cliente'] }}<br>
                                                        <strong>Líneas asociadas:</strong>
                                                        {{ implode(', ', $solicitud['lineas']) }}<br>
                                                        <strong>Total de la solicitud:</strong>
                                                        ${{ number_format($solicitud['total_importe'], 2) }}<br>
                                                        @if ($solicitud['uuid'] && $solicitud['uuid'] != 'N/A')
                                                            <strong>UUID:</strong>
                                                            <code>{{ $solicitud['uuid'] }}</code>
                                                            <button
                                                                class="btn btn-sm btn-outline-primary ms-2"
                                                                onclick="copiarUUID('{{ $solicitud['uuid'] }}')"
                                                                title="Copiar UUID"
                                                            >
                                                                📋 Copiar
                                                            </button>
                                                        @endif
                                                    </small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                    <!-- Items del ticket -->
                                    @foreach ($ticketData['items'] as $item)
                                        @php
                                            // Verificar si este item pertenece a alguna solicitud de factura
                                            $tieneSolicitudItem = $item->IdSolicitudFactura && $item->SolicitudFE == 0;
                                            $bgColorItem = $tieneSolicitudItem ? '#e8f4f8' : '';
                                        @endphp
                                        <tr
                                            style="{{ $esCancelado ? 'background-color: #fef5f5; opacity: 0.9;' : ($bgColorItem ? 'background-color: ' . $bgColorItem . ';' : '') }}">
                                            <td>{{ $item->CodArticulo }}</td>
                                            <td>{{ $item->NomArticulo }}</td>
                                            <td>{{ number_format($item->CantArticulo, 4) }}</td>
                                            <td>${{ number_format($item->PrecioArticulo, 2) }}</td>
                                            <td>{{ number_format($item->IvaArticulo, 2) }}</td>
                                            <td>${{ number_format($item->ImporteArticulo, 2) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->FechaVenta)->format('d/m/Y H:i:s') }}</td>
                                            <td>{{ $item->NomGrupo ?? 'N/A' }}</td>
                                            <td>{{ $item->NomFamilia ?? 'N/A' }}</td>
                                            <td>
                                                @if ($item->NomPaquete)
                                                    {{ $item->NomPaquete }}
                                                @else
                                                    <span class="text-muted">---</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->Linea }}
                                                @if ($tieneSolicitudItem)
                                                    <span
                                                        class="badge bg-info ms-1"
                                                        style="font-size: 0.7rem;"
                                                    >Factura</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @php
                                            // Solo acumular totales si no es cancelado
                                            if (!$esCancelado) {
                                                $totalCantidad += floatval($item->CantArticulo);
                                                $totalIva += floatval($item->IvaArticulo);
                                                $totalImporte += floatval($item->ImporteArticulo);
                                            }
                                        @endphp
                                    @endforeach

                                    <!-- Fila de totales del ticket -->
                                    <tr
                                        class="table-secondary"
                                        style="background-color: {{ $esCancelado ? '#f8d7da' : ($tieneFactura ? '#d4edda' : '#f8f9fa') }};"
                                    >
                                        <td colspan="1"><strong>Totales</strong></td>
                                        <td colspan="1">{{ count($ticketData['items']) }} artículos</td>
                                        <td colspan="1">
                                            <strong>{{ number_format($ticketData['totales']['cantidad'], 4) }}</strong>
                                        </td>
                                        <td colspan="1"></td>
                                        <td colspan="1">
                                            <strong>{{ number_format($ticketData['totales']['iva'], 2) }}</strong>
                                        </td>
                                        <td colspan="1">
                                            <strong>${{ number_format($ticketData['totales']['importe'], 2) }}</strong>
                                        </td>
                                        <td colspan="5"></td>
                                    </tr>

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
                                    <tr style="background-color: #e9ecef;">
                                        <td colspan="2"><strong>Totales Generales:</strong></td>
                                        <td colspan="1"><strong>{{ number_format($totalCantidad, 4) }}</strong></td>
                                        <td colspan="1"></td>
                                        <td colspan="1"><strong>{{ number_format($totalIva, 2) }}</strong></td>
                                        <td colspan="1"><strong>${{ number_format($totalImporte, 2) }}</strong></td>
                                        <td colspan="5"></td>
                                    </tr>
                                    <tr>
                                        <td
                                            colspan="11"
                                            class="text-muted small"
                                        >
                                            * Total de tickets: {{ count($groupedByTicket) }} |
                                            Total de tickets activos: {{ $totalTicketsActivos }} |
                                            Total de tickets cancelados: {{ $totalTicketsCancelados }} |
                                            Total de artículos vendidos (activos): {{ number_format($totalCantidad, 4) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script>
            function toggleTicket(ticketId) {
                var items = document.querySelectorAll('.ticket-item-' + ticketId);
                var isHidden = items.length > 0 && items[0].style.display === 'none';

                items.forEach(function(item) {
                    item.style.display = isHidden ? '' : 'none';
                });
            }

            function copiarUUID(uuid) {
                // Crear un elemento temporal para copiar el texto
                var tempInput = document.createElement('input');
                tempInput.value = uuid;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);

                // Mostrar notificación (opcional)
                alert('UUID copiado: ' + uuid);
            }
        </script>

        <style>
            .ticket-item-row {
                transition: all 0.3s ease;
            }

            /* Estilo para el botón de copiar */
            .btn-outline-primary {
                padding: 2px 8px;
                font-size: 0.75rem;
                line-height: 1;
            }

            .btn-outline-primary:hover {
                background-color: #0d6efd;
                color: white;
            }
        </style> --}}


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
                                    <th>Artículo</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Iva</th>
                                    <th>Importe</th>
                                    <th>Fecha venta</th>
                                    <th>Grupo</th>
                                    <th>Familia</th>
                                    <th>Paquete</th>
                                    <th>Linea</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    // Inicializamos las variables para la suma
                                    $totalCantidad = 0;
                                    $totalIva = 0;
                                    $totalImporte = 0;
                                    $totalTicketsActivos = 0;
                                    $totalTicketsCancelados = 0;

                                    // Agrupar los datos por IdEncabezado (ticket)
                                    $groupedByTicket = [];
                                    foreach ($data as $item) {
                                        $ticketId = $item->IdEncabezado;
                                        if (!isset($groupedByTicket[$ticketId])) {
                                            $groupedByTicket[$ticketId] = [
                                                'items' => [],
                                                'ticket_info' => $item,
                                                'totales' => [
                                                    'cantidad' => 0,
                                                    'iva' => 0,
                                                    'importe' => 0,
                                                ],
                                                'es_cancelado' => false,
                                            ];
                                        }

                                        // Detectar si el ticket está cancelado
                                        if ($item->StatusVenta == 1 && $item->MotivoCancel) {
                                            $groupedByTicket[$ticketId]['es_cancelado'] = true;
                                        }

                                        // Agregar item al ticket
                                        $groupedByTicket[$ticketId]['items'][] = $item;

                                        // Acumular totales del ticket
                                        $groupedByTicket[$ticketId]['totales']['cantidad'] += floatval(
                                            $item->CantArticulo,
                                        );
                                        $groupedByTicket[$ticketId]['totales']['iva'] += floatval($item->IvaArticulo);
                                        $groupedByTicket[$ticketId]['totales']['importe'] += floatval(
                                            $item->ImporteArticulo,
                                        );
                                    }

                                    // Contar tickets activos y cancelados
                                    foreach ($groupedByTicket as $ticket) {
                                        if ($ticket['es_cancelado']) {
                                            $totalTicketsCancelados++;
                                        } else {
                                            $totalTicketsActivos++;
                                        }
                                    }

                                    // Ordenar tickets por fecha (más reciente primero)
                                    krsort($groupedByTicket);
                                @endphp

                                @forelse ($groupedByTicket as $ticketId => $ticketData)
                                    @php
                                        $esCancelado = $ticketData['es_cancelado'];
                                        $ticketInfo = $ticketData['ticket_info'];
                                        $headerBgColor = $esCancelado ? '#f8d7da' : '#e3f2fd';
                                        $headerTextColor = $esCancelado ? '#721c24' : '#000000';
                                        $badgeClass = $esCancelado ? 'bg-danger' : 'bg-success';
                                        $badgeText = $esCancelado ? 'CANCELADO' : 'ACTIVO';
                                    @endphp

                                    <!-- Fila de cabecera del ticket -->
                                    <tr
                                        class="table-primary"
                                        style="background-color: {{ $headerBgColor }}; cursor: pointer; color: {{ $headerTextColor }}; border-left: 4px solid {{ $esCancelado ? '#dc3545' : '#0d6efd' }};"
                                        onclick="toggleTicket('ticket-{{ $ticketId }}')"
                                    >
                                        <td colspan="11">
                                            <strong>🎫 TICKET #{{ $ticketInfo->IdTicket ?? $ticketId }}</strong>
                                            - Tienda: {{ $ticketInfo->NomTienda ?? 'N/A' }}
                                            - Fecha:
                                            {{ \Carbon\Carbon::parse($ticketInfo->FechaVenta)->format('d/m/Y H:i:s') }}
                                            - Vendedor: {{ $ticketInfo->NomUsuario ?? 'N/A' }}
                                            - Cliente:
                                            {{ $ticketInfo->NombreEmpleadoComprador ? $ticketInfo->NombreEmpleadoComprador . ' ' . $ticketInfo->ApellidosEmpleadoComprador : 'PÚBLICO GENERAL' }}
                                        </td>
                                    </tr>

                                    <!-- Mensaje de cancelación si el ticket está cancelado -->
                                    @if ($esCancelado)
                                        <tr style="background-color: #fff3cd;">
                                            <td
                                                colspan="11"
                                                class="py-2 ps-4"
                                            >
                                                <small class="text-danger">
                                                    <strong>⚠️ TICKET CANCELADO</strong><br>
                                                    <strong>Fecha Cancelación:</strong>
                                                    {{ \Carbon\Carbon::parse($ticketInfo->FechaCancelacion)->format('d/m/Y H:i:s') }}<br>
                                                    <strong>Usuario Cancelación:</strong>
                                                    {{ $ticketInfo->NombreUsuarioCancelacion . ' ' . $ticketInfo->ApellidoUsuarioCancelacion ?? 'N/A' }}<br>
                                                    <strong>Motivo:</strong>
                                                    {{ $ticketInfo->MotivoCancel ?? 'No especificado' }}
                                                </small>
                                            </td>
                                        </tr>
                                    @endif

                                    <!-- Items del ticket -->
                                    @foreach ($ticketData['items'] as $item)
                                        <tr style="{{ $esCancelado ? 'background-color: #fef5f5; opacity: 0.9;' : '' }}">
                                            <td>{{ $item->CodArticulo }}</td>
                                            <td>{{ $item->NomArticulo }}</td>
                                            <td>{{ number_format($item->CantArticulo, 4) }}</td>
                                            <td>${{ number_format($item->PrecioArticulo, 2) }}</td>
                                            <td>{{ number_format($item->IvaArticulo, 2) }}</td>
                                            <td>${{ number_format($item->ImporteArticulo, 2) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->FechaVenta)->format('d/m/Y H:i:s') }}</td>
                                            <td>{{ $item->NomGrupo ?? 'N/A' }}</td>
                                            <td>{{ $item->NomFamilia ?? 'N/A' }}</td>
                                            <td>
                                                @if ($item->NomPaquete)
                                                    {{ $item->NomPaquete }}
                                                @else
                                                    <span class="text-muted">---</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->Linea }}</td>
                                        </tr>
                                        @php
                                            // Solo acumular totales si no es cancelado
                                            if (!$esCancelado) {
                                                $totalCantidad += floatval($item->CantArticulo);
                                                $totalIva += floatval($item->IvaArticulo);
                                                $totalImporte += floatval($item->ImporteArticulo);
                                            }
                                        @endphp
                                    @endforeach

                                    <!-- Fila de totales del ticket -->
                                    <tr
                                        class="table-secondary"
                                        style="background-color: {{ $esCancelado ? '#f8d7da' : '#f8f9fa' }};"
                                    >
                                        <td colspan="1"><strong>Totales</strong></td>
                                        <td>{{ count($ticketData['items']) }} artículos</td>
                                        <td><strong>{{ number_format($ticketData['totales']['cantidad'], 4) }}</strong>
                                        </td>
                                        <td></td>
                                        <td><strong>{{ number_format($ticketData['totales']['iva'], 2) }}</strong></td>
                                        <td><strong>${{ number_format($ticketData['totales']['importe'], 2) }}</strong>
                                        </td>
                                        <td colspan="5"></td>
                                    </tr>

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
                                    <tr style="background-color: #e9ecef;">
                                        <td colspan="2"><strong>Totales Generales:</strong></td>
                                        <td><strong>{{ number_format($totalCantidad, 4) }}</strong></td>
                                        <td></td>
                                        <td><strong>{{ number_format($totalIva, 2) }}</strong></td>
                                        <td><strong>${{ number_format($totalImporte, 2) }}</strong></td>
                                        <td colspan="5"></td>
                                    </tr>
                                    <tr>
                                        <td
                                            colspan="11"
                                            class="text-muted small"
                                        >
                                            * Total de tickets: {{ count($groupedByTicket) }} |
                                            Total de tickets activos: {{ $totalTicketsActivos }} |
                                            Total de tickets cancelados: {{ $totalTicketsCancelados }} |
                                            Total de artículos vendidos (activos): {{ number_format($totalCantidad, 4) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reemplaza el script y el estilo con esto: -->
        <script>
            function toggleTicket(ticketId) {
                var items = document.querySelectorAll('.ticket-item-' + ticketId);
                var isHidden = items.length > 0 && items[0].style.display === 'none';

                items.forEach(function(item) {
                    item.style.display = isHidden ? '' : 'none';
                });
            }
        </script>

        <style>
            .ticket-item-row {
                transition: all 0.3s ease;
            }
        </style> --}}

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
                                    <th>Artículo</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Iva</th>
                                    <th>Importe</th>
                                    <th>Fecha venta</th>
                                    <th>Familia</th>
                                    <th>Paquete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Inicializamos las variables para la suma
                                    $totalCantidad = 0;
                                    $totalIva = 0;
                                    $totalImporte = 0;

                                    // Agrupar los datos por IdEncabezado (ticket)
                                    $groupedByTicket = [];
                                    foreach ($data as $item) {
                                        $ticketId = $item->IdEncabezado;
                                        if (!isset($groupedByTicket[$ticketId])) {
                                            $groupedByTicket[$ticketId] = [
                                                'items' => [],
                                                'ticket_info' => $item,
                                                'totales' => [
                                                    'cantidad' => 0,
                                                    'iva' => 0,
                                                    'importe' => 0,
                                                ],
                                            ];
                                        }

                                        // Agregar item al ticket
                                        $groupedByTicket[$ticketId]['items'][] = $item;

                                        // Acumular totales del ticket
                                        $groupedByTicket[$ticketId]['totales']['cantidad'] += floatval(
                                            $item->CantArticulo,
                                        );
                                        $groupedByTicket[$ticketId]['totales']['iva'] += floatval($item->IvaArticulo);
                                        $groupedByTicket[$ticketId]['totales']['importe'] += floatval(
                                            $item->ImporteArticulo,
                                        );
                                    }

                                    // Ordenar tickets por fecha (más reciente primero)
                                    krsort($groupedByTicket);
                                @endphp

                                @forelse ($groupedByTicket as $ticketId => $ticketData)
                                    <!-- Fila de cabecera del ticket -->
                                    <tr
                                        class="table-primary"
                                        style="background-color: #e3f2fd; cursor: pointer;"
                                        onclick="toggleTicket('ticket-{{ $ticketId }}')"
                                    >
                                        <td colspan="9">
                                            <strong>🎫 TICKET
                                                #{{ $ticketData['ticket_info']->IdTicket ?? $ticketId }}</strong>
                                            - Tienda: {{ $ticketData['ticket_info']->NomTienda ?? 'N/A' }}
                                            - Fecha:
                                            {{ \Carbon\Carbon::parse($ticketData['ticket_info']->FechaVenta)->format('d/m/Y H:i:s') }}
                                            - Vendedor: {{ $ticketData['ticket_info']->NomUsuario ?? 'N/A' }}
                                            - Cliente:
                                            {{ $ticketData['ticket_info']->NombreEmpleadoComprador ? $ticketData['ticket_info']->NombreEmpleadoComprador . ' ' . $ticketData['ticket_info']->ApellidosEmpleadoComprador : 'PÚBLICO GENERAL' }}
                                        </td>
                                    </tr>

                                    <!-- Items del ticket (inicialmente ocultos) -->
                            <tbody
                                id="ticket-{{ $ticketId }}"
                                class="ticket-items"
                                style="display: table-row-group;"
                            >
                                @foreach ($ticketData['items'] as $item)
                                    <tr>
                                        <td>{{ $item->CodArticulo }}</td>
                                        <td>{{ $item->NomArticulo }}</td>
                                        <td>{{ number_format($item->CantArticulo, 4) }}</td>
                                        <td>${{ number_format($item->PrecioArticulo, 2) }}</td>
                                        <td>{{ number_format($item->IvaArticulo, 2) }}</td>
                                        <td>${{ number_format($item->ImporteArticulo, 2) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->FechaVenta)->format('d/m/Y H:i:s') }}</td>
                                        <td>{{ $item->NomFamilia ?? 'N/A' }}</td>
                                        <td>
                                            @if ($item->NomPaquete)
                                                {{ $item->NomPaquete }}
                                            @else
                                                <span class="text-muted">---</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                        // Acumulamos los valores totales generales
                                        $totalCantidad += floatval($item->CantArticulo);
                                        $totalIva += floatval($item->IvaArticulo);
                                        $totalImporte += floatval($item->ImporteArticulo);
                                    @endphp
                                @endforeach

                                <!-- Fila de totales del ticket -->
                                <tr
                                    class="table-secondary"
                                    style="background-color: #f8f9fa;"
                                >
                                    <td colspan=""><strong>Totales</strong></td>
                                    <td>{{ count($ticketData['items']) }} articulos</td>
                                    <td><strong>{{ number_format($ticketData['totales']['cantidad'], 4) }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ number_format($ticketData['totales']['iva'], 2) }}</strong></td>
                                    <td><strong>${{ number_format($ticketData['totales']['importe'], 2) }}</strong></td>
                                    <td colspan="3"></td>
                                </tr>
                            </tbody>

                            <!-- Separador entre tickets -->
                            <tr>
                                <td
                                    colspan="9"
                                    style="border-bottom: 2px solid #dee2e6;"
                                ></td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="9"
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
                                    <tr style="background-color: #e9ecef;">
                                        <td colspan="2"><strong>Totales Generales:</strong></td>
                                        <td><strong>{{ number_format($totalCantidad, 4) }}</strong></td>
                                        <td></td>
                                        <td><strong>{{ number_format($totalIva, 2) }}</strong></td>
                                        <td><strong>${{ number_format($totalImporte, 2) }}</strong></td>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <td
                                            colspan="9"
                                            class="text-muted small"
                                        >
                                            * Total de tickets: {{ count($groupedByTicket) }} |
                                            Total de artículos vendidos: {{ number_format($totalCantidad, 4) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}

        <script>
            function toggleTicket(ticketId) {
                var element = document.getElementById(ticketId);
                if (element.style.display === 'none') {
                    element.style.display = 'table-row-group';
                } else {
                    element.style.display = 'none';
                }
            }
        </script>

        <style>
            .ticket-items tr:hover {
                background-color: #f5f5f5;
            }

            .table-primary {
                cursor: pointer;
                transition: background-color 0.3s;
            }

            .table-primary:hover {
                background-color: #d0e4f5 !important;
            }
        </style>
    </x-layout.page-container>
@endsection
