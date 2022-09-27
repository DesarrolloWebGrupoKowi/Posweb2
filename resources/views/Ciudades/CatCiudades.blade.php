@extends('plantillaBase.masterblade')
@section('title','Catálogo de Ciudades')
@section('contenido')
    <div class="container cuchi">
        <div>
        <h2 class="titulo">Catálogo de Ciudades</h2>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>
        <div class="row">
        <div class="col-12">
        <form action="/CatCiudades" method="get">
            <div class="row">
            <div class="col-2">
            <input class="form-control" type="text" name="txtFiltro" id="txtFiltro" placeholder="Ciudad" value="{{$txtFiltro}}">
            </div>
            <div class="col-2">
                <button class="btn btn-default">
                <span class="material-icons">search</span>
                </button>
            </div>
            <div class="col-8">
            <button type="button" class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar"><span class="material-icons">add_circle_outline</span></button>
            </div>
            </div>
            </form>
        </div>
        </div>
        <div class="col-xl-12">
            <div class="table-responsive">
                <table class="table table-sm table-responsive table-striped">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($ciudades) <= 0)
                        <td colspan="4">No Hay Coincidencias!</td>
                        @else
                        @foreach($ciudades as $ciudad)
                        <tr>
                            <td>{{$ciudad->IdCiudad}}</td>
                            <td>{{$ciudad->NomCiudad}}</td>
                            <td>{{$ciudad->NomEstado}}</td>
                            <td>
                                <button class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#ModalEditar{{$ciudad->IdCiudad}}"><span class="material-icons">edit</span></button>
                            </td>
                        </tr>
                        @include('Ciudades.ModalEditar')
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
            <!--Modal Agregar Estado-->
            @include('Ciudades.ModalAgregar')
    <br>
    <div class="d-flex justify-content-center">
            {!! $ciudades->links() !!}
    </div>
@endsection