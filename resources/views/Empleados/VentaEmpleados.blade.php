<x-page-container title="Ventas a Empleados">
        <x-card-gradient-header
            icon="people"
            title="Ventas a Empleados"
            subtitle="Reporte de ventas realizadas por empleados"
        >
            <x-slot:buttons>
                <a
                    href="/VentaEmpleadosExcel?{{ http_build_query(request()->only(['fecha1', 'fecha2', 'chkNomina', 'numNomina', 'idTienda', 'tipoNomina', 'fechaInterfaz', 'codigoInterfaz', 'soloAdeudos'])) }}"
                    class="btn-header-ghost"
                    title="Exportar a Excel"
                    style="background: #f0fdf4; color: #10b981;"
                    onmouseover="this.style.background='#dcfce7'; this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.background='#f0fdf4'; this.style.transform='translateY(0)'"
                >
                    <i class="bi bi-file-earmark-excel"></i> Exportar
                </a>
                <x-header.buttons.refresh-button />
                <x-header.buttons.home-button />
            </x-slot:buttons>

            <!-- Filtros -->
            <div class="border-bottom p-4">
                <form
                    method="GET"
                    action="/VentaEmpleados"
                >
                    {{-- Fila 1: Filtros principales + Botones --}}
                    <div class="row g-3 align-items-end">
                        <!-- Fecha Inicio -->
                        <div class="col-md-3">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                <i class="bi bi-calendar3 me-1"></i>Fecha Inicio
                            </label>
                            <input
                                type="date"
                                name="fecha1"
                                class="form-control"
                                style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                                value="{{ request('fecha1') }}"
                                autofocus
                            >
                        </div>

                        <!-- Fecha Fin -->
                        <div class="col-md-3">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                <i class="bi bi-calendar3 me-1"></i>Fecha Fin
                            </label>
                            <input
                                type="date"
                                name="fecha2"
                                class="form-control"
                                style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                                value="{{ request('fecha2') }}"
                            >
                        </div>

                        <!-- Nómina + Checkbox -->
                        <div class="col-md-3">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                <i class="bi bi-person-badge me-1"></i>Nómina
                            </label>
                            <div class="d-flex align-items-center gap-2">
                                <input
                                    type="text"
                                    name="numNomina"
                                    id="numNomina"
                                    class="form-control"
                                    style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                                    placeholder="Núm. nómina"
                                    value="{{ request('numNomina') }}"
                                >
                            </div>
                        </div>

                        <!-- Solo Adeudos -->
                        <div class="col-md-1">
                            <div class="form-check pt-4">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="soloAdeudos"
                                    id="soloAdeudos"
                                    {{ request('soloAdeudos') == 'on' ? 'checked' : '' }}
                                    style="cursor: pointer; border-color: #94a3b8;"
                                >
                                <label
                                    class="form-check-label"
                                    for="soloAdeudos"
                                    style="color: #475569; font-size: 0.78rem; cursor: pointer;"
                                >
                                    Adeudos
                                </label>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="col-md-2">
                            <x-form.advanced-toggle :active="$filtrosAvanzadosActivos" />
                        </div>
                    </div>

                    {{-- Fila 2: Filtros avanzados (ocultos) --}}
                    <div
                        id="filaFiltrosAvanzados"
                        class="row g-3 align-items-end {{ $filtrosAvanzadosActivos ? '' : 'd-none' }} mt-3"
                    >
                        <!-- Tienda -->
                        <div class="col-md-3">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                <i class="bi bi-shop me-1"></i>Tienda
                            </label>
                            <select
                                name="idTienda"
                                class="form-select"
                                style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                            >
                                <option value="">Todas las tiendas</option>
                                @foreach ($tiendas as $tienda)
                                    <option
                                        value="{{ $tienda->IdTienda }}"
                                        {{ request('idTienda') == $tienda->IdTienda ? 'selected' : '' }}
                                    >
                                        {{ $tienda->NomTienda }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tipo Nómina -->
                        <div class="col-md-2">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                <i class="bi bi-list-ol me-1"></i>Tipo Nómina
                            </label>
                            <select
                                name="tipoNomina"
                                class="form-select"
                                style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                            >
                                <option value="">Todas</option>
                                <option
                                    value="3"
                                    {{ request('tipoNomina') == '3' ? 'selected' : '' }}
                                >Semanal</option>
                                <option
                                    value="4"
                                    {{ request('tipoNomina') == '4' ? 'selected' : '' }}
                                >Quincenal</option>
                            </select>
                        </div>

                        <!-- Código Interfaz -->
                        <div class="col-md-2">
                            <label
                                class="form-label fw-medium mb-2"
                                style="color: #475569; font-size: 0.85rem;"
                            >
                                <i class="bi bi-hash me-1"></i>Código Interfaz
                            </label>
                            <input
                                type="text"
                                name="codigoInterfaz"
                                class="form-control"
                                style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px 12px; font-size: 0.85rem;"
                                placeholder="Código"
                                value="{{ request('codigoInterfaz') }}"
                            >
                        </div>
                    </div>
                </form>
            </div>

            {{-- SECCIÓN 2: KPIs --}}
            <div class="p-4">
                <div class="row g-3">
                    @php
                        $totalTransacciones = $ventasEmpleado->count();
                        $ticketPromedio = $totalTransacciones > 0 ? $importeTotal / $totalTransacciones : 0;
                        $porcentajeAdeudo = $importeTotal > 0 ? round(($importeCredito / $importeTotal) * 100, 1) : 0;
                    @endphp

                    <div class="col-xl-3 col-md-6 col-12">
                        <div
                            class="kpi-card"
                            style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-radius: 12px; padding: 20px; position: relative; overflow: hidden;"
                        >
                            <div
                                style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(59, 130, 246, 0.08); border-radius: 50%;">
                            </div>
                            <div style="position: relative; z-index: 1;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span
                                        style="color: #1d4ed8; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                    >Total Ventas</span>
                                    <i
                                        class="bi bi-cart"
                                        style="color: #3b82f6; font-size: 1.3rem; opacity: 0.7;"
                                    ></i>
                                </div>
                                <h3
                                    class="mb-1"
                                    style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                                >{{ $totalTransacciones }}</h3>
                                <span style="color: #94a3b8; font-size: 0.78rem;">Transacciones</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 col-12">
                        <div
                            class="kpi-card"
                            style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-radius: 12px; padding: 20px; position: relative; overflow: hidden;"
                        >
                            <div
                                style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(16, 185, 129, 0.08); border-radius: 50%;">
                            </div>
                            <div style="position: relative; z-index: 1;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span
                                        style="color: #059669; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                    >Ticket Promedio</span>
                                    <i
                                        class="bi bi-receipt"
                                        style="color: #10b981; font-size: 1.3rem; opacity: 0.7;"
                                    ></i>
                                </div>
                                <h3
                                    class="mb-1"
                                    style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                                >${{ number_format($ticketPromedio, 2) }}</h3>
                                <span style="color: #94a3b8; font-size: 0.78rem;">Por transacción</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 col-12">
                        <div
                            class="kpi-card"
                            style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-radius: 12px; padding: 20px; position: relative; overflow: hidden;"
                        >
                            <div
                                style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(245, 158, 11, 0.08); border-radius: 50%;">
                            </div>
                            <div style="position: relative; z-index: 1;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span
                                        style="color: #d97706; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                                    >Adeudo</span>
                                    <i
                                        class="bi bi-credit-card"
                                        style="color: #f59e0b; font-size: 1.3rem; opacity: 0.7;"
                                    ></i>
                                </div>
                                <h3
                                    class="mb-1"
                                    style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                                >${{ number_format($importeCredito, 2) }}</h3>
                                <span style="color: #94a3b8; font-size: 0.78rem;">{{ $porcentajeAdeudo }}% del
                                    total</span>
                            </div>
                        </div>
                    </div>

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
                                    >Importe Total</span>
                                    <i
                                        class="bi bi-cash-stack"
                                        style="color: #8b5cf6; font-size: 1.3rem; opacity: 0.7;"
                                    ></i>
                                </div>
                                <h3
                                    class="mb-1"
                                    style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                                >${{ number_format($importeTotal, 2) }}</h3>
                                <span style="color: #94a3b8; font-size: 0.78rem;">MXN</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 3: TABLA Y GRÁFICAS --}}
            <div class="px-4 pb-4">
                <div class="row g-4">
                    {{-- TABLA --}}
                    <div class="col-xxl-8">
                        <div
                            class="rounded p-4 shadow-sm"
                            style="background: white; border-radius: 12px;"
                        >
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                <h5
                                    class="mb-0"
                                    style="font-weight: 600; color: #0f172a; font-size: 1rem;"
                                >VENTAS A EMPLEADOS</h5>
                                <div class="d-flex gap-2">
                                    <div class="btn-group">
                                        <button
                                            class="btn btn-sm active"
                                            id="btnVistaTabla"
                                            onclick="cambiarVista('tabla')"
                                            style="background: #1e293b; color: white; border: none; border-radius: 6px 0 0 6px; padding: 6px 12px; font-size: 0.8rem;"
                                        >📋 Tabla</button>
                                        <button
                                            class="btn btn-sm"
                                            id="btnVistaTickets"
                                            onclick="cambiarVista('tickets')"
                                            style="background: #f1f5f9; color: #475569; border: none; border-radius: 0 6px 6px 0; padding: 6px 12px; font-size: 0.8rem;"
                                        >🎫 Tickets</button>
                                    </div>
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

                            {{-- Vista Tabla --}}
                            <div
                                id="vistaTabla"
                                class="table-responsive"
                                style="overflow-y: auto;"
                            >
                                <table class="table-hover table-custom table">
                                    <thead style="position: sticky; top: 0; z-index: 2;">
                                        <tr>
                                            <th><i class="bi bi-calendar3 me-1"></i>Fecha</th>
                                            <th><i class="bi bi-shop me-1"></i>Tienda</th>
                                            <th><i class="bi bi-hash me-1"></i>Nómina</th>
                                            <th><i class="bi bi-person me-1"></i>Empleado</th>
                                            <th><i class="bi bi-building me-1"></i>Empresa</th>
                                            <th><i class="bi bi-ticket me-1"></i>Ticket</th>
                                            <th><i class="bi bi-upc-scan me-1"></i>Código</th>
                                            <th><i class="bi bi-box me-1"></i>Artículo</th>
                                            <th class="text-end"><i class="bi bi-cash me-1"></i>Importe</th>
                                            <th><i class="bi bi-list-ol me-1"></i>Tipo</th>
                                            <th><i class="bi bi-credit-card me-1"></i>Pago</th>
                                            <th><i class="bi bi-circle me-1"></i>Crédito</th>
                                            <th><i class="bi bi-link me-1"></i>Interfaz</th>
                                            <th><i class="bi bi-calendar3 me-1"></i>Fecha Int.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($ventasEmpleado as $ventaEmpleado)
                                            <tr>
                                                <td style="font-size: 0.8rem;">
                                                    {{ \Carbon\Carbon::parse($ventaEmpleado->FechaVenta)->format('d/m/Y H:i') }}
                                                </td>
                                                <td style="font-weight: 500;">{{ $ventaEmpleado->NomTienda }}</td>
                                                <td style="font-weight: 600; color: #0f172a;">
                                                    {{ $ventaEmpleado->NumNomina }}</td>
                                                <td>{{ $ventaEmpleado->Nombre }} {{ $ventaEmpleado->Apellidos }}</td>
                                                <td>{{ $ventaEmpleado->Empresa }}</td>
                                                <td style="font-weight: 500;">{{ $ventaEmpleado->IdTicket }}</td>
                                                <td>{{ $ventaEmpleado->CodArticulo }}</td>
                                                <td
                                                    class="text-truncate"
                                                    style="max-width: 180px;"
                                                    title="{{ $ventaEmpleado->NomArticulo }}"
                                                >{{ $ventaEmpleado->NomArticulo }}</td>
                                                <td
                                                    class="text-end"
                                                    style="font-weight: 500;"
                                                >${{ number_format($ventaEmpleado->ImporteArticulo, 2) }}</td>
                                                <td>{{ ['' => '', 3 => 'SEMANAL', 4 => 'QUINCENAL'][$ventaEmpleado->TipoNomina] ?? '' }}
                                                </td>
                                                <td>{{ $ventaEmpleado->NomTipoPago }}</td>
                                                <td class="text-center">
                                                    @if ($ventaEmpleado->StatusCredito == '0')
                                                        <span class="tags-yellow">Crédito</span>
                                                    @endif
                                                </td>
                                                <td>{{ $ventaEmpleado->IdHistorialCredito }}</td>
                                                <td style="font-size: 0.8rem;">
                                                    {{ $ventaEmpleado->FechaInterfaz ? \Carbon\Carbon::parse($ventaEmpleado->FechaInterfaz)->format('d/m/Y H:i') : '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td
                                                    colspan="14"
                                                    class="py-5 text-center"
                                                >
                                                    <i
                                                        class="bi bi-inbox"
                                                        style="font-size: 2.5rem; color: #94a3b8;"
                                                    ></i>
                                                    <p
                                                        class="mt-2"
                                                        style="color: #64748b; font-size: 0.85rem;"
                                                    >No hay ventas para mostrar</p>
                                                    <a
                                                        href="/VentaEmpleados"
                                                        class="btn btn-sm d-flex align-items-center mx-auto mt-2 gap-1"
                                                        style="background: #f1f5f9; color: #475569; border: none; border-radius: 8px; padding: 8px 16px; width: fit-content;"
                                                    >
                                                        <i class="bi bi-x-circle"></i> Resetear filtros
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Vista Tickets --}}
                            <div
                                id="vistaTickets"
                                class="table-responsive"
                                style="display: none; overflow-y: auto;"
                            >
                                @php $encabezadosGrouped = $ventasEmpleado->groupBy('IdEncabezado'); @endphp
                                <table class="table-hover table-custom table">
                                    <thead style="position: sticky; top: 0; z-index: 2;">
                                        <tr>
                                            <th></th>
                                            <th><i class="bi bi-calendar3 me-1"></i>Fecha</th>
                                            <th><i class="bi bi-shop me-1"></i>Tienda</th>
                                            <th><i class="bi bi-hash me-1"></i>Nómina</th>
                                            <th><i class="bi bi-person me-1"></i>Empleado</th>
                                            <th><i class="bi bi-building me-1"></i>Empresa</th>
                                            <th class="text-center"><i class="bi bi-box me-1"></i>Artículos</th>
                                            <th class="text-end"><i class="bi bi-cash-stack me-1"></i>Importe Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($encabezadosGrouped as $encabezadoId => $items)
                                            @php
                                                $firstItem = $items->first();
                                                $encabezadoTotal = $items->sum('ImporteArticulo');
                                            @endphp
                                            <tr
                                                data-bs-toggle="collapse"
                                                data-bs-target="#detalleEncabezado{{ $encabezadoId }}"
                                                aria-expanded="false"
                                                aria-controls="detalleEncabezado{{ $encabezadoId }}"
                                                style="cursor: pointer;"
                                            >
                                                <td class="text-center"><i class="bi bi-chevron-down"></i></td>
                                                <td style="font-size: 0.8rem;">
                                                    {{ \Carbon\Carbon::parse($firstItem->FechaVenta)->format('d/m/Y H:i') }}
                                                </td>
                                                <td style="font-weight: 500;">{{ $firstItem->NomTienda }}</td>
                                                <td style="font-weight: 600; color: #0f172a;">{{ $firstItem->NumNomina }}
                                                </td>
                                                <td>{{ $firstItem->Nombre }} {{ $firstItem->Apellidos }}</td>
                                                <td>{{ $firstItem->Empresa }}</td>
                                                <td
                                                    class="text-center"
                                                    style="font-weight: 500;"
                                                >{{ $items->count() }}</td>
                                                <td
                                                    class="text-end"
                                                    style="font-weight: 600; color: #10b981;"
                                                >${{ number_format($encabezadoTotal, 2) }}</td>
                                            </tr>
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
                                                            style="background: #f8fafc;"
                                                        >
                                                            <table class="table-hover table-custom mb-0 table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Ticket</th>
                                                                        <th>Código</th>
                                                                        <th>Artículo</th>
                                                                        <th class="text-end">Importe</th>
                                                                        <th>Tipo</th>
                                                                        <th>Crédito</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($items as $item)
                                                                        <tr>
                                                                            <td style="font-weight: 500;">
                                                                                {{ $item->IdTicket }}</td>
                                                                            <td>{{ $item->CodArticulo }}</td>
                                                                            <td>{{ $item->NomArticulo }}</td>
                                                                            <td
                                                                                class="text-end"
                                                                                style="font-weight: 500;"
                                                                            >${{ number_format($item->ImporteArticulo, 2) }}
                                                                            </td>
                                                                            <td>{{ ['' => '', 3 => 'SEMANAL', 4 => 'QUINCENAL'][$item->TipoNomina] ?? '' }}
                                                                            </td>
                                                                            <td>
                                                                                @if ($item->StatusCredito == '0')
                                                                                    <span
                                                                                        class="tags-yellow">Crédito</span>
                                                                                @else
                                                                                    <span class="tags-green">Pagado</span>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr style="background: #f1f5f9;">
                                                                        <td
                                                                            colspan="3"
                                                                            class="fw-bold text-end"
                                                                        >Total:</td>
                                                                        <td
                                                                            class="fw-bold text-end"
                                                                            style="color: #10b981;"
                                                                        >${{ number_format($encabezadoTotal, 2) }}</td>
                                                                        <td colspan="2"></td>
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
                        </div>
                    </div>

                    {{-- GRÁFICA --}}
                    <div class="col-xxl-4">
                        <div
                            class="rounded p-4 shadow-sm"
                            style="background: white; border-radius: 12px;"
                        >
                            <h5
                                class="mb-3"
                                style="font-weight: 600; color: #0f172a; font-size: 1rem;"
                            >
                                <i
                                    class="bi bi-graph-up me-2"
                                    style="color: #64748b;"
                                ></i>Ventas por Día
                            </h5>
                            @if ($ventasEmpleado && count($ventasEmpleado) > 0)
                                <div style="height: 250px;">
                                    <canvas id="ventasPorDiaChart"></canvas>
                                </div>
                            @else
                                <div
                                    class="d-flex justify-content-center align-items-center"
                                    style="height: 250px;"
                                >
                                    <div class="text-center">
                                        <i
                                            class="bi bi-bar-chart"
                                            style="font-size: 2.5rem; color: #94a3b8;"
                                        ></i>
                                        <p
                                            class="mt-2"
                                            style="color: #64748b;"
                                        >Sin datos</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </x-card-gradient-header>
    </x-page-container>

