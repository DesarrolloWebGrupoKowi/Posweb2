@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Límite Crédito Para Empleados')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Límites de Crédito Para Empleados'])<div>
                    <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                        class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregarEmpleado">
                        <i class="fa fa-plus-circle pe-1"></i> Agregar Empleado
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
                        <th class="rounded-start">Num Nómina</th>
                        <th>Empleado</th>
                        <th>Límite Crédito</th>
                        <th>Ventas Diarias</th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($limitesCredito as $lCredito)
                        <tr>
                            <td>{{ $lCredito->NumNomina }}</td>
                            <td>{{ $lCredito->Nombre }} {{ $lCredito->Apellidos }}</td>
                            <td>${{ $lCredito->Limite }}</td>
                            <td>{{ $lCredito->TotalVentaDiaria }}</td>
                            <td>
                                <button class="btn-table" data-bs-toggle="modal"
                                    data-bs-target="#ModalEditarEmpleado{{ $lCredito->IdCatLimiteCreditoEspecial }}">
                                    @include('components.icons.edit')
                                </button>
                                <button class="btn-table btn-table-delete" data-bs-toggle="modal"
                                    data-bs-target="#ModalEliminarEmpleado{{ $lCredito->IdCatLimiteCreditoEspecial }}">
                                    @include('components.icons.delete')
                                </button>
                            </td>
                            @include('LimiteCreditoEspecial.ModalEditar')
                            @include('LimiteCreditoEspecial.ModalEliminar')
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('LimiteCreditoEspecial.ModalAgregar')
@endsection
