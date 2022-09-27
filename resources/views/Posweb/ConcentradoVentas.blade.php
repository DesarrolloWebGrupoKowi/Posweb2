@extends('plantillaBase.masterblade')
@section('title', 'Concentrado de Ventas')
@section('contenido')
<div class="container">
    <h2 class="titulo card shadow mb-3">Concentrado de Ventas - {{ $tienda->NomTienda }}</h2>
</div>
<div class="container">
    <form action="/ConcentradoVentas">
        <div class="row mb-3">
            <div class="col-2">
                <input type="date" name="fecha1" class="form-control" value="{{ $fecha1 }}" required>
            </div>
            <div class="col-2">
                <input type="date" name="fecha2" class="form-control" value="{{ $fecha2 }}" required>
            </div>
            <div class="col-1">
                <button class="btn card">
                    <span class="material-icons">search</span>
                </button>
            </div>
        </div>
    </form>
    <table class="table table-sm table-responsive table-striped shadow">
        <thead class="table-dark">
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Peso</th>
                <th>Precio</th>
                <th>Iva</th>
                <th>Importe</th>
            </tr>
        </thead>
        <tbody>
            @if ($concentrado->count() == 0)
                <tr>
                    <td colspan="6">No Hay Ventas en Rango de Fechas Seleccionadas!</td>
                </tr>
            @else
            @foreach ($concentrado as $tConcentrado)
            <tr>
                <td>{{ $tConcentrado->CodArticulo }}</td>
                <td>{{ $tConcentrado->NomArticulo }}</td>
                <td>{{ number_format($tConcentrado->Peso, 3) }}</td>
                <td>{{ number_format($tConcentrado->PrecioArticulo, 2) }}</td>
                <td>{{ number_format($tConcentrado->Iva, 2) }}</td>
                <td>{{ number_format($tConcentrado->Importe, 2) }}</td>
            </tr>
        @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <th></th>
                <th style="text-align: center">Totales :</th>
                <th>{{ number_format($totalPeso, 3) }}</th>
                <th></th>
                <th>${{ number_format($totalIva, 2) }}</th>
                <th>${{ number_format($totalImporte, 2) }}</th>
            </tr>
        </tfoot>
    </table>
</div>
@endsection