@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Actualizar Inventario')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', [
                    'titulo' => 'Actualizar Inventario',
                    'options' => [['name' => 'Inventario', 'value' => '/ReporteStockAdmin']],
                ])

            </div>

            <div>
                @include('Alertas.Alertas')
            </div>
        </div>


        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex flex-wrap align-items-center justify-content-end gap-2 pb-2" action="/UpdateStockViewAdmin">
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
            <form action="/UpdateStockAdmin/{{ $idTienda }}" method="POST">
                @csrf
                <table class="w-100">
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Código</th>
                            <th>Articulo</th>
                            <th>Stock</th>
                            <th class="rounded-end">Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('components.table-empty', ['items' => $stocks, 'colspan' => 4])
                        @foreach ($stocks as $stock)
                            <tr>
                                <td>{{ $stock->CodArticulo }}</td>
                                <td>
                                    {{ $stock->NomArticulo }}
                                </td>
                                <td style="color: {!! $stock->StockArticulo <= 0 ? 'red; font-weight:bold;' : '' !!}">{{ $stock->StockArticulo }}</td>
                                <td>
                                    <input name="stock[{{ $stock->CodArticulo }}]"
                                        class="px-2 form-control rounded-2 fw-bold" style="width: 120px;" type="number"
                                        step="any" value="{{ $stock->StockArticulo }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-end">
                    @if (count($stocks) != 0)
                        <button class="mt-4 mb-4 btn btn-dark" type="submit">Guardar</button>
                    @endif
                </div>
            </form>
        </div>
    </div>

@endsection
