@extends('plantillaBase.masterblade')
@section('title', 'Reporte de Mermas')
@section('contenido')
    <div class="d-flex justify-content-center mb-3">
        <div class="col-auto">
            <h2 class="card shadow p-1">Reporte de Mermas</h2>
        </div>
    </div>
    <div class="container mb-3">
        <form action="/ReporteMermas" method="GET">
            <div class="row d-flex justify-content-center">
                <div class="col-auto">
                    <input type="date" class="form-control shadow" name="fecha1" required value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-auto">
                    <input type="date" class="form-control shadow" name="fecha2" required value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-auto">

                </div>
                <div class="col-auto">
                    <div class="input-group">
                        <span class="input-group-text shadow">
                            <input class="form-check-input" type="checkbox" name="agrupado" id="agrupado">
                        </span>
                        <span class="input-group-text card shadow">Reporte Agrupado por dia</span>
                    </div>
                </div>
                <div class="col-auto">
                    <button class="btn card shadow">
                        <span class="material-icons">search</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    @if (!empty($fecha) || !empty($fecha2))
        <div class="container-fluid">
            <hr>
        </div>
        <div class="container">
            <div class="row">
                @foreach ($mermas as $merma)
                    <div class="col-4">
                        <h5 class="bg-warning text-black shadow">{{ $merma->NomTipoMerma }}</h5>
                        <table class="table table-striped table-responsive shadow">
                            <thead class="table-dark">
                                <tr>
                                    <th>Código</th>
                                    <th>Articulo</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($merma->Mermas->count() == 0)
                                    <tr>
                                        <td style="color: red" colspan="3"><i class="fa fa-exclamation-circle"></i> No
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
                @endforeach
            </div>
        </div>
    @endif
@endsection
