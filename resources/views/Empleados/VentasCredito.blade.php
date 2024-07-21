@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Reporte de Ventas a Crédito')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Reporte de Ventas a Crédito Empleados'])
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex align-items-center justify-content-end gap-2 pb-2" action="/VentasCredito">
                <div class="col-auto">
                    <input type="date" class="form-control rounded" style="line-height: 18px" name="fecha1"
                        id="fecha1" value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
                </div>
                <div class="col-auto">
                    <input type="date" class="form-control rounded" style="line-height: 18px" name="fecha2"
                        id="fecha2" value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                </div>
                <div class="input-group" style="max-width: 300px">
                    <select class="form-select rounded" style="line-height: 18px" name="tipoNomina" id="tipoNomina">
                        @foreach ($tiposNomina as $tipoNomina)
                            <option {!! $tipoNomina->TipoNomina == $idTipoNomina ? 'selected' : '' !!} value="{{ $tipoNomina->TipoNomina }}">
                                {{ $tipoNomina->NomTipoNomina }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-dark-outline">
                    @include('components.icons.search')
                </button>
            </form>
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
                    @include('components.table-empty', ['items' => $ventasCredito, 'colspan' => 6])
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
                </tbody>
                @if (count($ventasCredito) > 0)
                    <tfoot>
                        <tr style="font-weight: 500">
                            <td></td>
                            <td class="text-end">Total: </td>
                            <td>$ {{ number_format($iTotalCredito, 2) }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

@endsection
