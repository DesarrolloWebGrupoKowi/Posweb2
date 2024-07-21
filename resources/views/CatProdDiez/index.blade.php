@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo Productos Cambio de Lista')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo Productos Cambio de Lista'])
                <div>
                    @if (Auth::user()->tipoUsuario->IdTipoUsuario != 2)
                        <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                            class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                            Agregar Producto @include('components.icons.plus-circle')
                        </button>
                    @endif
                </div>
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex align-items-center justify-content-end gap-2 pb-2" action="/CatProdDiez" method="GET">
                <div class="d-flex align-items-center gap-2">
                    <label for="textValue" class="text-secondary" style="font-weight: 500">Buscar:</label>
                    <input class="form-control rounded" style="line-height: 18px" type="text" name="textValue"
                        id="textValue" value="{{ $textValue }}" autofocus>
                </div>
            </form>

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Código</th>
                        <th>Articulo</th>
                        <th>Peso Minimo</th>
                        <th>Peso Maximo</th>
                        <th>Lista Precio</th>
                        <th>Usuario</th>
                        <th>Fecha Creación</th>
                        <th class="{{ Auth::user()->tipoUsuario->IdTipoUsuario == 2 ? 'rounded-end' : '' }}">Estatus</th>
                        @if (Auth::user()->tipoUsuario->IdTipoUsuario != 2)
                            <th class="rounded-end">Eliminar</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $catProducts, 'colspan' => 10])
                    @foreach ($catProducts as $item)
                        <tr style="vertical-align: middle">
                            <td>{{ $item->CodArticulo }}</td>
                            <td>{{ $item->NomArticulo }}</td>
                            <td>{{ $item->Cantidad_Ini }}</td>
                            <td>{{ $item->Cantidad_Fin }}</td>
                            <td>{{ $item->NomListaPrecio }}</td>
                            <td>{{ $item->NomUsuario }}</td>
                            <td>{{ strftime('%d %B %Y', strtotime($item->Creacion)) }}</td>
                            <td>
                                @if ($item->Status == 0)
                                    <span class="tags-green">Activo</span>
                                @else
                                    <span class="tags-red">Cancelado</span>
                                @endif
                            </td>
                            @if (Auth::user()->tipoUsuario->IdTipoUsuario != 2)
                                <td>
                                    <button class="btn-table btn-table-delete" data-bs-toggle="modal"
                                        data-bs-target="#ModalEliminarConfirm{{ $item->IdCatProdDiez }}"
                                        title="Eliminar artículo">
                                        @include('components.icons.delete')
                                    </button>
                                    @include('CatProdDiez.ModalEliminarConfirm')
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $catProducts])
        </div>
    </div>

    <!--Modal Agregar Estado-->
    @include('CatProdDiez.ModalAgregar')
@endsection
