@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Sub Tipos de Merma')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Sub Tipos de Merma'])
            @if (!empty($idTipoMerma))
                <button class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#ModalAgregarSubTipoMerma">
                    <i class="fa fa-plus-circle"></i> Agregar subtipo
                </button>
            @endif
        </div>
        <form class="d-flex align-items-center justify-content-between pb-4 gap-4" id="formTipoMerma" action="/SubTiposMerma "
            method="GET">
            <div class="input-group" style="max-width: 350px">
                <select class="form-select" name="idTipoMerma" id="idTipoMerma">
                    <option value="">Seleccione Tipo de Merma</option>
                    @foreach ($tiposMerma as $tipoMerma)
                        <option {!! $idTipoMerma == $tipoMerma->IdTipoMerma ? 'selected' : '' !!} value="{{ $tipoMerma->IdTipoMerma }}">
                            {{ $tipoMerma->NomTipoMerma }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        @if (!empty($idTipoMerma))
            <div class="content-table content-table-full card p-4" style="border-radius: 20px">
                <table>
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Tipo de Merma</th>
                            <th>Sub Tipo de Merma</th>
                            <th class="rounded-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody style="vertical-align: middle">
                        @if ($subTiposMerma->count() == 0)
                            <tr>
                                <td colspan="3">No hay sub tipos de merma para este tipo de merma!</td>
                            </tr>
                        @else
                            @foreach ($subTiposMerma as $subTipoMerma)
                                <tr>
                                    <td>{{ $subTipoMerma->NomTipoMerma }}</td>
                                    <td>{{ $subTipoMerma->NomSubTipoMerma }}</td>
                                    <td>
                                        <button class="btn" data-bs-toggle="modal"
                                            data-bs-target="#ModalEliminarSubTipoMerma{{ $subTipoMerma->IdSubTipoMerma }}">
                                            <span style="color: red" class="material-icons">delete_forever</span>
                                        </button>
                                    </td>
                                    @include('TiposMerma.ModalEliminarSubTipoMerma')
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @include('TiposMerma.ModalAgregarSubTipoMerma')

    <script>
        document.getElementById('idTipoMerma').addEventListener('change', (e) => {
            document.getElementById('formTipoMerma').submit();
        });
    </script>
@endsection
