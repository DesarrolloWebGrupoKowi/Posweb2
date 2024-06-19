@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Reporte de Ventas por Grupos')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', [
                    'titulo' => 'Reporte de Ventas por Grupo' . $tienda->NomTienda,
                ])
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex align-items-center justify-content-end gap-2 pb-2" action="/VentaPorGrupo">
                <div class="col-2">
                    <input type="date" class="form-control rounded" style="line-height: 18px" name="fecha1" required
                        value="{{ $fecha1 }}">
                </div>
                <div class="col-2">
                    <input type="date" class="form-control rounded" style="line-height: 18px" name="fecha2" required
                        value="{{ $fecha2 }}">
                </div>
                <div class="col-2">
                    <select class="form-select rounded" style="line-height: 18px" name="idGrupo" id="idGrupo">
                        @foreach ($grupos as $grupo)
                            <option {!! $idGrupo == $grupo->IdGrupo ? 'selected' : '' !!} value="{{ $grupo->IdGrupo }}">{{ $grupo->NomGrupo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group" style="width: auto">
                    <span class="input-group-text">
                        <input {!! $agrupado == 'on' ? 'checked' : '' !!} class="form-check-input rounded" style="line-height: 18px"
                            type="checkbox" name="agrupado" id="agrupado">
                    </span>
                    <span class="input-group-text card" style="line-height: 18px">Reporte Agrupado</span>
                </div>
                <button class="btn btn-dark-outline">
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
                        <th class="rounded-end">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $ventasPorGrupo, 'colspan' => 5])
                    @foreach ($ventasPorGrupo as $ventaPorGrupo)
                        <tr>
                            <td>{{ $ventaPorGrupo->CodArticulo }}</td>
                            <td>{{ $ventaPorGrupo->NomArticulo }}</td>
                            <td>{{ number_format($ventaPorGrupo->CantArticulo, 3) }}</td>
                            <td>{{ number_format($ventaPorGrupo->PrecioArticulo, 2) }}</td>
                            <td>{{ number_format($ventaPorGrupo->ImporteArticulo, 2) }}</td>
                        </tr>
                    @endforeach
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
