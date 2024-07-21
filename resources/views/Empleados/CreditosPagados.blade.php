@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Reporte de Créditos Pagados')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Créditos Pagados Por Empleado'])
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">

            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    @if ($creditosPagados->count() == 0 && !empty($numNomina))
                        <h5 class="mb-0"> @include('components.icons.info') No se Encontro el Empleado!</h5>
                    @else
                        @foreach ($creditosPagados as $cEmpleado)
                            <h5 class="mb-0" style="font-size: 16px;">@include('components.icons.cart-user')
                                {{ $cEmpleado->NumNomina }}</h5>
                            <h5 class="ps-2 mb-0" style="font-size: 16px;">{{ $cEmpleado->Nombre }}
                                {{ $cEmpleado->Apellidos }}
                            </h5>
                        @endforeach
                    @endif
                </div>
                <form class="d-flex flex-wrap align-items-center justify-content-end gap-2 pb-2" action="/CreditosPagados">
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
                            id="numNomina" placeholder="Nómina" value="{{ $numNomina }}" required>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-dark-outline" style="line-height: 18px">
                            @include('components.icons.search')
                        </button>
                    </div>
                </form>
            </div>

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Tienda</th>
                        <th>Fecha</th>
                        <th class="rounded-end">Crédito Pagado</th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $creditosPagados, 'colspan' => 3])
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
