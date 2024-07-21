@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Tipos de Pago')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Tipos de Pago'])
                <div>
                    <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                        class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                        Agregar tipo de pago @include('components.icons.plus-circle')
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
