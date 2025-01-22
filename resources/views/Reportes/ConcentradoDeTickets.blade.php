@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Concentrado de Tickets')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="gap-4 pt-4 container-fluid width-general d-flex flex-column">

        <div class="p-4 border-0 card" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Concentrado de Tickets'])
                <div>
                    <form action="/ExportReporteConcentradoDeTickets" method="GET">
                        <select class="d-none" name="idTienda">
                            <option value="">Seleccione Tienda</option>
                            @foreach ($tiendas as $tienda)
                                <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                                </option>
                            @endforeach
                        </select>
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
        <div class="p-4 border-0 card" style="border-radius: 10px">
            <!--CONTAINER FILTROS-->
            <form class="gap-2 pb-2 d-flex align-items-center justify-content-end flex-wrap"
                action="/ReporteConcentradoDeTickets" method="GET">
                <div class="col-auto">
                    <select class="rounded form-select" style="line-height: 18px" name="idTienda" id="idTienda">
                        <option value="">Seleccione Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <input class="rounded form-control" style="line-height: 18px" type="date" name="fecha1"
                        id="fecha1" value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}" autofocus>
                </div>
                <div class="col-auto">
                    <input class="rounded form-control" style="line-height: 18px" type="date" name="fecha2"
                        id="fecha2" value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-dark-outline" title="Buscar">
                        @include('components.icons.search')
                    </button>
                </div>
            </form>

            <div class="content-table content-table-full" style="max-height: calc(65vh);">
                <table class="w-100">
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Ciudad</th>
                            <th>Tienda</th>
                            <th>Fecha</th>
                            <th>Tickets</th>
                            <th class="rounded-end">Importe</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalImporte = 0;
                        @endphp

                        @if ($concentrado->count() == 0)
                            <tr>
                                <td colspan="9">No Hay Ventas en Rango de Fechas Seleccionadas!</td>
                            </tr>
                        @else
                            @foreach ($concentrado as $tConcentrado)
                                <tr>
                                    <td>{{ $tConcentrado->NomCiudad }}</td>
                                    <td>{{ $tConcentrado->NomTienda }}</td>
                                    <td>{{ \Carbon\Carbon::parse($tConcentrado->Fecha)->format('d/m/Y') }}</td>
                                    <td>{{ $tConcentrado->Tickets }}</td>
                                    <td>{{ number_format($tConcentrado->Importe, 2) }}</td>
                                </tr>

                                @php
                                    $totalImporte += $tConcentrado->Importe;
                                @endphp
                            @endforeach
                        @endif
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="4"><strong>Total:</strong></td>
                            <td><strong>{{ number_format($totalImporte, 2) }}</strong></td>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
    </div>

@endsection
