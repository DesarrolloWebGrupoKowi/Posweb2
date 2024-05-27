@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Dinero Electronico')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Dinero Electronico'])
            @if (!empty($fecha1) && !empty($fecha2))
                <div>
                    <form action="/ExportReporteDineroElectronido" method="GET">
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
                    </form>
                </div>
            @endif
        </div>
        <form class="d-flex align-items-center justify-content-end pb-4 gap-2" action="/ReporteDineroElectronido">
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
                <input type="date" class="form-control" name="fecha1" id="fecha1"
                    value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
            </div>
            <div class="col-auto">
                <input type="date" class="form-control" name="fecha2" id="fecha2"
                    value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-dark-outline">
                    <span class="material-icons">search</span>
                </button>
            </div>
        </form>
        @if (!empty($fecha1) && !empty($fecha2))
            <div class="content-table content-table-full card p-4" style="border-radius: 20px">
                <div class="mb-2 d-flex justify-content-end">
                    <span class="card bg-dark text-white p-1">{{ strftime('%d %B %Y', strtotime($fecha1)) }} -
                        {{ strftime('%d %B %Y', strtotime($fecha2)) }}</span>
                </div>
                <table>
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Tienda</th>
                            <th>Día</th>
                            <th class="text-center">Semanal Crédito</th>
                            <th class="text-center">Semanal Contado</th>
                            <th class="text-center">Quincenal Crédito</th>
                            <th class="text-center">Quincenal Contado</th>
                            <th class="rounded-end">Contado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($concentrado->count() == 0)
                            <tr>
                                <th colspan="7"><i class="fa fa-exclamation-triangle"></i> No Se Encontraron Registros!
                                </th>
                            </tr>
                        @else
                            @foreach ($concentrado as $item)
                                <tr>
                                    <td>{{ $item->NomTienda }}</td>
                                    <td>{{ $item->Fecha }}</td>
                                    <td class="text-end">${{ number_format($item->semanal_creadito, 2) }}</td>
                                    <td class="text-end">${{ number_format($item->semanal_contado, 2) }}</td>
                                    <td class="text-end">${{ number_format($item->quincenal_creadito, 2) }}</td>
                                    <td class="text-end">${{ number_format($item->quincenal_contado, 2) }}</td>
                                    <td class="text-end">${{ number_format($item->contado, 2) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
