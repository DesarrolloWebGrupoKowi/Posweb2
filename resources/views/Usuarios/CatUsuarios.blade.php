@extends('plantillaBase.masterblade')
@section('title', 'Catálogo de Usuarios')
@section('contenido')
<div class="container-fluid">
    @include('Alertas.Alertas')
</div>
<div class="container cuchi">
    <div>
        <h2 class="titulo">Catálogo de Usuarios</h2>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <form action="/CatUsuarios" method="get">
                <div class="row">
                    <div class="col-sm-2 my-1">
                        <input type="text" class="form-control" name="txtFiltro" value="{{ $txtFiltro }}" placeholder="Buscar Usuario">
                    </div>
                    <div class="col-sm-1 my-1">
                        <button class="btn btn-default Buscar"><span class="material-icons">search</span></button>
                    </div>
                    <div class="col-sm-2 my-1">
                        <select class="form-select" name="Activo">
                            <option {!! $Activo=='0' ? 'selected' : '' !!} value="0">Activos</option>
                            <option {!! $Activo=='1' ? 'selected' : '' !!} value="1">Inactivos</option>
                        </select>
                    </div>
                    <div class="col-sm-1 my-1">
                        <button class="btn btn-default"><span class="material-icons">find_replace</span></button>
                    </div>
                    <div class="col-sm-6 my-1">
                        <button type="button" role="tooltip" title="Agregar Usuario" class="btn btn-default Agregar"
                            data-bs-toggle="modal" data-bs-target="#ModalAgregar"><span
                                class="material-icons">person_add</span></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-xl-12">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Nómina</th>
                            <th>Correo</th>
                            <th>Tipo de Usuario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($usuarios) <= 0) <tr>
                            <td colspan="8">El Usuario No Existe!</td>
                            </tr>
                            @else
                            @if ($Activo == 1)
                            @foreach ($usuarios as $usuario)
                            <tr>
                                <td>{{ $usuario->NomUsuario }}</td>
                                <td>{{ $usuario->NumNomina }}</td>
                                <td>{{ $usuario->Correo }}</td>
                                <td>{{ $usuario->NomTipoUsuario }}</td>
                                <td>
                                    <button class="btn btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalActivarUsuario{{ $usuario->IdUsuario }}">
                                        <span class="material-icons" style="color: blue">toggle_on</span>
                                    </button>
                                </td>
                            </tr>
                            <!--Modal Activar Usuario-->
                            @include('Usuarios.ModalActivarUsuario')
                            @endforeach
                            @else
                            @foreach ($usuarios as $usuario)
                            <tr>
                                <td>{{ $usuario->NomUsuario }}</td>
                                <td>{{ $usuario->NumNomina }}</td>
                                <td>{{ $usuario->Correo }}</td>
                                <td>{{ $usuario->NomTipoUsuario }}</td>
                                <td>
                                    <button class="btn btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#ModalEditar{{ $usuario->IdUsuario }}-{{ $usuario->NomUsuario }}"><span
                                            class="material-icons">edit</span></button>
                                    <button class="btn btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#ModalEliminar{{ $usuario->IdUsuario }}-{{ $usuario->NomUsuario }}"><span
                                            class="material-icons eliminar">delete_forever</span></button>
                                    <button class="btn btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalCambiarPassword{{ $usuario->IdUsuario }}-{{ $usuario->NomUsuario }}"><span
                                            class="material-icons" style="color: green;">key</span></button>
                                </td>
                            </tr>
                            <!--Modal Editar Usuario-->
                            @include('Usuarios.ModalEditar')
                            <!--Modal Eliminar Usuario-->
                            @include('Usuarios.modalEliminar')
                            <!--Modal Valida Usuario-->
                            @include('Usuarios.ModalValidarUsuario')
                            <!--Modal Cambiar Contraseña-->
                            @include('Usuarios.ModalCambiarPassword')
                            @endforeach
                            @endif
                            @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<br>
<div class="d-flex justify-content-center">
    {!! $usuarios->links() !!}
</div>
<!--Modal Agregar Usuario-->
@include('Usuarios.ModalAgregar')
@endsection