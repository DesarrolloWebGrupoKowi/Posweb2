<x-page-container title="Concentrado Ventas por Tipo de Precio">
    <x-card-gradient-header
        icon="tags"
        title="Concentrado Ventas por Tipo de Precio"
        subtitle="Reporte de ventas agrupadas por lista de precio"
    >
        <x-slot:buttons>
            <a
                href="/ExportReportePorTipoDePrecio?{{ http_build_query(request()->only(['fecha1', 'fecha2'])) }}"
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
        <x-form.form action="/ReportePorTipoDePrecio">
            <x-form.group>
                <x-form.date
                    name="fecha1"
                    label="Fecha Inicio"
                    icon="calendar3"
                    col="col-md-5"
                    :value="empty($fecha1) ? date('Y-m-d') : $fecha1"
                    :autofocus="true"
                />
                <x-form.date
                    name="fecha2"
                    label="Fecha Fin"
                    icon="calendar3"
                    col="col-md-4"
                    :value="empty($fecha2) ? date('Y-m-d') : $fecha2"
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

        <!-- KPIs -->
        <div class="p-4 pb-0">
            <div class="d-flex flex-wrap gap-3">
                <div
                    class="kpi-card"
                    style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-radius: 12px; padding: 16px 20px; position: relative; overflow: hidden; flex: 1; min-width: 160px;"
                >
                    <div
                        style="position: absolute; top: -15px; right: -15px; width: 60px; height: 60px; background: rgba(16, 185, 129, 0.08); border-radius: 50%;">
                    </div>
                    <div style="position: relative; z-index: 1;">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span
                                style="color: #059669; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                            >Menudeo</span>
                            <i
                                class="bi bi-cart"
                                style="color: #10b981; font-size: 1.1rem; opacity: 0.6;"
                            ></i>
                        </div>
                        <h3
                            class="mb-1"
                            style="font-weight: 700; color: #0f172a; font-size: 1.3rem;"
                        >${{ number_format($totales['MENUDEO'], 2) }}</h3>
                        <span style="color: #64748b; font-size: 0.75rem;">{{ $clientes['MENUDEO'] }} clientes</span>
                    </div>
                </div>

                <div
                    class="kpi-card"
                    style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-radius: 12px; padding: 16px 20px; position: relative; overflow: hidden; flex: 1; min-width: 160px;"
                >
                    <div
                        style="position: absolute; top: -15px; right: -15px; width: 60px; height: 60px; background: rgba(245, 158, 11, 0.08); border-radius: 50%;">
                    </div>
                    <div style="position: relative; z-index: 1;">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span
                                style="color: #d97706; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                            >Minorista</span>
                            <i
                                class="bi bi-shop"
                                style="color: #f59e0b; font-size: 1.1rem; opacity: 0.6;"
                            ></i>
                        </div>
                        <h3
                            class="mb-1"
                            style="font-weight: 700; color: #0f172a; font-size: 1.3rem;"
                        >${{ number_format($totales['MINORISTA'], 2) }}</h3>
                        <span style="color: #64748b; font-size: 0.75rem;">{{ $clientes['MINORISTA'] }} clientes</span>
                    </div>
                </div>

                <div
                    class="kpi-card"
                    style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-radius: 12px; padding: 16px 20px; position: relative; overflow: hidden; flex: 1; min-width: 160px;"
                >
                    <div
                        style="position: absolute; top: -15px; right: -15px; width: 60px; height: 60px; background: rgba(59, 130, 246, 0.08); border-radius: 50%;">
                    </div>
                    <div style="position: relative; z-index: 1;">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span
                                style="color: #1d4ed8; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                            >Detalle</span>
                            <i
                                class="bi bi-tag"
                                style="color: #3b82f6; font-size: 1.1rem; opacity: 0.6;"
                            ></i>
                        </div>
                        <h3
                            class="mb-1"
                            style="font-weight: 700; color: #0f172a; font-size: 1.3rem;"
                        >${{ number_format($totales['DETALLE'], 2) }}</h3>
                        <span style="color: #64748b; font-size: 0.75rem;">{{ $clientes['DETALLE'] }} clientes</span>
                    </div>
                </div>

                <div
                    class="kpi-card"
                    style="background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%); border-radius: 12px; padding: 16px 20px; position: relative; overflow: hidden; flex: 1; min-width: 160px;"
                >
                    <div
                        style="position: absolute; top: -15px; right: -15px; width: 60px; height: 60px; background: rgba(139, 92, 246, 0.08); border-radius: 50%;">
                    </div>
                    <div style="position: relative; z-index: 1;">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span
                                style="color: #7c3aed; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                            >EmpySoc</span>
                            <i
                                class="bi bi-people"
                                style="color: #8b5cf6; font-size: 1.1rem; opacity: 0.6;"
                            ></i>
                        </div>
                        <h3
                            class="mb-1"
                            style="font-weight: 700; color: #0f172a; font-size: 1.3rem;"
                        >${{ number_format($totales['EMPYSOC'], 2) }}</h3>
                        <span style="color: #64748b; font-size: 0.75rem;">{{ $clientes['EMPYSOC'] }} clientes</span>
                    </div>
                </div>

                {{-- Total General --}}
                @php
                    $totalGeneral =
                        ($totales['MENUDEO'] ?? 0) +
                        ($totales['MINORISTA'] ?? 0) +
                        ($totales['DETALLE'] ?? 0) +
                        ($totales['EMPYSOC'] ?? 0);
                @endphp
                <div
                    class="kpi-card"
                    style="background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%); border-radius: 12px; padding: 16px 20px; position: relative; overflow: hidden; flex: 1; min-width: 160px;"
                >
                    <div
                        style="position: absolute; top: -15px; right: -15px; width: 60px; height: 60px; background: rgba(71, 85, 105, 0.06); border-radius: 50%;">
                    </div>
                    <div style="position: relative; z-index: 1;">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span
                                style="color: #475569; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;"
                            >Total</span>
                            <i
                                class="bi bi-cash-stack"
                                style="color: #64748b; font-size: 1.1rem; opacity: 0.6;"
                            ></i>
                        </div>
                        <h3
                            class="mb-1"
                            style="font-weight: 700; color: #0f172a; font-size: 1.3rem;"
                        >${{ number_format($totalGeneral, 2) }}</h3>
                        <span style="color: #64748b; font-size: 0.75rem;">Suma total</span>
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
                        ></i>Concentrado por Tipo de Precio
                    </h5>
                    <p class="section-content-subtitle">Listado de ventas agrupadas por lista de precio</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-shop me-1"></i>Tienda</th>
                            <th><i class="bi bi-tag me-1"></i>Tipo Precio</th>
                            <th class="text-center"><i class="bi bi-box me-1"></i>Kilos</th>
                            <th class="text-end"><i class="bi bi-cash-stack me-1"></i>Importe</th>
                            <th class="text-center"><i class="bi bi-people me-1"></i>Clientes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($concentrado as $tConcentrado)
                            <tr>
                                <td style="font-weight: 500;">{{ $tConcentrado->NomTienda }}</td>
                                <td>
                                    <span
                                        style="background: #eff6ff; color: #3b82f6; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 500;"
                                    >
                                        {{ $tConcentrado->NomListaPrecio }}
                                    </span>
                                </td>
                                <td
                                    class="text-center"
                                    style="font-weight: 500;"
                                >{{ number_format($tConcentrado->kilos, 2) }} kg</td>
                                <td
                                    class="text-end"
                                    style="font-weight: 600; color: #10b981;"
                                >${{ number_format($tConcentrado->importe, 2) }}</td>
                                <td
                                    class="text-center"
                                    style="font-weight: 500;"
                                >{{ number_format($tConcentrado->tickets, 0) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="5"
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
                    </tbody>
                    @if ($concentrado->count() > 0)
                        <tfoot>
                            <tr style="background: #f1f5f9; font-weight: 700;">
                                <td
                                    colspan="2"
                                    style="color: #0f172a;"
                                >Totales:</td>
                                <td></td>
                                <td
                                    class="text-end"
                                    style="color: #10b981;"
                                >${{ number_format($totales['TOTAL'], 2) }}</td>
                                <td class="text-center">{{ $clientes['TOTAL'] }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </x-card-gradient-header>
</x-page-container>
