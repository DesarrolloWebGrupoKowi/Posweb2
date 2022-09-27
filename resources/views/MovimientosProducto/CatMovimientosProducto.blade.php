@extends('plantillaBase.masterblade')
@section('title','Catálogo de Movimientos de Producto')
@section('contenido')
    <div class="d-flex justify-content-center">
        <div class="col-auto">
            <h2 class="card shadow p-1">
                Catálogo de Movimientos de Producto
            </h2>
        </div>
    </div>
    <div class="container mb-3">
        <div class="row d-flex justify-content-end">
            <div class="col-auto">
                <button class="btn card shadow" data-bs-toggle="modal" data-bs-target="#ModalAgregarMovimiento">
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
                    <th>Id</th>
                    <th>Movimiento</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($movimientosProducto as $mProducto)
                    <tr>
                        <td>{{ $mProducto->IdMovimiento }}</td>
                        <td>{{ $mProducto->NomMovimiento }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @include('MovimientosProducto.ModalAgregarMovimiento')
@endsection