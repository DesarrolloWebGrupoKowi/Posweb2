@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Concentrado de Mermas')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <!--TITULO-->
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Concentrado de Mermas'])
            <div>
                {{-- <form action="/ExportReporteConcentradoDeArticulos" method="GET">
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
                        <i class="fa fa-file-excel-o pe-2"></i> Exportar
                    </button>
                </form> --}}
            </div>
        </div>

        <!--CONTAINER FILTROS-->
        <form class="d-flex align-items-center justify-content-end flex-wrap pb-2 gap-2" action="/ReporteMermasAdmin"
            method="GET">
            <input type="hidden" class="idPagination" value="&idTienda={{ $idTienda }}">
            <input type="hidden" class="idPagination" value="&fecha1={{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
            <input type="hidden" class="idPagination" value="&fecha2={{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
            <div class="col-auto">
                <select class="form-select" name="idTienda" id="idTienda">
                    <option value="">Seleccione Tienda</option>
                    @foreach ($tiendas as $tienda)
                        <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <input class="form-control" type="date" name="fecha1" id="fecha1"
                    value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
            </div>
            <div class="col-auto">
                <input class="form-control" type="date" name="fecha2" id="fecha2"
                    value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
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
                        <th class="rounded-start">Código</th>
                        <th>Articulo</th>
                        <th>Captura</th>
                        <th>Merma</th>
                        <th class="text-center">Cantidad</th>
                        <th>Interfaz</th>
                        <th class="rounded-end">Interfazado</th>
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
                                {{-- <td>{{ $tConcentrado->IdMerma }}</td> --}}
                                <td>{{ $tConcentrado->CodArticulo }}</td>
                                <td>{{ $tConcentrado->NomArticulo }}</td>
                                <td>{{ strftime('%d %B %Y, %H:%M', strtotime($tConcentrado->FechaCaptura)) }}</td>
                                <td>{{ $tConcentrado->NomTipoMerma }}</td>
                                <td style="text-align: right" class="fw-bold">
                                    <p class="pe-3 m-0">{{ number_format($tConcentrado->CantArticulo, 2) }}</p>
                                </td>
                                <td>{{ strftime('%d %B %Y, %H:%M', strtotime($tConcentrado->FechaInterfaz)) }}</td>
                                <td>
                                    @if ($tConcentrado->FechaInterfaz)
                                        <i class="fa fa-check"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {!! $concentrado->links() !!}
    </div>
@endsection
