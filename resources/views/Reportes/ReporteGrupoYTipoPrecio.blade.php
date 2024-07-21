@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Concentrado por Grupo y Tipo de Precio')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Concentrado por Grupo y Tipo de Precio'])
                <div>
                    <form action="/ExportReporteGrupoYTipoPrecio" method="GET">
                        <input type="hidden" name="fecha1" value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
                        <input type="hidden" name="fecha2" value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                        <button type="submit" class="input-group-text text-decoration-none btn-excel">
                            Exportar @include('components.icons.excel')
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!--CONCENTRADO DE VENTAS POR RANGO DE FECHAS-->
        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <!--CONTAINER FILTROS-->
            <form class="d-flex align-items-center justify-content-end gap-2 pb-2" action="/ReporteGrupoYTipoPrecio"
                method="GET">
                <div class="col-auto">
                    <input class="form-control rounded" style="line-height: 18px" type="date" name="fecha1"
                        id="fecha1" value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}" autofocus>
                </div>
                <div class="col-auto">
                    <input class="form-control rounded" style="line-height: 18px" type="date" name="fecha2"
                        id="fech2" value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                </div>
                <button class="btn btn-dark-outline" title="Buscar">
                    @include('components.icons.search')
                </button>
            </form>
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Tienda</th>
                        <th>Grupo</th>
                        <th>Tipo Precio</th>
                        <th class="text-center">Cantidad</th>
                        <th class="rounded-end text-center">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($concentrado->count() == 0)
                        <tr>
                            <td colspan="5">No Hay Ventas en Rango de Fechas Seleccionadas!</td>
                        </tr>
                    @else
                        @php
                            $importes = 0;
                            $kilos = 0;
                        @endphp
                        @foreach ($concentrado as $tConcentrado)
                            <tr>
                                <td>{{ $tConcentrado->NomTienda }}</td>
                                <td>{{ $tConcentrado->NomGrupo }}</td>
                                <td>{{ $tConcentrado->NomListaPrecio }}</td>
                                <td class="text-end">{{ number_format($tConcentrado->kilos, 2) }}</td>
                                <td class="text-end">${{ number_format($tConcentrado->importe, 2) }}</td>
                                @php
                                    $kilos += $tConcentrado->kilos;
                                    $importes += $tConcentrado->importe;
                                @endphp
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total:</td>
                            <td class="text-end">{{ number_format($kilos, 2) }}</td>
                            <td class="text-end">${{ number_format($importes, 2) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection
