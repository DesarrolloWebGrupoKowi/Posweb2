@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Módulo de Captura de Mermas')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Captura de Mermas'])
            <div>
                <a href="/ReporteMermas" class="btn btn-sm btn-dark">
                    <i class="fa fa-plus-circle pe-1"></i> Reporte de Mermas
                </a>
            </div>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>

        <form class="d-flex align-items-center justify-content-end gap-2" id="formMerma" action="/CapMermas">
            <div class="form-group" style="max-width: 300px">
                <label class="text-secondary" for="idTipoMerma"><strong>Tipo de Merma</strong></label>
                <select class="form-select" name="idTipoMerma" id="idTipoMerma" required>
                    <option value="">Seleccione Tipo de Merma</option>
                    @foreach ($tiposMerma as $tipoMerma)
                        <option {!! $idTipoMerma == $tipoMerma->IdTipoMerma ? 'selected' : '' !!} value="{{ $tipoMerma->IdTipoMerma }}">
                            {{ $tipoMerma->NomTipoMerma }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <form class="d-flex align-items-end justify-content-end pb-4 gap-2" action="/TmpMermas/{{ $idTipoMerma }}"
            method="POST">
            @csrf
            @if ($subTiposMerma->count() > 0)
                <div class="col-auto">
                    <label for="idSubTipoMerma"><strong>Sub Tipo de Merma</strong></label>
                    <select class="form-select" name="idSubTipoMerma" id="idSubTipoMerma">
                        @foreach ($subTiposMerma as $subTipoMerma)
                            <option value="{{ $subTipoMerma->IdSubTipoMerma }}">
                                {{ $subTipoMerma->NomSubTipoMerma }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
            @if (!empty($idTipoMerma))
                <div class="col-auto">
                    <label for="codArticulo"><strong>Código del Articulo</strong></label>
                    <input class="form-control" list="articulos" name="codArticulo" id="codArticulo" placeholder="Escriba"
                        autocomplete="off" required>
                    <datalist id="articulos">
                        @foreach ($articulosTipoMerma as $articuloTipoMerma)
                            <option value="{{ $articuloTipoMerma->CodArticulo }}">
                                {{ $articuloTipoMerma->NomArticulo }}
                            </option>
                        @endforeach
                    </datalist>
                </div>
                <div class="col-2">
                    <label for="codArticulo"><strong>Cantidad a Mermar</strong></label>
                    <input class="form-control" type="number" min="0.10" step="any" name="cantArticulo"
                        id="cantArticulo" placeholder="Cantidad a Mermar" required>
                </div>
                <div class="col-5">
                    <label for="comentario"><strong>Comentario</strong></label>
                    <input class="form-control" type="text" name="comentario" id="comentario" placeholder="Comentario"
                        required>
                </div>
                <div class="col-auto">
                    <button id="btnAgregarMerma" class="btn btn-dark-outline">
                        <span class="material-icons">add_circle_outline</span>
                    </button>
                </div>
            @endif
        </form>

        @if (!empty($idTipoMerma) && $tmpMermas->count() > 0)

            <div class="content-table content-table-full card p-4" style="border-radius: 20px">
                <h5 style="font-size: 16px">Articulos a Mermar</h5>
                <table>
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Código</th>
                            <th>Articulo</th>
                            <th>Cantidad</th>
                            <th>Merma</th>
                            <th>Submerma</th>
                            <th>Comentario</th>
                            <th class="rounded-end"><i class="fa fa-trash-o"></i></th>
                        </tr>
                    </thead>
                    <tbody style="vertical-align: middle">
                        @foreach ($tmpMermas as $merma)
                            <tr>
                                <td>{{ $merma->CodArticulo }}</td>
                                <td>{{ $merma->NomArticulo }}</td>
                                <td>{{ number_format($merma->CantArticulo, 2) }}</td>
                                <td>{{ $merma->NomTipoMerma }}</td>
                                <td>{{ $merma->NomSubTipoMerma }}</td>
                                <td>{{ $merma->Comentario }}</td>
                                <td>
                                    <button class="btn" data-bs-toggle="modal"
                                        data-bs-target="#ModalEliminarMermaTmp{{ $merma->IdTmpMerma }}">
                                        <span style="color: red" class="material-icons">delete_forever</span>
                                    </button>
                                </td>
                                @include('CapMermas.ModalEliminarMermaTmp')
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-2 d-flex justify-content-end">
                    <button class="btn btn-warning shadow" data-bs-toggle="modal"
                        data-bs-target="#ModalConfirmarGuardarMermas">
                        <i class="fa fa-save"></i> Guadar Mermas
                    </button>
                </div>
            </div>
        @endif

        @include('CapMermas.ModalConfirmarGuardarMermas')
    </div>

    <script>
        document.getElementById('idTipoMerma').addEventListener('change', (e) => {
            document.getElementById('formMerma').submit();
        });
    </script>
@endsection
