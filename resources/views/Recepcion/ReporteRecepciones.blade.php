@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Reporte de Recepciones')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Reporte de Recepciones'])
        </div>
        <form class="d-flex align-items-center justify-content-end pb-4 gap-2" action="/ReporteRecepciones">
            <div class="col-auto">
                <input class="form-control" type="date" name="fecha1" id="fecha1"
                    value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
            </div>
            <div class="col-auto">
                <input class="form-control" type="date" name="fecha2" id="fecha2"
                    value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <span class="input-group-text">
                        <input {!! $chkReferencia == 'on' ? 'checked' : '' !!} class="form-check-input mt-0" type="checkbox" name="chkReferencia"
                            id="chkReferencia">
                    </span>
                    <span class="input-group-text card">Referencia</span>
                    <input {!! $chkReferencia == 'on' ? '' : 'disabled' !!} class="form-control" type="text" name="referencia" id="referencia"
                        value="{{ $referencia }}">
                </div>
            </div>
            <div class="col-auto">
                <button class="btn card">
                    <span class="material-icons">search</span>
                </button>
            </div>
        </form>

        @if (!empty($fecha1) && !empty($fecha2))
            <div class="content-table content-table-full card p-4" style="border-radius: 20px">
                <table>
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Referencia</th>
                            <th>Fecha Llegada</th>
                            <th>Fecha Recepción</th>
                            <th>Status</th>
                            <th class="rounded-end">Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($recepciones->count() == 0)
                            <tr>
                                <td colspan="5">No se Encontraron Recepciones!</td>
                            </tr>
                        @else
                            @foreach ($recepciones as $recepcion)
                                <tr>
                                    <td>{{ $recepcion->PackingList }}</td>
                                    <td>{{ strftime('%d %B %Y, %H:%M', strtotime($recepcion->FechaLlegada)) }}</td>
                                    @if (empty($recepcion->FechaRecepcion))
                                        <td></td>
                                    @else
                                        <td>{{ strftime('%d %B %Y, %H:%M', strtotime($recepcion->FechaRecepcion)) }}</td>
                                    @endif
                                    <th style="color: {!! $recepcion->IdStatusRecepcion == 3 ? 'red' : '' !!}">
                                        {{ $recepcion->StatusRecepcion->NomStatusRecepcion }}</th>
                                    <td>
                                        <i style="cursor: pointer; font-size: 24px" class="fa fa-folder-open"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalDetalleRecepcion{{ $recepcion->IdCapRecepcion }}"></i>
                                        @include('Recepcion.ModalDetalleRecepcion')
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        @endif
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
