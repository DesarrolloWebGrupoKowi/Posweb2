@extends('plantillaBase.masterblade')
@section('title', 'Reporte de Adeudos')
@section('contenido')
    <div class="container mb-3">
        <h2 class="titulo card">Adeudos Por Empleado</h2>
    </div>
    <div class="container mb-3">
        <form action="/AdeudosEmpleado">
            <div class="row">
                <div class="col-auto">
                    <input class="form-control shadow" type="number" name="numNomina" placeholder="Nómina"
                        value="{{ $numNomina }}" required>
                </div>
                <div class="col-auto">
                    <button class="btn card shadow">
                        <span class="material-icons">search</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="container">
        <div class="mb-3">
            <div class="row d-flex justify-content-center">
                @if ($adeudo->count() == 0 && !empty($numNomina))
                    <div class="col-auto bg-danger text-white shadow">
                        <h5>
                            <i class="fa fa-exclamation-triangle"></i> No se Encontro el Empleado <i
                                class="fa fa-exclamation-triangle"></i>
                        </h5>
                    </div>
                @endif
                @foreach ($adeudo as $aEmpleado)
                    <div class="col-auto">
                        <h5 class="bg-dark text-white"><i class="fa fa-id-card"></i> {{ $aEmpleado->NumNomina }}</h5>
                    </div>
                    <div class="col-auto">
                        <h5 class="bg-dark text-white">{{ $aEmpleado->Nombre }} {{ $aEmpleado->Apellidos }}</h5>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="table-responsive card">
            <table class="table table-responsive table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Tienda</th>
                        <th>Fecha Venta</th>
                        <th>Adeudo</th>
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
