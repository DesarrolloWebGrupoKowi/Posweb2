@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Interfaz de Creditos')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Interfaz de Créditos'])
        </div>
        <form class="d-flex align-items-center justify-content-end pb-4 gap-2 flex-wrap" id="formBuscarCreditos"
            action="/InterfazCreditos" method="GET">
            <div class="input-group" style="max-width: 150px">
                <input type="date" class="form-control" name="fecha1" id="fecha1"
                    value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
            </div>
            <div class="input-group" style="max-width: 150px">
                <input type="date" class="form-control" name="fecha2" id="fecha2"
                    value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
            </div>
            <div class="input-group" style="max-width: 300px">
                <select {!! !empty($chkNomina) ? 'disabled' : '' !!} class="form-select" name="tipoNomina" id="tipoNomina">
                    @foreach ($tiposNomina as $tipoNomina)
                        <option {!! $idTipoNomina == $tipoNomina->TipoNomina ? 'selected' : '' !!} value="{{ $tipoNomina->TipoNomina }}">
                            {{ $tipoNomina->NomTipoNomina }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-group" style="max-width: 300px">
                <span class="input-group-text">
                    <input {!! !empty($chkNomina) ? 'checked' : '' !!} class="form-check-input mt-0" type="checkbox" name="chkNomina"
                        id="chkNomina">
                </span>
                <input {!! empty($chkNomina) ? 'disabled' : '' !!} class="form-control" type="number" name="numNomina" id="numNomina"
                    value="{{ $numNomina }}" placeholder="# Nómina" required>
            </div>
            <div class="col-auto">
                <button id="btnBuscar" class="btn btn-warning">
                    <i class="fa fa-search"></i> Buscar
                </button>
                <button id="btnBuscandoCreditos" hidden class="btn btn-warning" type="button">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Buscando...
                </button>
            </div>
        </form>
        <div>
            @include('Alertas.Alertas')
        </div>

        @if (!empty($fecha1) && !empty($fecha2))
            <div class="content-table content-table-full card p-4" style="border-radius: 20px">
                <div class="d-flex justify-content-center">
                    <h4>Créditos Empleado -
                        {{ empty($chkNomina) ? $nomTipoNomina : $empleado }}
                    </h4>
                </div>
                <div class="d-flex justify-content-end">
                    <h6><u>Se encontraron ({{ count($creditos) }}) registros</u></h6>
                </div>
                <div class="content-table content-table-full mt-4" style="height: 58vh">
                    <table style="width: 100%;">
                        <thead class="table-head">
                            <tr>
                                <th class="rounded-start">Ciudad</th>
                                <th>Tienda</th>
                                <th>Nómina</th>
                                <th>Empleado</th>
                                <th>Importe</th>
                                <th class="rounded-end">Sistema</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (empty($creditos))
                                <tr>
                                    <th colspan="6">No hay créditos pendientes por exportar!</th>
                                </tr>
                            @else
                                @foreach ($creditos as $credito)
                                    <tr>
                                        <td>{{ $credito->NomCiudad }}</td>
                                        <td>{{ $credito->NomTienda }}</td>
                                        <td>{{ $credito->NumNomina }}</td>
                                        <td>{{ $credito->Nombre }} {{ $credito->Apellidos }}</td>
                                        <th>$ {{ number_format($credito->ImporteCredito, 2) }}</th>
                                        <th>
                                            @if ($credito->isSistemaNuevo == 1)
                                                <i style="color: green" class="fa fa-chrome"></i> Sistema nuevo
                                            @else
                                                <i style="color: red" class="fa fa-internet-explorer"></i> Sistema viejo
                                            @endif
                                        </th>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <th></th>
                                <th></th>
                                <th style="text-align: right; font-size: 23px">Total: </th>
                                <th style="font-size: 23px">${{ number_format($totalAdeudo, 2) }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endif
    </div>

    @if (!empty($creditos))
        <div class="container mt-2 mb-3">
            <div class="row d-flex justify-content-center">
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
                numNomina.disabled = true;
                tipoNomina.disabled = false;
            }
        });

        document.getElementById('btnExportar').addEventListener('click', function() {
            document.getElementById('btnExportar').hidden = true;
            document.getElementById('btnCargandoDatos').hidden = false;
        });

        document.getElementById('formBuscarCreditos').addEventListener('submit', function() {
            document.getElementById('btnBuscar').hidden = true;
            document.getElementById('btnBuscandoCreditos').hidden = false;
        });
    </script>
@endsection
