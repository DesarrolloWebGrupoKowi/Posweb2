@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Articulos')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <div class="gap-4 pt-4 container-fluid width-95 d-flex flex-column">

        <div class="p-4 border-0 card" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Articulos'])
                <div>
                    <a href="/BuscarArticulo" class="btn btn-sm btn-dark" title="Agregar articulo">
                        Descargar articulo @include('components.icons.plus-circle')
                    </a>
                </div>
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="p-4 border-0 content-table content-table-full card" style="border-radius: 10px">
            @include('components.table-search')
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id</th>
                        <th>Código</th>
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
                    @include('components.table-empty', ['items' => $articulos, 'colspan' => 15])
                    @foreach ($articulos as $articulo)
                        <tr>
                            <td>{{ $articulo->IdArticulo }}</td>
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
                                <button class="btn-table" data-bs-toggle="modal"
                                    data-bs-target="#ModalEditar-{{ $articulo->CodArticulo }}" title="Editar articulo">
                                    @include('components.icons.list')
                                </button>
                            </td>
                            @include('Articulos.ModalEditar')
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $articulos])
        </div>
    </div>
@endsection
