@extends('plantillaBase.masterblade')
@section('title', 'Catálogo de Tipos de Merma')
@section('contenido')
    <div class="d-flex justify-content-center mb-3">
        <div class="col-auto">
            <h2 class="card shadow p-1">Catálogo de Tipos de Merma</h2>
        </div>
    </div>
    <div class="container mb-3">
        @include('Alertas.Alertas')
    </div>
    <div class="container">
        <div class="d-flex justify-content-end mb-2 me-3">
            <div class="col-auto">
                <button class="btn card shadow" data-bs-toggle="modal" data-bs-target="#ModalAgregarTipoMerma">
                    <span class="material-icons">add_circle</span>
                </button>
            </div>
        </div>
        <table class="table table-striped table-responsive shadow">
            <thead class="table-dark">
                <tr>
                    <th>Id</th>
                    <th>Tipo Merma</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody style="vertical-align: middle">
                @if ($tiposMerma->count() == 0)
                    <tr>
                        <td colspan="3">No hay tipos de merma agregadas!</td>
                    </tr>
                @else
                    @foreach ($tiposMerma as $tipoMerma)
                        <tr>
                            <td>{{ $tipoMerma->IdTipoMerma }}</td>
                            <td>{{ $tipoMerma->NomTipoMerma }}</td>
                            <td>
                                <button class="btn">
                                    <span style="color: red" class="material-icons">delete_forever</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    @include('TiposMerma.ModalAgregarTipoMerma')
@endsection
