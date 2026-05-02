@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Descuentos y promociones detallado')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <x-layout.page-container>

        <!-- SECCIÓN 1: TITULO Y FILTROS -->
        <x-layout.section-card>
            <!-- Título y botones principales -->
            <div class="d-flex justify-content-sm-between align-items-end align-items-sm-start flex-column flex-sm-row mb-2">
                <x-title titulo="Descuentos y promociones detallado" />
                <div class="d-flex gap-2">
                    <x-filters.buttons.refresh-button />
                    <x-filters.buttons.home-button />
                </div>
            </div>

            <!-- Formulario de filtros -->
            <x-filters.filter-form>
                <!-- Filtros Básicos -->
                <x-filters.filter-group>
                    <x-filters.inputs.select-input
                        name="idPlaza"
                        label="Plaza"
                        :options="$plazas->pluck('NomPlaza', 'IdPlaza')->toArray()"
                        :value="request('idPlaza')"
                    />
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
                    />
                    <x-filters.inputs.checkbox-input
                        name="activos"
                        label="Activos"
                        :checked="request('activos') == 'on'"
                        helperText="Ver activos"
                    />
                </x-filters.filter-group>
                <!-- Filtros Avanzados -->
                <x-filters.advanced-collapse
                    :active="$filtrosAvanzadosActivos"
                    :showBadge="true"
                >
                    <x-filters.filter-group>
                        <x-filters.inputs.text-input
                            name="codigo"
                            label="Código"
                            placeholder="Código articulo"
                            compact="true"
                        />
                        <x-filters.inputs.checkbox-input
                            name="detallado"
                            label="Detallado"
                            :checked="request('detallado') == 'on'"
                            helperText="Ver detallado"
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

            <div>
                @include('Alertas.Alertas')
            </div>
        </x-layout.section-card>

        <!-- SECCIÓN: TABLAS -->
        <div
            class="flex-grow-1 d-flex gap-4"
            style="min-height: 0;"
        >
            <div
                class="d-flex flex-column"
                style="flex: 2; min-width: 0; min-height: 0;"
            >
                <div
                    class="card d-flex flex-column border-0 p-4"
                    style="border-radius: 10px; min-height: 0;"
                >
                    <div class="d-flex justify-content-end mb-3">
                        <x-filters.buttons.link-button
                            href="/CatDescuentos"
                            text="Crear descuento"
                            color="primary"
                        />
                    </div>
                    <div class="table-responsive content-table-sm">
                        <table class="table">
                            <thead class="table-head">
                                <tr>
                                    <th>#</th>
                                    <th>NomDescuento</th>
                                    <th>CodArticulo</th>
                                    <th>NomArticulo</th>
                                    <th>PrecioDescuento</th>
                                    <th>FechaInicio</th>
                                    <th>FechaFin</th>
                                    <th>FechaCreacion</th>
                                    <th>FechaDesactivar</th>
                                    <th>NomTipoDescuento</th>
                                    <th>NomTienda</th>
                                    <th>NomPlaza</th>
                                    <th>Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($descuentosDetallados as $item)
                                    <tr
                                        style="{{ $item->StatusDetalle == 1 ? 'text-decoration: line-through; color: #9ca3af; opacity: 0.6' : '' }}">
                                        <td>{{ $item->IdEncDescuento }}</td>
                                        <td>{{ $item->NomDescuento }}</td>
                                        <td>{{ $item->CodArticulo ?? '-' }}</td>
                                        <td>{{ $item->NomArticulo ?? '-' }}</td>
                                        <td>{{ $item->PrecioDescuento !== null ? number_format($item->PrecioDescuento, 2) : '-' }}
                                        </td>
                                        <td>{{ $item->FechaInicio ? strftime('%d %B %Y, %H:%M', strtotime($item->FechaInicio)) : '-' }}
                                        </td>
                                        <td>{{ $item->FechaFin ? strftime('%d %B %Y, %H:%M', strtotime($item->FechaFin)) : '-' }}
                                        </td>
                                        <td>{{ $item->FechaCreacion ? strftime('%d %B %Y, %H:%M', strtotime($item->FechaCreacion)) : '-' }}
                                        </td>
                                        <td>
                                            @if ($item->FechaDesactivar)
                                                {{ strftime('%d %B %Y, %H:%M', strtotime($item->FechaDesactivar)) }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>{{ $item->NomTipoDescuento ?? '-' }}</td>
                                        <td>{{ $item->NomTienda ?? '-' }}</td>
                                        <td>{{ $item->NomPlaza ?? '-' }}</td>
                                        <td>
                                            @php
                                                $hoy = \Carbon\Carbon::now()->startOfDay();
                                                $fechaFin = \Carbon\Carbon::parse($item->FechaFin)->startOfDay();
                                                $itemStatus = $item->StatusDescuento ?? ($item->Status ?? null);
                                            @endphp
                                            @if ($item->StatusDescuento == 1 || $item->StatusDetalle == 1)
                                                <span class="tags-red">Deshabilitado</span>
                                            @elseif ($fechaFin->lt($hoy))
                                                <span class="tags-blue">Expirado</span>
                                            @elseif ($item->StatusDescuento == 0 && $item->StatusDetalle == 0)
                                                <span class="tags-green">Activo</span>
                                            @endif
                                        </td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td
                                            colspan="15"
                                            class="py-5 text-center"
                                        >
                                            <x-table-empty-state
                                                title="No hay descuentos ni promociones"
                                                icon="tags"
                                                :message="'No se encontraron descuentos o promociones para los criterios de búsqueda seleccionados.'"
                                                :suggestion="'Revisa los filtros de fecha, tienda o producto, o crea nuevas promociones para aumentar las ventas.'"
                                                action="Ver promociones activas"
                                                actionUrl="{{ request()->fullUrlWithQuery(['activo' => 1]) }}"
                                            />
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
    </style>
@endsection
