@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Concentrado de Ventas')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4 flex-1" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Concentrado de Ventas ' . $tienda->NomTienda])

            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex align-items-center justify-content-end gap-2 pb-2" action="/ConcentradoVentas">

                <div class="d-flex align-items-center gap-2">
                    <input type="date" name="fecha1" class="form-control rounded" style="line-height: 18px"
                        value="{{ $fecha1 }}" required>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <input type="date" name="fecha2" class="form-control rounded" style="line-height: 18px"
                        value="{{ $fecha2 }}" required>
                </div>
                <button class="btn btn-dark-outline" title="Agregar Usuario">
                    @include('components.icons.search')
                </button>
            </form>

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
                    @include('components.table-empty', ['items' => $concentrado, 'colspan' => 6])
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
