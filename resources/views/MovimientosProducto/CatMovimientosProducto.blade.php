@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Movimientos de Producto')
@section('dashboardWidth', 'max-width: 1440px;')
@section('contenido')
    <div class="container-fluid pt-4" style="max-width: 1440px;">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Movimientos de Producto'])
            <div>
                <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar movimiento"
                    class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregarMovimiento">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar movimiento
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
