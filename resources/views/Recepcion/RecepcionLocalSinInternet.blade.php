@extends('plantillaBase.masterblade')
@section('title', 'Recepción Sin Internet')
@section('contenido')
    <div class="d-flex justify-content-center mb-3">
        <div class="col-auto">
            <h2 class="card shadow p-1">Recepción Sin Internet (Local)</h2>
        </div>
    </div>
    <div class="container mb-3">
        <div class="d-flex justify-content-center">
            <div class="col-auto">
                @include('Alertas.Alertas')
            </div>
        </div>
    </div>
    <div class="container mb-2">
        <form action="/AgregarProductoLocalSinInternet" method="GET">
            <div class="row ms-3">
                <div class="col-auto">
                    <input class="form-control shadow" list="articulos" name="codArticulo" id="codArticulo"
                        placeholder="Escriba" autocomplete="off" required>
                    <datalist id="articulos">
                        @foreach ($articulos as $articulo)
                            <option value="{{ $articulo->CodArticulo }}">
                                {{ $articulo->NomArticulo }}
                            </option>
                        @endforeach
                    </datalist>
                </div>
                <div class="col-auto">
                    <input class="form-control shadow" type="number" name="cantArticulo" placeholder="Cantidad"
                        step="any" required>
                </div>
                <div class="col-auto">
                    <button class="btn card shadow">
                        <span class="material-icons">add_circle</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    @if ($capturasSinInternet->count() > 0)
        <div class="container">
            <table class="table table-striped table-responsive">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Articulo</th>
                        <th>Cantidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($capturasSinInternet as $articuloLocal)
                        <tr>
                            <td>{{ $articuloLocal->CodArticulo }}</td>
                            <td>{{ $articuloLocal->NomArticulo }}</td>
                            <td>{{ number_format($articuloLocal->CantArticulo, 3) }}</td>
                            <td>
                                <i class="fa fa-trash" style="color: red; font-size: 22px; cursor: pointer;"
                                data-bs-toggle="modal" data-bs-target="#EliminarArticulo{{ $articuloLocal->IdCapRecepcionManual }}"></i>
                            </td>
                            @include('Recepcion.ModalEliminarArticuloSinInternet')
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="col-auto">
                    <button class="btn btn-warning shadow" data-bs-toggle="modal" data-bs-target="#confirmarRecepcionSinInternet">
                        <i class="fa fa-check"></i> Recepcionar Producto
                    </button>
                </div>
            </div>
        </div>
        @include('Recepcion.ConfirmarRecepcionSinInternet')
    @endif
@endsection
