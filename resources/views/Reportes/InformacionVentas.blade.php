<x-page-container title="Información de Ventas">
    <x-card-gradient-header
        icon="info-circle"
        title="Información de Ventas"
        subtitle="Reporte de ventas por tienda"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form action="/InformacionVentas">
            <x-form.group>
                <x-form.select
                    name="idTienda"
                    label="Tienda"
                    icon="shop"
                    col="col-md-4"
                    :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                    :autofocus="true"
                />
                <x-form.date
                    name="fecha1"
                    label="Fecha Inicio"
                    icon="calendar3"
                    col="col-md-2"
                    :value="empty($fecha1) ? date('Y-m-d') : $fecha1"
                />
                <x-form.date
                    name="fecha2"
                    label="Fecha Fin"
                    icon="calendar3"
                    col="col-md-2"
                    :value="empty($fecha2) ? date('Y-m-d') : $fecha2"
                />
                <x-form.checkbox-input
                    name="agrupar"
                    label="Agrupar"
                    icon="layers"
                    :checked="$agrupar == 'on'"
                    col="col-md-1"
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

        <!-- KPIs -->
        <div class="p-4 pb-0">
            <div class="row g-3">
                @php
                    $totalTickets = $concentrado->sum('Tickets');
                    $totalKilos = $concentrado->sum('cantidad');
                    $totalImporte = $concentrado->sum('Importe');
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
                                >Tickets</span>
                                <i
                                    class="bi bi-ticket-perforated"
                                    style="color: #3b82f6; font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                            >{{ number_format($totalTickets) }}</h3>
                            <span style="color: #94a3b8; font-size: 0.78rem;">Tickets</span>
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
                                >Kilos</span>
                                <i
                                    class="bi bi-box"
                                    style="color: #10b981; font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color: #0f172a; font-size: 1.5rem;"
                            >{{ number_format($totalKilos, 2) }} kg</h3>
                            <span style="color: #94a3b8; font-size: 0.78rem;">Total vendido</span>
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
                                >Prom. Ticket</span>
                                <i
                                    class="bi bi-cash"
                                    style="color: #f59e0b; font-size: 1.3rem; opacity: 0.7;"
                                ></i>
                            </div>
                            <h3
                                class="mb-1"
                                style="font-weight: 700; color":
                                #0f172a;
                                font-size:
                                1.5rem;"
                            >
                                ${{ $totalTickets > 0 ? number_format($totalImporte / $totalTickets, 2) : '0.00' }}
                            </h3>
                            <span style="color: #94a3b8; font-size: 0.78rem;">Por ticket</span>
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
                            >${{ number_format($totalImporte, 2) }}</h3>
                            <span style="color: #94a3b8; font-size: 0.78rem;">MXN</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                <div>
                    <h5 class="section-content-title">
                        <i
                            class="bi bi-table me-2"
                            style="color: #64748b;"
                        ></i>Información de Ventas
                    </h5>
                    <p class="section-content-subtitle">Listado de ventas registradas en el sistema</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-calendar3 me-1"></i>Fecha</th>
                            <th class="text-center"><i class="bi bi-ticket me-1"></i>Tickets</th>
                            <th class="text-center"><i class="bi bi-box me-1"></i>Kilos</th>
                            <th class="text-end"><i class="bi bi-cash-stack me-1"></i>Importe</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $nombreTienda = '';
                            $subtotalTickets = 0;
                            $subtotalKilos = 0;
                            $subtotalImporte = 0;
                            $totalTickets = 0;
                            $totalKilos = 0;
                            $totalImporte = 0;
                        @endphp

                        @forelse ($concentrado as $tConcentrado)
                            @php $NomTienda = $tConcentrado->NomTienda ?? ''; @endphp

                            {{-- Nueva tienda --}}
                            @if ($nombreTienda != $NomTienda)
                                {{-- Subtotal de la tienda anterior --}}
                                @if ($nombreTienda != '')
                                    <tr style="background: #f8f9fa; font-weight: 600; border-bottom: 1px solid #e9edf0">
                                        <td style="color: #64748b;">
                                            <i class="bi bi-bar-chart me-1"></i>Subtotal
                                        </td>
                                        <td class="text-center">{{ number_format($subtotalTickets) }}</td>
                                        <td class="text-center">{{ number_format($subtotalKilos, 2) }} kg</td>
                                        <td
                                            class="text-end"
                                            style="color: #10b981;"
                                        >${{ number_format($subtotalImporte, 2) }}</td>
                                    </tr>
                                    <!-- Separador entre tickets -->
                                    <tr>
                                        <td
                                            colspan="4"
                                            style="border-bottom: 3px solid #dee2e6; padding: 5px;"
                                        ></td>
                                    </tr>
                                @endif

                                {{-- Header de nueva tienda (estilo ticket) --}}
                                <tr
                                    style="background: #e2e8f0;"
                                    {{-- class="table-primary" --}}
                                    {{-- style="background-color: #e3f2fd; cursor: pointer; color: #0d6efd; border-left: 4px solid #0d6efd; font-weight: 600;" --}}
                                >
                                    <td
                                        colspan="4"
                                        class="p-0"
                                        style="border: none;"
                                    >
                                        <div
                                            style="padding: 10px 16px; font-weight: 600; color: #0f172a; font-size: 0.9rem;">
                                            <i class="bi bi-shop me-2"></i>{{ $NomTienda }}
                                        </div>
                                    </td>
                                </tr>

                                @php
                                    $nombreTienda = $NomTienda;
                                    $subtotalTickets = 0;
                                    $subtotalKilos = 0;
                                    $subtotalImporte = 0;
                                @endphp
                            @endif

                            <tr>
                                <td style="font-size: 0.85rem; padding-left: 24px;">
                                    {{ \Carbon\Carbon::parse($tConcentrado->Fecha)->format('d/m/Y') }}
                                </td>
                                <td class="text-center">
                                    <span {{-- style="background: #eff6ff; color: #3b82f6; padding: 2px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 500;" --}}>
                                        {{ $tConcentrado->Tickets }}
                                    </span>
                                </td>
                                <td
                                    class="text-center"
                                    style="font-weight: 500;"
                                >
                                    {{ number_format($tConcentrado->cantidad, 2) }} kg
                                </td>
                                <td
                                    class="text-end"
                                    style="font-weight: 600; color: #10b981;"
                                >
                                    ${{ number_format($tConcentrado->Importe, 2) }}
                                </td>
                            </tr>

                            @php
                                $subtotalTickets += $tConcentrado->Tickets;
                                $subtotalKilos += $tConcentrado->cantidad;
                                $subtotalImporte += $tConcentrado->Importe;
                                $totalTickets += $tConcentrado->Tickets;
                                $totalKilos += $tConcentrado->cantidad;
                                $totalImporte += $tConcentrado->Importe;
                            @endphp
                        @empty
                            <tr>
                                <td
                                    colspan="4"
                                    class="py-5 text-center"
                                >
                                    <i
                                        class="bi bi-inbox"
                                        style="font-size: 2.5rem; color: #94a3b8;"
                                    ></i>
                                    <p
                                        class="mt-2"
                                        style="color: #64748b; font-size: 0.85rem;"
                                    >No hay ventas en el rango de fechas seleccionadas</p>
                                </td>
                            </tr>
                        @endforelse

                        {{-- Subtotal de la última tienda --}}
                        @if ($nombreTienda != '')
                            <tr style="background: #f8f9fa; font-weight: 600;">
                                <td style="color: #64748b;">
                                    <i class="bi bi-bar-chart me-1"></i>Subtotal
                                </td>
                                <td class="text-center">{{ number_format($subtotalTickets) }}</td>
                                <td class="text-center">{{ number_format($subtotalKilos, 2) }} kg</td>
                                <td
                                    class="text-end"
                                    style="color: #10b981;"
                                >${{ number_format($subtotalImporte, 2) }}</td>
                            </tr>

                            {{-- Separador final --}}
                            <tr>
                                <td
                                    colspan="4"
                                    style="border-bottom: 3px solid #dee2e6; padding: 5px;"
                                ></td>
                            </tr>
                        @endif
                    </tbody>
                    @if ($concentrado->count() > 0)
                        <tfoot>
                            <tr style="background: #f1f5f9; font-weight: 700;">
                                <td style="color: #0f172a;">Totales Generales</td>
                                <td class="text-center">{{ number_format($totalTickets) }}</td>
                                <td class="text-center">{{ number_format($totalKilos, 2) }} kg</td>
                                <td
                                    class="text-end"
                                    style="color: #10b981;"
                                >${{ number_format($totalImporte, 2) }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </x-card-gradient-header>
</x-page-container>
