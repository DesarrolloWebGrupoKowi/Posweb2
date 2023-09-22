@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Tipos de Merma')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Tipos de Merma'])
            <div>
                <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                    class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregarTipoMerma">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar tipo
                </button>
            </div>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>
        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id</th>
                        <th>Tipo Merma</th>
                        <th class="rounded-end">Acciones</th>
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
                                    <button class="btn" data-bs-toggle="modal"
                                        data-bs-target="#ModalEliminarArticuloTipoMerma{{ $tipoMerma->IdTipoMerma }}">
                                        <span style="color: red" class="material-icons">delete_forever</span>
                                    </button>
                                </td>
                                @include('TiposMerma.ModalEliminarTipoMerma')
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    {{-- <div class="container">
        <div class="d-flex justify-content-end mb-2 me-3">
            <div class="col-auto">
                <button class="btn card shadow" data-bs-toggle="modal" data-bs-target="#ModalAgregarTipoMerma">
                    <span class="material-icons">add_circle</span>
                </button>
            </div>
        </div>
         --}}
    @include('TiposMerma.ModalAgregarTipoMerma')
@endsection
