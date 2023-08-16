@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Límite Crédito')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Tipos de Nómina y Límites de Crédito'])
            {{-- <div>
                <button type="button" class="btn btn-sm btn-dark" role="tooltip" title="Agregar Usuario"
                    class="btn btn-default Agregar" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                    <i class="fa fa-plus-circle pe-1"></i> Agregar familia
                </button>
            </div> --}}
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id Tipo Nómina</th>
                        <th>Tipo Empleado</th>
                        <th>Límite Crédito</th>
                        <th>Ventas Diarias</th>
                        <th class="rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($limitesCredito as $lCredito)
                        <tr>
                            <td>{{ $lCredito->TipoNomina }}</td>
                            <td>{{ $lCredito->NomTipoNomina }}</td>
                            <td>${{ $lCredito->Limite }}</td>
                            <td>{{ $lCredito->TotalVentaDiaria }}</td>
                            <td>
                                <button class="btn" data-bs-toggle="modal"
                                    data-bs-target="#ModalEditar{{ $lCredito->IdCatLimiteCredito }}">
                                    <span class="material-icons">edit</span>
                                </button>
                            </td>
                            @include('LimiteCredito.ModalEditar')
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
