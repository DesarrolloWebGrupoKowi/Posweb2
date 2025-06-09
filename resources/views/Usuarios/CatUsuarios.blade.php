@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Usuarios')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Usuarios'])
                <div class="">
                    <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                        class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                        Agregar usuario @include('components.icons.plus-circle')
                    </button>
                </div>
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex flex-wrap justify-content-end gap-2 pb-2" action="/CatUsuarios" method="get">
                <div>
                    <label for="txtFiltro" class="text-secondary" style="font-weight: 500">Nombre:</label>
                    <input type="text" class="form-control rounded" style="line-height: 18px" name="txtFiltro"
                        value="{{ $txtFiltro }}" autofocus>
                </div>
                <div>
                    <label class="text-secondary" style="font-weight: 500">Tipo usuario:</label>
                    <select name="IdTipoUsuario" id="IdTipoUsuario" class="form-select rounded" style="line-height: 18px">
                        <option value=""> Todos </option>
                        @foreach ($tipoUsuarios as $tipoUsuario)
                            <option value="{{ $tipoUsuario->IdTipoUsuario }}"
                                {{ $idTipoUsuario == $tipoUsuario->IdTipoUsuario ? 'selected' : '' }}>
                                {{ $tipoUsuario->NomTipoUsuario }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-secondary" style="font-weight: 500">Estatus:</label>
                    <select name="estatus" class="form-select rounded" style="line-height: 18px">
                        <option value="-1"> Todos </option>
                        <option value="0" {{ $estatus == 0 ? 'selected' : '' }}> Activo </option>
                        <option value="1" {{ $estatus == 1 ? 'selected' : '' }}> Inactivo </option>
                    </select>
                </div>
                <div class="d-flex align-items-end">
                    <button class="btn btn-dark-outline">
                        @include('components.icons.search')
                    </button>
                </div>
            </form>

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Usuario</th>
                        <th>Nómina</th>
                        <th>Empleado</th>
                        <th>Correo</th>
                        <th>Tipo de Usuario</th>
                        <th>Estatus</th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $usuarios, 'colspan' => 8])
                    @foreach ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->NomUsuario }}</td>
                            <td>{{ $usuario->NumNomina }}</td>
                            <td>{{ $usuario->Nombre }} {{ $usuario->Apellidos }}</td>
                            <td>{{ $usuario->Correo }}</td>
                            <td>{{ $usuario->NomTipoUsuario }}</td>
                            <td>
                                @if ($usuario->Status == 1)
                                    <span class="tags-red">
                                        @include('components.icons.x')
                                    </span>
                                @else
                                    <span class="tags-green">
                                        @include('components.icons.check-all')
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if ($usuario->Status)
                                    <button class="btn-table" data-bs-toggle="modal"
                                        data-bs-target="#modalActivarUsuario{{ $usuario->IdUsuario }}"
                                        title="Activar usuario">
                                        @include('components.icons.switch')
                                    </button>
                                @else
                                    <button class="btn-table" data-bs-toggle="modal"
                                        data-bs-target="#ModalEditar{{ $usuario->IdUsuario }}-{{ $usuario->NomUsuario }}"
                                        title="Modificar usuario">
                                        @include('components.icons.edit')
                                    </button>
                                    <button class="btn-table btn-table-delete" data-bs-toggle="modal"
                                        data-bs-target="#ModalEliminar{{ $usuario->IdUsuario }}-{{ $usuario->NomUsuario }}"
                                        title="Desactivar usuario">
                                        @include('components.icons.delete')
                                    </button>
                                    <button class="btn-table btn-table-success" data-bs-toggle="modal"
                                        data-bs-target="#modalCambiarPassword{{ $usuario->IdUsuario }}-{{ $usuario->NomUsuario }}"
                                        title="Cambiar contraseña">
                                        @include('components.icons.key')
                                    </button>
                                @endif
                            </td>
                            </td>
                        </tr>
                        @include('Usuarios.ModalActivarUsuario')
                        <!--Modal Editar Usuario-->
                        @include('Usuarios.ModalEditar')
                        <!--Modal Eliminar Usuario-->
                        @include('Usuarios.modalEliminar')
                        <!--Modal Valida Usuario-->
                        @include('Usuarios.ModalValidarUsuario')
                        <!--Modal Cambiar Contraseña-->
                        @include('Usuarios.ModalCambiarPassword')
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $usuarios])
        </div>
    </div>

    <!--Modal Agregar Usuario-->
    @include('Usuarios.ModalAgregar')
@endsection
