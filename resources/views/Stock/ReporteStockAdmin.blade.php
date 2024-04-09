@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Inventario')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-4">
            @include('components.title', ['titulo' => 'Inventario '])
            <form class="d-flex align-items-center justify-content-end gap-2" action="/ReporteStockAdmin">
                <div class="d-flex align-items-center justify-content-end">
                    <div class="input-group" style="min-width: 300px">
                        {{-- <input type="text" class="form-control" name="codArticulo" id="codArticulo" placeholder="Buscar"
                            value="{{ $codArticulo }}" autofocus> --}}
                        <select class="form-select" name="idTienda" id="idTienda" required>
                            <option value="">Seleccione Tienda</option>
                            @foreach ($tiendas as $tienda)
                                <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                                </option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <button class="input-group-text">
                                <span class="material-icons">search</span>
                            </button>
                        </div>
                    </div>
                </div>
                <a href="/UpdateStockViewAdmin" class="btn btn-dark">
                    Ajuste de inventario
                </a>
            </form>
        </div>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Código</th>
                        <th>Articulo</th>
                        <th class="rounded-end">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($stocks) == 0)
                        <tr>
                            <td colspan="3">No se Encontraron Coincidencias!</td>
                        </tr>
                    @else
                        @foreach ($stocks as $stock)
                            <tr>
                                <td>{{ $stock->CodArticulo }}</td>
                                <td>
                                    {{ $stock->NomArticulo }}
                                </td>
                                <td style="color: {!! $stock->StockArticulo == 0 ? 'red; font-weight:bold;' : '' !!}">{{ $stock->StockArticulo }}</td>
                            </tr>
                        @endforeach
                    @endif
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
