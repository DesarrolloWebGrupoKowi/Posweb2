@extends('plantillaBase.masterblade')
@section('title','Catálogo de Tipo de Menus')
@section('contenido')
<div class="container cuchi">
    <div>
        <h2 class="titulo">
            Catálogo de Tipos de Menús
        </h2>
    </div>
    <div>
        @include('Alertas.Alertas')
    </div>
    <div class="col-12">
        <div class="row">
                <div class="col-2">
            <form action="/CatTipoMenu">
                    <select class="form-select" name="activo" id="activo">
                        <option {!! $activo == 0 ? 'selected' : '' !!} value="0">Activos</option>
                        <option {!! $activo == 1 ? 'selected' : '' !!} value="1">Inactivos</option>
                    </select>
                </div>
                <div class="col-2">
                    <button class="btn"><span class="material-icons">search</span></button>
                </div>
            </form>
            <div class="col-8 Agregar">
                <button class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar"><span class="material-icons">add_circle</span></button>
            </div>
        </div>
        <div class="col-12">
            <div class="table-responsive table-sm">
                <table class="table table-striped table-sm table-responsive">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if(count($tipoMenus) <= 0)
                        <tr>
                            <td colspan="3">No Hay Tipo de Menús</td>
                        </tr>
                        @else
                        @foreach($tipoMenus as $tipoMenu)
                            <tr>
                                <td>{{$tipoMenu->IdTipoMenu}}</td>
                                <td>{{$tipoMenu->NomTipoMenu}}</td>
                                <td>
                                    <button class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#ModalEditar{{$tipoMenu->IdTipoMenu}}">
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
    </div>
</div>
<!--Modal Agregar Tipo de Menu-->
@include('TipoMenu.ModalAgregar')
@endsection