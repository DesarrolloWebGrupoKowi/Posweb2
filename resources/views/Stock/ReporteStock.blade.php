@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Reporte de Stock')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4 flex-1" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Reporte de Stock ' . $tienda->NomTienda])
                <div class="d-flex align-items-center justify-content-end gap-4">
                    <a href="/ReporteStock" class="btn btn-dark-outline">
                        @include('components.icons.switch')
                    </a>
                </div>
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex align-items-center justify-content-end gap-2 pb-2" action="/ReporteStock">
                <div class="d-flex align-items-center gap-2">
                    <label for="codArticulo" class="text-secondary" style="font-weight: 500">Buscar:</label>
                    <input class="form-control rounded" style="line-height: 18px" type="text" name="codArticulo"
                        id="codArticulo" value="{{ $codArticulo }}" autofocus>
                </div>
            </form>

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Código</th>
                        <th>Articulo</th>
                        <th class="rounded-end">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $stocks, 'colspan' => 3])
                    @foreach ($stocks as $stock)
                        <tr>
                            <td>{{ $stock->CodArticulo }}</td>
                            <td>
                                {{ $stock->NomArticulo }}
                            </td>
                            <td style="color: {!! $stock->StockArticulo == 0 ? 'red; font-weight:bold;' : '' !!}">{{ $stock->StockArticulo }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th style="text-align: center">Total: </th>
                        <th>{{ number_format($totalStock, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
