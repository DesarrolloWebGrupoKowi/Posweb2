@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Tipos de Pago')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Tipos de Pago'])
            <div>
                <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                    class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar tipo de pago
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
                        <th class="rounded-start">Id de Tipo de Pago</th>
                        <th>Nombre</th>
                        <th>Clave Sat</th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tiposPago as $tipoPago)
                        <tr>
                            <td>{{ $tipoPago->IdTipoPago }}</td>
                            <td>{{ $tipoPago->NomTipoPago }}</td>
                            <td>{{ $tipoPago->ClaveSat }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('TipoPago.ModalAgregar')

@endsection
