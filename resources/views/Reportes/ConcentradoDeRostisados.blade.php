<x-page-container title="Concentrado de Rostisados">
    <x-card-gradient-header
        icon="fire"
        title="Concentrado de Rostisados"
        subtitle="Reporte de productos rostisados por tienda"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <!-- Filtros -->
        <x-form.form action="/ReporteRosticeroAdmin">
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
                        ></i>Concentrado de Rostisados
                    </h5>
                    <p class="section-content-subtitle">Listado de productos rostisados registrados</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-hover table-custom table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-hash me-1"></i>Folio</th>
                            <th><i class="bi bi-box-arrow-down me-1"></i>Artículo Baja</th>
                            <th class="text-end"><i class="bi bi-hash me-1"></i>Cantidad Baja</th>
                            <th><i class="bi bi-box-arrow-up me-1"></i>Artículo Alta</th>
                            <th class="text-end"><i class="bi bi-hash me-1"></i>Cantidad Alta</th>
                            <th class="text-end"><i class="bi bi-graph-down me-1"></i>Merma Estándar</th>
                            <th class="text-end"><i class="bi bi-exclamation-triangle me-1"></i>Merma Real</th>
                            <th class="text-end"><i class="bi bi-exclamation-triangle me-1"></i>Merma Recalentado</th>
                            <th><i class="bi bi-calendar3 me-1"></i>Fecha</th>
                            <th class="text-center"><i class="bi bi-gear me-1"></i>Acciones</th>
                            <th><i class="bi bi-database me-1"></i>Interfazado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($concentrado as $tConcentrado)
                            @php
                                $piezasActivas = $tConcentrado->Detalle
                                    ->where('Status', 0)
                                    ->whereNull('CantMermaRecalentado');

                                $cantidad = $piezasActivas->sum('Cantidad');
                                $mermaReal = $tConcentrado->CantidadMatPrima - $cantidad;
                                $mermaRecalentado = $tConcentrado->Detalle
                                    ->where('Status', 0)
                                    ->sum('CantMermaRecalentado');
                                $excedeMerma = $mermaReal > $tConcentrado->MermaStnd;
                                $totalPiezas = $tConcentrado->Detalle->count();
                            @endphp
                            <tr style="text-wrap: nowrap;">
                                <td style="font-weight: 600; color: #0f172a;">{{ $tConcentrado->IdRosticero }}</td>
                                <td>
                                    <span style="font-weight: 500;">{{ $tConcentrado->CodigoMatPrima }}</span>
                                    <small
                                        style="color: #64748b; display: block; font-size: 0.78rem;">{{ $tConcentrado->ArticuloMatPrima }}</small>
                                </td>
                                <td
                                    class="text-end"
                                    style="font-weight: 500;"
                                >{{ $tConcentrado->CantidadMatPrima }}</td>
                                <td>
                                    <span style="font-weight: 500;">{{ $tConcentrado->CodigoVenta }}</span>
                                    <small
                                        style="color: #64748b; display: block; font-size: 0.78rem;">{{ $tConcentrado->ArticuloVenta }}</small>
                                </td>
                                <td
                                    class="text-end"
                                    style="font-weight: 500;"
                                >
                                    {{ $cantidad }}
                                    <small
                                        style="color: #94a3b8; display: block; font-size: 0.7rem;">{{ $totalPiezas }}
                                        pzas</small>
                                </td>
                                <td class="text-end">{{ $tConcentrado->MermaStnd }}</td>
                                <td
                                    class="text-end"
                                    style="font-weight: 500; {{ $excedeMerma ? 'color: #ef4444;' : '' }}"
                                >
                                    {{ $mermaReal }}
                                </td>
                                <td
                                    class="text-end"
                                    style="font-weight: 500; {{ $mermaRecalentado ? 'color: #ef4444;' : '' }}"
                                >
                                    {{ $mermaRecalentado > 0 ? $mermaRecalentado : '-' }}
                                </td>
                                <td style="font-size: 0.75rem; text-transform: uppercase;">
                                    {{ \Carbon\Carbon::parse($tConcentrado->Fecha)->locale('es')->isoFormat('DD MMMM YYYY, HH:mm') }}
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-2">
                                        <x-table.buttons.edit-button
                                            :id="$tConcentrado->IdRosticero"
                                            icon="list"
                                            modal="modalDetalle"
                                            title="Ver detalle"
                                            label="Ver detalle"
                                        />
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        @if ($tConcentrado->FechaInterfazBaja)
                                            <div style="background: #eff6ff; padding: 6px 10px; border-radius: 8px;">
                                                <div class="d-flex align-items-center gap-1">
                                                    <span
                                                        style="background: #3b82f6; color: white; padding: 2px 8px; border-radius: 20px; font-size: 0.7rem; font-weight: 600;"
                                                    >Baja</span>
                                                    <small
                                                        style="color: #64748b; font-size: 0.72rem;">{{ \Carbon\Carbon::parse($tConcentrado->FechaInterfazBaja)->format('d/m/Y H:i') }}</small>
                                                </div>
                                                <small
                                                    style="color: #475569; display: block; margin-top: 2px; font-size: 0.72rem;"
                                                >
                                                    <i
                                                        class="bi bi-person me-1"></i>{{ $tConcentrado->NombreUsuarioBaja }}
                                                    {{ $tConcentrado->ApellidosUsuarioBaja }}
                                                </small>
                                            </div>
                                        @endif
                                        @if ($tConcentrado->FechaInterfazAlta)
                                            <div style="background: #f0fdf4; padding: 6px 10px; border-radius: 8px;">
                                                <div class="d-flex align-items-center gap-1">
                                                    <span
                                                        style="background: #10b981; color: white; padding: 2px 8px; border-radius: 20px; font-size: 0.7rem; font-weight: 600;"
                                                    >Alta</span>
                                                    <small
                                                        style="color: #64748b; font-size: 0.72rem;">{{ \Carbon\Carbon::parse($tConcentrado->FechaInterfazAlta)->format('d/m/Y H:i') }}</small>
                                                </div>
                                                <small
                                                    style="color":
                                                    #475569;
                                                    display:
                                                    block;
                                                    margin-top:
                                                    2px;
                                                    font-size:
                                                    0.72rem;"
                                                >
                                                    <i
                                                        class="bi bi-person me-1"></i>{{ $tConcentrado->NombreUsuarioAlta }}
                                                    {{ $tConcentrado->ApellidosUsuarioAlta }}
                                                </small>
                                            </div>
                                        @endif
                                        @if ($tConcentrado->Status == 1)
                                            <span
                                                style="background: #fef2f2; color: #ef4444; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 500;"
                                            >Cancelado</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <x-table-empty-data
                                colspan="11"
                                title="Sin datos disponibles"
                                message="No se encontraron productos rostisados con los filtros seleccionados"
                                icon="search"
                                :action="true"
                                actionText="Limpiar filtros"
                                actionUrl="/ReporteRosticeroAdmin"
                            />
                        @endforelse
                    </tbody>
                </table>
                {{-- ✅ AQUÍ VAN LOS MODALES (fuera de la tabla) --}}
                @foreach ($concentrado as $tConcentrado)
                    @include('Reportes.ModalVerDetalleRostizado')
                @endforeach
            </div>
            @include('components.paginate', ['items' => $concentrado])
        </div>
    </x-card-gradient-header>
</x-page-container>
