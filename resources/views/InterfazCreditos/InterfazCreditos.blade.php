@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Interfaz de Creditos')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Interfaz de Créditos'])
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        {{-- @if (!empty($fecha1) && !empty($fecha2)) --}}
        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex flex-wrap align-items-center justify-content-end gap-2 pb-2" id="formBuscarCreditos"
                action="/InterfazCreditos" method="GET">
                <div class="input-group" style="max-width: 150px">
                    <input type="date" class="form-control rounded" style="line-height: 18px" name="fecha1"
                        id="fecha1" value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
                </div>
                <div class="input-group" style="max-width: 150px">
                    <input type="date" class="form-control rounded" style="line-height: 18px" name="fecha2"
                        id="fecha2" value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                </div>
                <div class="input-group" style="max-width: 300px">
                    <select {!! !empty($chkNomina) ? 'disabled' : '' !!} class="form-select rounded" style="line-height: 18px" name="tipoNomina"
                        id="tipoNomina">
                        @foreach ($tiposNomina as $tipoNomina)
                            <option {!! $idTipoNomina == $tipoNomina->TipoNomina ? 'selected' : '' !!} value="{{ $tipoNomina->TipoNomina }}">
                                {{ $tipoNomina->NomTipoNomina }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group" style="max-width: 300px">
                    <span class="input-group-text">
                        <input {!! !empty($chkNomina) ? 'checked' : '' !!} class="form-check-input mt-0 rounded" style="line-height: 18px"
                            type="checkbox" name="chkNomina" id="chkNomina">
                    </span>
                    <input {!! empty($chkNomina) ? 'disabled' : '' !!} class="form-control rounded" style="line-height: 18px" type="number"
                        name="numNomina" id="numNomina" value="{{ $numNomina }}" placeholder="# Nómina" required>
                </div>
                <div class="col-auto">
                    {{-- <button id="btnBuscar" class="btn btn-warning">
                        <i class="fa fa-search"></i> Buscar
                    </button> --}}
                    <button id="btnBuscar" class="btn btn-dark-outline">
                        @include('components.icons.search')
                    </button>
                    <button id="btnBuscandoCreditos" hidden class="btn btn-warning" type="button">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Buscando...
                    </button>
                </div>
            </form>

            <div class="d-flex justify-content-between flex-wrap">
                <p class="text-uppercase fs-6" style="font-weight: 500">
                    Créditos Empleado - {{ empty($chkNomina) ? $nomTipoNomina : $empleado }}
                </p>
                <p class="fs-6" style="font-weight: 500"><u>Se encontraron ({{ count($creditos) }}) registros</u></p>
            </div>
            <div class="content-table content-table-full" style="height: 58vh">
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
                        @include('components.table-empty', ['items' => $creditos, 'colspan' => 6])
                        @foreach ($creditos as $credito)
                            <tr>
                                <td>{{ $credito->NomCiudad }}</td>
                                <td>{{ $credito->NomTienda }}</td>
                                <td>{{ $credito->NumNomina }}</td>
                                <td>{{ $credito->Nombre }} {{ $credito->Apellidos }}</td>
                                <th>$ {{ number_format($credito->ImporteCredito, 2) }}</th>
                                <th>
                                    @if ($credito->isSistemaNuevo == 1)
                                        <span class="text-success">@include('components.icons.chrome')</span> Sistema nuevo
                                    @else
                                        <span class="text-danger">@include('components.icons.edge')</span> Sistema viejo
                                    @endif
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                    @if (count($creditos) > 0)
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
                    @endif
                </table>
            </div>

            @if (!empty($creditos))
                <div class="container mt-2">
                    <div class="row d-flex justify-content-center">
                        <div class="col-auto">
                            <button class="btn btn-warning" data-bs-toggle="modal"
                                data-bs-target="#ModalConfirmarInterfazCreditos">
                                <i class="fa fa-check"></i> Interfazar Créditos
                            </button>
                        </div>
                    </div>
                </div>
                @include('InterfazCreditos.ModalConfirmacion')
            @endif
        </div>
        {{-- @endif --}}
    </div>


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
