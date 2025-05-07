@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Concentrado de Articulos')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="gap-4 pt-4 container-fluid width-general d-flex flex-column">

        <div class="p-4 border-0 card" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Concentrado de Articulos'])
                <div>
                    <form action="/ExportReporteConcentradoDeArticulos" method="GET">
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
                action="/ReporteConcentradoDeArticulos" method="GET">
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
                    <input class="rounded form-control" style="line-height: 18px" type="text" name="txtFiltro"
                        id="txtFiltro" value="{{ $txtFiltro }}" placeholder="CODIGO O ARTICULO">
                </div>
                <div class="col-auto">
                    <input class="rounded form-control" style="line-height: 18px" type="date" name="fecha1"
                        id="fecha1" value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
                </div>
                <div class="col-auto">
                    <input class="rounded form-control" style="line-height: 18px" type="date" name="fecha2"
                        id="fecha2" value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                </div>
                @if (Auth::user()->IdTipoUsuario == 2)
                    <div class="col-auto">
                        <input type="radio" class="btn-check" name="optionsOnline" id="danger-outlined" autocomplete="off"
                            value="off" {{ $optionsOnline == 'off' ? 'checked' : '' }}>
                        <label class="btn btn-outline-danger" for="danger-outlined">@include('components.icons.cloud-slash')</label>

                        <input type="radio" class="btn-check" name="optionsOnline" id="success-outlined"
                            autocomplete="off" value="on" {{ $optionsOnline == 'on' ? 'checked' : '' }}>
                        <label class="btn btn-outline-success" for="success-outlined">@include('components.icons.cloud-check')</label>

                    </div>
                @endif
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
                            <th>Grupo</th>
                            <th>Código</th>
                            <th>Articulo</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Iva</th>
                            <th class="rounded-end">Importe</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Inicializamos las variables para la suma
                            $totalPeso = 0;
                            $totalIva = 0;
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
                                    <td>{{ $tConcentrado->NomGrupo }}</td>
                                    <td>{{ $tConcentrado->CodArticulo }}</td>
                                    <td>{{ $tConcentrado->NomArticulo }}</td>
                                    <td>{{ number_format($tConcentrado->Peso, 3) }}</td>
                                    <td>{{ number_format($tConcentrado->PrecioArticulo, 2) }}</td>
                                    <td>{{ number_format($tConcentrado->Iva, 2) }}</td>
                                    <td>{{ number_format($tConcentrado->Importe, 2) }}</td>
                                </tr>

                                @php
                                    // Acumulamos los valores
                                    $totalPeso += $tConcentrado->Peso;
                                    $totalIva += $tConcentrado->Iva;
                                    $totalImporte += $tConcentrado->Importe;
                                @endphp
                            @endforeach
                        @endif
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="5"><strong>Total:</strong></td>
                            <td><strong>{{ number_format($totalPeso, 3) }}</strong></td>
                            <td></td>
                            <td><strong>{{ number_format($totalIva, 2) }}</strong></td>
                            <td><strong>{{ number_format($totalImporte, 2) }}</strong></td>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
    </div>

@endsection
