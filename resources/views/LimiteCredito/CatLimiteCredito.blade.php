@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Catálogo de Límite Crédito')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Tipos de Nómina y Límites de Crédito'])
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
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
                                <button class="btn-table" data-bs-toggle="modal"
                                    data-bs-target="#ModalEditar{{ $lCredito->IdCatLimiteCredito }}">
                                    @include('components.icons.edit')
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
