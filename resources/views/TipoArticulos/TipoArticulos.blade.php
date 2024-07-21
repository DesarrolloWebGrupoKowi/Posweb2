@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Tipos de Articulo')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Tipos de Articulo'])
                <div>
                    <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                        class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregarTipoArticulo">
                        Agregar tipo @include('components.icons.plus-circle')
                    </button>
                </div>
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Unidad de Negocio</th>
                        <th>Tipo de Articulo</th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($tiposArticulo->count() == 0)
                        <tr>
                            <td colspan="3">No hay tipos de articulo agregados!</td>
                        </tr>
                    @else
                        @foreach ($tiposArticulo as $tipoArticulo)
                            <tr>
                                <td>{{ $tipoArticulo->IdTipoArticulo }}</td>
                                <td>{{ $tipoArticulo->NomTipoArticulo }}</td>
                                <td>
                                    <button class="btn-table btn-table-delete" data-bs-toggle="modal"
                                        data-bs-target="#ModalEliminarTipoArticulo{{ $tipoArticulo->IdCatTipoArticulo }}">
                                        @include('components.icons.delete')
                                    </button>
                                </td>
                                @include('TipoArticulos.ModalEliminarTipoArticulo')
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @include('TipoArticulos.ModalAgregarTipoArticulo')
@endsection
