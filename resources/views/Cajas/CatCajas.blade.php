@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Cajas')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Catálogo de Cajas'])
                <div>
                    <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                        class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                        Agregar caja @include('components.icons.plus-circle')
                    </button>
                </div>
            </div>
        </div>
        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
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
