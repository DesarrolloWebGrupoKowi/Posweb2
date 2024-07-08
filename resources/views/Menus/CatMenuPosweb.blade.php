@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Menú Posweb')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Menús'])
                <div>
                    <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                        class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                        Agregar menú @include('components.icons.plus-circle')
                    </button>
                </div>
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            @include('components.table-search')

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id</th>
                        <th>Nombre</th>
                        <th>TipoMenu</th>
                        <th>Link</th>
                        <th>Icono</th>
                        <th>Background Color</th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($menusPosweb as $menuPosweb)
                        <tr>
                            <td>{{ $menuPosweb->cmpIdMenu }}</td>
                            <td>{{ $menuPosweb->cmpNomMenu }}</td>
                            <td>{{ $menuPosweb->ctmNomTipoMenu }}</td>
                            <td>{{ $menuPosweb->cmpLink }}</td>
                            <td>{{ $menuPosweb->cmpIcono }}</td>
                            <td>{{ $menuPosweb->cmpBgColor }}</td>
                            <td>
                                <button class="btn-table" data-bs-toggle="modal"
                                    data-bs-target="#ModalEditar{{ $menuPosweb->cmpIdMenu }}">
                                    @include('components.icons.edit')
                                </button>
                            </td>
                        </tr>
                        @include('Menus.ModalEditar')
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $menusPosweb])
        </div>
    </div>
    @include('Menus.ModalAgregar')
@endsection
