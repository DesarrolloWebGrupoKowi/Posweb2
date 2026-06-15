@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Reporte de Movimientos de Inventario')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <x-layout.page-container>
        <!-- SECCIÓN 1: TITULO Y FILTROS -->
        <x-layout.section-card>
            <!-- Título y botones principales -->
            <x-layout.section-title>
                <x-title titulo="Reporte de Movimientos de Inventario" />
                <div class="d-flex gap-2">
                    <x-filters.buttons.excel-button
                        route="/ReporteMovimientosInventario/exports"
                        :params="[
                            'idTienda' => request('idTienda'),
                            'fecha_inicio' => request('fecha_inicio'),
                            'fecha_fin' => request('fecha_fin'),
                            'fecha' => request('fecha'),
                            'cod_articulo' => request('cod_articulo'),
                            'nom_articulo' => request('nom_articulo'),
                            'id_movimiento' => request('id_movimiento'),
                            'usuario' => request('usuario'),
                            'num_nomina' => request('num_nomina'),
                            'referencia' => request('referencia'),
                            'id_caja' => request('id_caja'),
                        ]"
                    />
                    <x-filters.buttons.refresh-button />
                    <x-filters.buttons.home-button />
                </div>
            </x-layout.section-title>
            <!-- Formulario de filtros -->
            <x-filters.filter-form>
                <!-- Filtros Básicos -->
                <x-filters.filter-group>
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
                        :autofocus="true"
                    />
                    <x-filters.inputs.date-input
                        name="fecha_inicio"
                        label="Fecha Inicio"
                        :value="request('fecha_inicio')"
                    />
                    <x-filters.inputs.date-input
                        name="fecha_fin"
                        label="Fecha Fin"
                        :value="request('fecha_fin')"
                    />
                </x-filters.filter-group>


                <!-- Filtros Avanzados -->
                <x-filters.advanced-collapse
                    :active="$filtrosAvanzadosActivos"
                    :showBadge="true"
                >
                    <x-filters.filter-group>
                        <x-filters.inputs.text-input
                            name="cod_articulo"
                            label="Código Artículo"
                            placeholder="Buscar por código"
                            :value="request('cod_articulo')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="nom_articulo"
                            label="Nombre Artículo"
                            placeholder="Buscar por nombre"
                            :value="request('nom_articulo')"
                            compact="true"
                        />
                        <x-filters.inputs.select-input
                            name="id_movimiento"
                            label="Tipo Movimiento"
                            width="150px"
                            :options="$movimientosProducto->pluck('NomMovimiento', 'IdMovimiento')->toArray()"
                            :value="request('id_movimiento')"
                            placeholder="Seleccionar tipo de movimiento"
                        />
                        <x-filters.inputs.text-input
                            name="usuario"
                            label="Usuario"
                            placeholder="Nombre o usuario"
                            :value="request('usuario')"
                            compact="true"
                        />
                    </x-filters.filter-group>

                    <x-filters.filter-group>
                        <x-filters.inputs.text-input
                            name="num_nomina"
                            label="Nómina"
                            placeholder="Número de nómina"
                            :value="request('num_nomina')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="referencia"
                            label="Referencia"
                            placeholder="Buscar por referencia"
                            :value="request('referencia')"
                            compact="true"
                        />
                        <x-filters.inputs.text-input
                            name="id_caja"
                            label="ID Caja"
                            placeholder="ID de caja"
                            :value="request('id_caja')"
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
        </x-layout.section-card>

        <!-- SECCIÓN: TABLAS -->
        <div
            class="flex-grow-1 d-flex gap-4"
            {{-- style="min-height: 0;" --}}
        >
            <div
                class="d-flex flex-column flex-grow-1"
                {{-- style="flex: 2; min-width: 0; min-height: 0;" --}}
                style="min-width: 0;"
            >
                <div
                    class="card d-flex flex-column border-0 p-4"
                    style="border-radius: 10px; min-height: 0;"
                >
                    <div class="table-responsive content-table-sm">
                        <table class="table">
                            <thead class="table-head">
                                <tr>
                                    <th>ID</th>
                                    <th>Tienda</th>
                                    <th>Código</th>
                                    <th>Artículo</th>
                                    <th>Cantidad</th>
                                    <th>UOM</th>
                                    <th style="text-wrap: nowrap;">Fecha Movimiento</th>
                                    <th>Referencia</th>
                                    <th>Movimiento</th>
                                    <th>Usuario</th>
                                    <th>Nomina</th>
                                    <th>Empleado</th>
                                    <th style="text-wrap: nowrap;">ID Caja</th>
                                    <th style="text-wrap: nowrap;">Referencia ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $movimiento)
                                    <tr>
                                        <td>{{ $movimiento->IdDatHistorialMovimientos }}</td>
                                        <td style="min-width: 150px; text-wrap: nowrap">{{ $movimiento->NomTienda }}</td>
                                        <td>{{ $movimiento->CodArticulo }}</td>
                                        <td style="min-width: 200px;">{{ $movimiento->NomArticulo }}</td>
                                        <td class="text-end">{{ number_format($movimiento->CantArticulo, 2) }}</td>
                                        <td>{{ $movimiento->UOM }}</td>
                                        <td style="min-width: 150px;">
                                            {{ \Carbon\Carbon::parse($movimiento->FechaMovimiento)->format('d/m/Y H:i:s') }}
                                        </td>
                                        <td style="min-width: 150px;">{{ $movimiento->Referencia }}</td>
                                        <td>{{ $movimiento->NomMovimiento }}</td>
                                        <td>{{ $movimiento->NomUsuario }}</td>
                                        <td>{{ $movimiento->NumNomina }}</td>
                                        <td style="min-width: 200px;">{{ $movimiento->NombreEmpleado }}</td>
                                        <td>{{ $movimiento->IDCAJA }}</td>
                                        <td>{{ $movimiento->ReferenciaId }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td
                                            colspan="15"
                                            class="py-5 text-center"
                                        >
                                            <x-table-empty-state
                                                title="No hay movimientos de productos"
                                                icon="box"
                                                :message="'No se encontraron movimientos de productos para los criterios de búsqueda seleccionados.'"
                                                :suggestion="'Modifica las fechas, revisa los filtros de artículo, movimiento o usuario para encontrar los registros deseados.'"
                                                action="Ver movimientos de hoy"
                                                actionUrl="{{ request()->fullUrlWithQuery(['fecha' => now()->toDateString()]) }}"
                                            />
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @include('components.paginate', ['items' => $data])
                </div>
            </div>
        </div>

    </x-layout.page-container>
@endsection
