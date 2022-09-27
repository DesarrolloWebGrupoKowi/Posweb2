@extends('plantillaBase.masterblade')
@section('title','Catálogo de Articulos')
@section('contenido')
<div class="container mb-3">
    @include('Alertas.Alertas')
</div>
    <div class="personalizadoContainer cuchi mb-3">
        <div>
            <h2 class="titulo">Catálogo de Articulos</h2>
        </div>
        <form action="/CatArticulos">
            <div class="row">
                <div class="col-2">
                    <input type="text" name="txtFiltroArticulo" class="form-control" placeholder="Nombre" value="{{ $filtroArticulo }}">
                </div>
                <div class="col-1">
                    <button class="btn"><span class="material-icons">search</span></button>
                </div>
                <div class="col-1">
                    <a href="/CatArticulos" class="btn"><span class="material-icons">visibility</span></a>
                </div>
                <div class="col-8">
                    <a href="/BuscarArticulo" class="btn Agregar">
                        <span class="material-icons">loupe</span>
                    </a>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-responsive table-sm table-striped">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Amece</th>
                        <th>UOM</th>
                        <th>UOM2</th>
                        <th>Peso</th>
                        <th>Código Etiqueta</th>
                        <th>Precio Recorte</th>
                        <th>Factor</th>
                        <th>Familia</th>
                        <th>Grupo</th>
                        <th>Iva</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($articulos) == 0)
                        <tr>
                            <td colspan="13">No Hay Articulos</td>
                        </tr>
                    @else
                        @foreach ($articulos as $articulo)
                            <tr>
                                <td>{{$articulo->CodArticulo}}</td>
                                <td>{{$articulo->NomArticulo}}</td>
                                <td>{{$articulo->Amece}}</td>
                                <td>{{$articulo->UOM}}</td>
                                <td>{{$articulo->UOM2}}</td>
                                <td>{{$articulo->Peso}}</td>
                                <td>{{$articulo->CodEtiqueta}}</td>
                                <td>{{$articulo->PrecioRecorte}}</td>
                                <td>{{$articulo->Factor}}</td>
                                <td>{{$articulo->NomFamilia}}</td>
                                <td>{{$articulo->NomGrupo}}</td>
                                <td>
                                    @if ($articulo->Iva == 0)
                                        Si
                                    @else
                                        No
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#ModalEditar-{{$articulo->CodArticulo}}">
                                        <span style="font-size: 18px" class="material-icons">edit</span>
                                    </button>
                                </td>
                                @include('Articulos.ModalEditar')
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-flex justify-content-center">
        {!! $articulos->links() !!}
    </div>
@endsection