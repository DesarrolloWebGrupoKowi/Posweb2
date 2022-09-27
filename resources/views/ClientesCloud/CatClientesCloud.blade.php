@extends('plantillaBase.masterblade')
@section('title','Catálogo de Clientes Cloud')
@section('contenido')
<div class="container mb-3">
    <h2 class="titulo">Catálogo de Clientes Cloud</h2>
</div>
<div class="container">
    <div class="">
        @include('Alertas.Alertas')
    </div>
    <div class="row">
        <div class="d-flex justify-content-end">
            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                <i class="fa fa-plus-square"></i> Agregar Cliente
            </button>
        </div>
    </div>
</div>
<div class="container mb-3">
    <div class="table-responsive cuchi">
        <table class="table table-striped table-responsive">
            <thead>
                <tr>
                    <th>Id Cliente</th>
                    <th>Nombre</th>
                    <th>Tipo de Cliente</th>
                </tr>
            </thead>
            <tbody>
                @if ($clientesCloud->count() == 0)
                    <tr>
                        <td colspan="3">No Hay Coincidencias!</td>
                    </tr>
                @else
                @foreach ($clientesCloud as $clienteCloud)
                <tr>
                    <td>{{ $clienteCloud->IdClienteCloud }}</td>
                    <td>{{ $clienteCloud->NomClienteCloud }}</td>
                    <td>{{ $clienteCloud->TipoCliente }}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@include('ClientesCloud.ModalAgregar')
@endsection