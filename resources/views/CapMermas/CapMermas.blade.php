@extends('plantillaBase.masterblade')
@section('title', 'Módulo de Captura de Mermas')
@section('contenido')
    <div class="container mb-3">
        <div class="d-flex justify-content-center">
            <div class="col-auto">
                <h2 class="card shadow p-1">Captura de Mermas</h2>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="d-flex justify-content-end">
            <div class="col-auto">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-bug"></i></span>
                    <a href="/ReporteMermas" class="btn btn-sm btn-warning">
                        Reporte de Mermas
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="container mb-3">
        @include('Alertas.Alertas')
    </div>
    <div class="container mb-3">
        <form class="mb-3" id="formMerma" action="/CapMermas">
            <div class="row d-flex justify-content-center">
                <div class="col-auto">
                    <select class="form-select shadow" name="idTipoMerma" id="idTipoMerma" required>
                        <option value="">Seleccione Tipo de Merma</option>
                        @foreach ($tiposMerma as $tipoMerma)
                            <option {!! $idTipoMerma == $tipoMerma->IdTipoMerma ? 'selected' : '' !!} value="{{ $tipoMerma->IdTipoMerma }}">
                                {{ $tipoMerma->NomTipoMerma }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
        <form action="/TmpMermas/{{ $idTipoMerma }}" method="POST">
            <div class="row d-flex justify-content-center">
                @csrf
                @if ($subTiposMerma->count() > 0)
                    <div class="col-auto">
                        <select class="form-select shadow" name="idSubTipoMerma" id="idSubTipoMerma">
                            @foreach ($subTiposMerma as $subTipoMerma)
                                <option value="{{ $subTipoMerma->IdSubTipoMerma }}">{{ $subTipoMerma->NomSubTipoMerma }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                @if (!empty($idTipoMerma))
                    <div class="col-auto">
                        <input class="form-control shadow" list="articulos" name="codArticulo" id="codArticulo"
                            placeholder="Código ó Articulo" autocomplete="off" required>
                        <datalist id="articulos">
                            @foreach ($articulosTipoMerma as $articuloTipoMerma)
                                <option value="{{ $articuloTipoMerma->CodArticulo }}">{{ $articuloTipoMerma->NomArticulo }}
                                </option>
                            @endforeach
                        </datalist>
                    </div>
                    <div class="col-2">
                        <input class="form-control shadow" type="number" min="0.10" step="any" name="cantArticulo"
                            id="cantArticulo" placeholder="Cantidad a Mermar" required>
                    </div>
                    <div class="col-5">
                        <input class="form-control shadow" type="text" name="comentario" id="comentario"
                            placeholder="Comentario" required>
                    </div>
                    <div class="col-auto">
                        <button id="btnAgregarMerma" class="btn btn-warning shadow">
                            <i class="fa fa-plus"></i> Agregar
                        </button>
                    </div>
                @endif
            </div>
        </form>
    </div>
    @if (!empty($idTipoMerma) && $tmpMermas->count() > 0)
        <div class="container mb-3">
            <div class="d-flex justify-content-center">
                <div class="col-auto">
                    <h4 class="card shadow-lg p-1 border-1 border-warning rounded-3">Articulos a Mermar</h4>
                </div>
            </div>
            <table class="table table-responsive table-striped shadow">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Articulo</th>
                        <th>Cantidad</th>
                        <th>Merma</th>
                        <th>Submerma</th>
                        <th>Comentario</th>
                        <th><i class="fa fa-trash-o"></i></th>
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
                                <button class="btn" data-bs-toggle="modal" data-bs-target="#ModalEliminarMermaTmp{{ $merma->IdTmpMerma }}">
                                    <span style="color: red" class="material-icons">delete_forever</span>
                                </button>
                            </td>
                            @include('CapMermas.ModalEliminarMermaTmp')
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="col-auto">
                    <button class="btn btn-warning shadow" data-bs-toggle="modal" data-bs-target="#ModalConfirmarGuardarMermas">
                        <i class="fa fa-save"></i> Guadar Mermas
                    </button>
                </div>
            </div>
        </div>
    @endif
    @include('CapMermas.ModalConfirmarGuardarMermas')

    <script>
        document.getElementById('idTipoMerma').addEventListener('change', (e) => {
            document.getElementById('formMerma').submit();
        });
    </script>
@endsection
