@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Ventas por tipo de Precio')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <!--TITULO-->
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Ventas por tipo de Precio'])
            <div>
                <form action="/ExportReportePorTipoDePrecio" method="GET">
                    <input type="hidden" name="fecha" value="{{ empty($fecha) ? date('Y-m-d') : $fecha }}">
                    <button type="submit" class="input-group-text text-decoration-none btn-excel">
                        <i class="fa fa-file-excel-o pe-2"></i> Exportar
                    </button>
                </form>
            </div>
        </div>

        <!--CONTAINER FILTROS-->
        <form class="d-flex align-items-center justify-content-end flex-wrap pb-2 gap-2" action="/ReportePorTipoDePrecio"
            method="GET">
            <div class="col-auto">
                <input class="form-control" type="date" name="fecha" id="fecha"
                    value="{{ empty($fecha) ? date('Y-m-d') : $fecha }}">
            </div>
            <div class="col-auto">
                <button class="btn card">
                    <span class="material-icons">search</span>
                </button>
            </div>
        </form>

        <!--CONCENTRADO DE VENTAS POR RANGO DE FECHAS-->
        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Tienda</th>
                        <th>Tipo precio</th>
                        <th class="text-center">Kilos</th>
                        <th class="text-center">Importe</th>
                        <th class="rounded-end text-center">Clientes</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($concentrado->count() == 0)
                        <tr>
                            <td colspan="6">No Hay Ventas en Rango de Fechas Seleccionadas!</td>
                        </tr>
                    @else
                        @foreach ($concentrado as $tConcentrado)
                            <tr>
                                <td>{{ $tConcentrado->NomTienda }}</td>
                                <td>{{ $tConcentrado->NomListaPrecio }}</td>
                                <td class="text-end">{{ $tConcentrado->kilos }}</td>
                                <td class="text-end">${{ number_format($tConcentrado->importe, 2) }}</td>
                                <td class="text-end">{{ $tConcentrado->tickets }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection
