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
                        <input type="hidden" name="txtFiltro" value="{{ $txtFiltro }}">
                        <input type="hidden" name="optionsOnline" value="{{ $optionsOnline }}">
                        <input type="hidden" name="agrupado" value="{{ $agrupado }}">
                        <input type="hidden" name="agrupadoArticulo" value="{{ $agrupadoArticulo }}">
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
                <!-- Controles de filtro principales -->
                <div class="col-auto">
                    <select class="form-select form-select-sm" name="idTienda" id="idTienda">
                        <option value="">Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option {!! $idTienda == $tienda->IdTienda ? 'selected' : '' !!} value="{{ $tienda->IdTienda }}">
                                {{ $tienda->NomTienda }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto">
                    <input class="form-control form-control-sm" style="width: 180px;" type="text" name="txtFiltro"
                        id="txtFiltro" value="{{ $txtFiltro }}" placeholder="Código/Artículo">
                </div>

                <div class="col-auto">
                    <input class="form-control form-control-sm" type="date" name="fecha1" id="fecha1"
                        value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
                </div>

                <div class="col-auto">
                    <input class="form-control form-control-sm" type="date" name="fecha2" id="fecha2"
                        value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                </div>

                <!-- Checkboxes como botones toggle compactos -->
                <div class="col-auto">
                    <div class="btn-group btn-group-sm" role="group">
                        <input type="checkbox" class="btn-check" value="on" id="agrupado" name="agrupado"
                            {{ $agrupado ? 'checked' : '' }}>
                        <label class="btn btn-outline-secondary" for="agrupado" title="Agrupado por fecha">
                            📅 Fecha
                        </label>

                        <input type="checkbox" class="btn-check" value="on" id="agrupadoArticulo"
                            name="agrupadoArticulo" {{ $agrupadoArticulo ? 'checked' : '' }}>
                        <label class="btn btn-outline-secondary" for="agrupadoArticulo" title="Agrupado por artículo">
                            📦 Artículo
                        </label>
                    </div>
                </div>

                @if (Auth::user()->IdTipoUsuario == 2)
                    <div class="col-auto">
                        <div class="btn-group btn-group-sm" role="group">
                            <input type="radio" class="btn-check" name="optionsOnline" id="danger-outlined" value="off"
                                {{ $optionsOnline == 'off' ? 'checked' : '' }}>
                            <label class="btn btn-outline-danger" for="danger-outlined" title="Modo offline">
                                @include('components.icons.cloud-slash')
                            </label>
                            <input type="radio" class="btn-check" name="optionsOnline" id="success-outlined"
                                value="on" {{ $optionsOnline == 'on' ? 'checked' : '' }}>
                            <label class="btn btn-outline-success" for="success-outlined" title="Modo online">
                                @include('components.icons.cloud-check')
                            </label>
                        </div>
                    </div>
                @endif

                <div class="col-auto">
                    <button class="btn btn-outline-dark btn-sm bg-dark text-white" title="Buscar">
                        @include('components.icons.search')
                    </button>
                </div>
            </form>

            <style>
                /* Opcional: Ajustar tamaño de controles en pantallas pequeñas */
                @media (max-width: 768px) {

                    .form-select-sm,
                    .form-control-sm {
                        font-size: 0.875rem;
                        padding: 0.25rem 0.5rem;
                    }

                    .btn-group-sm .btn {
                        padding: 0.25rem 0.5rem;
                        font-size: 0.875rem;
                    }
                }
            </style>

            <div class="content-table content-table-full" style="max-height: calc(65vh);">
                <table class="w-100">
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Ciudad</th>
                            <th>Tienda</th>
                            @if ($agrupado)
                                <th>Fecha</th>
                            @endif
                            <th>Grupo</th>
                            <th>Código</th>
                            <th>Articulo</th>
                            <th>Cantidad</th>
                            @if (!$agrupadoArticulo)
                                <th>Precio</th>
                            @endif
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
                                    @if ($agrupado)
                                        {{-- <td>{{ $tConcentrado->FechaVenta }}</td> --}}
                                        <td>{{ \Carbon\Carbon::parse($tConcentrado->FechaVenta)->format('d/m/Y') }}</td>
                                    @endif
                                    <td>{{ $tConcentrado->NomGrupo }}</td>
                                    <td>{{ $tConcentrado->CodArticulo }}</td>
                                    <td>{{ $tConcentrado->NomArticulo }}</td>
                                    <td>{{ number_format($tConcentrado->Peso, 3) }}</td>
                                    @if (!$agrupadoArticulo)
                                        <td>{{ number_format($tConcentrado->PrecioArticulo, 2) }}</td>
                                    @endif
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
                            @if ($agrupado)
                                <td></td>
                            @endif
                            <td><strong>{{ number_format($totalPeso, 3) }}</strong></td>
                            @if (!$agrupadoArticulo)
                                <td></td>
                            @endif
                            <td><strong>{{ number_format($totalIva, 2) }}</strong></td>
                            <td><strong>{{ number_format($totalImporte, 2) }}</strong></td>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
    </div>

@endsection
