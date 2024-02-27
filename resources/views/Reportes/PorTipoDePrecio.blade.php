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
                    <input type="hidden" name="fecha1" value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
                    <input type="hidden" name="fecha2" value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
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
            <div class="col-12 col-md-6 col-lg-3 pb-4">
                <div class="card p-4 d-flex flex-row justify-content-between" style="border-radius: 20px;">
                    <span class="text-secondary">DETALLE: </span>
                    <b class="{{ number_format($totales['DETALLE'], 2) == 0 ? 'eliminar' : 'send' }}"
                        style="font-size: 16px">
                        ${{ number_format($totales['DETALLE'], 2) }}
                    </b>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 pb-4">
                <div class="card p-4 d-flex flex-row justify-content-between" style="border-radius: 20px;">
                    <span class="text-secondary">MENUDEO: </span>
                    <b class="{{ number_format($totales['MENUDEO'], 2) == 0 ? 'eliminar' : 'send' }}"
                        style="font-size: 16px">
                        ${{ number_format($totales['MENUDEO'], 2) }}
                    </b>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 pb-4">
                <div class="card p-4 d-flex flex-row justify-content-between" style="border-radius: 20px;">
                    <span class="text-secondary">MINORISTA: </span>
                    <b class="{{ number_format($totales['MINORISTA'], 2) == 0 ? 'eliminar' : 'send' }}"
                        style="font-size: 16px">
                        ${{ number_format($totales['MINORISTA'], 2) }}
                    </b>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 pb-4">
                <div class="card p-4 d-flex flex-row justify-content-between" style="border-radius: 20px;">
                    <span class="text-secondary">EMPYSOC: </span>
                    <b class="{{ number_format($totales['EMPYSOC'], 2) == 0 ? 'eliminar' : 'send' }}"
                        style="font-size: 16px">
                        ${{ number_format($totales['EMPYSOC'], 2) }}
                    </b>
                </div>
            </div>
        </div>

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
                                <td class="text-end">{{ number_format($tConcentrado->kilos, 2) }}</td>
                                <td class="text-end">${{ number_format($tConcentrado->importe, 2) }}</td>
                                <td class="text-end">{{ number_format($tConcentrado->tickets, 0) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total:</td>
                            <td class="text-end">${{ number_format($totales['TOTAL'], 2) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection
