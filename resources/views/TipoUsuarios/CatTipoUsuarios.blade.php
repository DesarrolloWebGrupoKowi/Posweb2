@extends('plantillaBase.masterblade')
@section('title','Catálogo de Tipo de Usuarios')
@section('contenido')
<div class="container cuchi mb-3">
    <div>
        <h2 class="titulo">Catálogo de Tipo de Usuarios</h2>
    </div>
        <div>
            @include('Alertas.Alertas')
        </div>
        <div class="col-xl-12">
            <form action="/CatTipoUsuarios" id="formTipoUsuarios">
                <div class="row">
                    <div class="col-2">
                        <select class="form-select" name="filtroActivo" id="filtroActivo">
                            <option {!! $filtroActivo == 0 ? 'selected' : '' !!} value="0">Activos</option>
                            <option {!! $filtroActivo == 1 ? 'selected' : '' !!} value="1">Inactivos</option>
                        </select>
                    </div>
                    <div class="col-10">
                        <button type="button" class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar"><span class="material-icons">add_circle</span></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-xl-12">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Id Tipo de Usuario</th>
                            <th>Tipo de Usuario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($tipoUsuarios) == 0)
                            <tr>
                                <td colspan="3">No Hay Tipo de Usuarios</td>
                            </tr>
                        @else
                            @foreach($tipoUsuarios as $tipoUsuario)
                            <tr>
                                <td>{{$tipoUsuario->IdTipoUsuario}}</td>
                                <td>{{$tipoUsuario->NomTipoUsuario}}</td>
                                <td>
                                @if ($filtroActivo != 1)
                                    <button class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#ModalEditar{{$tipoUsuario->IdTipoUsuario}}">
                                        <i class="material-icons">edit</i>
                                    </button>
                                    <button class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#ModalConfirmar{{$tipoUsuario->IdTipoUsuario}}">
                                        <i class="material-icons eliminar">delete_forever</i>
                                    </button> 
                                @endif
                                </td>
                                <!--Modal Editar-->
                                @include('TipoUsuarios.ModalEditar')
                                <!--Modal Confirmar-->
                                @include('TipoUsuarios.ModalConfirmar')
                                @endforeach
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
</div>

<!--Modal Agregar Tipo de Usuario-->
@include('TipoUsuarios.ModalAgregar')


<script src="js/scriptTipoUsuarios.js"></script>
@endsection