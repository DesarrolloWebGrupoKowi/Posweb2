@extends('plantillaBase.masterblade')
@section('title', 'Catálogo de Plazas')
@section('contenido')
    <div class="container cuchi">
        <div>
            <h2 class="titulo">Catálogo de Plazas</h2>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>
        <form action="/CatPlazas">
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-2">
                        <select class="form-select" name="activo" id="activo">
                            <option {!! $activo == 0 ? 'selected' : '' !!} value="0">Activas</option>
                            <option {!! $activo == 1 ? 'selected' : '' !!} value="1">Inactivas</option>
                        </select>
                    </div>
                    <div class="col-1">
                        <button class="btn btn-default"><span class="material-icons">search</span></button>
                    </div>
        </form>
        <div class="col-9">
            <button type="button" class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                <span class="material-icons">add_circle_outline</span>
            </button>
        </div>
    </div>
    </div>
    <div class="col-12">
        <div class="table-responsive table-sm">
            <table class="table table-striped table-responsive table-sm">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Ciudad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($plazas) <= 0)
                        <td colspan="5">No Hay Ninguna Plaza!</td>
                    @else
                        @foreach ($plazas as $plaza)
                            <tr>
                                <td>{{ $plaza->IdPlaza }}</td>
                                <td>{{ $plaza->NomPlaza }}</td>
                                <td>{{ $plaza->ccNomCiudad }}</td>
                                <td>{{ $plaza->ceNomEstado }}</td>
                                <td>
                                    <button class="btn btn-default btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#ModalEditar{{ $plaza->IdPlaza }}"><span
                                            class="material-icons">edit</span>
                                    </button>
                                </td>
                            </tr>
                            <!--Modal Editar Plaza-->
                            @include('Plazas.ModalEditar')
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    </div>
    <!--Modal Agregar Plaza-->
    @include('Plazas.ModalAgregar')
@endsection
