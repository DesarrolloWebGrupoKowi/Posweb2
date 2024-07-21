@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Sub Tipos de Merma')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Sub Tipos de Merma'])
                <form class="d-flex align-items-center justify-content-end" id="formTipoMerma" action="/SubTiposMerma "
                    method="GET">
                    <div class="form-group">
                        <label class="fw-bold text-secondary">Tipo de merma</label>
                        <select class="form-select rounded" style="line-height: 18px" name="idTipoMerma" id="idTipoMerma">
                            <option value="">Seleccione Tipo de Merma</option>
                            @foreach ($tiposMerma as $tipoMerma)
                                <option {!! $idTipoMerma == $tipoMerma->IdTipoMerma ? 'selected' : '' !!} value="{{ $tipoMerma->IdTipoMerma }}">
                                    {{ $tipoMerma->NomTipoMerma }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>

        {{-- @if (!empty($idTipoMerma))
                    <button class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#ModalAgregarSubTipoMerma">
                        <i class="fa fa-plus-circle"></i> Agregar subtipo
                    </button>
                @endif --}}

        @if (empty($idTipoMerma))
            <h2 class="text-center">Selecciona un tipo de merma</h2>
        @endif

        @if (!empty($idTipoMerma))
            <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
                <div class="d-flex justify-content-end mb-2">
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                        data-bs-target="#ModalAgregarSubTipoMerma">
                        <i class="fa fa-plus-circle"></i> Agregar subtipo
                    </button>
                </div>

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
                                        <button class="btn-table btn-table-delete" data-bs-toggle="modal"
                                            data-bs-target="#ModalEliminarSubTipoMerma{{ $subTipoMerma->IdSubTipoMerma }}">
                                            @include('components.icons.delete')
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
