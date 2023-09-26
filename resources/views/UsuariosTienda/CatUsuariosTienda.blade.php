@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Usuarios Tienda')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Usuarios Tienda'])
            <div>
                <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                    class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar usuario
                </button>
            </div>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>

        <form class="d-flex align-items-center justify-content-end pb-4 gap-2" action="/CatUsuariosTienda">
            <div class="input-group" style="max-width: 300px">
                <input class="form-control" type="text" name="txtUsuarioTienda" id="txtUsuarioTienda"
                    value="{{ $txtUsuarioTienda }}" placeholder="Usuario Tienda" autofocus>
                <div class="input-group-append">
                    <button class="input-group-text"><span class="material-icons">search</span></button>
                </div>
            </div>
        </form>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id</th>
                        <th>Usuario</th>
                        <th>Tienda</th>
                        <th>Plaza</th>
                        <th>Todas</th>
                        <th class="rounded-end">Acciones</th>
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

    <div class="mt-5 d-flex justify-content-center">
        {!! $usuariosTienda->links() !!}
    </div>
    <!--Modal Agregar Usuario Tienda-->
    @include('UsuariosTienda.ModalAgregar')
@endsection
