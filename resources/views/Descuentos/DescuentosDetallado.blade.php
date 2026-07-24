<x-page-container title="Descuentos y Promociones Detallado">
    <x-card-gradient-header
        icon="tags"
        title="Descuentos y Promociones Detallado"
        subtitle="Vista detallada de artículos por descuento"
    >
        <x-slot:buttons>
            <x-header.buttons.home-button />
            <x-header.buttons.refresh-button />
        </x-slot:buttons>

        <x-form.form action="/VerDescuentos">
            <!-- Fila 1: Filtros principales -->
            <x-form.group>
                <x-form.select
                    name="idPlaza"
                    label="Plaza"
                    icon="building"
                    col="col-md-3"
                    placeholder="Todas las plazas"
                    :options="$plazas->pluck('NomPlaza', 'IdPlaza')->toArray()"
                    :selected="request('idPlaza')"
                />
                <x-form.select
                    name="idTienda"
                    label="Tienda"
                    icon="shop"
                    col="col-md-3"
                    placeholder="Todas las tiendas"
                    :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                    :selected="request('idTienda')"
                />
                <x-form.checkbox-input
                    name="activos"
                    label="Solo activos"
                    icon="check-circle"
                    :checked="request('activos') == 'on'"
                    col="col-md-2"
                />
                <x-form.checkbox-input
                    name="detallado"
                    label="Detallado"
                    icon="list-ul"
                    :checked="request('detallado') == 'on'"
                    col="col-md-2"
                />
                <div class="col-md-2">
                    <x-form.advanced-toggle :active="$filtrosAvanzadosActivos" />
                </div>
            </x-form.group>

            <!-- Fila 2: Filtros avanzados (ocultos) -->
            <x-form.advanced-panel :active="$filtrosAvanzadosActivos">
                <x-form.group>
                    <x-form.text
                        name="codigo"
                        label="Código"
                        icon="upc-scan"
                        placeholder="Código artículo"
                        col="col-md-3"
                        :value="request('codigo')"
                    />
                </x-form.group>
            </x-form.advanced-panel>
        </x-form.form>

        <div class="p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                <div>
                    <h5 class="section-content-title">
                        <i
                            class="bi bi-table me-2"
                            style="color: #64748b;"
                        ></i>Descuentos Detallados
                    </h5>
                    <p class="section-content-subtitle">{{ $descuentosDetallados->count() }} registros</p>
                </div>
                <a
                    href="/CatDescuentos"
                    class="btn-modern btn-agregar"
                >
                    <i class="bi bi-plus-circle"></i> Crear descuento
                </a>
            </div>

            <div
                class="table-responsive"
                style="max-height: 58vh; overflow-y: auto;"
            >
                <table class="table-hover table-custom table">
                    <thead style="position: sticky; top: 0; z-index: 2; background: #f8fafc;">
                        <tr>
                            <th><i class="bi bi-hash me-1"></i></th>
                            <th><i class="bi bi-tag me-1"></i>Descuento</th>
                            <th><i class="bi bi-upc me-1"></i>Código</th>
                            <th><i class="bi bi-box me-1"></i>Artículo</th>
                            <th class="text-end"><i class="bi bi-currency-dollar me-1"></i>Precio Desc.</th>
                            <th><i class="bi bi-calendar-check me-1"></i>Inicio</th>
                            <th><i class="bi bi-calendar-x me-1"></i>Fin</th>
                            <th><i class="bi bi-calendar-plus me-1"></i>Creado</th>
                            <th><i class="bi bi-calendar-minus me-1"></i>Desact.</th>
                            <th><i class="bi bi-gear me-1"></i>Tipo</th>
                            <th><i class="bi bi-shop me-1"></i>Tienda</th>
                            <th><i class="bi bi-building me-1"></i>Plaza</th>
                            <th><i
                                    class="bi bi-circle-fill me-1"
                                    style="font-size: 0.5rem;"
                                ></i>Estatus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($descuentosDetallados as $item)
                            @php
                                $hoy = \Carbon\Carbon::now()->startOfDay();
                                $fechaFin = \Carbon\Carbon::parse($item->FechaFin)->startOfDay();
                            @endphp
                            <tr
                                style="{{ $item->StatusDetalle == 1 ? 'text-decoration: line-through; color: #9ca3af; opacity: 0.6' : '' }}">
                                <td style="font-weight: 600; color: #0f172a;">{{ $item->IdEncDescuento }}</td>
                                <td>{{ $item->NomDescuento }}</td>
                                <td style="font-weight: 500;">{{ $item->CodArticulo ?? '-' }}</td>
                                <td>{{ $item->NomArticulo ?? '-' }}</td>
                                <td
                                    class="text-end"
                                    style="font-weight: 500;"
                                >
                                    {{ $item->PrecioDescuento !== null ? '$' . number_format($item->PrecioDescuento, 2) : '-' }}
                                </td>
                                <td style="color: #64748b;">
                                    {{ $item->FechaInicio ? strftime('%d %b %Y', strtotime($item->FechaInicio)) : '-' }}
                                </td>
                                <td style="color: #64748b;">
                                    {{ $item->FechaFin ? strftime('%d %b %Y', strtotime($item->FechaFin)) : '-' }}</td>
                                <td style="color: #64748b;">
                                    {{ $item->FechaCreacion ? strftime('%d %b %Y', strtotime($item->FechaCreacion)) : '-' }}
                                </td>
                                <td style="color: #64748b;">
                                    {{ $item->FechaDesactivar ? strftime('%d %b %Y', strtotime($item->FechaDesactivar)) : '-' }}
                                </td>
                                <td>{{ $item->NomTipoDescuento ?? '-' }}</td>
                                <td style="color: #64748b;">{{ $item->NomTienda ?? '-' }}</td>
                                <td style="color: #64748b;">{{ $item->NomPlaza ?? '-' }}</td>
                                <td>
                                    @if ($item->StatusDescuento == 1 || $item->StatusDetalle == 1)
                                        <span class="badge-status badge-inactive">Deshabilitado</span>
                                    @elseif ($fechaFin->lt($hoy))
                                        <span class="badge-status badge-pending">Expirado</span>
                                    @else
                                        <span class="badge-status badge-active">Activo</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13">
                                    <div class="py-5 text-center">
                                        <div class="empty-state-icon mx-auto mb-3">
                                            <i
                                                class="bi bi-tags fs-3"
                                                style="color: #94a3b8;"
                                            ></i>
                                        </div>
                                        <h6 class="text-muted">Sin descuentos ni promociones</h6>
                                        <small class="text-muted">No se encontraron resultados con los filtros
                                            seleccionados</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-card-gradient-header>
</x-page-container>
