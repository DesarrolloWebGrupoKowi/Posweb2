@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo Grupos')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Grupos'])
                <div class="">
                    <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                        class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                        Agregar grupo @include('components.icons.plus-circle')
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
                        <th>Grupo</th>
                        <th class="rounded-end">Estatus</th>
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
                                <td>
                                    @if ($grupo->Status == 1)
                                        <span class="tags-red">
                                            @include('components.icons.x')
                                        </span>
                                    @else
                                        <span class="tags-green">
                                            @include('components.icons.check-all')
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @include('Grupos.ModalAgregar')
@endsection
