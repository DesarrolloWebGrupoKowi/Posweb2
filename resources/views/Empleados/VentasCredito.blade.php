@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Reporte de Ventas a Crédito')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Reporte de Ventas a Crédito Empleados'])
        </div>

        <form class="d-flex align-items-center justify-content-end pb-4 gap-2"action="/VentasCredito">
            <div class="col-auto">
                <input type="date" class="form-control" name="fecha1" id="fecha1"
                    value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
            </div>
            <div class="col-auto">
                <input type="date" class="form-control" name="fecha2" id="fecha2"
                    value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
            </div>
            <div class="input-group" style="max-width: 300px">
                <select class="form-select" name="tipoNomina" id="tipoNomina">
                    @foreach ($tiposNomina as $tipoNomina)
                        <option {!! $tipoNomina->TipoNomina == $idTipoNomina ? 'selected' : '' !!} value="{{ $tipoNomina->TipoNomina }}">
                            {{ $tipoNomina->NomTipoNomina }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-dark-outline">
                <span class="material-icons">search</span>
            </button>
        </form>
        @if (!empty($fecha1) && !empty($fecha2))
            <div class="content-table content-table-full card p-4" style="border-radius: 20px">
                <table>
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Nómina</th>
                            <th>Empleado</th>
                            <th>Importe</th>
                            <th>Tienda</th>
                            <th>Ciudad</th>
                            <th class="rounded-end">Empresa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($ventasCredito->count() == 0)
                            <tr>
                                <th colspan="6"><i class="fa fa-exclamation-triangle"></i> No Hay Registros!</th>
                            </tr>
                        @else
                            @foreach ($ventasCredito as $ventaCredito)
                                <tr>
                                    <td>{{ $ventaCredito->NumNomina }}</td>
                                    <td>{{ $ventaCredito->Nombre }} {{ $ventaCredito->Apellidos }}</td>
                                    <td>$ {{ number_format($ventaCredito->ImporteCredito, 2) }}</td>
                                    <td>{{ $ventaCredito->NomTienda }}</td>
                                    <td>{{ $ventaCredito->NomCiudad }}</td>
                                    <td>{{ $ventaCredito->Empresa }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th style="text-align: center">Total: </th>
                            <th>$ {{ number_format($iTotalCredito, 2) }}</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>

@endsection
