@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Reporte de Adeudos')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-4">
            @include('components.title', ['titulo' => 'Adeudos Por Empleado'])
            <form action="/AdeudosEmpleado">
                <label class="fw-bold text-secondary pb-1">Escribe el numero de nómina</label>
                <div class="input-group" style="max-width: 300px">
                    <input class="form-control" type="number" name="numNomina" placeholder="Nómina" value="{{ $numNomina }}"
                        required autofocus>
                    <div class="input-group-append">
                        <button class="input-group-text"><span class="material-icons">search</span></button>
                    </div>
                </div>
            </form>
        </div>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <div class="mb-2 d-flex align-items-center gap-2">
                @if ($adeudo->count() == 0 && !empty($numNomina))
                    <h5>
                        <i class="fa fa-exclamation-triangle"></i> No se Encontro el Empleado <i
                            class="fa fa-exclamation-triangle"></i>
                    </h5>
                @endif
                @foreach ($adeudo as $aEmpleado)
                    <h5 style="font-size: 1rem;"><i class="fa fa-id-card pe-1"></i> {{ $aEmpleado->NumNomina }}</h5>
                    <h5 style="font-size: 1rem;">{{ $aEmpleado->Nombre }} {{ $aEmpleado->Apellidos }}</h5>
                @endforeach
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
                    @foreach ($adeudo as $aEmpleado)
                        @if ($aEmpleado->Adeudos->count() == 0)
                            <tr>
                                <td colspan="3">No Hay Adeudo!</td>
                            </tr>
                        @else
                            @foreach ($aEmpleado->Adeudos as $adeudoEmpleado)
                                <tr>
                                    <td>{{ $adeudoEmpleado->NomTienda }}</td>
                                    <td>{{ strftime('%d %B %Y, %H:%M', strtotime($adeudoEmpleado->FechaVenta)) }}</td>
                                    <td>${{ number_format($adeudoEmpleado->ImporteCredito, 2) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th style="text-align: center">Total Adeudo: </th>
                        <th>${{ number_format($adeudoTotal, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
