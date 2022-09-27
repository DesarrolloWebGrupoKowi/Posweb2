@extends('plantillaBase.masterblade')
@section('title', 'Catálogo de Usuarios Tienda')
@section('contenido')
    <div class="container cuchi">
        <div>
            <h2 class="titulo">Catálogo de Usuarios Tienda</h2>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>
        <div class="col-xl-12">
            <div class="row">
                <div class="col-3">
                    <form action="/CatUsuariosTienda">
                        <input class="form-control" type="text" name="txtUsuarioTienda" id="txtUsuarioTienda"
                            value="{{ $txtUsuarioTienda }}" placeholder="Usuario Tienda">
                </div>
                <div class="col-1">
                    <button class="btn btn-default">
                        <span class="material-icons">search</span>
                    </button>
                </div>
                </form>
                <div class="col-8">
                    <button type="button" class="btn btn-default Agregar" data-bs-toggle="modal"
                        data-bs-target="#ModalAgregar"><span class="material-icons">add_circle_outline</span></button>
                </div>
            </div>
            <div class="col-12">
                <div class="table-responsive table-sm">
                    <table class="table table-responsive table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Usuario</th>
                                <th>Tienda</th>
                                <th>Plaza</th>
                                <th>Todas</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($usuariosTienda) <= 0)
                                <td colspan="6">No se Encontro Ningun Usuario!</td>
                            @endif
                            @foreach ($usuariosTienda as $usuarioTienda)
                                <tr>
                                    <td>{{ $usuarioTienda->IdUsuarioTienda }}</td>
                                    <td>{{ $usuarioTienda->NomUsuario }}</td>
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
                                        <td><span class="material-icons check">done</span></td>
                                    @else
                                        <td><span class="material-icons check">clear</span></td>
                                    @endif
                                    <td>
                                        <button class="btn btn-default btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#ModalEditar{{ $usuarioTienda->IdUsuarioTienda }}">
                                            <span class="material-icons">edit</span>
                                        </button>
                                        <button class="btn btn-default btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#ModalEliminar{{ $usuarioTienda->IdUsuarioTienda }}">
                                            <span class="material-icons eliminar">delete_forever</span>
                                        </button>
                                    </td>
                                </tr>
                                @include('UsuariosTienda.ModalEditar')
                                @include('UsuariosTienda.ModalEliminar')
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="d-flex justify-content-center">
        {!! $usuariosTienda->links() !!}
    </div>
    <!--Modal Agregar Usuario Tienda-->
    @include('UsuariosTienda.ModalAgregar')
@endsection