@section('scripts')
    <script>
        // Toggle panel de filtros avanzados
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

        // Expandir/Contraer tabla
        let tablaExpandida = false;

        function toggleExpandirTabla() {
            const card = document.querySelector('#vistaTabla').closest('.rounded');
            const btn = document.getElementById('btnExpandirTexto');
            if (!tablaExpandida) {
                card.style.position = 'fixed';
                card.style.top = '52px';
                card.style.left = '0';
                card.style.width = '100vw';
                card.style.height = 'calc(100vh - 60px)';
                card.style.zIndex = '1050';
                card.style.borderRadius = '0';
                card.style.overflow = 'auto';
                card.style.background = '#f8fafc';
                card.style.padding = '24px';
                document.body.appendChild(card);
                btn.textContent = 'Contraer';
            } else {
                location.reload();
            }
            tablaExpandida = !tablaExpandida;
        }

        // Cambiar vista Tabla/Tickets
        function cambiarVista(vista) {
            const vt = document.getElementById('vistaTabla');
            const vk = document.getElementById('vistaTickets');
            const bt = document.getElementById('btnVistaTabla');
            const bk = document.getElementById('btnVistaTickets');
            if (vista === 'tabla') {
                vt.style.display = 'block';
                vk.style.display = 'none';
                bt.style.background = '#1e293b';
                bt.style.color = 'white';
                bk.style.background = '#f1f5f9';
                bk.style.color = '#475569';
            } else {
                vt.style.display = 'none';
                vk.style.display = 'block';
                bt.style.background = '#f1f5f9';
                bt.style.color = '#475569';
                bk.style.background = '#1e293b';
                bk.style.color = 'white';
            }
        }

        // Checkbox de nómina
        const chk = document.getElementById('chkNomina');
        const num = document.getElementById('numNomina');
        if (chk && num) {
            chk.addEventListener('change', () => {
                num.disabled = !chk.checked;
                if (!chk.checked) num.value = '';
            });
        }

        // Gráfica
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('ventasPorDiaChart')?.getContext('2d');
            @php
                $ventasPorDia = $ventasEmpleado
                    ->groupBy(function ($item) {
                        return \Carbon\Carbon::parse($item->FechaVenta)->format('Y-m-d');
                    })
                    ->map(function ($items) {
                        return $items->sum('ImporteArticulo');
                    });
            @endphp
            if (ctx && @json($ventasPorDia->keys()).length > 0) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($ventasPorDia->keys()) !!},
                        datasets: [{
                            label: 'Ventas',
                            data: {!! json_encode($ventasPorDia->values()) !!},
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
        });
    </script>
@endsection
