@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Historial mermas')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', [
                    'titulo' => 'Historial mermas',
                    'options' => [['name' => 'Mermas', 'value' => '/CapMermas']],
                ])
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">

            <form class="d-flex align-items-center justify-content-end gap-2 pb-2" action="/ReporteMermas" method="GET">
                <div class="d-flex flex-column">
                    <input type="date" class="form-control rounded" style="line-height: 18px" name="fecha1" required
                        value="{{ $fecha1 }}">
                </div>
                <div class="d-flex flex-column">
                    <input type="date" class="form-control rounded" style="line-height: 18px" name="fecha2" required
                        value="{{ $fecha2 }}">
                </div>
                <div class="d-flex flex-column">
                    <div class="input-group rounded" style="line-height: 18px">
                        <span class="input-group-text">
                            <input class="form-check-input" type="checkbox" name="agrupado" id="agrupado"
                                {{ $agrupadoDia ? 'checked' : '' }}>
                        </span>
                        <span class="input-group-text card" style="line-height: 18px">Reporte Agrupado por dia</span>
                    </div>
                </div>
                <div class="col-auto">
                    <button class="btn btn-dark-outline">
                        @include('components.icons.search')
                    </button>
                </div>
            </form>

            @if (!empty($fecha) || !empty($fecha2))
                <table>
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Código</th>
                            <th>Articulo</th>
                            <th>Tipo Merma</th>
                            <th>Fecha Captura</th>
                            <th class="rounded-end">Cantidad</th>
                        </tr>
                    </thead>
                    @php
                        $sumCantidad = 0;
                    @endphp
                    @foreach ($mermas as $merma)
                        <tbody>
                            @if ($merma->Mermas->count() != 0)
                                @foreach ($merma->Mermas as $mCaptura)
                                    <tr>
                                        <td>{{ $mCaptura->CodArticulo }}</td>
                                        <td>{{ $mCaptura->NomArticulo }}</td>
                                        <td>{{ $merma->NomTipoMerma }}</td>
                                        <td> {{ $mCaptura->FechaCaptura }} </td>
                                        <td>{{ number_format($mCaptura->CantArticulo, 2) }}</td>
                                    </tr>
                                    @php
                                        $sumCantidad = $sumCantidad + $mCaptura->CantArticulo;
                                    @endphp
                                @endforeach
                            @endif
                        </tbody>
                    @endforeach
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="text-align: right; font-weight: bold;">Total:</td>
                            <td style="font-weight: bold;">{{ number_format($sumCantidad, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            @endif
        </div>
    </div>

@endsection
