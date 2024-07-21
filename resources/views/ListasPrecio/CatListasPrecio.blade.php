@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Listas de Precio')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Listas de Precio'])
                <div>
                    <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                        class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                        Agregar lista de precio @include('components.icons.plus-circle')
                    </button>
                </div>
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            @include('components.table-search')
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
                                    <button class="btn-table" data-bs-toggle="modal"
                                        data-bs-target="#ModalEditar{{ $listaPrecio->IdListaPrecio }}"
                                        title="Editar lista de precio">
                                        @include('components.icons.list')
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
