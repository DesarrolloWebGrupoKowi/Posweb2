@extends('plantillaBase.masterblade')
@section('title', 'Catálogo de Tipos de Pago')
@section('contenido')

<div class="titulo">
    <h2>Catálogo de Tipos de Pago</h2>
</div>
<div class="mb-3">
    @include('Alertas.Alertas')
</div>
<div class="container cuchi">
    <div class="row">
        <div class="d-flex justify-content-end">
            <button class="btn" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                <span class="material-icons">add_circle</span>
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-responsive table-striped">
            <thead>
                <tr>
                    <th>Id de Tipo de Pago</th>
                    <th>Nombre</th>
                    <th>Clave Sat</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tiposPago as $tipoPago)
                    <tr>
                        <td>{{ $tipoPago->IdTipoPago }}</td>
                        <td>{{ $tipoPago->NomTipoPago }}</td>
                        <td>{{ $tipoPago->ClaveSat }}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@include('TipoPago.ModalAgregar')

@endsection