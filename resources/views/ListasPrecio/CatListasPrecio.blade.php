@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Listas de Precio')
@section('dashboardWidth', 'max-width: 1440px;')
@section('contenido')
    <div class="container-fluid pt-4" style="max-width: 1440px;">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Listas de Precio'])
            <div>
                <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                    class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar lista de precio
                </button>
                <a href="/CatListasPrecio" class="btn btn-dark-outline">
                    <span class="material-icons">refresh</span>
                </a>
            </div>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <form class="d-flex align-items-center justify-content-end pb-4 gap-4" action="/CatListasPrecio">
            <div class="input-group" style="max-width: 300px">
                <input type="text" name="txtListaPrecio" id="txtListaPrecio" class="form-control"
                    placeholder="Lista de Precios" value="{{ $filtroListaPrecio }}" autofocus>
                <div class="input-group-append">
                    <button class="input-group-text"><span class="material-icons">search</span></button>
                </div>
            </div>
        </form>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id</th>
                        <th>Nombre</th>
                        <th>Peso Minimo</th>
                        <th>Peso Maximo</th>
                        <th>Iva</th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($listasPrecio) == 0)
                        <tr>
                            <td colspan="5">No Hay Listas de Precio!</td>
                        </tr>
                    @else
                        @foreach ($listasPrecio as $listaPrecio)
                            <tr>
                                <td>{{ $listaPrecio->IdListaPrecio }}</td>
                                <td>{{ $listaPrecio->NomListaPrecio }}</td>
                                <td>{{ $listaPrecio->PesoMinimo }}</td>
                                <td>{{ $listaPrecio->PesoMaximo }}</td>
                                <td>{{ $listaPrecio->PorcentajeIva }}</td>
                                <td>
                                    <button class="btn btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#ModalEditar{{ $listaPrecio->IdListaPrecio }}">
                                        <span class="material-icons">edit</span>
                                    </button>
                                </td>
                            </tr>
                            <!--Modal Editar-->
                            @include('ListasPrecio.ModalEditar')
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @include('ListasPrecio.ModalAgregar')

    <script src="js/ListasPrecioScript.js"></script>
@endsection
