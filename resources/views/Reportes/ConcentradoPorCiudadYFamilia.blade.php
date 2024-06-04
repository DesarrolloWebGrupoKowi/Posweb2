@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Concentrado por Ciudad y Familia')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <!--TITULO-->
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Concentrado por Ciudad y Familia'])
            <div>
                <form action="/ExportReporteConcentradoPorCiudadYFamilia" method="GET">
                    <input type="hidden" name="fecha1" value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
                    <input type="hidden" name="fecha2" value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                    <button type="submit" class="input-group-text text-decoration-none btn-excel">
                        <i class="fa fa-file-excel-o pe-2"></i> Exportar
                    </button>
                </form>
            </div>
        </div>

        <!--CONTAINER FILTROS-->
        <form class="d-flex align-items-center justify-content-end flex-wrap pb-2 gap-2"
            action="/ReporteConcentradoPorCiudadYFamilia" method="GET">
            <div class="col-auto">
                <input class="form-control" type="date" name="fecha1" id="fecha1"
                    value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
            </div>
            <div class="col-auto">
                <input class="form-control" type="date" name="fecha2" id="fech2"
                    value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
            </div>
            <div class="col-auto">
                <button class="btn card">
                    <span class="material-icons">search</span>
                </button>
            </div>
        </form>

        <div class="row">
            @foreach ($totales as $key => $total)
                @if ($key != 'TOTAL')
                    <div class="col pb-3">
                        <div class="card p-3" style="border-radius: 20px;">
                            <div class="d-flex flex-row gap-2 justify-content-between">
                                <span class="text-secondary" style="font-size: 12px">{{ $key }} </span>
                                <b class="{{ number_format($total, 2) == 0 ? 'eliminar' : 'send' }}">
                                    ${{ number_format($total, 2) }}
                                </b>
                            </div>
                            <div class="d-flex flex-row justify-content-between">
                                <span class="text-secondary" style="font-size: 12px">KILOS: </span>
                                <b class="{{ number_format($kilos[$key], 2) == 0 ? 'eliminar' : 'send' }}"
                                    style="font-size: 12px"> {{ number_format($kilos[$key], 2) }}
                                </b>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!--CONCENTRADO DE VENTAS POR RANGO DE FECHAS-->
        <div class="content-table content-table-full card p-4" style="border-radius: 20px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Ciudad</th>
                        <th>Familia</th>
                        <th class="text-center">Kilos</th>
                        <th class="rounded-end text-center">Importe</th>
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
                                <td>{{ $tConcentrado->NomCiudad }}</td>
                                <td>{{ $tConcentrado->NomGrupo }}</td>
                                <td class="text-end">{{ number_format($tConcentrado->kilos, 2) }}</td>
                                <td class="text-end">${{ number_format($tConcentrado->importe, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" class="text-end fw-bold">Total:</td>
                            <td class="text-end">{{ number_format($kilos['TOTAL'], 2) }}</td>
                            <td class="text-end">${{ number_format($totales['TOTAL'], 2) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection
