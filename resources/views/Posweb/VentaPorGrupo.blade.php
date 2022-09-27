@extends('plantillaBase.masterblade')
@section('title', 'Reporte de Ventas por Grupos')
@section('contenido')
    <div class="container mb-3">
        <h2 class="titulo card shadow">Reporte de Ventas por Grupo - {{ $tienda->NomTienda }}</h2>
    </div>
    <div class="container">
        <form action="/VentaPorGrupo">
            <div class="row mb-3">
                <div class="col-2">
                    <input type="date" class="form-control shadow" name="fecha1" required value="{{ $fecha1 }}">
                </div>
                <div class="col-2">
                    <input type="date" class="form-control shadow" name="fecha2" required value="{{ $fecha2 }}">
                </div>
                <div class="col-2">
                    <select class="form-select shadow" name="idGrupo" id="idGrupo">
                        @foreach ($grupos as $grupo)
                            <option {!! $idGrupo == $grupo->IdGrupo ? 'selected' : '' !!} value="{{ $grupo->IdGrupo }}">{{ $grupo->NomGrupo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <div class="input-group">
                        <span class="input-group-text shadow">
                            <input {!! $agrupado == 'on' ? 'checked' : '' !!} class="form-check-input" type="checkbox" name="agrupado" id="agrupado">
                        </span>
                        <span class="input-group-text card shadow">Reporte Agrupado</span>
                    </div>
                </div>
                <div class="col-1">
                    <button class="btn card shadow">
                        <span class="material-icons">search</span>                    
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="container">
        <table class="table table-sm table-responsive table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Peso</th>
                    <th>Precio</th>
                    <th>Importe</th>
                </tr>
            </thead>
            <tbody>
                @if ($ventasPorGrupo->count() == 0)
                    <tr>
                        <td colspan="5">No Hay Ventas!</td>
                    </tr>
                @else
                @foreach ($ventasPorGrupo as $ventaPorGrupo)
                    <tr>
                        <td>{{ $ventaPorGrupo->CodArticulo }}</td>
                        <td>{{ $ventaPorGrupo->NomArticulo }}</td>
                        <td>{{ number_format($ventaPorGrupo->CantArticulo, 3) }}</td>
                        <td>{{ number_format($ventaPorGrupo->PrecioArticulo, 2) }}</td>
                        <td>{{ number_format($ventaPorGrupo->ImporteArticulo, 2) }}</td>
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
                    <th>$ {{ number_format($totalImporte, 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection