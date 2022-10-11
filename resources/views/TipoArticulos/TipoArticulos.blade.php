@extends('plantillaBase.masterblade')
@section('title','Catálogo de Tipos de Articulo')
@section('contenido')
<div class="d-flex justify-content-center mb-3">
    <div class="col-auto">
        <h2 class="card shadow p-1">Catálogo de Tipos de Articulo</h2>
    </div>
</div>
<div class="container mb-3">
    <div class="row d-flex justify-content-end">
        <div class="col-auto">
            <button class="btn card shadow" data-bs-toggle="modal" data-bs-target="#ModalAgregarTipoArticulo">
                <span class="material-icons">add_circle</span>
            </button>
        </div>
    </div>
</div>
<div class="container">
    @include('Alertas.Alertas')
</div>
<div class="container">
    <table class="table table-striped table-responsive shadow">
        <thead class="table-dark">
            <tr>
                <th>Unidad de Negocio</th>
                <th>Tipo de Articulo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if ($tiposArticulo->count() == 0)
                <tr>
                    <td colspan="3">No hay tipos de articulo agregados!</td>
                </tr>
            @else
                @foreach ($tiposArticulo as $tipoArticulo)
                    <tr>
                        <td>{{ $tipoArticulo->IdTipoArticulo }}</td>
                        <td>{{ $tipoArticulo->NomTipoArticulo }}</td>
                        <td>
                            <button class="btn" data-bs-toggle="modal" data-bs-target="#ModalEliminarTipoArticulo">
                                <span style="color: red" class="material-icons">delete_forever</span>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
@include('TipoArticulos.ModalAgregarTipoArticulo')
@endsection