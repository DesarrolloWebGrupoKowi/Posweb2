@extends('plantillaBase.masterblade')
@section('title','Catálogo de Familias')
@section('contenido')
    <div class="container cuchi mb-3">
        <div>
            <h2 class="titulo">Catálogo de Familias</h2>
        </div>
        <div class="mb-3">
            @include('Alertas.Alertas')
        </div>
        <form action="/CatFamilias">
            <div class="row">
                <div class="col-2">
                    <input type="text" name="txtFiltro" class="form-control" placeholder="Busqueda" value="{{ $txtFiltro }}">
                </div>
                <div class="col-2">
                    <button class="btn">
                        <span class="material-icons">search</span>
                    </button>
                </div>
                <div class="col-2">
                    <a href="/CatFamilias"><span class="material-icons">visibility</span></a>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar"><span class="material-icons">add_circle_outline</span></button>
                </div>
            </div>
        </form>
        <div class="table-responsive table-sm">
            <table class="table table-sm table-striped table-responsive">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Familia</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($familias) == 0)
                        <tr>
                            <td colspan="3">No Hay Familias!</td>
                        </tr>
                    @else
                        @foreach ($familias as $familia)
                            <tr>
                                <td>{{$familia->IdFamilia}}</td>
                                <td>{{$familia->NomFamilia}}</td>
                                <td>
                                    <button class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#ModalEditar-{{ $familia->IdFamilia }}">
                                        <span class="material-icons">edit</span>
                                    </button>
                                    <button class="btn btn-sm">
                                        <span class="material-icons eliminar">delete_forever</span>
                                    </button>
                                </td>
                                <!-- Modal Editar -->
                                @include('Familias.ModalEditar')
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-flex justify-content-center">
        {!! $familias->links() !!}
    </div>
    @include('Familias.ModalAgregar')
@endsection