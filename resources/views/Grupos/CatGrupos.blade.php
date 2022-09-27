@extends('plantillaBase.masterblade')
@section('title','Catálogo Grupos')
@section('contenido')
    <div class="container cuchi">
        <div>
            <h2 class="titulo">Catálogo de Grupos</h2>
        </div>
        <div>
            alertas
        </div>
        <div class="row">
            <div class="col-2">
                filtro
            </div>
            <div class="col-10">
                <button class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar"><span class="material-icons">add_circle_outline</span></button>
            </div>
        </div>
        <div class="table-responsive table-sm">
            <table class="table table-sm table-striped table-responsive">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Grupo</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($grupos) == 0)
                        <tr>
                            <td colspan="3">No Hay Grupos!</td>
                        </tr>
                    @else
                        @foreach ($grupos as $grupo)
                            <tr>
                                <td>{{$grupo->IdGrupo}}</td>
                                <td>{{$grupo->NomGrupo}}</td>
                                <td>{{$grupo->Status}}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @include('Grupos.ModalAgregar')
@endsection