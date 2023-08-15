@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Articulos')
@section('dashboardWidth', 'width-95')
@section('contenido')

    <div class="container-fluid pt-4 width-95">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Articulos'])
            <div>
                <a href="/BuscarArticulo" class="btn btn-sm btn-dark">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar articulo
                </a>
                <a href="/CatArticulos" class="btn btn-dark-outline">
                    <span class="material-icons">refresh</span>
                </a>
            </div>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <form class="d-flex align-items-center justify-content-end pb-4 gap-2" action="/CatArticulos">
            <div class="input-group" style="max-width: 300px">
                <input type="text" name="txtFiltroArticulo" class="form-control" placeholder="Nombre del articulo"
                    value="{{ $filtroArticulo }}">
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
                        <th>Amece</th>
                        <th>UOM</th>
                        <th>UOM2</th>
                        <th>Peso</th>
                        <th>Plu</th>
                        <th>Precio Recorte</th>
                        <th>Factor</th>
                        <th>Tipo</th>
                        <th>Familia</th>
                        <th>Grupo</th>
                        <th>Iva</th>
                        <th class="rounded-end">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($articulos) == 0)
                        <tr>
                            <td colspan="14">No Hay Articulos</td>
                        </tr>
                    @else
                        @foreach ($articulos as $articulo)
                            <tr>
                                <td>{{ $articulo->CodArticulo }}</td>
                                <td>{{ $articulo->NomArticulo }}</td>
                                <td>{{ $articulo->Amece }}</td>
                                <td>{{ $articulo->UOM }}</td>
                                <td>{{ $articulo->UOM2 }}</td>
                                <td>{{ $articulo->Peso }}</td>
                                <td>{{ $articulo->CodEtiqueta }}</td>
                                <td>{{ $articulo->PrecioRecorte }}</td>
                                <td>{{ $articulo->Factor }}</td>
                                <td>{{ $articulo->NomTipoArticulo }}</td>
                                <td>{{ $articulo->NomFamilia }}</td>
                                <td>{{ $articulo->NomGrupo }}</td>
                                <td>
                                    @if ($articulo->Iva == 0)
                                        Si
                                    @else
                                        No
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#ModalEditar-{{ $articulo->CodArticulo }}">
                                        <span style="font-size: 18px" class="material-icons">edit</span>
                                    </button>
                                </td>
                                @include('Articulos.ModalEditar')
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-5 d-flex justify-content-center">
        {!! $articulos->links() !!}
    </div>
@endsection
