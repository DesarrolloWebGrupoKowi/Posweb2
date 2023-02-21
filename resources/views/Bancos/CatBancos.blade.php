@extends('plantillaBase.masterblade')
@section('title','Catálogo de Bancos')
@section('contenido')
<div class="d-flex justify-content-center">
    <div class="col-auto">
        <h2 class="card shadow p-1">Catálogo de Bancos</h2>
    </div>
</div>
<div class="container mb-2">
    <div class="row d-flex justify-content-end">
        <div class="col-auto">
            <button class="btn card shadow" data-bs-toggle="modal" data-bs-target="#ModalAgregarBanco">
                <span class="material-icons">add_circle</span>
            </button>
        </div>
    </div>
</div>
<div class="container">
    <table class="table table-striped table-responsive shadow">
        <thead class="table-dark">
            <tr>
                <th>Id Banco</th>
                 <th>Banco</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bancos as $banco)
                <tr>
                    <td>{{ $banco->IdBanco }}</td>
                    <td>{{ $banco->NomBanco }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@include('Bancos.ModalAgregarBanco')
@endsection
