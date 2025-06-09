@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Información de ventas')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="gap-4 pt-4 container-fluid width-general d-flex flex-column">

        <div class="p-4 border-0 card" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Información de ventas'])
            </div>
        </div>

        <!--CONCENTRADO DE VENTAS POR RANGO DE FECHAS-->
        <div class="p-4 border-0 card" style="border-radius: 10px">
            <!--CONTAINER FILTROS-->
            <form class="gap-2 pb-2 d-flex align-items-center justify-content-end flex-wrap" action="/InformacionVentas"
                method="GET">
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
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="on" id="flexCheckDefault" name="agrupar"
                            {{ $agrupar == 'on' ? 'checked' : '' }}>
                        <label class="form-check-label" for="flexCheckDefault">
                            Agrupar tiendas
                        </label>
                    </div>
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
                            {{-- <th class="rounded-start">Ciudad</th>
                            <th>Tienda</th> --}}
                            {{-- <th class="rounded-start">Tienda</th>
                            <th>Fecha</th> --}}
                            <th class="rounded-start">Fecha</th>
                            <th class="text-center">Tickets</th>
                            <th class="text-center">Kilos</th>
                            <th class="text-center rounded-end">Importe</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalTickets = 0;
                            $totalKilos = 0;
                            $totalImporte = 0;
                        @endphp

                        @if ($concentrado->count() == 0)
                            <tr>
                                <td colspan="9">No Hay Ventas en Rango de Fechas Seleccionadas!</td>
                            </tr>
                        @else
                            @php
                                $nombreTienda = '';
                            @endphp
                            @foreach ($concentrado as $tConcentrado)
                                @php
                                    $NomTienda = isset($tConcentrado->NomTienda) ? $tConcentrado->NomTienda : '';
                                @endphp
                                @if ($nombreTienda != $NomTienda)
                                    <tr>
                                        <td colspan="4" style="background: #e8ecf0">{{ $tConcentrado->NomTienda }} </td>
                                    </tr>
                                    @php
                                        $nombreTienda = $tConcentrado->NomTienda;
                                    @endphp
                                @endif
                                <tr>
                                    <td class="w-50">{{ \Carbon\Carbon::parse($tConcentrado->Fecha)->format('d/m/Y') }}
                                    </td>
                                    <td class="text-end">{{ $tConcentrado->Tickets }}</td>
                                    <td class="text-end">{{ number_format($tConcentrado->cantidad, 2) }}</td>
                                    <td class="text-end">{{ number_format($tConcentrado->Importe, 2) }}</td>
                                </tr>

                                @php
                                    $totalTickets += $tConcentrado->Tickets;
                                    $totalKilos += $tConcentrado->cantidad;
                                    $totalImporte += $tConcentrado->Importe;
                                @endphp
                            @endforeach
                        @endif
                    </tbody>

                    <tfoot>
                        <tr>
                            <td><strong>Total:</strong></td>
                            <td class="text-end"><strong>{{ $totalTickets }}</strong></td>
                            <td class="text-end"><strong>{{ number_format($totalKilos, 2) }}</strong></td>
                            <td class="text-end"><strong>{{ number_format($totalImporte, 2) }}</strong></td>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
    </div>

@endsection
