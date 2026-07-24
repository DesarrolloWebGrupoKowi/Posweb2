<x-page-container title="Dinero Electrónico">
    <x-card-gradient-header
        icon="wallet2"
        title="Dinero Electrónico"
        subtitle="Reporte de dinero electrónico por tienda"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
            @if (!empty($fecha1) && !empty($fecha2))
                <a
                    href="/ExportReporteDineroElectronido?{{ http_build_query(request()->only(['idTienda', 'fecha1', 'fecha2'])) }}"
                    class="btn-header-ghost"
                    title="Exportar a Excel"
                    style="background: #f0fdf4; color: #10b981;"
                    onmouseover="this.style.background='#dcfce7'; this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.background='#f0fdf4'; this.style.transform='translateY(0)'"
                >
                    <i class="bi bi-file-earmark-excel"></i> Exportar
                </a>
            @endif
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form action="/ReporteDineroElectronido">
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
                        ></i>Concentrado de Dinero Electrónico
                    </h5>
                    <p class="section-content-subtitle">Listado de transacciones de dinero electrónico</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-shop me-1"></i>Tienda</th>
                            <th><i class="bi bi-calendar3 me-1"></i>Día</th>
                            <th class="text-end"><i class="bi bi-credit-card me-1"></i>Semanal Crédito</th>
                            <th class="text-end"><i class="bi bi-credit-card me-1"></i>Quincenal Crédito</th>
                            <th class="text-end"><i class="bi bi-cash-stack me-1"></i>Contado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalSemanal = 0;
                            $totalQuincenal = 0;
                            $totalContado = 0;
                        @endphp

                        @forelse ($concentrado as $item)
                            <tr>
                                <td style="font-weight: 500;">{{ $item->NomTienda }}</td>
                                <td style="font-size: 0.85rem;">{{ $item->Fecha }}</td>
                                <td
                                    class="text-end"
                                    style="font-weight: 500;"
                                >${{ number_format($item->semanal_creadito, 2) }}</td>
                                <td
                                    class="text-end"
                                    style="font-weight: 500;"
                                >${{ number_format($item->quincenal_creadito, 2) }}</td>
                                <td
                                    class="text-end"
                                    style="font-weight: 500;"
                                >${{ number_format($item->contado, 2) }}</td>
                            </tr>
                            @php
                                $totalSemanal += $item->semanal_creadito;
                                $totalQuincenal += $item->quincenal_creadito;
                                $totalContado += $item->contado;
                            @endphp
                        @empty
                            <x-table-empty-data
                                colspan="5"
                                title="Sin datos disponibles"
                                message="No se encontraron registros de dinero electrónico con los filtros seleccionados"
                                icon="search"
                                :action="true"
                                actionText="Limpiar filtros"
                                actionUrl="/ReporteDineroElectronido"
                            />
                        @endforelse
                    </tbody>
                    @if ($concentrado->count() > 0)
                        <tfoot>
                            <tr style="background: #f1f5f9; font-weight: 700;">
                                <td
                                    colspan="2"
                                    class=""
                                    style="color: #0f172a;"
                                >Totales:</td>
                                <td
                                    class="text-end"
                                    style="color: #0f172a;"
                                >${{ number_format($totalSemanal, 2) }}</td>
                                <td
                                    class="text-end"
                                    style="color: #0f172a;"
                                >${{ number_format($totalQuincenal, 2) }}</td>
                                <td
                                    class="text-end"
                                    style="color: #0f172a;"
                                >${{ number_format($totalContado, 2) }}</td>
                            </tr>
                            <tr>
                                <td
                                    colspan="5"
                                    class="text-muted small py-2"
                                >
                                    * {{ $concentrado->count() }} registros |
                                    Total general:
                                    ${{ number_format($totalSemanal + $totalQuincenal + $totalContado, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </x-card-gradient-header>
</x-page-container>
