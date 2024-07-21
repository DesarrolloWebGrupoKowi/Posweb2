@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Articulos Por Tipo de Merma')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Articulos por Tipo de Merma'])
                <form class="d-flex align-items-center justify-content-end" id="formTipoMermaArticulo"
                    action="/TiposMermaArticulo" method="GET">
                    <div class="d-flex align-items-center gap-2">
                        <label class="text-secondary" style="font-weight: 500; text-wrap: nowrap;">Tipo de merma:</label>
                        <select class="form-select rounded" style="line-height: 18px" name="idTipoMerma" id="idTipoMerma"
                            autofocus>
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
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">

            @if (!empty($idTipoMerma))
                <form class="mb-2" action="/AgregarArticuloMerma/{{ $idTipoMerma }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-auto d-flex gap-2">
                            <input class="form-control rounded" style="line-height: 18px" list="articulos"
                                name="codArticulo" id="codArticulo" placeholder="Código ó Articulo" autocomplete="off"
                                required>
                            <datalist id="articulos">
                                @foreach ($articulos as $articulo)
                                    <option value="{{ $articulo->CodArticulo }}">{{ $articulo->NomArticulo }}</option>
                                @endforeach
                            </datalist>
                            <button class="btn btn-dark-outline" title="Buscar">
                                @include('components.icons.plus-circle')
                            </button>
                        </div>
                        <div class="col d-flex justify-content-end align-items-end">
                            <h6>Articulos disponibles: ({{ $tiposMermaArticulo->count() }})</h6>
                        </div>
                    </div>
                </form>
            @endif
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
                    @include('components.table-empty', ['items' => $tiposMermaArticulo, 'colspan' => 4])
                    @foreach ($tiposMermaArticulo as $tipoMermaArticulo)
                        <tr>
                            <td>{{ $tipoMermaArticulo->NomTipoMerma }}</td>
                            <td>{{ $tipoMermaArticulo->CodArticulo }}</td>
                            <td>{{ $tipoMermaArticulo->NomArticulo }}</td>
                            <td>
                                <button class="btn-table btn-table-delete" data-bs-toggle="modal"
                                    data-bs-target="#ModalEliminarArticuloTipoMerma{{ $tipoMermaArticulo->CodArticulo }}"
                                    title="Eliminar artículo">
                                    @include('components.icons.delete')
                                </button>
                            </td>
                            @include('TiposMerma.ModalEliminarArticuloTipoMerma')
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('idTipoMerma').addEventListener('change', (e) => {
            document.getElementById('formTipoMermaArticulo').submit();
        });
    </script>
@endsection
