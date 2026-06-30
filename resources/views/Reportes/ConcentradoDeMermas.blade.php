<x-page-container title="Concentrado de Mermas">
    <x-card-gradient-header
        icon="trash"
        title="Concentrado de Mermas"
        subtitle="Reporte de mermas registradas por tienda"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
            <a
                href="/ReporteMermasAdminExcel?{{ http_build_query(request()->only(['idTienda', 'fecha1', 'fecha2', 'txtFiltro'])) }}"
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
        <x-form.form action="/ReporteMermasAdmin">
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
                    name="fecha1"
                    label="Fecha Inicio"
                    icon="calendar3"
                    col="col-md-2"
                    :value="request('fecha1')"
                />
                <x-form.date
                    name="fecha2"
                    label="Fecha Fin"
                    icon="calendar3"
                    col="col-md-2"
                    :value="request('fecha2')"
                />
                <x-form.text
                    name="txtFiltro"
                    label="Artículo"
                    icon="search"
                    placeholder="Buscar por código o artículo..."
                    col="col-md-2"
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
                        ></i>Concentrado de Mermas
                    </h5>
                    <p class="section-content-subtitle">Listado de mermas registradas en el sistema</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>Folio</th>
                            <th><i class="bi bi-upc-scan me-1"></i>Código</th>
                            <th><i class="bi bi-box me-1"></i>Artículo</th>
                            <th><i class="bi bi-shop me-1"></i>Tienda</th>
                            <th><i class="bi bi-calendar3 me-1"></i>Captura</th>
                            <th><i class="bi bi-tag me-1"></i>Merma</th>
                            <th class="text-end"><i class="bi bi-hash me-1"></i>Cantidad</th>
                            <th><i class="bi bi-chat-left-text me-1"></i>Comentario</th>
                            <th><i class="bi bi-calendar3 me-1"></i>Interfaz</th>
                            <th class="text-center"><i class="bi bi-database me-1"></i>Interfazado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($concentrado as $tConcentrado)
                            <tr>
                                <td style="font-weight: 600; color: #0f172a;">{{ $tConcentrado->FolioMerma }}</td>
                                <td style="font-weight: 500;">{{ $tConcentrado->CodArticulo }}</td>
                                <td>
                                    <span
                                        class="text-truncate"
                                        style="max-width: 200px; display: inline-block;"
                                        title="{{ $tConcentrado->NomArticulo }}"
                                    >
                                        {{ $tConcentrado->NomArticulo }}
                                    </span>
                                </td>
                                <td>{{ $tConcentrado->NomTienda }}</td>
                                <td style="font-size: 0.85rem;">
                                    {{ \Carbon\Carbon::parse($tConcentrado->FechaCaptura)->locale('es')->isoFormat('D MMM YYYY, HH:mm') }}
                                </td>
                                <td>
                                    <span
                                        style="background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 500;"
                                    >
                                        {{ $tConcentrado->NomTipoMerma }}
                                    </span>
                                </td>
                                <td
                                    class="text-end"
                                    style="font-weight: 500;"
                                >{{ number_format($tConcentrado->CantArticulo, 3) }}</td>
                                <td>
                                    <span
                                        class="text-truncate"
                                        style="max-width: 150px; display: inline-block;"
                                        title="{{ $tConcentrado->Comentario }}"
                                    >
                                        {{ $tConcentrado->Comentario ?: '-' }}
                                    </span>
                                </td>
                                <td style="font-size: 0.85rem;">
                                    {{ $tConcentrado->FechaInterfaz ? \Carbon\Carbon::parse($tConcentrado->FechaInterfaz)->locale('es')->isoFormat('D MMM YYYY, HH:mm') : '-' }}
                                </td>
                                <td class="text-center">
                                    @if ($tConcentrado->FechaInterfaz)
                                        <span
                                            style="background: #f0fdf4; color: #10b981; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 500;"
                                        >
                                            <i class="bi bi-check-circle me-1"></i>Interfazado
                                        </span>
                                    @else
                                        <span
                                            style="background: #fffbeb; color: #f59e0b; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 500;"
                                        >
                                            <i class="bi bi-clock me-1"></i>Pendiente
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <x-table-empty-data
                                colspan="10"
                                title="Sin datos disponibles"
                                message="No se encontraron mermas con los filtros seleccionados"
                                icon="search"
                                :action="true"
                                actionText="Limpiar filtros"
                                actionUrl="/ReporteMermasAdmin"
                            />
                        @endforelse
                    </tbody>
                </table>
            </div>
            @include('components.paginate', ['items' => $concentrado])
        </div>
    </x-card-gradient-header>
</x-page-container>
