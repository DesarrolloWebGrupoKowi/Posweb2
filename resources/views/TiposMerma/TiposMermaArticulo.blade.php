@extends('plantillaBase.masterblade')
@section('title', 'Articulos Por Tipo de Merma')
<style>
    i {
        cursor: pointer;
    }
</style>
@section('contenido')
    <div class="d-flex justify-content-center mb-3">
        <div class="col-auto">
            <h2 class="card shadow p-1">Tipos de Merma por Articulo</h2>
        </div>
    </div>
    <div class="container mb-3">
        @include('Alertas.Alertas')
    </div>
    <div class="container mb-3">
        <form id="formTipoMermaArticulo" action="/TiposMermaArticulo" method="GET">
            <div class="row d-flex justify-content-center">
                <div class="col-auto">
                    <select class="form-select shadow" name="idTipoMerma" id="idTipoMerma">
                        <option value="">Seleccione Tipo de Merma</option>
                        @foreach ($tiposMerma as $tipoMerma)
                            <option {!! $idTipoMerma == $tipoMerma->IdTipoMerma ? 'selected' : '' !!} value="{{ $tipoMerma->IdTipoMerma }}">
                                {{ $tipoMerma->NomTipoMerma }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
    @if (!empty($idTipoMerma))
        <div class="container mb-3">
            <form action="/AgregarArticuloMerma/{{ $idTipoMerma }}" method="POST">
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
                        <button class="btn card shadow">
                            <span class="material-icons">add_circle</span>
                        </button>
                    </div>
                    <div class="col d-flex justify-content-end me-4">
                        <h4>Articulos disponibles: ({{ $tiposMermaArticulo->count() }})</h4>
                    </div>
                </div>
            </form>
        </div>
        <div style="height: 70vh;" class="container table-responsive">
            <table class="table table-striped table-responsive shadow">
                <thead class="table-dark">
                    <tr>
                        <th>Tipo Merma</th>
                        <th>Código</th>
                        <th>Articulo</th>
                        <th>Acciones</th>
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

    <script>
        document.getElementById('idTipoMerma').addEventListener('change', (e) => {
            document.getElementById('formTipoMermaArticulo').submit();
        });
    </script>
@endsection
