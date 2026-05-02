@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Descuentos y promociones')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <x-layout.page-container>

        <!-- SECCIÓN 1: TITULO Y FILTROS -->
        <x-layout.section-card>
            <!-- Título y botones principales -->
            <div class="d-flex justify-content-sm-between align-items-end align-items-sm-start flex-column flex-sm-row mb-2">
                <x-title titulo="Descuentos y promociones" />
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
                        {{-- :active="$filtrosAvanzadosActivos" --}}
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
            class="flex-grow-1"
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
                                    <th>Id</th>
                                    <th>Descuento</th>
                                    <th>Tipo</th>
                                    <th>Tienda</th>
                                    <th>Plaza</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Fecha creado</th>
                                    <th>Fecha Deshabilitado</th>
                                    <th>Estatus</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($descuentos as $descuento)
                                    <tr>
                                        <td>{{ $descuento->IdEncDescuento }}</td>
                                        <td>{{ $descuento->NomDescuento }}</td>
                                        <td>{{ $descuento->NomTipoDescuento }}</td>
                                        <td>{{ $descuento->NomTienda ?? '-' }}</td>
                                        <td>{{ $descuento->NomPlaza ?? '-' }}</td>
                                        <td>{{ strftime('%d %B %Y, %H:%M', strtotime($descuento->FechaInicio)) }}</td>
                                        <td>{{ strftime('%d %B %Y, %H:%M', strtotime($descuento->FechaFin)) }}</td>
                                        <td>
                                            {{ strftime('%d %B %Y, %H:%M', strtotime($descuento->FechaCreacion)) }}
                                        </td>
                                        <td>
                                            @if ($descuento->FechaDesactivar)
                                                {{ strftime('%d %B %Y, %H:%M', strtotime($descuento->FechaDesactivar)) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $hoy = \Carbon\Carbon::now()->startOfDay();
                                                $fechaFin = \Carbon\Carbon::parse($descuento->FechaFin)->startOfDay();
                                            @endphp

                                            @if ($descuento->Status == 1)
                                                <span class="tags-red">Deshabilitado</span>
                                            @elseif ($fechaFin->lt($hoy))
                                                <span class="tags-blue">Expirado</span>
                                            @elseif ($descuento->Status == 0)
                                                <span class="tags-green">Activo</span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button
                                                    class="btn btn-sm btn-outline-primary d-flex align-items-center gap-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#ModalArticulos{{ $descuento->IdEncDescuento }}"
                                                    title="Detalle de descuento"
                                                >
                                                    @include('components.icons.list') Productos
                                                </button>
                                                <a
                                                    href="/EditarDescuento/{{ $descuento->IdEncDescuento }}"
                                                    class="btn btn-sm btn-outline-primary d-flex align-items-center gap-2"
                                                    title="Editar descuento"
                                                >
                                                    @include('components.icons.edit') Ver
                                                </a>
                                                @if ($descuento->Status == 0 && !$fechaFin->lt($hoy))
                                                    <button
                                                        class="btn btn-sm btn-outline-danger d-flex align-items-center gap-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#ModalEliminarConfirm{{ $descuento->IdEncDescuento }}"
                                                        title="Eliminar descuento"
                                                    >
                                                        @include('components.icons.arrow-down') Deshabilar
                                                    </button>
                                                @endif
                                            </div>
                                            @include('Descuentos.ModalArticulos')
                                            @include('Descuentos.ModalEliminarConfirm')
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
                        @include('components.paginate', ['items' => $descuentos])

                    </div>
                </div>
            </div>
        </div>
    </x-layout.page-container>
@endsection
