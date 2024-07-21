@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Reporte de Adeudos')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Adeudos Por Empleado'])
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    @if (count($adeudo) == 0 && !empty($txtFiltro))
                        <h5>
                            @include('components.icons.info') No se Encontro el Empleado
                        </h5>
                    @endif
                    @foreach ($adeudo as $aEmpleado)
                        <span class="fs-6" style="font-weight: 500"> @include('components.icons.cart-user')
                            {{ $aEmpleado->NumNomina }} </span>
                        <span class="fs-6" style="font-weight: 500">{{ $aEmpleado->Nombre }}
                            {{ $aEmpleado->Apellidos }}</span>
                    @endforeach
                </div>
                @include('components.table-search', ['placeholder' => 'No Nomina'])
            </div>
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Tienda</th>
                        <th>Fecha Venta</th>
                        <th class="rounded-end">Adeudo</th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $adeudo, 'colspan' => 3])
                    @foreach ($adeudo as $aEmpleado)
                        @foreach ($aEmpleado->Adeudos as $adeudoEmpleado)
                            <tr>
                                <td>{{ $adeudoEmpleado->NomTienda }}</td>
                                <td>{{ strftime('%d %B %Y, %H:%M', strtotime($adeudoEmpleado->FechaVenta)) }}</td>
                                <td>${{ number_format($adeudoEmpleado->ImporteCredito, 2) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
                @if (count($adeudo) > 0)
                    <tfoot>
                        <tr style="font-weight: 500">
                            <td></td>
                            <td class="text-end">Total Adeudo: </td>
                            <td>${{ number_format($adeudoTotal, 2) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

@endsection
