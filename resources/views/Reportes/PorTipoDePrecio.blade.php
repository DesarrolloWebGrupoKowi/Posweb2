@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Ventas por tipo de Precio')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Ventas por tipo de Precio'])
                <div class="d-flex gap-2">
                    <form class="d-flex align-items-center justify-content-end flex-wrap pb-0 gap-2"
                        action="/ReportePorTipoDePrecio" method="GET">
                        <div class="col-auto">
                            <input class="form-control rounded" style="line-height: 18px" type="date" name="fecha1"
                                id="fecha1" value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}" autofocus>
                        </div>
                        <div class="col-auto">
                            <input class="form-control rounded" style="line-height: 18px" type="date" name="fecha2"
                                id="fech2" value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-dark-outline" title="Buscar">
                                @include('components.icons.search')
                            </button>
                        </div>
                    </form>

                    <form action="/ExportReportePorTipoDePrecio" method="GET">
                        <input type="hidden" name="fecha1" value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
                        <input type="hidden" name="fecha2" value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                        <button class="btn btn-sm btn-excel" title="Buscar">
                            Exportar @include('components.icons.excel')
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!--CONTAINER FILTROS-->


        <div class="row">
            <div class="col-12 col-md-6 col-lg-3 pb-sm-4 pb-lg-0 pb-0">
                <div class="card p-4 border-0" style="border-radius: 10px;">
                    <div class="d-flex flex-row justify-content-between">
                        <span class="text-secondary">DETALLE: </span>
                        <b class="{{ number_format($totales['DETALLE'], 2) == 0 ? 'eliminar' : 'send' }}"
                            style="font-size: 16px">
                            ${{ number_format($totales['DETALLE'], 2) }}
                        </b>
                    </div>
                    <div class="d-flex flex-row justify-content-between">
                        <span class="text-secondary">CLIENTES: </span>
                        <b class="{{ number_format($clientes['DETALLE'], 2) == 0 ? 'eliminar' : 'send' }}"
                            style="font-size: 16px"> {{ $clientes['DETALLE'] }}
                        </b>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 pb-sm-4 pb-lg-0 pb-0">
                <div class="card p-4 border-0" style="border-radius: 10px;">
                    <div class="d-flex flex-row justify-content-between">
                        <span class="text-secondary">MENUDEO: </span>
                        <b class="{{ number_format($totales['MENUDEO'], 2) == 0 ? 'eliminar' : 'send' }}"
                            style="font-size: 16px">
                            ${{ number_format($totales['MENUDEO'], 2) }}
                        </b>
                    </div>
                    <div class="d-flex flex-row justify-content-between">
                        <span class="text-secondary">CLIENTES: </span>
                        <b class="{{ number_format($clientes['MENUDEO'], 2) == 0 ? 'eliminar' : 'send' }}"
                            style="font-size: 16px"> {{ $clientes['MENUDEO'] }}
                        </b>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 pb-sm-4 pb-md-0 pb-0">
                <div class="card p-4 border-0" style="border-radius: 10px;">
                    <div class="d-flex flex-row justify-content-between">
                        <span class="text-secondary">MINORISTA: </span>
                        <b class="{{ number_format($totales['MINORISTA'], 2) == 0 ? 'eliminar' : 'send' }}"
                            style="font-size: 16px">
                            ${{ number_format($totales['MINORISTA'], 2) }}
                        </b>
                    </div>
                    <div class="d-flex flex-row justify-content-between">
                        <span class="text-secondary">CLIENTES: </span>
                        <b class="{{ number_format($clientes['MINORISTA'], 2) == 0 ? 'eliminar' : 'send' }}"
                            style="font-size: 16px"> {{ $clientes['MINORISTA'] }}
                        </b>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 pb-0">
                <div class="card p-4 border-0" style="border-radius: 10px;">
                    <div class="d-flex flex-row justify-content-between">
                        <span class="text-secondary">EMPYSOC: </span>
                        <b class="{{ number_format($totales['EMPYSOC'], 2) == 0 ? 'eliminar' : 'send' }}"
                            style="font-size: 16px">
                            ${{ number_format($totales['EMPYSOC'], 2) }}
                        </b>
                    </div>
                    <div class="d-flex flex-row justify-content-between">
                        <span class="text-secondary">CLIENTES: </span>
                        <b class="{{ number_format($clientes['EMPYSOC'], 2) == 0 ? 'eliminar' : 'send' }}"
                            style="font-size: 16px"> {{ $clientes['EMPYSOC'] }}
                        </b>
                    </div>
                </div>
            </div>
        </div>

        <!--CONCENTRADO DE VENTAS POR RANGO DE FECHAS-->
        <div class="content-table content-table-flex-none content-table-full card border-0 p-4" style="border-radius: 10px">
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
                            <td class="text-end">{{ $clientes['TOTAL'] }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection
