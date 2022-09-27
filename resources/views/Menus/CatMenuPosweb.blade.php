@extends('plantillaBase.masterblade')
@section('title', 'Catálogo de Menú Posweb')
@section('contenido')
    <div class="container cuchi">
        <div>
            <h2 class="titulo">Catálogo de Menús</h2>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>
        <form action="/CatMenuPosweb">
            <div class="row">
                <div class="col-2">
                    <input type="text" name="txtFiltroMenu" class="form-control" placeholder="Menú"
                        value="{{ $filtroMenu }}">
                </div>
                <div class="col-2">
                    <button class="btn"><span class="material-icons">search</span></button>
                </div>
                <div class="col-1">
                    <a class="btn" href="/CatMenuPosweb">
                        <span class="material-icons">refresh</span>
                    </a>
                </div>
                <div class="col-7">
                    <button type="button" class="btn btn-default Agregar" data-bs-toggle="modal"
                        data-bs-target="#ModalAgregar">
                        <span class="material-icons">add_circle_outline</span>
                    </button>
                </div>
            </div>
        </form>
        <div class="col-12">
            <div class="table-responsive mb-3">
                <table class="table table-responsive table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>TipoMenu</th>
                            <th>Link</th>
                            <th>Icono</th>
                            <th>Background Color</th>
                            <th>Acciones</th>
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
    </div>
    <div class="d-flex justify-content-center">
        {!! $menusPosweb->links() !!}
    </div>
    @include('Menus.ModalAgregar')
@endsection
