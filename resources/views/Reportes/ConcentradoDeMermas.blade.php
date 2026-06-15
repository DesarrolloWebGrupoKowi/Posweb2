@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Concentrado de Mermas')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <x-layout.page-container>
        <!-- SECCIÓN 1: TITULO Y FILTROS -->
        <x-layout.section-card>
            <!-- Título y botones principales -->
            <x-layout.section-title>
                <x-title titulo="Concentrado de Mermas" />
                <div class="d-flex gap-2">
                    <x-filters.buttons.excel-button
                        route="/ReporteMermasAdminExcel"
                        :params="[
                            'idTienda' => request('idTienda'),
                            'fecha1' => request('fecha1'),
                            'fecha2' => request('fecha2'),
                            'txtFiltro' => request('txtFiltro'),
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
                    />
                    <x-filters.inputs.date-input
                        name="fecha1"
                        label="Fecha"
                        :value="request('fecha1')"
                        :autofocus="true"
                    />
                    <x-filters.inputs.date-input
                        name="fecha2"
                        label="Fecha"
                        :value="request('fecha2')"
                        :autofocus="true"
                    />
                    <x-filters.inputs.text-input
                        name="txtFiltro"
                        label="Artículo"
                        placeholder="Buscar por código o artículo"
                    />
                </x-filters.filter-group>
                <x-slot:buttons>
                    <x-filters.buttons.clear-button />
                    <x-filters.buttons.submit-button />
                </x-slot:buttons>
            </x-filters.filter-form>
        </x-layout.section-card>

        <!-- SECCIÓN: TABLAS -->
        <div
            class="flex-grow-1 d-flex gap-4 pb-4"
            {{-- style="min-height: 0;" --}}
        >
            <div
                class="d-flex flex-column"
                {{-- style="flex: 2; min-width: 0; min-height: 0;" --}}
                style="flex: 2; min-width: 0;"
            >
                <div
                    class="card d-flex flex-column border-0 p-4"
                    {{-- style="border-radius: 10px; min-height: 0;" --}}
                    style="border-radius: 10px;"
                >
                    <div class="table-responsive content-table-sm">
                        <table class="table">
                            <thead class="table-head">
                                <tr>
                                    <th class="rounded-start">Folio</th>
                                    <th>Código</th>
                                    <th>Articulo</th>
                                    <th>Tienda</th>
                                    <th>Captura</th>
                                    <th>Merma</th>
                                    <th class="text-center">Cantidad</th>
                                    <th>Comentario</th>
                                    <th>Interfaz</th>
                                    <th class="rounded-end">Interfazado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($concentrado as $tConcentrado)
                                    <tr>
                                        {{-- <td>{{ $tConcentrado->IdMerma }}</td> --}}
                                        <td>{{ $tConcentrado->FolioMerma }}</td>
                                        <td>{{ $tConcentrado->CodArticulo }}</td>
                                        <td>{{ $tConcentrado->NomArticulo }}</td>
                                        <td>{{ $tConcentrado->NomTienda }}</td>
                                        <td>{{ strftime('%d %B %Y, %H:%M', strtotime($tConcentrado->FechaCaptura)) }}</td>
                                        <td>{{ $tConcentrado->NomTipoMerma }}</td>
                                        <td
                                            style="text-align: right"
                                            class="fw-bold"
                                        >
                                            <p class="m-0 pe-3">{{ number_format($tConcentrado->CantArticulo, 3) }}</p>
                                        </td>
                                        <td
                                            class="puntitos"
                                            title="{{ $tConcentrado->Comentario }}"
                                        >
                                            {{ $tConcentrado->Comentario }}
                                        </td>
                                        <td>
                                            {{ $tConcentrado->FechaInterfaz ? strftime('%d %B %Y, %H:%M', strtotime($tConcentrado->FechaInterfaz)) : '' }}
                                        </td>
                                        <td>
                                            @if ($tConcentrado->FechaInterfaz)
                                                <i class="fa fa-check"></i>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td
                                            colspan="14"
                                            class="py-5 text-center"
                                        >
                                            <x-table-empty-state
                                                title="Sin datos disponibles"
                                                icon="filter"
                                                :message="'No se encontraron resultados con los filtros seleccionados.'"
                                                :suggestion="'Intenta ampliar el rango de fechas o modificar los criterios de búsqueda.'"
                                                action="Limpiar filtros"
                                                actionUrl="/SolicitudesFactura"
                                            />
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @include('components.paginate', ['items' => $concentrado])
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
