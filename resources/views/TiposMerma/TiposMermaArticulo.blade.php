@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Articulos Por Tipo de Merma')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Articulos por Tipo de Merma'])
            <form class="d-flex align-items-center justify-content-end" id="formTipoMermaArticulo" action="/TiposMermaArticulo"
                method="GET">
                <div class="form-group" style="min-width: 300px">
                    <label class="fw-bold text-secondary">Tipo de merma</label>
                    <select class="form-select" name="idTipoMerma" id="idTipoMerma">
                        <option value="">Seleccione tipo de merma</option>
                        @foreach ($tiposMerma as $tipoMerma)
                            <option {!! $idTipoMerma == $tipoMerma->IdTipoMerma ? 'selected' : '' !!} value="{{ $tipoMerma->IdTipoMerma }}">
                                {{ $tipoMerma->NomTipoMerma }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>

        @if (!empty($idTipoMerma))
            <form class="mb-2" action="/AgregarArticuloMerma/{{ $idTipoMerma }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-auto">
                        <input class="form-control" list="articulos" name="codArticulo" id="codArticulo"
                            placeholder="Código ó Articulo" autocomplete="off" required>
                        <datalist id="articulos">
                            @foreach ($articulos as $articulo)
                                <option value="{{ $articulo->CodArticulo }}">{{ $articulo->NomArticulo }}</option>
                            @endforeach
                        </datalist>
                    </div>
                    <div class="col-auto">
                        <button class="btn card">
                            <span class="material-icons">add_circle</span>
                        </button>
                    </div>
                    <div class="col d-flex justify-content-end align-items-end">
                        <h4 style="font-size: 18px">Articulos disponibles: ({{ $tiposMermaArticulo->count() }})</h4>
                    </div>
                </div>
            </form>
            <div class="content-table content-table-full card p-4" style="border-radius: 20px">
                <table>
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Tipo Merma</th>
                            <th>Código</th>
                            <th>Articulo</th>
                            <th class="rounded-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody style="vertical-align: middle">
                        @if ($tiposMermaArticulo->count() == 0)
                            <tr>
                                <td colspan="4">No hay articulos para este tipo de merma!</td>
                            </tr>
                        @else
                            @foreach ($tiposMermaArticulo as $tipoMermaArticulo)
                                <tr>
                                    <td>{{ $tipoMermaArticulo->NomTipoMerma }}</td>
                                    <td>{{ $tipoMermaArticulo->CodArticulo }}</td>
                                    <td>{{ $tipoMermaArticulo->NomArticulo }}</td>
                                    <td>
                                        <i style="color: red; font-size: 22px" class="fa fa-trash-o" data-bs-toggle="modal"
                                            data-bs-target="#ModalEliminarArticuloTipoMerma{{ $tipoMermaArticulo->CodArticulo }}"></i>
                                    </td>
                                    @include('TiposMerma.ModalEliminarArticuloTipoMerma')
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <script>
        document.getElementById('idTipoMerma').addEventListener('change', (e) => {
            document.getElementById('formTipoMermaArticulo').submit();
        });
    </script>
@endsection
