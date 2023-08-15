@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Tiendas')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <div class="container-fluid pt-4 width-95">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Tiendas'])
            <div>
                <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar tienda
                </button>
                <a href="/CatTiendas" class="btn btn-dark-outline">
                    <span class="material-icons">refresh</span>
                </a>
            </div>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <form class="d-flex align-items-center justify-content-end pb-4 gap-2" action="/CatTiendas" method="get">
            <div class="input-group" style="max-width: 300px">
                <select class="form-select" name="filtroEstado" id="filtroEstado">
                    @foreach ($estados as $estado)
                        <option {!! $estado->IdEstado == $filtroEstado ? 'selected' : '' !!} value="{{ $estado->IdEstado }}">{{ $estado->NomEstado }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="input-group" style="max-width: 300px">
                <select class="form-select" name="filtroCiudad" id="filtroCiudad">
                    <option selected value="0">Seleccione</option>
                </select>
            </div>
            <button class="btn btn-dark-outline">
                <span class="material-icons">search</span>
            </button>
        </form>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
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
                                    <button class="btn btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#ModalEditar{{ $tienda->IdTienda }}">
                                        <span class="material-icons">edit</span>
                                    </button>
                                    <button class="btn btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#ModalEliminar{{ $tienda->IdTienda }}">
                                        <span class="material-icons eliminar">delete_forever</span>
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
        </div>
    </div>
    <br>
    <div class="d-sm-flex justify-content-center">
        {!! $tiendas->links() !!}
    </div>
    <!--Modal Agregar Tienda-->
    @include('Tiendas.ModalAgregar')

@endsection

@section('scriptTiendas')

@endsection
