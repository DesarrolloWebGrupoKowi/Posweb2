@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Reporte de Monedero Electrónico')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Reporte de Monedero Electrónico'])
            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">

            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    @if (empty($empleado) && !empty($numNomina))
                        <h5>
                            @include('components.icons.info') No se Encontro el Empleado
                        </h5>
                    @elseif(!empty($empleado))
                        <h5 style="font-size: 1rem;">@include('components.icons.cart-user'){{ $empleado->NumNomina }}</h5>
                        <h5 style="font-size: 1rem;">{{ $empleado->Nombre }} {{ $empleado->Apellidos }}</h5>
                    @endif
                </div>

                <form class="d-flex align-items-center justify-content-end gap-2 pb-2" action="/ReporteMonedero"
                    method="GET">
                    <div class="col-auto">
                        <input type="date" class="form-control rounded" style="line-height: 18px" name="fecha1"
                            id="fecha1" value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}" required>
                    </div>
                    <div class="col-auto">
                        <input type="date" class="form-control rounded" style="line-height: 18px" name="fecha2"
                            id="fecha2" value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}" required>
                    </div>
                    <div class="col-auto">
                        <input type="number" class="form-control rounded" style="line-height: 18px" name="numNomina"
                            id="numNomina" value="{{ $numNomina }}" placeholder="Nómina" autofocus required>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-dark-outline">
                            @include('components.icons.search')
                        </button>
                    </div>
                </form>
            </div>

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Fecha</th>
                        <th class="rounded-end">Movimiento</th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $movimientos, 'colspan' => 4])
                    @foreach ($movimientos as $movimiento)
                        <tr>
                            <td>{{ strftime('%d %B %Y, %H:%M', strtotime($movimiento->FechaGenerado)) }}</td>
                            <td style="color: {!! $movimiento->Monedero < 0 ? 'red' : '' !!}">{{ $movimiento->Monedero }}</td>
                        </tr>
                    @endforeach
                </tbody>
                @if ($movimientos->count() > 0)
                    <tfoot>
                        <tr style="font-weight: 500">
                            <td class="text-end">Monedero Disponible :</td>
                            <td>${{ number_format($monederoFinal < 0 ? 0 : $monederoFinal, 2) }}</th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection
