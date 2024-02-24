@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Actualizar Inventario')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-4">
            @include('components.title', [
                'titulo' => 'Actualizar Inventario',
                'options' => [['name' => 'Inventario', 'value' => '/ReporteStockAdmin']],
            ])
            <form class="d-flex align-items-center justify-content-end gap-2" action="/UpdateStockViewAdmin">
                <div class="d-flex align-items-center justify-content-end">
                    <div class="input-group" style="min-width: 300px">
                        {{-- <input type="text" class="form-control" name="codArticulo" id="codArticulo" placeholder="Buscar"
                            value="{{ $codArticulo }}" autofocus> --}}
                        <select class="form-select" name="idTienda" id="idTienda">
                            <option value="">Seleccione Tienda</option>
                            @foreach ($tiendas as $tienda)
                                <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                                </option>
                            @endforeach
                        </select>
                        {{-- <select class="form-select" name="idCaja" id="idCaja">
                            <option value="">Seleccione Caja</option>
                            @foreach ($cajas as $caja)
                                <option {!! $idCaja == $caja->IdCaja ? 'selected' : '' !!} value="{{ $caja->IdCaja }}">{{ $caja->NumCaja }}
                                </option>
                            @endforeach
                        </select> --}}
                        <div class="input-group-append">
                            <button class="input-group-text">
                                <span class="material-icons">search</span>
                            </button>
                        </div>
                    </div>
                </div>
                <a href="/UpdateStockViewAdmin" class="btn btn-dark-outline">
                    <span class="material-icons">refresh</span>
                </a>
            </form>
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <form action="/UpdateStockAdmin/{{ $idTienda }}" method="POST">
            <div class="content-table content-table-full card p-4" style="border-radius: 20px">
                @csrf
                <table>
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Código</th>
                            <th>Articulo</th>
                            <th>Stock</th>
                            <th class="rounded-end">Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($stocks->count() == 0)
                            <tr>
                                <td colspan="4">No se Encontraron Coincidencias!</td>
                            </tr>
                        @else
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
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
                @if ($stocks->count() != 0)
                    <button class="mt-4 mb-4 btn btn-dark" type="submit">Guardar</button>
                @endif
            </div>
        </form>
    </div>

@endsection
