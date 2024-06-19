@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Listado de Código de Etiquetas')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">
        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Listado de Códigos de Etiqueta'])
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <a href="/ListaCodEtiquetas" class="btn btn-dark-outline">
                        @include('components.icons.switch')
                    </a>
                    <a href="/GenerarPDF" class="btn btn-dark-outline" target="_blank">
                        @include('components.icons.print')
                    </a>
                </div>
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex align-items-center justify-content-end pb-2 gap-2" action="/ListaCodEtiquetas"
                method="GET">
                <div class="d-flex align-items-center gap-2">
                    <label for="txtFiltro" class="text-secondary" style="font-weight: 500">Buscar:</label>
                    <input class="form-control rounded" style="line-height: 18px" type="text" name="txtFiltro"
                        id="txtFiltro" value="{{ $txtFiltro }}" autofocus>
                </div>
            </form>
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Código</th>
                        <th>Nombre</th>
                        <th>Código Etiqueta</th>
                        <th class="rounded-end">Precios</th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $listaCodEtiquetas, 'colspan' => 4])
                    @foreach ($listaCodEtiquetas as $listaCodEtiqueta)
                        <tr>
                            <td>{{ $listaCodEtiqueta->CodArticulo }}</td>
                            <td>{{ $listaCodEtiqueta->NomArticulo }}</td>
                            <td>{{ $listaCodEtiqueta->CodEtiqueta }}</td>
                            <td>
                                <button class="btn-table" data-bs-toggle="modal"
                                    data-bs-target="#ModalPrecios{{ $listaCodEtiqueta->IdArticulo }}">
                                    @include('components.icons.list')
                                </button>
                                @include('CodEtiquetas.ModalPrecios')
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $listaCodEtiquetas])
        </div>
    </div>

@endsection
