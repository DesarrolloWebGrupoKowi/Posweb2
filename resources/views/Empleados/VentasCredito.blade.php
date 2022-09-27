@extends('plantillaBase.masterblade')
@section('title', 'Reporte de Ventas a Crédito')
@section('contenido')
    <div class="row d-flex justify-content-center mb-3">
        <div class="col-auto">
            <h2 class="card shadow p-1">Reporte de Ventas a Crédito Empleados</h2>
        </div>
    </div>
    <div class="container mb-3">
        <form action="/VentasCredito">
            <div class="row">
                <div class="col-auto">
                    <input type="date" class="form-control shadow" name="fecha1" id="fecha1"
                        value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
                </div>
                <div class="col-auto">
                    <input type="date" class="form-control shadow" name="fecha2" id="fecha2"
                        value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                </div>
                <div class="col-auto">
                    <select class="form-select shadow" name="tipoNomina" id="tipoNomina">
                        @foreach ($tiposNomina as $tipoNomina)
                            <option {!! $tipoNomina->TipoNomina == $idTipoNomina ? 'selected' : '' !!} value="{{ $tipoNomina->TipoNomina }}">
                                {{ $tipoNomina->NomTipoNomina }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn card shadow">
                        <span class="material-icons">search</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    @if (!empty($fecha1) && !empty($fecha2))
        <div class="container">
            <table class="table table-striped table-responsive">
                <thead class="table-dark">
                    <tr>
                        <th>Nómina</th>
                        <th>Empleado</th>
                        <th>Importe</th>
                        <th>Tienda</th>
                        <th>Ciudad</th>
                        <th>Empresa</th>
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
@endsection
