@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Tipo de Usuarios')
@section('dashboardWidth', 'max-width: 1440px;')
@section('contenido')
    <div class="container-fluid pt-4" style="max-width: 1440px;">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Tipo de Usuarios'])
            <div class="">
                <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar tipo de usuario
                </button>
            </div>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <form class="d-flex align-items-center justify-content-end pb-4" action="/CatTipoUsuarios" id="formTipoUsuarios">
            <div class="input-group" style="max-width: 300px">
                <select class="form-select" name="filtroActivo" id="filtroActivo">
                    <option {!! $filtroActivo == 0 ? 'selected' : '' !!} value="0">Activos</option>
                    <option {!! $filtroActivo == 1 ? 'selected' : '' !!} value="1">Inactivos</option>
                </select>
            </div>
        </form>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id Tipo de Usuario</th>
                        <th>Tipo de Usuario</th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($tipoUsuarios) == 0)
                        <tr>
                            <td colspan="3">No Hay Tipo de Usuarios</td>
                        </tr>
                    @else
                        @foreach ($tipoUsuarios as $tipoUsuario)
                            <tr>
                                <td>{{ $tipoUsuario->IdTipoUsuario }}</td>
                                <td style="width: 70%">{{ $tipoUsuario->NomTipoUsuario }}</td>
                                <td>
                                    @if ($filtroActivo != 1)
                                        <button class="btn btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#ModalEditar{{ $tipoUsuario->IdTipoUsuario }}">
                                            <i class="material-icons">edit</i>
                                        </button>
                                        <button class="btn btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#ModalConfirmar{{ $tipoUsuario->IdTipoUsuario }}">
                                            <i class="material-icons eliminar">delete_forever</i>
                                        </button>
                                    @endif
                                </td>
                                <!--Modal Editar-->
                                @include('TipoUsuarios.ModalEditar')
                                <!--Modal Confirmar-->
                                @include('TipoUsuarios.ModalConfirmar')
                        @endforeach
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!--Modal Agregar Tipo de Usuario-->
    @include('TipoUsuarios.ModalAgregar')


    <script src="js/scriptTipoUsuarios.js"></script>
@endsection
