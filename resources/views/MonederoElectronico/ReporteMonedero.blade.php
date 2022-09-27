@extends('plantillaBase.masterblade')
@section('title', 'Reporte de Monedero Electrónico')
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
    <div class="d-flex justify-content-center mb-2">
        <div class="col-auto">
            <h2 class="card shadow p-1">Reporte de Monedero Electrónico</h2>
        </div>
    </div>
    <div class="container mb-3">
        <form action="/ReporteMonedero" method="GET">
            <div class="row">
                <div class="col-auto">
                    <input type="date" class="form-control shadow" name="fecha1" id="fecha1"
                        value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}" required>
                </div>
                <div class="col-auto">
                    <input type="date" class="form-control shadow" name="fecha2" id="fecha2"
                        value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}" required>
                </div>
                <div class="col-auto">
                    <input type="text" class="form-control" name="numNomina" id="numNomina" value="{{ $numNomina }}"
                        placeholder="Nómina" autofocus required>
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
        @include('Alertas.Alertas')
    </div>
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-auto">
                @if (empty($empleado) && !empty($numNomina))
                    <h4><i class="fa fa-exclamation-triangle"></i> No se Encontro el Empleado <i
                            class="fa fa-exclamation-triangle"></i></h4>
                @elseif(!empty($empleado))
                    <h4>{{ $empleado->NumNomina }} - {{ $empleado->Nombre }} {{ $empleado->Apellidos }}</h4>
                @endif
            </div>
        </div>
        @if (!empty($numNomina) && !empty($empleado))
            <table class="table table-responsive table-striped shadow">
                <thead class="table-dark">
                    <tr>
                        <th>Fecha</th>
                        <th>Tienda</th>
                        <th>Ticket</th>
                        <th>Monedero</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($movimientos->count() == 0)
                        <tr>
                            <th colspan="4">No se encontraron movimientos en ese rango de fechas!</th>
                        </tr>
                    @else
                        @foreach ($movimientos as $movimiento)
                            @foreach ($movimiento->Encabezado as $eMon)
                                <tr>
                                    <td>{{ strftime('%d %B %Y, %H:%M', strtotime($movimiento->FechaMovimiento)) }}</td>
                                    <td>{{ $eMon->NomTienda }}</td>
                                    <td>
                                        <i id="dTicket" data-bs-toggle="modal"
                                            data-bs-target="#DetalleTicket{{ $eMon->IdEncabezado }}">{{ $eMon->IdTicket }}</i>
                                        @include('MonederoElectronico.ModalDetalleTicket')
                                    </td>
                                    <td style="color: {!! $movimiento->Monedero < 0 ? 'red' : '' !!}">{{ $movimiento->Monedero }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endif
                </tbody>
                @if ($movimientos->count() > 0)
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>Monedero Disponible :</th>
                            <th>${{ number_format($monederoFinal < 0 ? 0 : $monederoFinal, 2) }}</th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        @endif
    </div>
@endsection
