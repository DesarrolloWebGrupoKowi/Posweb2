@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Inventario')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Inventario '])

                <form class="d-flex align-items-center justify-content-end gap-2" action="/UpdateStockViewAdmin">
                    <input type="hidden" name="idTienda" value="{{ $idTienda }}" />
                    <button class="btn btn-dark">
                        Ajuste de inventario @include('components.icons.tools')
                    </button>
                </form>
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex flex-wrap align-items-center justify-content-end gap-2 pb-2" action="/ReporteStockAdmin">
                <div>
                    <select class="form-select rounded" style="line-height: 18px" name="idTienda" id="idTienda">
                        <option value="">Seleccione Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-dark-outline">
                    @include('components.icons.search')
                </button>
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
                    {{-- <tr>
                        <th></th>
                        <th style="text-align: center">Total: </th>
                        <th>{{ number_format($totalStock, 2) }}</th>
                    </tr> --}}
                </tfoot>
            </table>
        </div>
    </div>

@endsection
