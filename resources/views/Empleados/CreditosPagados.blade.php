@extends('plantillaBase.masterblade')
@section('title','Reporte de Créditos Pagados')
@section('contenido')
<div class="container">
    <h2 class="titulo card">Créditos Pagados Por Empleado</h2>
</div>
<div class="container mb-3">
    <form action="/CreditosPagados">
        <div class="row">
            <div class="col-auto">
                <input type="date" class="form-control shadow" name="fecha1" id="fecha1" value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}" required>
            </div>
            <div class="col-auto">
                <input type="date" class="form-control shadow" name="fecha2" id="fecha2" value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}" required>
            </div>
            <div class="col-auto">
                <input type="number" class="form-control shadow" name="numNomina" id="numNomina" placeholder="Nómina" value="{{ $numNomina }}" required>
            </div>
            <div class="col-auto">
                <button class="btn card">
                    <span class="material-icons">search</span>
                </button>
            </div>
        </div>
    </form>
</div>
<div class="container">
    <div class="row justify-content-center">
        @if ($creditosPagados->count() == 0 && !empty($numNomina))
        <div class="col-auto">
            <h5 class="bg-danger text-white p-1"><i class="fa fa-exclamation-triangle"></i> No se Encontro el Empleado!</h5>
        </div>
        @else
        @foreach ($creditosPagados as $cEmpleado)
        <div class="col-auto">
            <h5 class="bg-dark text-white p-1"><i class="fa fa-id-card"></i> {{ $cEmpleado->NumNomina }}</h5>
        </div>
        <div class="col-auto">
            <h5 class="bg-dark text-white p-1">{{ $cEmpleado->Nombre }} {{ $cEmpleado->Apellidos }}</h5>
        </div>
        @endforeach
        @endif
    </div>
    <table class="table table-responsive table-striped">
        <thead class="table-dark">
            <tr>
                <th>Tienda</th>
                <th>Fecha</th>
                <th>Crédito Pagado</th>
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
                    <td>{{ strftime("%d %B %Y, %H:%M", strtotime( $adeudoPagado->FechaVenta )) }}</td>
                    <td>{{ $adeudoPagado->ImporteCredito }}</td>
                </tr>
                @endforeach
                @endif
            @endforeach
        </tbody>
    </table>
</div>

@endsection