@extends('plantillaBase.masterblade')
@section('title','Catálogo de Listas de Precio')
@section('contenido')
    <div class="container cuchi">
        <div>
            <h2 class="titulo">Catálogo de Listas de Precio</h2>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>
        <form action="/CatListasPrecio">
            <div class="row">
                <div class="col-2">
                    <input type="text" name="txtListaPrecio" id="txtListaPrecio" class="form-control" placeholder="Lista de Precios" value="{{$filtroListaPrecio}}">
                </div>
                <div class="col-2">
                    <button class="btn">
                        <span class="material-icons">search</span>
                    </button>
                </div>
                <div class="col-2">
                    <a href="/CatListasPrecio" class="btn">
                        <span class="material-icons">visibility</span>
                    </a>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                        <span class="material-icons">add_circle_outline</span>
                    </button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-responsive table-striped table-sm">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Peso Minimo</th>
                        <th>Peso Maximo</th>
                        <th>Iva</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($listasPrecio) == 0)
                        <tr>
                            <td colspan="5">No Hay Listas de Precio!</td>
                        </tr> 
                    @else
                        @foreach ($listasPrecio as $listaPrecio)
                            <tr>
                                <td>{{$listaPrecio->IdListaPrecio}}</td>
                                <td>{{$listaPrecio->NomListaPrecio}}</td>
                                <td>{{$listaPrecio->PesoMinimo}}</td>
                                <td>{{$listaPrecio->PesoMaximo}}</td>
                                <td>{{$listaPrecio->PorcentajeIva}}</td>
                                <td>
                                    <button class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#ModalEditar{{$listaPrecio->IdListaPrecio}}">
                                        <span class="material-icons">edit</span>
                                    </button>
                                </td>
                            </tr>
                            <!--Modal Editar-->
                            @include('ListasPrecio.ModalEditar')
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@include('ListasPrecio.ModalAgregar')

<script src="js/ListasPrecioScript.js"></script>
@endsection