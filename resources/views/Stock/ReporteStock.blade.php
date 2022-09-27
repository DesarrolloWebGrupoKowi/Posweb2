@extends('plantillaBase.masterblade')
@section('title', 'Reporte de Stock')
@section('contenido')
    <div class="container mb-3">
        <div class="row d-flex justify-content-center">
            <div class="col-auto">
                <h3 class="card shadow p-1">Reporte de Stock - {{ $tienda->NomTienda }}</h3>
            </div>
        </div>
    </div>
    <div class="container mb-3">
        <form action="/ReporteStock">
            <div class="row">
                <div class="col-auto">
                    <input type="text" class="form-control" name="codArticulo" id="codArticulo" placeholder="Buscar"
                        value="{{ $codArticulo }}" required>
                </div>
                <div class="col-auto">
                    <button class="btn card shadow">
                        <span class="material-icons">search</span>
                    </button>
                </div>
                <div class="col d-flex justify-content-end">
                    <a href="/ReporteStock" class="btn card shadow">
                        <span class="material-icons">refresh</span>
                    </a>
                </div>
            </div>
        </form>
    </div>
    <div class="container mb-3">
        <table class="table table-sm table-responsive table-striped shadow">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Articulo</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                @if ($stocks->count() == 0)
                    <tr>
                        <td colspan="3">No se Encontraron Coincidencias!</td>
                    </tr>
                @else
                    @foreach ($stocks as $stock)
                        <tr>
                            <td>{{ $stock->CodArticulo }}</td>
                            <td>
                                @if (!empty($stock->Articulo->NomArticulo))
                                {{ $stock->Articulo->NomArticulo }}
                                @endif
                            </td>
                            <td style="color: {!! $stock->StockArticulo == 0 ? 'red; font-weight:bold;' : '' !!}">{{ $stock->StockArticulo }}</td>
                        </tr>
                    @endforeach
                @endif
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
@endsection
