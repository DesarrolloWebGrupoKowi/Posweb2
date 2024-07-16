@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Captura de Mermas')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Captura de Mermas'])
                <div>
                    <a href="/ReporteMermas" class="btn btn-dark">
                        Historial mermas @include('components.icons.text-file')
                    </a>
                </div>
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">

            <form class="d-flex align-items-center justify-content-end gap-2" id="formMerma" action="/CapMermas">
                <div class="d-flex align-items-center gap-2">
                    <label for="idTipoMerma" class="text-secondary" style="font-weight: 500; white-space: nowrap;">Tipo de
                        Merma:</label>
                    <select class="form-select rounded" style="line-height: 18px" name="idTipoMerma" id="idTipoMerma"
                        required>
                        <option value="">Seleccione Tipo de Merma</option>
                        @foreach ($tiposMerma as $tipoMerma)
                            <option {!! $idTipoMerma == $tipoMerma->IdTipoMerma ? 'selected' : '' !!} value="{{ $tipoMerma->IdTipoMerma }}">
                                {{ $tipoMerma->NomTipoMerma }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <form class="d-flex flex-wrap align-items-end justify-content-end pb-2 gap-2"
                action="/TmpMermas/{{ $idTipoMerma }}" method="POST">
                @csrf
                @if ($subTiposMerma->count() > 0)
                    <div class="col-auto">
                        <label for="idSubTipoMerma" class="text-secondary"
                            style="font-weight: 500; white-space: nowrap;">Sub Tipo de Merma</label>
                        <select class="form-select rounded" style="line-height: 18px" name="idSubTipoMerma"
                            id="idSubTipoMerma">
                            @foreach ($subTiposMerma as $subTipoMerma)
                                <option value="{{ $subTipoMerma->IdSubTipoMerma }}">
                                    {{ $subTipoMerma->NomSubTipoMerma }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                @if (!empty($idTipoMerma))
                    <div class="d-flex flex-column">
                        <label for="codArticulo" class="text-secondary"
                            style="font-weight: 500; white-space: nowrap;">Código del Articulo</label>
                        <input class="form-control rounded" style="line-height: 18px" list="articulos" name="codArticulo"
                            id="codArticulo" placeholder="Escriba" autocomplete="off" required>
                        <datalist id="articulos">
                            @foreach ($articulosTipoMerma as $articuloTipoMerma)
                                <option value="{{ $articuloTipoMerma->CodArticulo }}">
                                    {{ $articuloTipoMerma->NomArticulo }}
                                </option>
                            @endforeach
                        </datalist>
                    </div>
                    <div class="d-flex flex-column">
                        <label for="codArticulo" class="text-secondary"
                            style="font-weight: 500; white-space: nowrap;">Cantidad a Mermar</label>
                        <input class="form-control rounded" style="line-height: 18px;" type="number" min="0.10"
                            step="any" name="cantArticulo" id="cantArticulo" placeholder="Cantidad a Mermar" required>
                    </div>
                    <div class="d-flex flex-column">
                        <label for="comentario" class="text-secondary" style="font-weight: 500">Comentario</label>
                        <input class="form-control rounded" style="line-height: 18px" type="text" name="comentario"
                            id="comentario" placeholder="Comentario" required>
                    </div>
                    <div class="col-auto">
                        <button id="btnAgregarMerma" class="btn btn-dark-outline">
                            @include('components.icons.plus-circle')
                        </button>
                    </div>
                @endif
            </form>

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
                    @include('components.table-empty', ['items' => $tmpMermas, 'colspan' => 7])
                    @foreach ($tmpMermas as $merma)
                        <tr>
                            <td>{{ $merma->CodArticulo }}</td>
                            <td>{{ $merma->NomArticulo }}</td>
                            <td>{{ number_format($merma->CantArticulo, 2) }}</td>
                            <td>{{ $merma->NomTipoMerma }}</td>
                            <td>{{ $merma->NomSubTipoMerma }}</td>
                            <td>{{ $merma->Comentario }}</td>
                            <td>
                                <button class="btn-table" data-bs-toggle="modal"
                                    data-bs-target="#ModalEliminarMermaTmp{{ $merma->IdTmpMerma }}"
                                    title="Eliminar rostizado">
                                    @include('components.icons.delete')
                                </button>
                            </td>
                            @include('CapMermas.ModalEliminarMermaTmp')
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($tmpMermas->count() > 0)
                <div class="mt-2 d-flex justify-content-end">
                    <button class="btn btn-warning shadow" data-bs-toggle="modal"
                        data-bs-target="#ModalConfirmarGuardarMermas">
                        <i class="fa fa-save"></i> Guadar Mermas
                    </button>
                </div>
            @endif
        </div>


        @include('CapMermas.ModalConfirmarGuardarMermas')
    </div>

    <script>
        document.getElementById('idTipoMerma').addEventListener('change', (e) => {
            document.getElementById('formMerma').submit();
        });
    </script>
@endsection
