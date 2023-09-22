@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Reporte de Mermas')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Reporte de Mermas'])
            <div>
                <a href="/CapMermas" class="btn btn-sm btn-dark">
                    <i class="fa fa-plus-circle pe-1"></i> Captura de mermas
                </a>
            </div>
        </div>

        <form class="d-flex align-items-center justify-content-end pb-4 gap-2" action="/ReporteMermas" method="GET">
            <div class="col-auto">
                <input type="date" class="form-control" name="fecha1" required value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-auto">
                <input type="date" class="form-control" name="fecha2" required value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-auto">

            </div>
            <div class="col-auto">
                <div class="input-group">
                    <span class="input-group-text">
                        <input class="form-check-input" type="checkbox" name="agrupado" id="agrupado">
                    </span>
                    <span class="input-group-text card">Reporte Agrupado por dia</span>
                </div>
            </div>
            <div class="col-auto">
                <button class="btn btn-dark-outline">
                    <span class="material-icons">search</span>
                </button>
            </div>
        </form>

        @if (!empty($fecha) || !empty($fecha2))
            <div class="row">
                @foreach ($mermas as $merma)
                    <div class="col-4">

                        <div class="content-table content-table-full card p-4 mb-4" style="border-radius: 20px">
                            <h5 class="mb-2" style="font-size: 16px">{{ $merma->NomTipoMerma }}</h5>
                            <table>
                                <thead class="table-head">
                                    <tr>
                                        <th class="rounded-start">Código</th>
                                        <th>Articulo</th>
                                        <th class="rounded-end">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($merma->Mermas->count() == 0)
                                        <tr>
                                            <td style="color: red" colspan="3"><i class="fa fa-exclamation-circle"></i>
                                                No
                                                hay
                                                mermas</td>
                                        </tr>
                                    @else
                                        @php
                                            $sumCantidad = 0;
                                        @endphp
                                        @foreach ($merma->Mermas as $mCaptura)
                                            <tr>
                                                <td>{{ $mCaptura->CodArticulo }}</td>
                                                <td>{{ $mCaptura->NomArticulo }}</td>
                                                <td>{{ number_format($mCaptura->CantArticulo, 2) }}</td>
                                            </tr>
                                            @php
                                                $sumCantidad = $sumCantidad + $mCaptura->CantArticulo;
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                                @if ($merma->Mermas->count() > 0)
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th style="text-align: center">Total: </th>
                                            <th>{{ number_format($sumCantidad, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

@endsection
