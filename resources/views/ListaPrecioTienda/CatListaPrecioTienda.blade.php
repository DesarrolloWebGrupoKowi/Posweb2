@extends('plantillaBase.masterblade')
@section('title','Catálogo Listas de Precios Tienda')
@section('contenido')
    <div class="container cuchi">
        <div>
            <h2 class="titulo">Catálogo de Lista de Precios por Tienda</h2>
        </div>
        <div class="mb-3">
            @include('Alertas.Alertas')
        </div>
        <form id="formListaP" action="/CatListaPrecioTienda">
            <div class="row">
                <div class="col-3 mb-3">
                    <select class="form-select" name="filtroIdTienda" onchange="mostrarListas()">
                        <option {!! $idTienda != '' ? 'disabled' : '' !!} value="">Seleccione Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{$tienda->IdTienda}}">{{$tienda->NomTienda}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <button class="btn btnRemove" style="margin-left: 80%" onclick="removerLista()">
                        <i class="fa fa-remove"></i> Remover
                    </button>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-responsive">
                            <thead>
                                <th>Lista de Precio ({{count($listasPrecioTienda)}})</th>
                                <th>Seleccionar</th>
                            </thead>
                            <tbody>
                                @foreach ($listasPrecioTienda as $listaPrecioTienda)
                                    <tr>
                                        <td>{{$listaPrecioTienda->NomListaPrecio}}</td>
                                        <td><input class="form-check-input" type="checkbox" name="chkRemover[]" value="{{$listaPrecioTienda->IdListaPrecio}}"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-6">
                    <button class="btn btnAgregar" style="margin-left: 80%" onclick="agregarLista()">
                        <i class="fa fa-plus"></i> Agregar
                    </button>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-responsive">
                            <thead>
                                <th>Lista de Precio ({{count($listasPrecio)}})</th>
                                <th>Seleccionar</th>
                            </thead>
                            <tbody>
                                @foreach ($listasPrecio as $listaPrecio)
                                    <tr>
                                        <td>{{$listaPrecio->NomListaPrecio}}</td>
                                        <td><input class="form-check-input" type="checkbox" name="chkAgregar[]" value="{{$listaPrecio->IdListaPrecio}}"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection