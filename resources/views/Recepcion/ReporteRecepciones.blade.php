@extends('plantillaBase.masterblade')
@section('title', 'Reporte de Recepciones')
<style>
    i:active{
        color: orange;
        transform: scale(1.5)
    }
</style>
@section('contenido')
    <div class="container mb-3">
        <div class="row d-flex justify-content-center">
            <div class="col-auto">
                <h2 class="card shadow p-1">Reporte de Recepciones</h2>
            </div>
        </div>
    </div>
    <div class="container mb-3">
        <form action="/ReporteRecepciones">
            <div class="row">
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
                        <span class="input-group-text shadow">
                            <input {!! $chkReferencia == 'on' ? 'checked' : '' !!} class="form-check-input mt-0" type="checkbox" name="chkReferencia"
                                id="chkReferencia">
                        </span>
                        <span class="input-group-text card shadow">Referencia</span>
                        <input {!! $chkReferencia == 'on' ? '' : 'disabled' !!} class="form-control" type="text" name="referencia" id="referencia"
                            value="{{ $referencia }}">
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
    @if (!empty($fecha1) && !empty($fecha2))
        <div class="container">
            <table class="table table-responsive table-striped table-sm shadow">
                <thead class="table-dark">
                    <tr>
                        <th>Referencia</th>
                        <th>Fecha Llegada</th>
                        <th>Fecha Recepción</th>
                        <th>Status</th>
                        <th>Detalle</th>
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
                                <th style="color: {!! $recepcion->IdStatusRecepcion == 3 ? 'red': '' !!}">{{ $recepcion->StatusRecepcion->NomStatusRecepcion }}</th>
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
