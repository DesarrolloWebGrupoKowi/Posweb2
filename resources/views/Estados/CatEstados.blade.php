@extends('plantillaBase.masterblade')
@section('title','Catálogo de Estados')
@section('contenido')
    <div class="container cuchi">
        <div>
        <h2 class="titulo">Catálogo de Estados</h2>
        </div>
        <div>
        @include('Alertas.Alertas')
        </div>
        <div class="row">
        <div class="col-xl-12">
        <form action="/CatEstados" method="get">
            <div class="row">
            <div class="col-sm-2 my-1">
                <select class="form-select" name="Activo" id="Activo">
                    <option {!! $activo == '' ? 'selected' : '' !!} value="0">Activos</option>
                    <option {!! $activo == '1' ? 'selected' : '' !!} value="1">Inactivos</option>
                </select>
            </div>
            <div class="col-2 my-1">
                <button class="btn btn-default"><span class="material-icons">search</span></button>
            </div>
            <div class="col-8 my-1">
            <button type="button" class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar"><span class="material-icons">add_circle_outline</span></button>
            </div>
            </div>
            </form>
        </div>
        </div>
        <div class="col-xl-12">
            <div class="table-responsive table-sm">
                <table class="table table-responsive table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Id Estado</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($estados as $estado)
                        <tr>
                            <td>{{$estado->IdEstado}}</td>
                            <td>{{$estado->NomEstado}}</td>
                            <td>
                            <button class="btn btn-default btn-sm" data-bs-toggle="modal" data-bs-target="#ModalEditar{{$estado->IdEstado}}">
                            <span class="material-icons">edit</span>
                            </button>
                            </td>
                        </tr>
                        <!--Modal Editar-->
                        @include('Estados.ModalEditar')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br>
    <div class="d-flex justify-content-center">
            {!! $estados->links() !!}
    </div>
        <!--Modal Agregar Estado-->
        @include('Estados.ModalAgregar')
@endsection