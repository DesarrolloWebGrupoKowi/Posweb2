@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo Grupos')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">

        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Grupos'])
            <div class="">
                <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                    class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar grupo
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
                        <th>Grupo</th>
                        <th class="rounded-end">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($grupos) == 0)
                        <tr>
                            <td colspan="3">No Hay Grupos!</td>
                        </tr>
                    @else
                        @foreach ($grupos as $grupo)
                            <tr>
                                <td>{{ $grupo->IdGrupo }}</td>
                                <td>{{ $grupo->NomGrupo }}</td>
                                <td>{{ $grupo->Status }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @include('Grupos.ModalAgregar')
@endsection
