@extends('plantillaBase.masterblade')
@section('title', 'Listado de Código de Etiquetas')
<style>
    i {
        cursor: pointer;
    }

    i:active {
        transform: scale(1.2);
        color: orange;
    }
</style>
@section('contenido')
    <div class="container mb-3 mt-1">
        <div class="container titulo card shadow mb-3">
            <h2>Listado de Códigos de Etiqueta</h2>
        </div>
        <form action="/ListaCodEtiquetas" method="GET">
            <div class="row mb-3">
                <div class="col-4">
                    <input type="text" class="form-control" name="txtFiltro" placeholder="Nombre del Articulo"
                        value="{{ $txtFiltro }}" required>
                </div>
                <div class="col-2">
                    <button class="btn card shadow">
                        <span class="material-icons">search</span>
                    </button>
                </div>
                <div class="col-auto">
                    <a href="/ListaCodEtiquetas" class="btn card shadow">
                        <span class="material-icons">visibility</span>
                    </a>
                </div>
                <div class="col-auto">
                    <a href="/GenerarPDF" class="btn card shadow" target="_blank">
                        <span class="material-icons">print</span>
                    </a>
                </div>
            </div>
        </form>
    </div>
    <div class="container">
        <table class="table table-responsive table-striped shadow">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Código Etiqueta</th>
                    <th>Precios</th>
                </tr>
            </thead>
            <tbody>
                @if ($listaCodEtiquetas->count() == 0)
                @else
                    @foreach ($listaCodEtiquetas as $listaCodEtiqueta)
                        <tr>
                            <td>{{ $listaCodEtiqueta->CodArticulo }}</td>
                            <td>{{ $listaCodEtiqueta->NomArticulo }}</td>
                            <td>{{ $listaCodEtiqueta->CodEtiqueta }}</td>
                            <td>
                                <i style="font-size: 24px" class="fa fa-usd" data-bs-toggle="modal"
                                    data-bs-target="#ModalPrecios{{ $listaCodEtiqueta->IdArticulo }}"></i>
                                    @include('CodEtiquetas.ModalPrecios')
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">
        {!! $listaCodEtiquetas->links() !!}
    </div>

@endsection
