@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Reporte de Ventas a Empleados')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Reporte de Ventas a Empleados'])
        </div>

        <form class="d-flex align-items-center justify-content-end pb-4 gap-2" action="/VentaEmpleados">
            <div class="col-auto">
                <input class="form-control" type="date" name="fecha1" id="fecha1"
                    value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
            </div>
            <div class="col-auto">
                <input class="form-control" type="date" name="fecha2" id="fecha2"
                    value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <span class="input-group-text card">Buscar Empleado</span>
                    <span class="input-group-text">
                        <input {!! $chkNomina == 'on' ? 'checked' : '' !!} class="form-check-input mt-0" type="checkbox" name="chkNomina"
                            id="chkNomina">
                    </span>
                    <input {!! $chkNomina == 'on' ? '' : 'disabled' !!} class="form-control" type="number" name="numNomina" id="numNomina"
                        value="{!! $chkNomina == 'on' ? $numNomina : 'disabled' !!}" required>
                </div>
            </div>
            <div class="col-auto">
                <button class="btn btn-dark-outline">
                    <span class="material-icons">search</span>
                </button>
            </div>
        </form>

        @if (!empty($fecha1) && !empty($fecha2))
            <div class="content-table content-table-full card p-4" style="border-radius: 20px">
                <table>
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Fecha Compra</th>
                            <th>Tienda</th>
                            <th>Nómina</th>
                            <th>Empleado</th>
                            <th>Empresa</th>
                            <th>Ticket</th>
                            <th>Importe</th>
                            <th class="rounded-end">Crédito</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($ventasEmpleado->count() == 0)
                            <tr>
                                <td colspan="8">No Hay Registros!</td>
                            </tr>
                        @else
                            @foreach ($ventasEmpleado as $ventaEmpleado)
                                <tr>
                                    <td>{{ strftime('%d %B %Y, %H:%M', strtotime($ventaEmpleado->FechaVenta)) }}</td>
                                    <td>{{ $ventaEmpleado->NomTienda }}</td>
                                    <td>{{ $ventaEmpleado->NumNomina }}</td>
                                    <td>{{ $ventaEmpleado->Nombre }} {{ $ventaEmpleado->Apellidos }}</td>
                                    <td>{{ $ventaEmpleado->Empresa }}</td>
                                    <td>{{ $ventaEmpleado->IdTicket }}</td>
                                    <td>{{ number_format($ventaEmpleado->ImporteArticulo, 2) }}</td>
                                    <td>
                                        @if ($ventaEmpleado->StatusCredito == '0')
                                            <i class="fa fa-check"></i>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Totales: </th>
                            <th>{{ number_format($importeTotal, 2) }}</th>
                            <th>{{ number_format($importeCredito, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>

    <script>
        const chkNomina = document.getElementById('chkNomina');
        const numNomina = document.getElementById('numNomina');
        chkNomina.addEventListener('click', function() {
            if (numNomina.disabled == true) {
                numNomina.disabled = false;
            } else {
                numNomina.value = '';
                numNomina.disabled = true;

            }
        });
    </script>
@endsection
