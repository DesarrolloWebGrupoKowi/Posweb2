@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Tipo de Menus')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Tipos de Menús'])
            <div>
                <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                    class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar tipo de menu
                </button>
            </div>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <form class="d-flex align-items-center justify-content-end pb-4 gap-4" action="/CatTipoMenu">
            <div class="input-group" style="max-width: 300px">
                <select class="form-select" name="activo" id="activo">
                    <option {!! $activo == 0 ? 'selected' : '' !!} value="0">Activos</option>
                    <option {!! $activo == 1 ? 'selected' : '' !!} value="1">Inactivos</option>
                </select>
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
                        <th>Nombre</th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @if (count($tipoMenus) <= 0)
                        <tr>
                            <td colspan="3">No Hay Tipo de Menús</td>
                        </tr>
                    @else
                        @foreach ($tipoMenus as $tipoMenu)
                            <tr>
                                <td>{{ $tipoMenu->IdTipoMenu }}</td>
                                <td>{{ $tipoMenu->NomTipoMenu }}</td>
                                <td>
                                    <button class="btn btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#ModalEditar{{ $tipoMenu->IdTipoMenu }}">
                                        <span class="material-icons">edit</span>
                                    </button>
                                </td>
                            </tr>
                            @include('TipoMenu.ModalEditar')
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <!--Modal Agregar Tipo de Menu-->
    @include('TipoMenu.ModalAgregar')
@endsection
