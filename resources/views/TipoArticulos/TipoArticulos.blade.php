@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Tipos de Articulo')
@section('dashboardWidth', 'max-width: 1440px;')
@section('contenido')
    <div class="container-fluid pt-4" style="max-width: 1440px;">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Tipos de Articulo'])
            <div>
                <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                    class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregarTipoArticulo">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar tipo
                </button>
            </div>
        </div>
        <div>
            @include('Alertas.Alertas')
        </div>
        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
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
                                    <button class="btn" data-bs-toggle="modal"
                                        data-bs-target="#ModalEliminarTipoArticulo{{ $tipoArticulo->IdCatTipoArticulo }}">
                                        <span style="color: red" class="material-icons">delete_forever</span>
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
