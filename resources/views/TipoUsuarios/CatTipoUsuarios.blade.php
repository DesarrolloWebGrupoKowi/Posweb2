@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Tipo de Usuarios')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Tipo de Usuarios'])
                <div class="">
                    <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                        Agregar tipo de usuario @include('components.icons.plus-circle')
                    </button>
                </div>
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex align-items-center justify-content-end gap-2 pb-2" action="/CatTipoUsuarios"
                id="formTipoUsuarios">
                <div>
                    <select class="form-select rounded" style="line-height: 18px" name="filtroActivo" id="filtroActivo">
                        <option {!! $filtroActivo == 0 ? 'selected' : '' !!} value="0">Activos</option>
                        <option {!! $filtroActivo == 1 ? 'selected' : '' !!} value="1">Inactivos</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-dark-outline">
                        @include('components.icons.search')
                    </button>
                </div>
            </form>

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
                                        <button class="btn-table" data-bs-toggle="modal"
                                            data-bs-target="#ModalEditar{{ $tipoUsuario->IdTipoUsuario }}">
                                            @include('components.icons.edit')
                                        </button>
                                        <button class="btn-table btn-table-delete" data-bs-toggle="modal"
                                            data-bs-target="#ModalConfirmar{{ $tipoUsuario->IdTipoUsuario }}">
                                            @include('components.icons.delete')
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
