@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Concentrado de Mermas')
@section('dashboardWidth', 'width-95')
@section('contenido')
    <div class="gap-4 pt-4 container-fluid width-95 d-flex flex-column">

        <div class="p-4 border-0 card" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Concentrado de Mermas'])
                <form action="/ReporteMermasAdminExcel" method="GET">
                    <input type="hidden" name="fecha1" value="{{ $fecha1 }}">
                    <input type="hidden" name="fecha2" value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                    <input type="hidden" name="idTienda" value="{{ $idTienda }}">
                    <button class="btn card" type="submit">
                        @include('components.icons.print')
                    </button>
                </form>
            </div>
        </div>

        <!--CONCENTRADO DE VENTAS POR RANGO DE FECHAS-->
        <div class="p-4 border-0 content-table content-table-full card" style="border-radius: 10px">
            <!--CONTAINER FILTROS-->
            <form class="flex-wrap gap-2 pb-2 d-flex align-items-center justify-content-end" action="/ReporteMermasAdmin"
                method="GET">
                <input type="hidden" class="idPagination" value="&idTienda={{ $idTienda }}">
                <input type="hidden" class="idPagination" value="&fecha1={{ $fecha1 }}">
                <input type="hidden" class="idPagination" value="&fecha2={{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                <div class="col-auto">
                    <select class="rounded form-select" style="line-height: 18px" name="idTienda" id="idTienda" autofocus>
                        <option value="">Seleccione Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <input class="rounded form-control" style="line-height: 18px" type="date" name="fecha1"
                        id="fecha1" value="{{ $fecha1 }}">
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
                        <th>Código</th>
                        <th>Articulo</th>
                        <th>Tienda</th>
                        <th>Captura</th>
                        <th>Merma</th>
                        <th class="text-center">Cantidad</th>
                        <th>Comentario</th>
                        <th>Interfaz</th>
                        <th class="rounded-end">Interfazado</th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $concentrado, 'colspan' => 10])
                    @foreach ($concentrado as $tConcentrado)
                        <tr>
                            {{-- <td>{{ $tConcentrado->IdMerma }}</td> --}}
                            <td>{{ $tConcentrado->FolioMerma }}</td>
                            <td>{{ $tConcentrado->CodArticulo }}</td>
                            <td>{{ $tConcentrado->NomArticulo }}</td>
                            <td>{{ $tConcentrado->NomTienda }}</td>
                            <td>{{ strftime('%d %B %Y, %H:%M', strtotime($tConcentrado->FechaCaptura)) }}</td>
                            <td>{{ $tConcentrado->NomTipoMerma }}</td>
                            <td style="text-align: right" class="fw-bold">
                                <p class="m-0 pe-3">{{ number_format($tConcentrado->CantArticulo, 3) }}</p>
                            </td>
                            <td class="puntitos" title="{{ $tConcentrado->Comentario }}">
                                {{ $tConcentrado->Comentario }}
                            </td>
                            <td>
                                {{ $tConcentrado->FechaInterfaz ? strftime('%d %B %Y, %H:%M', strtotime($tConcentrado->FechaInterfaz)) : '' }}
                            </td>
                            <td>
                                @if ($tConcentrado->FechaInterfaz)
                                    <i class="fa fa-check"></i>
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
