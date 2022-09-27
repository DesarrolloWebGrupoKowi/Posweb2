@extends('plantillaBase.masterblade')
@section('title','Catálogo de Límite Crédito')
@section('contenido')
<div class="container">
    <h2 class="titulo card">Tipos de Nómina y Límites de Crédito</h2>
</div>
<div class="container">
    @include('Alertas.Alertas')
    <div class="row d-flex justify-content-end">
        <div class="col-auto">
            <button class="btn">
                <span class="material-icons">add_circle</span>
            </button>
        </div>
    </div>
    <table class="table table-responsive table-striped shadow">
        <thead class="table-dark">
            <tr>
                <th>Id Tipo Nómina</th>
                <th>Tipo Empleado</th>
                <th>Límite Crédito</th>
                <th>Ventas Diarias</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($limitesCredito as $lCredito)
                <tr>
                    <td>{{ $lCredito->TipoNomina }}</td>
                    <td>{{ $lCredito->NomTipoNomina }}</td>
                    <td>${{ $lCredito->Limite }}</td>
                    <td>{{ $lCredito->TotalVentaDiaria }}</td>
                    <td>
                        <button class="btn" data-bs-toggle="modal" data-bs-target="#ModalEditar{{ $lCredito->IdCatLimiteCredito }}">
                            <span class="material-icons">edit</span>
                        </button>
                    </td>
                    @include('LimiteCredito.ModalEditar')
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection