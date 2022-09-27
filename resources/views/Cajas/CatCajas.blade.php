@extends('plantillaBase.masterblade')
@section('title','Catálogo de Cajas')
@section('contenido')
<div class="container cuchi">
    <div class="titulo">
        <h2>Catálogo de Cajas</h2>
    </div>
    <div class="row">
        <div class="col d-flex justify-content-end">
            <button class="btn" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                <span class="material-icons">add_circle</span>
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-responsive table-striped">
            <thead>
                <tr>
                    <th>Id Caja</th>
                    <th>Número de Caja</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cajas as $caja)
                <tr>
                    <td>{{ $caja->IdCaja }}</td>
                    <td>{{ $caja->NumCaja }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@include('Cajas.ModalAgregar')
@endsection