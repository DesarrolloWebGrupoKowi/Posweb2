@extends('plantillaBase.masterblade')
@section('title', 'Catálogo de Tiendas')
@section('contenido')
    <div class="personalizadoContainer cuchi">
        <div class="row">
            <h2 class="titulo">Catálogo de Tiendas</h2>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>
        <div class="row">
            <div class="col-12">
                <form action="/CatTiendas" method="get">
                    <div class="row">
                        <div class="col-sm-2 my-2">
                            <select class="form-select" name="filtroEstado" id="filtroEstado">
                                @foreach ($estados as $estado)
                                    <option {!! $estado->IdEstado == $filtroEstado ? 'selected' : '' !!} value="{{ $estado->IdEstado }}">{{ $estado->NomEstado }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2 my-2">
                            <select class="form-select" name="filtroCiudad" id="filtroCiudad">
                                <option selected value="0">Seleccione</option>
                            </select>
                        </div>
                        <div class="col-sm-1 my-2">
                            <button class="btn btn-default"><span class="material-icons">search</span></button>
                        </div>
                        <div class="col-sm-1 my-2">
                            <a href="/CatTiendas" class="btn btn-default"><span class="material-icons">visibility</span></a>
                        </div>
                        <div class="col-5 my-1">
                            <button type="button" class="btn btn-default Agregar" data-bs-toggle="modal"
                                data-bs-target="#ModalAgregar">
                                <span class="material-icons">add_circle_outline</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-responsive table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Tienda</th>
                            <th>Telefono</th>
                            <th>Dirección</th>
                            <th>Ciudad</th>
                            <th>Acciones</th>
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
