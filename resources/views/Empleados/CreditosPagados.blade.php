@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Reporte de Créditos Pagados')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Créditos Pagados Por Empleado'])
        </div>
        <form class="d-flex align-items-center justify-content-end pb-4 gap-2" action="/CreditosPagados">
            <div class="col-auto">
                <input type="date" class="form-control" name="fecha1" id="fecha1"
                    value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}" required>
            </div>
            <div class="col-auto">
                <input type="date" class="form-control" name="fecha2" id="fecha2"
                    value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}" required>
            </div>
            <div class="col-auto">
                <input type="number" class="form-control" name="numNomina" id="numNomina" placeholder="Nómina"
                    value="{{ $numNomina }}" required>
            </div>
            <div class="col-auto">
                <button class="btn card">
                    <span class="material-icons">search</span>
                </button>
            </div>
        </form>
        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            @if ($creditosPagados->count() == 0 && !empty($numNomina))
                <div class="col-auto">
                    <h5><i class="fa fa-exclamation-triangle"></i> No se Encontro el
                        Empleado!</h5>
                </div>
            @else
                @foreach ($creditosPagados as $cEmpleado)
                    <div class="d-flex">
                        <h5 style="font-size: 16px;"><i class="fa fa-id-card pe-1"></i> {{ $cEmpleado->NumNomina }}</h5>
                        <h5 class="ps-2" style="font-size: 16px;">{{ $cEmpleado->Nombre }} {{ $cEmpleado->Apellidos }}
                        </h5>
                    </div>
                @endforeach
            @endif
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Tienda</th>
                        <th>Fecha</th>
                        <th class="rounded-end">Crédito Pagado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($creditosPagados as $creditoPagado)
                        @if ($creditoPagado->Adeudos->count() == 0)
                            <tr>
                                <td colspan="3">No Hay Créditos Pagados!</td>
                            </tr>
                        @else
                            @foreach ($creditoPagado->Adeudos as $adeudoPagado)
                                <tr>
                                    <td>{{ $adeudoPagado->NomTienda }}</td>
                                    <td>{{ strftime('%d %B %Y, %H:%M', strtotime($adeudoPagado->FechaVenta)) }}</td>
                                    <td>{{ $adeudoPagado->ImporteCredito }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
