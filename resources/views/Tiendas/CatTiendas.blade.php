@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Tiendas')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <div class="container-fluid width-95 d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Tiendas'])
                <div>
                    <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                        Agregar tienda @include('components.icons.plus-circle')
                    </button>
                </div>
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex flex-wrap align-items-center justify-content-end gap-2 pb-2" action="/CatTiendas"
                method="get">
                <div class="input-group" style="max-width: 300px">
                    <select class="form-select rounded" style="line-height: 18px" name="filtroEstado" id="filtroEstado">
                        @foreach ($estados as $estado)
                            <option {!! $estado->IdEstado == $filtroEstado ? 'selected' : '' !!} value="{{ $estado->IdEstado }}">{{ $estado->NomEstado }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group" style="max-width: 300px">
                    <select class="form-select rounded" style="line-height: 18px" name="filtroCiudad" id="filtroCiudad">
                        <option selected value="0">Seleccione</option>
                    </select>
                </div>
                <button class="btn btn-dark-outline">
                    @include('components.icons.search')
                </button>
            </form>

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id</th>
                        <th>Tienda</th>
                        <th>Telefono</th>
                        <th>Dirección</th>
                        <th>Ciudad</th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($tiendas) <= 0)
                        <tr>
                            <td colspan="6">No Hay Tiendas!</td>
                        </tr>
                    @else
                        @foreach ($tiendas as $tienda)
                            <tr>
                                <td>{{ $tienda->IdTienda }}</td>
                                <td>{{ $tienda->NomTienda }}</td>
                                <td>{{ $tienda->Telefono }}</td>
                                <td>{{ $tienda->Direccion }}</td>
                                <td>{{ $tienda->ccNomCiudad }}</td>
                                <td>
                                    <button class="btn-table" data-bs-toggle="modal"
                                        data-bs-target="#ModalEditar{{ $tienda->IdTienda }}">
                                        @include('components.icons.edit')
                                    </button>
                                    <button class="btn-table btn-table-delete" data-bs-toggle="modal"
                                        data-bs-target="#ModalEliminar{{ $tienda->IdTienda }}">
                                        @include('components.icons.delete')
                                    </button>
                                </td>
                            </tr>
                            <!-- Modal Editar Informacion -->
                            @include('Tiendas.ModalEditar')
                            <!-- Modal Eliminar -->
                            @include('Tiendas.ModalEliminar')
                        @endforeach
                    @endif
                </tbody>
            </table>
            @include('components.paginate', ['items' => $tiendas])
        </div>
    </div>
    <!--Modal Agregar Tienda-->
    @include('Tiendas.ModalAgregar')

@endsection

@section('scripts')
    <script src="{{ asset('js/tiendasScript.js') }}"></script>
@endsection
