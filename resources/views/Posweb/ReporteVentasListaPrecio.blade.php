@extends('plantillaBase.masterblade')
@section('title', 'Reporte de Ventas Por Listas de Precio')
@section('contenido')
    <div class="d-flex justify-content-center mb-2">
        <div class="col-auto">
            <h3 class="card shadow p-1">Reporte de Ventas Por Lista de Precio</h3>
        </div>
    </div>
    <div class="container mb-3">
        <form action="/ReporteVentasListaPrecio" method="GET">
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
                    <select class="form-select shadow" name="idListaPrecio" id="idListaPrecio" required>
                        <option value="">Seleccione Lista Precio</option>
                        @foreach ($listasPrecio as $listaPrecio)
                            <option {!! $listaPrecio->IdListaPrecio == $idListaPrecio ? 'selected' : '' !!} value="{{ $listaPrecio->IdListaPrecio }}">
                                {{ $listaPrecio->NomListaPrecio }}</option>
                        @endforeach
                    </select>
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
    @if (!empty($idListaPrecio))
        <div class="container">
            <table class="table table-striped table-responsive shadow">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Articulo</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Iva</th>
                        <th>Importe</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalCantidad = 0;
                        $totalImporte = 0;
                        $totalIva = 0;
                    @endphp
                    @if (empty($ventas))
                        <tr>
                            <th colspan="6"><i style="color: red" class="fa fa-exclamation-triangle"></i> No hay ventas
                                en ese rango de fechas</th>
                        </tr>
                    @else
                        @foreach ($ventas as $venta)
                            <tr>
                                <td>{{ $venta->CodArticulo }}</td>
                                <td>{{ $venta->NomArticulo }}</td>
                                <td>{{ number_format($venta->CantArticulo, 3) }}</td>
                                <td>${{ number_format($venta->PrecioArticulo, 2) }}</td>
                                <td>${{ number_format($venta->IvaArticulo, 2) }}</td>
                                <td>${{ number_format($venta->ImporteArticulo, 2) }}</td>
                            </tr>
                            @php
                                $totalCantidad = $totalCantidad + $venta->CantArticulo;
                                $totalImporte = $totalImporte + $venta->ImporteArticulo;
                                $totalIva = $totalIva + $venta->IvaArticulo;
                            @endphp
                        @endforeach
                    @endif
                </tbody>
                @if (!empty($ventas))
                    <tfoot>
                        <tr>
                            <th></th>
                            <th style="text-align: center">Totales: </th>
                            <th>{{ number_format($totalCantidad, 3) }}</th>
                            <th></th>
                            <th>${{ number_format($totalIva, 2) }}</th>
                            <th>${{ number_format($totalImporte, 2) }}</th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    @endif
@endsection
