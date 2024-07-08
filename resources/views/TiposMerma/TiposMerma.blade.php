@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Tipos de Merma')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Tipos de Merma'])
                <div>
                    <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                        class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregarTipoMerma">
                        Agregar tipo @include('components.icons.plus-circle')
                    </button>
                </div>
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>
        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id</th>
                        <th>Tipo Merma</th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody style="vertical-align: middle">
                    @include('components.table-empty', ['items' => $tiposMerma, 'colspan' => 3])
                    @foreach ($tiposMerma as $tipoMerma)
                        <tr>
                            <td>{{ $tipoMerma->IdTipoMerma }}</td>
                            <td>{{ $tipoMerma->NomTipoMerma }}</td>
                            <td>
                                <button class="btn-table btn-table-delete" data-bs-toggle="modal"
                                    data-bs-target="#ModalEliminarArticuloTipoMerma{{ $tipoMerma->IdTipoMerma }}">
                                    @include('components.icons.delete')
                                </button>
                            </td>
                            @include('TiposMerma.ModalEliminarTipoMerma')
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('TiposMerma.ModalAgregarTipoMerma')
@endsection
