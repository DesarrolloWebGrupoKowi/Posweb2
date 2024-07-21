@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Movimientos de Producto')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Movimientos de Producto'])
                <div>
                    <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar movimiento"
                        class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregarMovimiento">
                        Agregar movimiento @include('components.icons.plus-circle')
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
                        <th class="rounded-start">Id</th>
                        <th class="rounded-end">Movimiento</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($movimientosProducto as $mProducto)
                        <tr>
                            <td>{{ $mProducto->IdMovimiento }}</td>
                            <td>{{ $mProducto->NomMovimiento }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('MovimientosProducto.ModalAgregarMovimiento')
@endsection
