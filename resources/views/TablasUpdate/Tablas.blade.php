@extends('plantillaBase.masterblade')
@section('title', 'Catálogo de Tablas Para Actualizar')
@section('contenido')
    <div class="d-flex justify-content-center">
        <div class="col-auto">
            <h2 class="card shadow p-1">Catálogo de Tablas</h2>
        </div>
    </div>
    <div class="container mb-3">
        <div class="d-flex justify-content-end">
            <div class="col-auto">
                <button class="btn card shadow" data-bs-toggle="modal" data-bs-target="#ModalAgregarTabla">
                    <span class="material-icons">add_circle</span>
                </button>
            </div>
        </div>
    </div>
    <div class="container">
        <table class="table table-striped table-responsive shadow">
            <thead class="table-dark">
                <tr>
                    <th>Tabla</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tablas as $tabla)
                    <tr>
                        <td>{{ $tabla->NomTabla }}</td>
                        <td>
                            @if ($tabla->Status == 0)
                                <i class="fa fa-check"></i>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @include('TablasUpdate.ModalAgregarTabla')
@endsection