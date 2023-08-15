@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Cajas')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Catálogo de Cajas'])
            <div>
                <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                    class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar familia
                </button>
            </div>
        </div>
        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id Caja</th>
                        <th class="rounded-end">Número de Caja</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cajas as $caja)
                        <tr>
                            <td>{{ $caja->IdCaja }}</td>
                            <td>{{ $caja->NumCaja }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('Cajas.ModalAgregar')
@endsection
