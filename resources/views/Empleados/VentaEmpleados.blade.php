@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Reporte de Ventas a Empleados')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4 flex-1" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Reporte de Ventas a Empleados'])
                <div>
                    <form action="/VentaEmpleadosExcel" method="GET">
                        <input type="hidden" name="fecha1" value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
                        <input type="hidden" name="fecha2" value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                        <input type="hidden" name="chkNomina" value="{{ $chkNomina }}">
                        <input type="hidden" name="numNomina" value="{{ $numNomina }}">
                        <input type="hidden" name="idTienda" value="{{ $idTienda }}">
                        <button type="submit" class="input-group-text text-decoration-none btn-excel">
                            Exportar @include('components.icons.excel')
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex flex-wrap align-items-center justify-content-end gap-2 pb-2" action="/VentaEmpleados">
                <div class="col-auto">
                    <input class="form-control rounded" style="line-height: 18px" type="date" name="fecha1"
                        id="fecha1" value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
                </div>
                <div class="col-auto">
                    <input class="form-control rounded" style="line-height: 18px" type="date" name="fecha2"
                        id="fecha2" value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                </div>
                <div class="col-auto">
                    <select class="rounded form-select" style="line-height: 18px" name="idTienda" id="idTienda">
                        <option value="">Seleccione Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <div class="input-group">
                        <span class="input-group-text card" style="line-height: 18px">Buscar Empleado</span>
                        <span class="input-group-text">
                            <input {!! $chkNomina == 'on' ? 'checked' : '' !!} class="form-check-input mt-0"style="line-height: 18px"
                                type="checkbox" name="chkNomina" id="chkNomina">
                        </span>
                        <input {!! $chkNomina == 'on' ? '' : 'disabled' !!} class="form-control rounded" style="line-height: 18px" type="number"
                            name="numNomina" id="numNomina" value="{!! $chkNomina == 'on' ? $numNomina : 'disabled' !!}" placeholder="# Nómina" required>
                    </div>
                </div>
                <div class="col-auto">
                    <button class="btn btn-dark-outline">
                        @include('components.icons.search')
                    </button>
                </div>
            </form>
            <div class="content-table content-table-full">
                <table class="w-100">
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Fecha Compra</th>
                            <th>Tienda</th>
                            <th>Nómina</th>
                            <th>Empleado</th>
                            <th>Empresa</th>
                            <th>Ticket</th>
                            <th>Codigo</th>
                            <th>Articulo</th>
                            <th>Importe</th>
                            <th>Tipo empleado</th>
                            <th class="rounded-end">Crédito</th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('components.table-empty', ['items' => $ventasEmpleado, 'colspan' => 10])
                        @foreach ($ventasEmpleado as $ventaEmpleado)
                            <tr>
                                <td>{{ strftime('%d %B %Y, %H:%M', strtotime($ventaEmpleado->FechaVenta)) }}</td>
                                <td>{{ $ventaEmpleado->NomTienda }}</td>
                                <td>{{ $ventaEmpleado->NumNomina }}</td>
                                <td>{{ $ventaEmpleado->Nombre }} {{ $ventaEmpleado->Apellidos }}</td>
                                <td>{{ $ventaEmpleado->Empresa }}</td>
                                <td>{{ $ventaEmpleado->IdTicket }}</td>
                                <td>{{ $ventaEmpleado->CodArticulo }}</td>
                                <td>{{ $ventaEmpleado->NomArticulo }}</td>
                                <td>{{ number_format($ventaEmpleado->ImporteArticulo, 2) }}</td>
                                <td>
                                    {{ ['' => '', 3 => 'SEMANAL', 4 => 'QUINCENAL'][$ventaEmpleado->TipoNomina] }}
                                </td>
                                <td>
                                    @if ($ventaEmpleado->StatusCredito == '0')
                                        @include('components.icons.check-all')
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
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
        </div>
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
