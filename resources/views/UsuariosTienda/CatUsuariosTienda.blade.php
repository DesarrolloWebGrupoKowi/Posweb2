@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Usuarios Tienda')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <x-layout.page-container>

        <!-- SECCIÓN 1: TITULO Y FILTROS -->
        <x-layout.section-card>
            <!-- Título y botones principales -->
            <x-layout.section-title>
                <x-title titulo="Catálogo de Usuarios Tienda" />
                <div class="d-flex gap-2">
                    <x-filters.buttons.refresh-button />
                    <x-filters.buttons.home-button />
                </div>
            </x-layout.section-title>
            <!-- Formulario de filtros -->
            <x-filters.filter-form>
                <!-- Filtros Básicos -->
                <x-filters.filter-group>
                    <x-filters.inputs.text-input
                        name="txtFiltro"
                        label="Nombre"
                        placeholder="Buscar usuario"
                        autofocus
                    />
                    <x-filters.inputs.select-input
                        name="IdTienda"
                        label="Tienda"
                        :options="$tiendas->pluck('NomTienda', 'IdTienda')->toArray()"
                    />
                    <x-filters.inputs.select-input
                        name="IdPlaza"
                        label="Sucursales"
                        :options="$plazas->pluck('NomPlaza', 'IdPlaza')->toArray()"
                    />
                    <x-filters.inputs.checkbox-input
                        name="enTodasLasTiendas"
                        label="Todas"
                        :checked="request('enTodasLasTiendas') == 'on'"
                        helperText="Usuarios en todas las tiendas"
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
                    <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center mb-3 gap-3">
                        <div class="flex-column">
                            <h5 class="mb-0 text-gray-800">CONCENTRADO DE USUARIOS</h5>
                        </div>
                        <div class="d-flex gap-2">
                            <button
                                type="button"
                                class="btn btn-outline-primary btn-sm"
                                role="tooltip"
                                title="Agregar Usuario"
                                class="btn btn-default Agregar"
                                data-bs-toggle="modal"
                                data-bs-target="#ModalAgregar"
                            >
                                Agregar usuario
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive content-table-sm">
                        <table class="table">
                            <thead class="table-head">
                                <tr>
                                    <th class="rounded-start">Nómina</th>
                                    <th>Empleado</th>
                                    <th>Usuario</th>
                                    <th>Tipo usuario</th>
                                    <th>Tienda</th>
                                    <th>Plaza</th>
                                    <th>Todas</th>
                                    <th class="rounded-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($usuariosTienda as $usuarioTienda)
                                    <tr>
                                        <td>{{ $usuarioTienda->NumNomina }}</td>
                                        <td>{{ $usuarioTienda->Nombre . ' ' . $usuarioTienda->Apellidos }}</td>
                                        <td>{{ $usuarioTienda->NomUsuario }}</td>
                                        <td>{{ $usuarioTienda->NomTipoUsuario }}</td>
                                        @if (empty($usuarioTienda->NomTienda))
                                            <td>-</td>
                                        @else
                                            <td>{{ $usuarioTienda->NomTienda }}</td>
                                        @endif
                                        @if (empty($usuarioTienda->NomPlaza))
                                            <td>-</td>
                                        @else
                                            <td>{{ $usuarioTienda->NomPlaza }}</td>
                                        @endif
                                        @if ($usuarioTienda->Todas == 0)
                                            <td>
                                                <span class="tags-green">
                                                    Si
                                                    <!--@include('components.icons.check-all')-->
                                                </span>
                                            </td>
                                        @else
                                            <td>
                                                <span class="tags-red">
                                                    No
                                                    <!--@include('components.icons.x')-->
                                                </span>
                                            </td>
                                        @endif
                                        <td>
                                            <div class="d-flex justify-content-start gap-2">
                                                <button
                                                    class="btn btn-sm btn-outline-primary d-flex align-items-center gap-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#ModalEditar{{ $usuarioTienda->IdUsuarioTienda }}"
                                                >
                                                    @include('components.icons.edit')
                                                </button>
                                                <button
                                                    class="btn btn-sm btn-outline-danger d-flex align-items-center gap-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#ModalEliminar{{ $usuarioTienda->IdUsuarioTienda }}"
                                                >
                                                    @include('components.icons.delete')
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @include('UsuariosTienda.ModalEditar')
                                    @include('UsuariosTienda.ModalEliminar')
                                @empty

                                    <tr>
                                        <td
                                            colspan="15"
                                            class="py-5 text-center"
                                        >
                                            <x-table-empty-state
                                                title="Sin datos disponibles"
                                                icon="filter"
                                                :message="'No se encontraron resultados con los filtros seleccionados.'"
                                                :suggestion="'Intenta ampliar el rango de fechas o modificar los criterios de búsqueda.'"
                                                action="Limpiar filtros"
                                                actionUrl="/CatUsuariosTienda"
                                            />
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @include('components.paginate', ['items' => $usuariosTienda])
                </div>
            </div>
        </div>
    </x-layout.page-container>

    <!--Modal Agregar Usuario Tienda-->
    @include('UsuariosTienda.ModalAgregar')
@endsection
