@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Usuarios')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">

        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Usuarios'])
            <div class="">
                <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                    class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar usuario
                </button>
            </div>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <form class="d-flex align-items-center justify-content-end pb-4 gap-4" action="/CatUsuarios" method="get">
            <div class="input-group" style="max-width: 300px">
                <input type="text" class="form-control" name="txtFiltro" value="{{ $txtFiltro }}"
                    placeholder="Buscar Usuario">
                <div class="input-group-append">
                    <button class="input-group-text Buscar">
                        <span class="material-icons">search</span>
                    </button>
                </div>
            </div>
            <div class="input-group" style="max-width: 300px">
                <select class="form-select" name="Activo">
                    <option {!! $Activo == '0' ? 'selected' : '' !!} value="0">Activos</option>
                    <option {!! $Activo == '1' ? 'selected' : '' !!} value="1">Inactivos</option>
                </select>
                <div class="input-group-append">
                    <button class="input-group-text Buscar">
                        <span class="material-icons">find_replace</span>
                    </button>
                </div>
            </div>
        </form>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Usuario</th>
                        <th>Nómina</th>
                        <th>Correo</th>
                        <th>Tipo de Usuario</th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($usuarios) <= 0)
                        <tr>
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

        <div class="mt-5 d-flex justify-content-center">
            {!! $usuarios->links() !!}
        </div>
    </div>

    <!--Modal Agregar Usuario-->
    @include('Usuarios.ModalAgregar')
@endsection
