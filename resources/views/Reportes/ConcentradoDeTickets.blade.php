<x-page-container title="Concentrado de Tickets">
    <x-card-gradient-header
        icon="ticket-perforated"
        title="Concentrado de Tickets"
        subtitle="Reporte de tickets por tienda"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
            <a
                href="/ExportReporteConcentradoDeTickets?{{ http_build_query(request()->only(['idTienda', 'fecha1', 'fecha2'])) }}"
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
        <x-form.form action="/ReporteConcentradoDeTickets">
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
                    col="col-md-3"
                    :value="empty($fecha1) ? date('Y-m-d') : $fecha1"
                />
                <x-form.date
                    name="fecha2"
                    label="Fecha Fin"
                    icon="calendar3"
                    col="col-md-2"
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

        <!-- Tabla -->
        <div class="p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                <div>
                    <h5 class="section-content-title">
                        <i
                            class="bi bi-table me-2"
                            style="color: #64748b;"
                        ></i>Concentrado de Tickets
                    </h5>
                    <p class="section-content-subtitle">Listado de tickets registrados en el sistema</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-building me-1"></i>Ciudad</th>
                            <th><i class="bi bi-shop me-1"></i>Tienda</th>
                            <th><i class="bi bi-calendar3 me-1"></i>Fecha</th>
                            <th class="text-center"><i class="bi bi-ticket me-1"></i>Tickets</th>
                            <th class="text-end"><i class="bi bi-cash-stack me-1"></i>Importe</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalImporte = 0; @endphp

                        @forelse ($concentrado as $tConcentrado)
                            <tr>
                                <td>{{ $tConcentrado->NomCiudad }}</td>
                                <td style="font-weight: 500;">{{ $tConcentrado->NomTienda }}</td>
                                <td style="font-size: 0.85rem;">
                                    {{ \Carbon\Carbon::parse($tConcentrado->Fecha)->format('d/m/Y') }}</td>
                                <td
                                    class="text-center"
                                    style="font-weight: 500;"
                                >{{ $tConcentrado->Tickets }}</td>
                                <td
                                    class="text-end"
                                    style="font-weight: 500;"
                                >${{ number_format($tConcentrado->Importe, 2) }}</td>
                            </tr>
                            @php $totalImporte += $tConcentrado->Importe; @endphp
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
                            <tr style="background: #f8fafc; font-weight: 700;">
                                <td colspan="4">Total:</td>
                                <td class="text-end">${{ number_format($totalImporte, 2) }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </x-card-gradient-header>
</x-page-container>
