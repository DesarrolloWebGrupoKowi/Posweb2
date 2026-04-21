@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Dashboard de Ventas a Empleados')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <x-layout.page-container>

        <!-- SECCIÓN 1: FILTROS -->
        <x-layout.section-card>
            <!-- Título y botones principales -->
            <div class="d-flex justify-content-sm-between align-items-end align-items-sm-start flex-column flex-sm-row mb-2">
                <x-title titulo="Reporte de Ventas a Empleados" />
                <div class="d-flex gap-2">
                    <x-filters.buttons.excel-button
                        route="/VentaEmpleadosExcel"
                        :params="[
                            'fecha1' => request('fecha1'),
                            'fecha2' => request('fecha2'),
                            'chkNomina' => request('chkNomina'),
                            'numNomina' => request('numNomina'),
                            'idTienda' => request('idTienda'),
                            'tipoNomina' => request('tipoNomina'),
                            'fechaInterfaz' => request('fechaInterfaz'),
                            'codigoInterfaz' => request('codigoInterfaz'),
                            'soloAdeudos' => request('soloAdeudos'),
                        ]"
                    />
                    <x-filters.buttons.refresh-button />
                    <x-filters.buttons.home-button />
                </div>
            </div>

            <!-- Formulario de filtros -->
            <x-filters.filter-form>
                <!-- Filtros Básicos -->
                <x-filters.filter-group>
                    <x-filters.inputs.date-input
                        name="fecha1"
                        label="Fecha Inicio"
                        :value="request('fecha1')"
                        :autofocus="true"
                    />
                    <x-filters.inputs.date-input
                        name="fecha2"
                        label="Fecha Fin"
                        :value="request('fecha2')"
                    />
                    <x-filters.inputs.employee-search
                        :checkboxChecked="request('chkNomina') == 'on'"
                        :numberValue="request('numNomina')"
                    />
                    <x-filters.inputs.checkbox-input
                        name="soloAdeudos"
                        label="Solo Adeudos"
                        :checked="request('soloAdeudos') == 'on'"
                        helperText="Crédito pendiente"
                    />
                </x-filters.filter-group>

                <!-- Filtros Avanzados -->
                <x-filters.advanced-collapse
                    :active="$filtrosAvanzadosActivos"
                    :showBadge="true"
                >
                    <x-filters.filter-group>
                        <x-filters.inputs.select-input
                            name="idTienda"
                            label="Tienda"
                            :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                            compact="true"
                        />
                        <x-filters.inputs.select-input
                            name="tipoNomina"
                            label="Tipo Nómina"
                            :options="['3' => 'Semanal', '4' => 'Quincenal']"
                            compact="true"
                        />
                        {{-- <x-filters.inputs.date-input
                            name="fechaInterfaz"
                            label="Fecha Interfaz"
                            compact="true"
                        /> --}}
                        <x-filters.inputs.text-input
                            name="codigoInterfaz"
                            label="Código Interfaz"
                            placeholder="Código interfaz"
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
            <div class="row g-4">
                <x-kpi.kpi-card
                    title="Total Ventas"
                    :value="$ventasEmpleado->count()"
                    subtitle="Transacciones"
                    color="primary"
                    icon="components.icons.shopping-cart"
                />

                <x-kpi.kpi-card
                    title="Ticket Promedio"
                    :value="$ventasEmpleado->count() > 0 ? $importeTotal / $ventasEmpleado->count() : 0"
                    subtitle="Por transacción"
                    color="success"
                    icon="components.icons.ticket"
                    currency="true"
                />

                <x-kpi.kpi-card
                    title="Adeudo"
                    :value="$importeCredito"
                    :subtitle="$importeTotal > 0
                        ? round(($importeCredito / $importeTotal) * 100, 1) . '% del total'
                        : 'Sin deuda'"
                    color="info"
                    icon="components.icons.credit-card"
                    currency="true"
                />

                <x-kpi.kpi-card
                    title="Importe Total"
                    :value="$importeTotal"
                    subtitle="MXN"
                    color="danger"
                    icon="components.icons.dolar"
                    currency="true"
                />
            </div>
        </div>

        <!-- SECCIÓN 3: GRÁFICAS Y TABLAS -->
        <div
            class="flex-grow-1 d-flex gap-4"
            style="min-height: 0;"
        >
            <!-- SECCIÓN 3.1: Tabla -->
            <div
                class="d-flex flex-column"
                style="flex: 2; min-width: 0; min-height: 0;"
            >
                <div
                    class="card d-flex flex-column border-0 p-4"
                    style="border-radius: 10px; min-height: 0;"
                >
                    <!--Header tabla-->
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-shrink-0">
                        <h5 class="mb-0 text-gray-800">VENTAS A EMPLEADOS</h5>
                        <div class="d-flex gap-2">
                            <div class="btn-group">
                                <button
                                    class="btn btn-sm btn-outline-dark active"
                                    id="btnVistaTabla"
                                    onclick="cambiarVista('tabla')"
                                >📋 Vista Tabla</button>
                                <button
                                    class="btn btn-sm btn-outline-dark"
                                    id="btnVistaTickets"
                                    onclick="cambiarVista('tickets')"
                                >🎫 Vista Tickets</button>
                            </div>
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
                            <form
                                action="/VentaEmpleadosExcel"
                                method="GET"
                            >
                                <input
                                    type="hidden"
                                    name="fecha1"
                                    value="{{ request('fecha1', date('Y-m-d')) }}"
                                >
                                <input
                                    type="hidden"
                                    name="fecha2"
                                    value="{{ request('fecha2', date('Y-m-d')) }}"
                                >
                                <input
                                    type="hidden"
                                    name="chkNomina"
                                    value="{{ request('chkNomina') }}"
                                >
                                <input
                                    type="hidden"
                                    name="numNomina"
                                    value="{{ request('numNomina') }}"
                                >
                                <input
                                    type="hidden"
                                    name="idTienda"
                                    value="{{ request('idTienda') }}"
                                >
                                <input
                                    type="hidden"
                                    name="codArticulo"
                                    value="{{ request('codArticulo') }}"
                                >
                                <input
                                    type="hidden"
                                    name="fechaInterfaz"
                                    value="{{ request('fechaInterfaz') }}"
                                >
                                <input
                                    type="hidden"
                                    name="codigoInterfaz"
                                    value="{{ request('codigoInterfaz') }}"
                                >
                                <input
                                    type="hidden"
                                    name="soloAdeudos"
                                    value="{{ request('soloAdeudos') }}"
                                >
                                <button class="btn btn-sm btn-outline-dark btn-outline-dark-green">
                                    <span class="d-flex align-items-center gap-2">@include('components.icons.excel')
                                        Descargar</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Contenido detallado de las ventas -->
                    <div
                        id="vistaTabla"
                        class="flex-grow-1 table-responsive content-table-sm overflow-auto"
                        style="min-height: 0;"
                    >
                        <table class="table">
                            <thead class="table-head">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tienda</th>
                                    <th>Nómina</th>
                                    <th>Empleado</th>
                                    <th>Empresa</th>
                                    <th>Ticket</th>
                                    <th>Código</th>
                                    <th>Artículo</th>
                                    <th>Importe</th>
                                    <th>Tipo</th>
                                    <th>Pago</th>
                                    <th>Crédito</th>
                                    <th>Interfaz</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ventasEmpleado as $ventaEmpleado)
                                    <tr>
                                        <td style="min-width: 140px;">
                                            {{ strftime('%d %B %Y, %H:%M', strtotime($ventaEmpleado->FechaVenta)) }}</td>
                                        <td style="min-width: 160px;">{{ $ventaEmpleado->NomTienda }}</td>
                                        <td>{{ $ventaEmpleado->NumNomina }}</td>
                                        <td style="min-width: 160px;">{{ $ventaEmpleado->Nombre }}
                                            {{ $ventaEmpleado->Apellidos }}</td>
                                        <td style="min-width: 160px;">{{ $ventaEmpleado->Empresa }}</td>
                                        <td>{{ $ventaEmpleado->IdTicket }}</td>
                                        <td>{{ $ventaEmpleado->CodArticulo }}</td>
                                        <td style="min-width: 200px;">{{ $ventaEmpleado->NomArticulo }}</td>
                                        <td>{{ number_format($ventaEmpleado->ImporteArticulo, 2) }}</td>
                                        <td>{{ ['' => '', 3 => 'SEMANAL', 4 => 'QUINCENAL'][$ventaEmpleado->TipoNomina] }}
                                        <td>{{ $ventaEmpleado->NomTipoPago }}
                                        </td>
                                        <td>
                                            @if ($ventaEmpleado->StatusCredito == '0')
                                                @include('components.icons.check-all')
                                            @endif
                                        </td>
                                        <td>{{ $ventaEmpleado->IdHistorialCredito }}</td>
                                        <td style="min-width: 140px;">
                                            @if ($ventaEmpleado->FechaInterfaz != null)
                                                {{ strftime('%d %B %Y, %H:%M', strtotime($ventaEmpleado->FechaInterfaz)) }}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td
                                            colspan="13"
                                            class="py-5 text-center"
                                        >
                                            <x-table-empty-state
                                                title="No hay ventas para mostrar"
                                                icon="credit-card"
                                                :message="'No se encontraron ventas realizadas por empleados en el período seleccionado.'"
                                                :suggestion="'Prueba cambiando las fechas o los filtros de búsqueda para ver más resultados.'"
                                                action="Resetear filtros"
                                                actionUrl="/VentaEmpleados"
                                            />
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Contenido agrupado por encabezado (tickets) -->
                    <div
                        id="vistaTickets"
                        class="flex-grow-1 table-responsive content-table-sm overflow-auto"
                        style="display: none;"
                    >
                        @php $encabezadosGrouped = $ventasEmpleado->groupBy('IdEncabezado'); @endphp
                        <table class="table">
                            <thead class="table-head">
                                <tr>
                                    <th style="width: 40px;"></th>
                                    <th>Fecha</th>
                                    <th>Tienda</th>
                                    <th>Nómina</th>
                                    <th>Empleado</th>
                                    <th>Empresa</th>
                                    <th>Total Artículos</th>
                                    <th>Importe Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($encabezadosGrouped as $encabezadoId => $items)
                                    @php
                                        $firstItem = $items->first();
                                        $encabezadoTotal = $items->sum('ImporteArticulo');
                                        $fechaEncabezado = strftime(
                                            '%d %B %Y, %H:%M',
                                            strtotime($firstItem->FechaVenta),
                                        );
                                    @endphp
                                    <!-- Fila principal del encabezado -->
                                    <tr
                                        data-bs-toggle="collapse"
                                        data-bs-target="#detalleEncabezado{{ $encabezadoId }}"
                                        aria-expanded="false"
                                        aria-controls="detalleEncabezado{{ $encabezadoId }}"
                                        style="cursor: pointer;"
                                    >
                                        <td class="text-center">
                                            @include('components.icons.down', [
                                                'width' => 18,
                                                'height' => 18,
                                            ])
                                        </td>

                                        <td style="min-width: 140px;">{{ $fechaEncabezado }}</td>
                                        <td style="min-width: 160px;">{{ $firstItem->NomTienda }}</td>
                                        <td>{{ $firstItem->NumNomina }}</td>
                                        <td style="min-width: 160px;">{{ $firstItem->Nombre }}
                                            {{ $firstItem->Apellidos }}</td>
                                        <td style="min-width: 160px;">{{ $firstItem->Empresa }}</td>
                                        <td>{{ $items->count() }}</td>
                                        <td class="fw-bold text-success">${{ number_format($encabezadoTotal, 2) }}
                                        </td>
                                    </tr>

                                    <!-- Fila de detalle (colapsable) -->
                                    <tr>
                                        <td
                                            colspan="8"
                                            class="p-0"
                                        >
                                            <div
                                                class="collapse"
                                                id="detalleEncabezado{{ $encabezadoId }}"
                                            >
                                                <div
                                                    class="p-3"
                                                    style="background: #f8f9fa; border-top: 1px solid #dee2e6;"
                                                >
                                                    <h6 class="mb-3">Detalle de artículos - Encabezado
                                                        #{{ $encabezadoId }}</h6>
                                                    <table class="table-sm mb-0 table">
                                                        <thead class="table-head">
                                                            <tr>
                                                                <th>Ticket</th>
                                                                <th>Código</th>
                                                                <th>Artículo</th>
                                                                <th class="text-end">Importe</th>
                                                                <th>Tipo Nómina</th>
                                                                <th>Tipo pago</th>
                                                                <th>Crédito</th>
                                                                <th>Interfaz</th>
                                                                <th>Fecha Interfaz</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($items as $item)
                                                                <tr>
                                                                    <td>{{ $item->IdTicket }}</td>
                                                                    <td>{{ $item->CodArticulo }}</td>
                                                                    <td style="min-width: 200px;">
                                                                        {{ $item->NomArticulo }}</td>
                                                                    <td class="text-end">
                                                                        ${{ number_format($item->ImporteArticulo, 2) }}
                                                                    </td>
                                                                    <td>{{ ['' => '', 3 => 'SEMANAL', 4 => 'QUINCENAL'][$item->TipoNomina] ?? '' }}
                                                                    </td>
                                                                    <td>{{ $item->NomTipoPago }}</td>
                                                                    <td>
                                                                        @if ($item->StatusCredito == '0')
                                                                            <span class="badge bg-info">
                                                                                Crédito
                                                                            </span>
                                                                        @endif
                                                                        @if ($item->StatusCredito == '1')
                                                                            <span class="badge bg-success">
                                                                                Pagado
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $item->IdHistorialCredito }}</td>
                                                                    <td style="min-width: 140px;">
                                                                        @if ($item->FechaInterfaz != null)
                                                                            {{ strftime('%d %B %Y, %H:%M', strtotime($item->FechaInterfaz)) }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr style="background-color: #e9ecef;">
                                                                <td
                                                                    colspan="3"
                                                                    class="fw-bold text-end"
                                                                >Total Encabezado:</td>
                                                                <td class="fw-bold text-success text-end">
                                                                    ${{ number_format($encabezadoTotal, 2) }}</td>
                                                                <td colspan="5"></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- <div
                        id="vistaTickets"
                        style="display: none;"
                    >
                        @php $encabezadosGrouped = $ventasEmpleado->groupBy('IdEncabezado'); @endphp
                        @if ($encabezadosGrouped->count() == 0)
                            <div class="py-5 text-center">
                                <p class="text-muted">No hay datos para mostrar</p>
                            </div>
                        @else
                            <div
                                class="accordion"
                                id="accordionEncabezados"
                            >
                                @foreach ($encabezadosGrouped as $encabezadoId => $items)
                                    @php
                                        $firstItem = $items->first();
                                        $encabezadoTotal = $items->sum('ImporteArticulo');
                                        $fechaEncabezado = strftime(
                                            '%d %B %Y, %H:%M',
                                            strtotime($firstItem->FechaVenta),
                                        );
                                    @endphp
                                    <div
                                        class="accordion-item rounded-3 mb-3 overflow-hidden border"
                                        style="border-color: #e5e7eb;"
                                    >
                                        <!-- Header con tabla de resumen -->
                                        <div class="accordion-header">
                                            <button
                                                class="accordion-button collapsed p-0"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#encabezado{{ $encabezadoId }}"
                                                style="background: transparent;"
                                            >
                                                <div class="w-100">
                                                    <table
                                                        class="table-sm mb-0 table"
                                                        style="background: #f9fafb;"
                                                    >
                                                        <thead class="table-head">
                                                            <tr>
                                                                <th style="width: 40px;"></th>
                                                                <th>Fecha</th>
                                                                <th>Tienda</th>
                                                                <th>Nómina</th>
                                                                <th>Empleado</th>
                                                                <th>Empresa</th>
                                                                <th>Total Artículos</th>
                                                                <th>Importe Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <i class="fas fa-chevron-down"></i>
                                                                </td>
                                                                <td style="min-width: 140px;">{{ $fechaEncabezado }}</td>
                                                                <td style="min-width: 160px;">{{ $firstItem->NomTienda }}
                                                                </td>
                                                                <td>{{ $firstItem->NumNomina }}</td>
                                                                <td style="min-width: 160px;">{{ $firstItem->Nombre }}
                                                                    {{ $firstItem->Apellidos }}</td>
                                                                <td style="min-width: 160px;">{{ $firstItem->Empresa }}
                                                                </td>
                                                                <td>{{ $items->count() }}</td>
                                                                <td class="fw-bold text-success">
                                                                    ${{ number_format($encabezadoTotal, 2) }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </button>
                                        </div>

                                        <!-- Contenido desplegable con detalle de artículos -->
                                        <div
                                            id="encabezado{{ $encabezadoId }}"
                                            class="accordion-collapse collapse"
                                            data-bs-parent="#accordionEncabezados"
                                        >
                                            <div class="accordion-body p-0">
                                                <div class="table-responsive">
                                                    <table class="table-sm mb-0 table">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Ticket</th>
                                                                <th>Código</th>
                                                                <th>Artículo</th>
                                                                <th class="text-end">Importe</th>
                                                                <th>Tipo Nómina</th>
                                                                <th>Crédito</th>
                                                                <th>Interfaz</th>
                                                                <th>Fecha Interfaz</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($items as $item)
                                                                <tr>
                                                                    <td>{{ $item->IdTicket }}</td>
                                                                    <td>{{ $item->CodArticulo }}</td>
                                                                    <td style="min-width: 200px;">{{ $item->NomArticulo }}
                                                                    </td>
                                                                    <td class="text-end">
                                                                        ${{ number_format($item->ImporteArticulo, 2) }}
                                                                    </td>
                                                                    <td>{{ ['' => '', 3 => 'SEMANAL', 4 => 'QUINCENAL'][$item->TipoNomina] ?? '' }}
                                                                    </td>
                                                                    <td>
                                                                        @if ($item->StatusCredito == '0')
                                                                            <span class="badge bg-info">
                                                                                <i class="fas fa-check-circle"></i> Crédito
                                                                            </span>
                                                                        @else
                                                                            <span class="badge bg-secondary">Contado</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $item->IdHistorialCredito }}</td>
                                                                    <td style="min-width: 140px;">
                                                                        @if ($item->FechaInterfaz != null)
                                                                            {{ strftime('%d %B %Y, %H:%M', strtotime($item->FechaInterfaz)) }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot class="table-light">
                                                            <tr>
                                                                <td
                                                                    colspan="3"
                                                                    class="fw-bold text-end"
                                                                >Total Encabezado:</td>
                                                                <td class="fw-bold text-success text-end">
                                                                    ${{ number_format($encabezadoTotal, 2) }}</td>
                                                                <td colspan="4"></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div> --}}

                    {{-- <div
                        id="vistaTickets"
                        style="display: none;"
                    >
                        @php $encabezadosGrouped = $ventasEmpleado->groupBy('IdEncabezado'); @endphp
                        @if ($encabezadosGrouped->count() == 0)
                            <div class="py-5 text-center">
                                <p class="text-muted">No hay datos para mostrar</p>
                            </div>
                        @else
                            <div
                                class="accordion"
                                id="accordionEncabezados"
                            >
                                @foreach ($encabezadosGrouped as $encabezadoId => $items)
                                    @php
                                        $firstItem = $items->first();
                                        $encabezadoTotal = $items->sum('ImporteArticulo');
                                    @endphp
                                    <div
                                        class="accordion-item rounded-3 mb-3 overflow-hidden border"
                                        style="border-color: #e5e7eb;"
                                    >
                                        <!-- Header con tabla de resumen (similar a la vista detallada) -->
                                        <div class="accordion-header">
                                            <button
                                                class="accordion-button collapsed p-0"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#encabezado{{ $encabezadoId }}"
                                                style="background: transparent;"
                                            >
                                                <div class="w-100">
                                                    <table
                                                        class="table-sm mb-0 table"
                                                        style="background: #f9fafb;"
                                                    >
                                                        <thead class="table-head">
                                                            <tr>
                                                                <th style="width: 30px;"></th>
                                                                <th>Fecha</th>
                                                                <th>Tienda</th>
                                                                <th>Nómina</th>
                                                                <th>Empleado</th>
                                                                <th>Empresa</th>
                                                                <th>Ticket</th>
                                                                <th>Código</th>
                                                                <th>Artículo</th>
                                                                <th>Importe</th>
                                                                <th>Tipo</th>
                                                                <th>Crédito</th>
                                                                <th>Interfaz</th>
                                                                <th>Fecha Interfaz</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($items as $index => $item)
                                                                <tr>
                                                                    @if ($loop->first)
                                                                        <td
                                                                            rowspan="{{ $items->count() }}"
                                                                            style="vertical-align: middle; text-align: center;"
                                                                        >
                                                                            <i class="fas fa-chevron-down"></i>
                                                                        </td>
                                                                    @endif
                                                                    <td style="min-width: 140px;">
                                                                        {{ strftime('%d %B %Y, %H:%M', strtotime($item->FechaVenta)) }}
                                                                    </td>
                                                                    <td style="min-width: 160px;">{{ $item->NomTienda }}
                                                                    </td>
                                                                    <td>{{ $item->NumNomina }}</td>
                                                                    <td style="min-width: 160px;">{{ $item->Nombre }}
                                                                        {{ $item->Apellidos }}</td>
                                                                    <td style="min-width: 160px;">{{ $item->Empresa }}
                                                                    </td>
                                                                    <td>{{ $item->IdTicket }}</td>
                                                                    <td>{{ $item->CodArticulo }}</td>
                                                                    <td style="min-width: 200px;">{{ $item->NomArticulo }}
                                                                    </td>
                                                                    <td>${{ number_format($item->ImporteArticulo, 2) }}
                                                                    </td>
                                                                    <td>{{ ['' => '', 3 => 'SEMANAL', 4 => 'QUINCENAL'][$item->TipoNomina] ?? '' }}
                                                                    </td>
                                                                    <td>
                                                                        @if ($item->StatusCredito == '0')
                                                                            @include('components.icons.check-all')
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $item->IdHistorialCredito }}</td>
                                                                    <td style="min-width: 140px;">
                                                                        @if ($item->FechaInterfaz != null)
                                                                            {{ strftime('%d %B %Y, %H:%M', strtotime($item->FechaInterfaz)) }}
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            <!-- Fila de total -->
                                                            <tr style="background-color: #f3f4f6; font-weight: bold;">
                                                                <td
                                                                    colspan="9"
                                                                    class="text-end"
                                                                >Total Encabezado:</td>
                                                                <td class="text-success">
                                                                    ${{ number_format($encabezadoTotal, 2) }}</td>
                                                                <td colspan="4"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </button>
                                        </div>

                                        <!-- Aquí va el contenido detallado adicional si es necesario -->
                                        <div
                                            id="encabezado{{ $encabezadoId }}"
                                            class="accordion-collapse collapse"
                                            data-bs-parent="#accordionEncabezados"
                                        >
                                            <div class="accordion-body bg-light p-3">
                                                <p class="text-muted mb-0">
                                                    <i class="fas fa-info-circle"></i>
                                                    Detalles adicionales del encabezado #{{ $encabezadoId }}
                                                    - Total de artículos: {{ $items->count() }}
                                                    - Importe total: ${{ number_format($encabezadoTotal, 2) }}
                                                </p>
                                                <!-- Aquí puedes agregar más información detallada si lo necesitas -->
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div> --}}

                    {{-- <div
                        id="vistaTickets"
                        style="display: none;"
                    >
                        @php $encabezadosGrouped = $ventasEmpleado->groupBy('IdEncabezado'); @endphp
                        @if ($encabezadosGrouped->count() == 0)
                            <div class="py-5 text-center">
                                <p class="text-muted">No hay datos para mostrar</p>
                            </div>
                        @else
                            <div
                                class="accordion"
                                id="accordionEncabezados"
                            >
                                @foreach ($encabezadosGrouped as $encabezadoId => $items)
                                    @php
                                        $firstItem = $items->first();
                                        $encabezadoTotal = $items->sum('ImporteArticulo');
                                        $fechaEncabezado = strftime(
                                            '%d %B %Y, %H:%M',
                                            strtotime($firstItem->FechaVenta),
                                        );
                                    @endphp
                                    <div
                                        class="accordion-item rounded-3 mb-3 overflow-hidden border"
                                        style="border-color: #e5e7eb;"
                                    >
                                        <h2 class="accordion-header">
                                            <button
                                                class="accordion-button collapsed bg-light"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#encabezado{{ $encabezadoId }}"
                                                style="background: #f9fafb;"
                                            >
                                                <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                                    <div class="d-flex align-items-center gap-4">
                                                        <span class="badge bg-primary rounded-pill">Encabezado
                                                            #{{ $encabezadoId }}</span>
                                                        <span><i class="far fa-calendar-alt"></i>
                                                            {{ $fechaEncabezado }}</span>
                                                        <span><i class="fas fa-store"></i>
                                                            {{ $firstItem->NomTienda }}</span>
                                                        <span><i class="fas fa-user"></i> {{ $firstItem->Nombre }}
                                                            {{ $firstItem->Apellidos }}</span>
                                                        <span><i class="fas fa-id-card"></i> Nómina:
                                                            {{ $firstItem->NumNomina }}</span>
                                                    </div>
                                                    <div class="d-flex gap-3">
                                                        <span
                                                            class="fw-bold text-success">${{ number_format($encabezadoTotal, 2) }}</span>
                                                        <span class="badge bg-secondary">{{ $items->count() }}
                                                            artículo(s)</span>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div
                                            id="encabezado{{ $encabezadoId }}"
                                            class="accordion-collapse collapse"
                                            data-bs-parent="#accordionEncabezados"
                                        >
                                            <div class="accordion-body p-0">
                                                <!-- Tabla similar a la vista detallada -->
                                                <div class="table-responsive">
                                                    <table class="table-sm mb-0 table">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Ticket</th>
                                                                <th>Código</th>
                                                                <th>Artículo</th>
                                                                <th class="text-end">Importe</th>
                                                                <th>Tipo Nómina</th>
                                                                <th>Crédito</th>
                                                                <th>Interfaz</th>
                                                                <th>Fecha Interfaz</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($items as $item)
                                                                <tr>
                                                                    <td>{{ $item->IdTicket }}</td>
                                                                    <td>{{ $item->CodArticulo }}</td>
                                                                    <td style="min-width: 200px;">{{ $item->NomArticulo }}
                                                                    </td>
                                                                    <td class="text-end">
                                                                        ${{ number_format($item->ImporteArticulo, 2) }}
                                                                    </td>
                                                                    <td>{{ ['' => '', 3 => 'SEMANAL', 4 => 'QUINCENAL'][$item->TipoNomina] ?? '' }}
                                                                    </td>
                                                                    <td>
                                                                        @if ($item->StatusCredito == '0')
                                                                            <span class="badge bg-info">
                                                                                <i class="fas fa-check-circle"></i> Crédito
                                                                            </span>
                                                                        @else
                                                                            <span class="badge bg-secondary">Contado</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $item->IdHistorialCredito }}</td>
                                                                    <td style="min-width: 140px;">
                                                                        @if ($item->FechaInterfaz != null)
                                                                            {{ strftime('%d %B %Y, %H:%M', strtotime($item->FechaInterfaz)) }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot class="table-light">
                                                            <tr>
                                                                <td
                                                                    colspan="3"
                                                                    class="fw-bold text-end"
                                                                >Total Encabezado:</td>
                                                                <td class="fw-bold text-success text-end">
                                                                    ${{ number_format($encabezadoTotal, 2) }}</td>
                                                                <td colspan="4"></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div> --}}

                    {{-- <div
                        id="vistaTickets"
                        style="display: none;"
                    >
                        @php $ticketsGrouped = $ventasEmpleado->groupBy('IdTicket'); @endphp
                        @if ($ticketsGrouped->count() == 0)
                            <div class="py-5 text-center">
                                <p class="text-muted">No hay datos para mostrar</p>
                            </div>
                        @else
                            <div
                                class="accordion"
                                id="accordionTickets"
                            >
                                @foreach ($ticketsGrouped as $ticketId => $items)
                                    @php
                                        $firstItem = $items->first();
                                        $ticketTotal = $items->sum('ImporteArticulo');
                                        $fechaTicket = strftime('%d %B %Y, %H:%M', strtotime($firstItem->FechaVenta));
                                    @endphp
                                    <div
                                        class="accordion-item rounded-3 mb-3 overflow-hidden border"
                                        style="border-color: #e5e7eb;"
                                    >
                                        <h2 class="accordion-header">
                                            <button
                                                class="accordion-button collapsed bg-light"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#ticket{{ $ticketId }}"
                                                style="background: #f9fafb;"
                                            >
                                                <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                                    <div class="d-flex align-items-center gap-4">
                                                        <span class="badge bg-primary rounded-pill">Ticket
                                                            #{{ $ticketId }}</span>
                                                        <span><i class="far fa-calendar-alt"></i>
                                                            {{ $fechaTicket }}</span>
                                                        <span><i class="fas fa-store"></i>
                                                            {{ $firstItem->NomTienda }}</span>
                                                        <span><i class="fas fa-user"></i> {{ $firstItem->Nombre }}
                                                            {{ $firstItem->Apellidos }}</span>
                                                    </div>
                                                    <div class="d-flex gap-3">
                                                        <span
                                                            class="fw-bold text-success">${{ number_format($ticketTotal, 2) }}</span>
                                                        <span class="badge bg-secondary">{{ $items->count() }}
                                                            artículo(s)</span>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div
                                            id="ticket{{ $ticketId }}"
                                            class="accordion-collapse collapse"
                                            data-bs-parent="#accordionTickets"
                                        >
                                            <div class="accordion-body p-0">
                                                <table class="table-sm mb-0 table">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Código</th>
                                                            <th>Artículo</th>
                                                            <th class="text-end">Importe</th>
                                                            <th>Tipo Nómina</th>
                                                            <th>Crédito</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($items as $item)
                                                            <tr>
                                                                <td>{{ $item->CodArticulo }}</td>
                                                                <td>{{ $item->NomArticulo }}</td>
                                                                <td class="text-end">
                                                                    ${{ number_format($item->ImporteArticulo, 2) }}</td>
                                                                <td>{{ ['' => '', 3 => 'SEMANAL', 4 => 'QUINCENAL'][$item->TipoNomina] }}
                                                                </td>
                                                                <td>
                                                                    @if ($item->StatusCredito == '0')
                                                                        <span class="badge bg-info">Crédito</span>
                                                                    @else
                                                                        <span class="badge bg-secondary">Contado</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot class="table-light">
                                                        <tr>
                                                            <td
                                                                colspan="2"
                                                                class="fw-bold text-end"
                                                            >Total Ticket:</td>
                                                            <td class="fw-bold text-success text-end">
                                                                ${{ number_format($ticketTotal, 2) }}</td>
                                                            <td colspan="2"></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div> --}}
                </div>
            </div>

            <!-- SECCIÓN 3.2: Graficas -->
            <div style="flex: 1; min-width: 0;">
                <div class="row">
                    <div class="col-12 col-xl-6 mb-xl-0 col-xxl-12 mb-xxl-4 mb-4">
                        <div
                            class="card border-0 p-4"
                            style="border-radius: 10px"
                        >
                            <h6 class="fw-semibold mb-3">📈 Ventas por Día</h6>
                            @if ($ventasEmpleado && count($ventasEmpleado) > 0)
                                <canvas
                                    id="ventasPorDiaChart"
                                    height="200"
                                ></canvas>
                            @else
                                <div style="min-height: 200px;">
                                    <x-table-empty-state
                                        title="Sin datos para mostrar"
                                        icon="credit-card"
                                        :message="'No se encontraron ventas realizadas por empleados en el período seleccionado.'"
                                    />
                                </div>
                            @endif
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

        function verificarFiltrosActivos() {
            const filtros = ['idTienda', 'codArticulo', 'fechaInterfaz', 'codigoInterfaz'];
            const hayActivos = filtros.some(id => {
                const el = document.getElementById(id);
                return el && el.value && el.value !== '';
            });
            const badge = document.getElementById('filtrosBadge');
            if (badge) badge.style.display = hayActivos ? 'inline-block' : 'none';

            // Actualizar el estilo del botón
            const btnFiltros = document.getElementById('btnFiltrosAvanzados');
            // if (btnFiltros) {
            //     if (hayActivos) {
            //         btnFiltros.classList.add('bg-secondary', 'text-white');
            //     } else {
            //         btnFiltros.classList.remove('bg-secondary', 'text-white');
            //     }
            // }
        }

        function cambiarVista(vista) {
            const vistaTabla = document.getElementById('vistaTabla');
            const vistaTickets = document.getElementById('vistaTickets');
            const btnTabla = document.getElementById('btnVistaTabla');
            const btnTickets = document.getElementById('btnVistaTickets');

            if (vista === 'tabla') {
                vistaTabla.style.display = 'block';
                vistaTickets.style.display = 'none';
                btnTabla.classList.add('active');
                btnTickets.classList.remove('active');
            } else {
                vistaTabla.style.display = 'none';
                vistaTickets.style.display = 'block';
                btnTabla.classList.remove('active');
                btnTickets.classList.add('active');
            }
        }

        // Checkbox de nómina
        const chkNomina = document.getElementById('chkNomina');
        const numNomina = document.getElementById('numNomina');
        if (chkNomina && numNomina) {
            chkNomina.addEventListener('click', () => {
                if (numNomina.disabled) {
                    numNomina.disabled = false;
                } else {
                    numNomina.value = '';
                    numNomina.disabled = true;
                }
            });
        }

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            // Solo verificar filtros activos para el badge
            verificarFiltrosActivos();

            // Event listeners para actualizar el badge cuando cambien los filtros
            const filtrosInputs = ['idTienda', 'codArticulo', 'fechaInterfaz', 'codigoInterfaz'];
            filtrosInputs.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('change', verificarFiltrosActivos);
                    el.addEventListener('keyup', verificarFiltrosActivos);
                }
            });

            // Gráfica de Ventas por Día
            @php
                $ventasPorDia = $ventasEmpleado
                    ->groupBy(function ($item) {
                        return date('Y-m-d', strtotime($item->FechaVenta));
                    })
                    ->map(function ($dayItems) {
                        return $dayItems->sum('ImporteArticulo');
                    });
            @endphp

            const ctx1 = document.getElementById('ventasPorDiaChart')?.getContext('2d');
            if (ctx1 && {!! json_encode($ventasPorDia->keys()) !!}.length > 0) {
                new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($ventasPorDia->keys()) !!},
                        datasets: [{
                            label: 'Ventas ($)',
                            data: {!! json_encode($ventasPorDia->values()) !!},
                            borderColor: '#1e429f',
                            backgroundColor: 'rgba(30, 66, 159, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection
