@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Reporte de Ventas por Grupos')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Reporte de Ventas por Grupo' . $tienda->NomTienda])
        </div>
        <form class="d-flex align-items-center justify-content-end pb-4 gap-2" action="/VentaPorGrupo">
            <div class="col-2">
                <input type="date" class="form-control" name="fecha1" required value="{{ $fecha1 }}">
            </div>
            <div class="col-2">
                <input type="date" class="form-control" name="fecha2" required value="{{ $fecha2 }}">
            </div>
            <div class="col-2">
                <select class="form-select" name="idGrupo" id="idGrupo">
                    @foreach ($grupos as $grupo)
                        <option {!! $idGrupo == $grupo->IdGrupo ? 'selected' : '' !!} value="{{ $grupo->IdGrupo }}">{{ $grupo->NomGrupo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-group" style="width: auto">
                <span class="input-group-text">
                    <input {!! $agrupado == 'on' ? 'checked' : '' !!} class="form-check-input" type="checkbox" name="agrupado" id="agrupado">
                </span>
                <span class="input-group-text card">Reporte Agrupado</span>
            </div>
            <button class="btn btn-dark-outline">
                <span class="material-icons">search</span>
            </button>
        </form>

        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Código</th>
                        <th>Nombre</th>
                        <th>Peso</th>
                        <th>Precio</th>
                        <th class="rounded-end">Importe</th>
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
    </div>
@endsection
