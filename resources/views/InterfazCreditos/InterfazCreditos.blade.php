@extends('plantillaBase.masterblade')
@section('title', 'Interfaz de Creditos')
@section('contenido')
    <div class="container card p-2 shadow mb-3">
        <div class="d-flex justify-content-center mb-3">
            <div class="col-auto">
                <h2>Interfaz de Créditos</h2>
            </div>
        </div>
        <form action="/InterfazCreditos" method="GET">
            <div class="row d-flex justify-content-center">
                <div class="col-auto">
                    <input type="date" class="form-control" name="fecha1" id="fecha1"
                        value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
                </div>
                <div class="col-auto">
                    <input type="date" class="form-control" name="fecha2" id="fecha2"
                        value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                </div>
                <div class="col-auto">
                    <select {!! !empty($chkNomina) ? 'disabled' : '' !!} class="form-select" name="tipoNomina" id="tipoNomina">
                        @foreach ($tiposNomina as $tipoNomina)
                            <option {!! $idTipoNomina == $tipoNomina->TipoNomina ? 'selected' : '' !!} value="{{ $tipoNomina->TipoNomina }}">
                                {{ $tipoNomina->NomTipoNomina }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <div class="input-group">
                        <span class="input-group-text shadow">
                            <input {!! !empty($chkNomina) ? 'checked' : '' !!} class="form-check-input mt-0" type="checkbox" name="chkNomina"
                                id="chkNomina">
                        </span>
                        <input {!! empty($chkNomina) ? 'disabled' : '' !!} class="form-control" type="number" name="numNomina" id="numNomina"
                            value="{{ $numNomina }}" placeholder="# Nómina" required>
                    </div>
                </div>
                <div class="col-auto">
                    <button class="btn btn-warning">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="container">
        <div class="d-flex justify-content-center">
            @include('Alertas.Alertas')
        </div>
    </div>
    @if (!empty($fecha1) && !empty($fecha2))
        <div class="container card shadow p-1">
            <div class="d-flex justify-content-center">
                <h4>Créditos Empleado -
                    {{ empty($chkNomina) ? $nomTipoNomina : $empleado->Nombre . ' ' . $empleado->Apellidos }}</h4>
            </div>
            <div style="height: 65vh" class="table-responsive">
                <table class="table table-sm table-striped table-responsive">
                    <thead class="table-dark">
                        <tr>
                            <th>Ciudad</th>
                            <th>Tienda</th>
                            <th>Nómina</th>
                            <th>Empleado</th>
                            <th>Importe</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($creditos->count() == 0)
                            <tr>
                                <th colspan="5">No hay créditos pendientes por exportar!</th>
                            </tr>
                        @else
                            @foreach ($creditos as $credito)
                                <tr>
                                    <td>{{ $credito->NomCiudad }}</td>
                                    <td>{{ $credito->NomTienda }}</td>
                                    <td>{{ $credito->NumNomina }}</td>
                                    <td>{{ $credito->Nombre }} {{ $credito->Apellidos }}</td>
                                    <th>$ {{ number_format($credito->ImporteCredito, 2) }}</th>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <th></th>
                            <th></th>
                            <th style="text-align: center">Total: </th>
                            <th>$ {{ number_format($totalAdeudo, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endif
    @if ($creditos->count() > 0)
        <div class="container mt-2 mb-1">
            <div class="d-flex justify-content-center">
                <div class="col-auto">
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#ModalConfirmarInterfazCreditos">
                        <i class="fa fa-check"></i> Interfazar Créditos
                    </button>
                </div>
            </div>
        </div>
        @include('InterfazCreditos.ModalConfirmacion')
    @endif

    <script>
        const chkNomina = document.getElementById('chkNomina');
        const numNomina = document.getElementById('numNomina');
        const tipoNomina = document.getElementById('tipoNomina');
        chkNomina.addEventListener('click', function() {
            if (numNomina.disabled == true) {
                numNomina.disabled = false;
                tipoNomina.disabled = true;
            } else {
                //numNomina.value = '';
                numNomina.disabled = true;
                tipoNomina.disabled = false;
            }
        });

        document.getElementById('btnExportar').addEventListener('click', function() {
            document.getElementById('btnExportar').hidden = true;
            document.getElementById('btnCargandoDatos').hidden = false;
        });
    </script>
@endsection
