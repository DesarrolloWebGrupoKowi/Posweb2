@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Menú Posweb')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Menús'])
            <div>
                <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                    class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar menú
                </button>
                <a href="/CatMenuPosweb" class="btn btn-dark-outline">
                    <span class="material-icons">refresh</span>
                </a>
            </div>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <form class="d-flex align-items-center justify-content-end pb-4 gap-2" action="/CatMenuPosweb">
            <div class="input-group" style="max-width: 300px">
                <input type="text" name="txtFiltroMenu" class="form-control" placeholder="Menú"
                    value="{{ $filtroMenu }}" autofocus>
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
                                <button class="btn btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#ModalEditar{{ $menuPosweb->cmpIdMenu }}">
                                    <span style="font-size: 18px" class="material-icons">edit</span>
                                </button>
                            </td>
                        </tr>
                        @include('Menus.ModalEditar')
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-5 d-flex justify-content-center">
        {!! $menusPosweb->links() !!}
    </div>
    @include('Menus.ModalAgregar')
@endsection
