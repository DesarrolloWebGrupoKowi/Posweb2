@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Reporte de Recepciones')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', [
                    'titulo' => 'Reporte de Recepciones',
                    'options' => [['name' => 'Recepción de producto', 'value' => '/RecepcionProducto']],
                ])
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex flex-wrap align-items-center justify-content-end gap-2 pb-2" action="/ReporteRecepciones">
                <div class="col-auto">
                    <input class="form-control rounded" style="line-height: 18px" type="date" name="fecha1"
                        id="fecha1" value="{{ $fecha1 }}">
                </div>
                <div class="col-auto">
                    <input class="form-control rounded" style="line-height: 18px" type="date" name="fecha2"
                        id="fecha2" value="{{ $fecha2 }}">
                </div>
                <div class="col-auto">
                    <div class="input-group">
                        <span class="input-group-text">
                            <input {!! $chkReferencia == 'on' ? 'checked' : '' !!} class="form-check-input mt-0 rounded" style="line-height: 18px"
                                type="checkbox" name="chkReferencia" id="chkReferencia">
                        </span>
                        <span class="input-group-text card" style="line-height: 18px">Referencia</span>
                        <input {!! $chkReferencia == 'on' ? '' : 'disabled' !!} class="form-control rounded" style="line-height: 18px" type="text"
                            name="referencia" id="referencia" value="{{ $referencia }}">
                    </div>
                </div>
                <div class="col-auto">
                    <button class="btn card">
                        @include('components.icons.search')
                    </button>
                </div>
            </form>

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Folio</th>
                        <th>Referencia</th>
                        <th>Fecha Llegada</th>
                        <th>Fecha Recepción</th>
                        <th>Origen</th>
                        <th>Status</th>
                        <th class="rounded-end">Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $recepciones, 'colspan' => 7])
                    @foreach ($recepciones as $recepcion)
                        <tr>
                            <td>{{ $recepcion->IdRecepcionLocal }}</td>
                            <td>{{ $recepcion->PackingList }}</td>
                            <td>{{ strftime('%d %B %Y, %H:%M', strtotime($recepcion->FechaLlegada)) }}</td>
                            @if (empty($recepcion->FechaRecepcion))
                                <td></td>
                            @else
                                <td>{{ strftime('%d %B %Y, %H:%M', strtotime($recepcion->FechaRecepcion)) }}</td>
                            @endif
                            <td>
                                {{ $recepcion->NomTienda }}
                            </td>
                            <td>
                                @if ($recepcion->IdStatusRecepcion == 2)
                                    <span class="tags-green">{{ $recepcion->StatusRecepcion->NomStatusRecepcion }}</span>
                                @elseif ($recepcion->IdStatusRecepcion == 3)
                                    <span class="tags-red">{{ $recepcion->StatusRecepcion->NomStatusRecepcion }}</span>
                                @else
                                    <span class="tags-yellow">{{ $recepcion->StatusRecepcion->NomStatusRecepcion }}</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn-table" data-bs-toggle="modal"
                                    data-bs-target="#ModalDetalleRecepcion{{ $recepcion->IdCapRecepcion }}"
                                    title="Detalle de recepción">
                                    @include('components.icons.list')
                                </button>
                                @if ($recepcion->IdStatusRecepcion == 3)
                                    <button class="btn-table btn-table-delete" data-bs-toggle="modal"
                                        data-bs-target="#ModalMotivoCancelacion{{ $recepcion->IdCapRecepcion }}"
                                        title="Motivo de cancelación">
                                        @include('components.icons.text-file-x')
                                    </button>
                                @endif
                                @include('Recepcion.ModalDetalleRecepcion')
                                @include('Recepcion.ModalMotivoCancelacion')
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('components.paginate', ['items' => $recepciones])
        </div>
    </div>


    <script>
        const chkReferencia = document.getElementById('chkReferencia');
        const referencia = document.getElementById('referencia');

        chkReferencia.addEventListener('click', (e) => {
            if (referencia.disabled == true) {
                referencia.disabled = false;
            } else {
                referencia.disabled = true;
                referencia.value = '';
            }
        });
    </script>
@endsection
