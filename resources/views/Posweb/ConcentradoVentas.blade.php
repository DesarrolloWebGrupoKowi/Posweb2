@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Concentrado de Ventas')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Concentrado de Ventas ' . $tienda->NomTienda])
            <form class="d-flex align-items-center justify-content-end gap-2"action="/ConcentradoVentas">
                <div class="input-group" style="min-width: 200px">
                    <input type="date" name="fecha1" class="form-control" value="{{ $fecha1 }}" required>
                </div>
                <div class="input-group" style="min-width: 200px">
                    <input type="date" name="fecha2" class="form-control" value="{{ $fecha2 }}" required>
                </div>
                <button class="btn btn-dark-outline">
                    <span class="material-icons">search</span>
                </button>
            </form>
        </div>
        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Código</th>
                        <th>Nombre</th>
                        <th>Peso</th>
                        <th>Precio</th>
                        <th>Iva</th>
                        <th class="rounded-end">Importe</th>
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
    </div>
@endsection
