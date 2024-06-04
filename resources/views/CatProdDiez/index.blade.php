@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo Productos Cambio de Lista')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo Productos Cambio de Lista'])
            <div>
                @if (Auth::user()->tipoUsuario->IdTipoUsuario != 2)
                    <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                        class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                        <i class="fa fa-plus-circle pe-1"></i> Agregar Producto
                    </button>
                @endif
                <a class="btn btn-dark-outline" href="/CatProdDiez">
                    <span class="material-icons">refresh</span>
                </a>
            </div>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <form class="d-flex align-items-center justify-content-between pb-4 gap-4" action="/CatProdDiez" method="GET">
            <div>
                {{-- <h6>Descuentos Activos: ({{ $descuentosActivos }})</h6> --}}
            </div>
            <div class="input-group" style="max-width: 300px">
                <input type="text" class="form-control" name="textValue" id="textValue"
                    placeholder="Nombre/código artículo" value="{{ $textValue }}" autofocus>
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
                    @if ($catProducts->count() == 0)
                        <tr>
                            <td colspan="10">No Hay Coincidencias!</td>
                        </tr>
                    @else
                        @foreach ($catProducts as $item)
                            <tr style="vertical-align: middle">
                                <td>{{ $item->CodArticulo }}</td>
                                <td>{{ $item->NomArticulo }}</td>
                                <td>{{ $item->Cantidad_Ini }}</td>
                                <td>{{ $item->Cantidad_Fin }}</td>
                                <td>{{ $item->NomListaPrecio }}</td>
                                <td>{{ $item->NomUsuario }}</td>
                                <td>{{ strftime('%d %B %Y', strtotime($item->Creacion)) }}</td>
                                <td class="text-center">
                                    @if ($item->Status == 0)
                                        <i class="text-success fs-5 fa fa-check-circle-o" aria-hidden="true"></i>
                                    @else
                                        <i class="fs-5 fa fa-times-circle-o" aria-hidden="true"></i>
                                    @endif
                                </td>
                                @if (Auth::user()->tipoUsuario->IdTipoUsuario != 2)
                                    <td>
                                        <button class="btn" data-bs-toggle="modal"
                                            data-bs-target="#ModalEliminarConfirm{{ $item->IdCatProdDiez }}">
                                            <span style="color: red" class="material-icons">delete_forever</span>
                                        </button>
                                        @include('CatProdDiez.ModalEliminarConfirm')
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!--Modal Agregar Estado-->
    @include('CatProdDiez.ModalAgregar')
@endsection
