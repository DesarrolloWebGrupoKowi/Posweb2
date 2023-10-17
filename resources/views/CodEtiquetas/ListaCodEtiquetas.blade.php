@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Listado de Código de Etiquetas')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Listado de Códigos de Etiqueta'])
            <div>
                <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                    class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar menú
                </button>
                <a href="/ListaCodEtiquetas" class="btn btn-dark-outline">
                    <span class="material-icons">refresh</span>
                </a>
                <a href="/GenerarPDF" class="btn btn-dark-outline" target="_blank">
                    <span class="material-icons">print</span>
                </a>
            </div>
        </div>

        <form class="d-flex align-items-center justify-content-end pb-4 gap-2" action="/ListaCodEtiquetas" method="GET">
            <div class="input-group" style="max-width: 300px">
                <input type="text" class="form-control" name="txtFiltro" placeholder="Nombre del Articulo"
                    value="{{ $txtFiltro }}" required autofocus>
                <div class="input-group-append">
                    <button class="input-group-text"><span class="material-icons">search</span></button>
                </div>
            </div>
        </form>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
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
                    @if ($listaCodEtiquetas->count() == 0)
                    @else
                        @foreach ($listaCodEtiquetas as $listaCodEtiqueta)
                            <tr>
                                <td>{{ $listaCodEtiqueta->CodArticulo }}</td>
                                <td>{{ $listaCodEtiqueta->NomArticulo }}</td>
                                <td>{{ $listaCodEtiqueta->CodEtiqueta }}</td>
                                <td>
                                    <i style="font-size: 24px" class="fa fa-usd" data-bs-toggle="modal"
                                        data-bs-target="#ModalPrecios{{ $listaCodEtiqueta->IdArticulo }}"></i>
                                    @include('CodEtiquetas.ModalPrecios')
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {!! $listaCodEtiquetas->links() !!}
        </div>
    </div>

@endsection
