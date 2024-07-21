@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Tipo de Menus')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Tipos de Menús'])
                <div>
                    <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                        class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                        Agregar tipo de menu @include('components.icons.plus-circle')
                    </button>
                </div>
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>


        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex flex-wrap align-items-center justify-content-end gap-2 pb-2"action="/CatTipoMenu">
                <div class="col-auto">
                    <select class="form-select rounded" style="line-height: 18px" name="activo" id="activo">
                        <option {!! $activo == 0 ? 'selected' : '' !!} value="0">Activos</option>
                        <option {!! $activo == 1 ? 'selected' : '' !!} value="1">Inactivos</option>
                    </select>
                </div>
                <button class="btn btn-dark-outline">
                    @include('components.icons.search')
                </button>
            </form>

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
                                    <button class="btn-table" data-bs-toggle="modal"
                                        data-bs-target="#ModalEditar{{ $tipoMenu->IdTipoMenu }}">
                                        @include('components.icons.edit')
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
