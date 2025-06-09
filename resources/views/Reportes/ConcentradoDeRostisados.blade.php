@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Concentrado de Rostisados')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <div class="gap-4 pt-4 container-fluid width-95 d-flex flex-column">

        <div class="p-4 border-0 card" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Concentrado de Rostisados'])
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
        </div>

        <!--CONCENTRADO DE VENTAS POR RANGO DE FECHAS-->
        <div class="p-4 border-0 content-table content-table-full card" style="border-radius: 10px">
            <!--CONTAINER FILTROS-->
            <form class="flex-wrap gap-2 pb-2 d-flex align-items-center justify-content-end" action="/ReporteRosticeroAdmin"
                method="GET">
                <input type="hidden" class="idPagination" value="&idTienda={{ $idTienda }}">
                <input type="hidden" class="idPagination" value="&fecha1={{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
                <input type="hidden" class="idPagination" value="&fecha2={{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                <div class="col-auto">
                    <select class="rounded form-select" style="line-height: 18px" name="idTienda" id="idTienda" autofocus
                        required>
                        <option value="">Seleccione Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <input class="rounded form-control" style="line-height: 18px" type="date" name="fecha1"
                        id="fecha1" value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
                </div>
                <div class="col-auto">
                    <input class="rounded form-control" style="line-height: 18px" type="date" name="fecha2"
                        id="fecha2" value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-dark-outline">
                        @include('components.icons.search')
                    </button>
                </div>
            </form>

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Folio</th>
                        <th>Articulo Baja</th>
                        {{-- <th>Rostisado</th> --}}
                        {{-- <th>Materia baja</th> --}}
                        <th>Cantidad baja</th>
                        <th>Articulo alta</th>
                        {{-- <th>Materia alta</th> --}}
                        <th>Cantidad alta</th>
                        <th>Merma estandar</th>
                        <th>Merma real</th>
                        {{-- <th>Recalentado</th> --}}
                        <th>Fecha</th>
                        <th class="rounded-end">Interfazado</th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $concentrado, 'colspan' => 10])
                    @foreach ($concentrado as $tConcentrado)
                        <tr>
                            <td>{{ $tConcentrado->IdRosticero }}</td>
                            {{-- <td>{{ $tConcentrado->NomTienda }}</td> --}}
                            {{-- <td>{{ $tConcentrado->CodigoMatPrima }}</td> --}}
                            <td>{{ $tConcentrado->CodigoMatPrima }} - {{ $tConcentrado->ArticuloMatPrima }}</td>
                            <td>{{ $tConcentrado->CantidadMatPrima }}</td>
                            <td>{{ $tConcentrado->CodigoVenta }} - {{ $tConcentrado->ArticuloVenta }}</td>
                            {{-- <td>{{ $tConcentrado->CodigoVenta }}</td> --}}
                            <td>{{ $tConcentrado->CantidadVenta }}</td>
                            <td>{{ $tConcentrado->MermaStnd }}</td>
                            <td>
                                <span
                                    class="{{ $tConcentrado->MermaReal > $tConcentrado->MermaStnd ? 'text-danger' : '' }}">
                                    {{ $tConcentrado->MermaReal }}
                                </span>
                            </td>
                            {{-- <td>{{ $tConcentrado->CantidadVenta }}</td> --}}
                            <td>{{ strftime('%d %B %Y, %H:%M', strtotime($tConcentrado->Fecha)) }}</td>
                            <td>
                                @if ($tConcentrado->FechaInterfazAlta)
                                    <span class="tags-blue ms-2" title="Finalizado"> Alta</span>
                                @endif
                                @if ($tConcentrado->FechaInterfazBaja)
                                    <span class="tags-blue ms-2" title="Finalizado"> Baja </span>
                                @endif
                                @if ($tConcentrado->Status == 1)
                                    <span class="tags-red ms-2" title="Finalizado"> Cancelado </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $concentrado])
        </div>
    </div>
@endsection
