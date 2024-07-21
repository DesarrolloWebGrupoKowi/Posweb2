@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Historial mermas')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', [
                    'titulo' => 'Historial mermas',
                    'options' => [['name' => 'Captura de Mermas', 'value' => '/CapMermas']],
                ])
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-between">
                @include('components.number-paginate')
                {{-- @include('components.table-search') --}}
                <form id="search-form" class="d-flex align-items-center justify-content-end gap-2 pb-2" action="/ReporteMermas"
                    method="GET">
                    <div class="d-flex flex-column">
                        <input type="date" class="form-control rounded" style="line-height: 18px" name="fecha1"
                            value="{{ $fecha1 }}" autofocus>
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
            </div>

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Id</th>
                        <th>Folio</th>
                        <th>Código</th>
                        <th>Articulo</th>
                        <th>Tipo Merma</th>
                        <th>Fecha Captura</th>
                        <th>Cantidad</th>
                        <th>Comentario</th>
                        <th class="rounded-end"></th>
                    </tr>
                </thead>
                @php
                    $sumCantidad = 0;
                @endphp
                @foreach ($mermas as $merma)
                    {{-- <tbody> --}}
                    {{-- @if ($merma->Mermas->count() != 0) --}}
                    {{-- @foreach ($merma->Mermas as $mCaptura) --}}
                    <tr>
                        <td>{{ $merma->IdMerma }}</td>
                        <td>{{ $merma->FolioMerma }}</td>
                        <td>{{ $merma->CodArticulo }}</td>
                        <td>{{ $merma->NomArticulo }}</td>
                        <td>{{ $merma->NomTipoMerma }}</td>
                        <td>{{ $merma->FechaCaptura }} </td>
                        <td>{{ number_format($merma->CantArticulo, 3) }}</td>
                        <td class="puntitos" title="{{ $merma->Comentario }}">
                            {{ $merma->Comentario }}
                        </td>
                        <td>
                            @if ($merma->Subir == 1)
                                <span class="tags-green" title="En linea"> @include('components.icons.cloud-check') </span>
                            @else
                                <span class="tags-red" title="Fuera de linea"> @include('components.icons.cloud-slash') </span>
                            @endif
                        </td>
                    </tr>
                    {{-- @php
                                        $sumCantidad = $sumCantidad + $mCaptura->CantArticulo;
                                    @endphp
                                @endforeach --}}
                    {{-- @endif --}}
                    {{-- </tbody> --}}
                @endforeach
                {{-- <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="text-align: right; font-weight: bold;">Total:</td>
                            <td style="font-weight: bold;">{{ number_format($sumCantidad, 2) }}</td>
                        </tr>
                    </tfoot> --}}
            </table>
            @include('components.paginate', ['items' => $mermas])
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        document.addEventListener('submit', e => {
            if (e.target.matches('#search-form')) {
                const form = document.getElementById('search-form');
                const url = location.href;
                const queryString = window.location.search;
                const urlParams = new URLSearchParams(queryString);
                const entries = urlParams.entries();

                for (const entry of entries) {
                    if (entry[0] != 'agrupado' && entry[0] != 'fecha1' && entry[0] != 'fecha2') {
                        let input = document.createElement('input');
                        input.type = "hidden";
                        input.name = entry[0];
                        input.value = entry[1];
                        form.appendChild(input);
                    }
                }

                form.setAttribute('action', url);
                form.submit();
            }
        })
    </script>
@endsection
