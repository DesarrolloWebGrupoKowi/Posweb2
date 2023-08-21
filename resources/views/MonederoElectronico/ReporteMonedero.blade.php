@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Reporte de Monedero Electrónico')
@section('dashboardWidth', 'width-general')
<style>
    .container #dTicket {
        cursor: pointer;
        font-style: normal;
        text-decoration: underline;
    }

    .container #dTicket:hover {
        font-weight: bold;
        color: rgb(17, 133, 250);
    }
</style>
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Reporte de Monedero Electrónico'])
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <form class="d-flex align-items-center justify-content-end pb-4 gap-2" action="/ReporteMonedero" method="GET">
            <div class="col-auto">
                <input type="date" class="form-control" name="fecha1" id="fecha1"
                    value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}" required>
            </div>
            <div class="col-auto">
                <input type="date" class="form-control" name="fecha2" id="fecha2"
                    value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}" required>
            </div>
            <div class="col-auto">
                <input type="number" class="form-control" name="numNomina" id="numNomina" value="{{ $numNomina }}"
                    placeholder="Nómina" autofocus required>
            </div>
            <div class="col-auto">
                <button class="btn btn-dark-outline">
                    <span class="material-icons">search</span>
                </button>
            </div>
        </form>
        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <div class="mb-2 d-flex align-items-center gap-2">
                @if (empty($empleado) && !empty($numNomina))
                    <h5>
                        <i class="fa fa-exclamation-triangle"></i> No se Encontro el Empleado
                        <i class="fa fa-exclamation-triangle"></i>
                    </h5>
                @elseif(!empty($empleado))
                    <h5 style="font-size: 1rem;"><i class="fa fa-id-card pe-1"></i> {{ $empleado->NumNomina }}</h5>
                    <h5 style="font-size: 1rem;">{{ $empleado->Nombre }} {{ $empleado->Apellidos }}</h5>
                @endif
            </div>
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Fecha</th>
                        <th class="rounded-end">Movimiento</th>
                    </tr>
                </thead>
                <tbody>
                    @if (empty($numNomina) && empty($empleado))
                        <tr>
                            <th colspan="4">Selecciona un numero de nomina!</th>
                        </tr>
                    @endif
                    @if ($movimientos->count() == 0)
                        <tr>
                            <th colspan="4">No se encuentan movimientos!</th>
                        </tr>
                    @else
                        @foreach ($movimientos as $movimiento)
                            <tr>
                                <td>{{ strftime('%d %B %Y, %H:%M', strtotime($movimiento->FechaGenerado)) }}</td>
                                <td style="color: {!! $movimiento->Monedero < 0 ? 'red' : '' !!}">{{ $movimiento->Monedero }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                @if ($movimientos->count() > 0)
                    <tfoot>
                        <tr>
                            <th>Monedero Disponible :</th>
                            <th>${{ number_format($monederoFinal < 0 ? 0 : $monederoFinal, 2) }}</th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection
