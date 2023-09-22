@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Concentrado de Adeudos')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid pt-4 width-general">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Concentrado de Adeudos'])
            @if (!empty($fecha1) && !empty($fecha2))
                <div class="col-auto">
                    <span class="card bg-dark text-white p-1">{{ strftime('%d %B %Y', strtotime($fecha1)) }} -
                        {{ strftime('%d %B %Y', strtotime($fecha2)) }}</span>
                </div>
            @endif
        </div>
        <form class="d-flex align-items-center justify-content-end pb-4 gap-2" action="/ConcentradoAdeudos">
            <div class="col-auto">
                <input type="date" class="form-control" name="fecha1" id="fecha1"
                    value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
            </div>
            <div class="col-auto">
                <input type="date" class="form-control" name="fecha2" id="fecha2"
                    value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
            </div>
            <div class="col-auto">
                <select {!! $chkNomina == 'on' ? 'disabled' : '' !!} class="form-select" name="tipoNomina" id="tipoNomina">
                    @foreach ($tiposNomina as $tipoNomina)
                        <option {!! $tipoNomina->TipoNomina == $idTipoNomina ? 'selected' : '' !!} value="{{ $tipoNomina->TipoNomina }}">
                            {{ $tipoNomina->NomTipoNomina }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <span class="input-group-text card">Buscar Empleado</span>
                    <span class="input-group-text">
                        <input {!! !empty($chkNomina) ? 'checked' : '' !!} class="form-check-input mt-0" type="checkbox" name="chkNomina"
                            id="chkNomina">
                    </span>
                    <input {!! empty($chkNomina) ? 'disabled' : 'enabled' !!} class="form-control" type="number" name="numNomina" id="numNomina"
                        placeholder="# Nómina" value="{{ $numNomina }}" required>
                </div>
            </div>
            <div class="col-auto">
                <button class="btn btn-dark-outline">
                    <span class="material-icons">search</span>
                </button>
            </div>
        </form>
        @if (!empty($fecha1) && !empty($fecha2))
            <div class="content-table content-table-full card p-4" style="border-radius: 20px">
                <table>
                    <thead class="table-head">
                        <tr>
                            <th class="rounded-start">Nómina</th>
                            <th>Empleado</th>
                            <th>Tienda</th>
                            <th class="rounded-end">Adeudo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($concentradoAdeudos->count() == 0)
                            <tr>
                                <th colspan="4"><i class="fa fa-exclamation-triangle"></i> No Se Encontraron Registros!
                                </th>
                            </tr>
                        @else
                            @foreach ($concentradoAdeudos as $adeudo)
                                <tr>
                                    <td>{{ $adeudo->NumNomina }}</td>
                                    <td>{{ $adeudo->Nombre }} {{ $adeudo->Apellidos }}</td>
                                    <td>{{ $adeudo->NomTienda }}</td>
                                    <td>$ {{ number_format($adeudo->ImporteCredito, 2) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th style="text-align: center">Total: </th>
                            <th>$ {{ number_format($iTotalCredito, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>

    <script>
        const chkNomina = document.getElementById('chkNomina');
        const numNomina = document.getElementById('numNomina');
        const tipoNomina = document.getElementById('tipoNomina');

        chkNomina.addEventListener('click', (e) => {
            if (numNomina.disabled == false) {
                numNomina.value = '';
                numNomina.disabled = true;
                tipoNomina.disabled = false;
            } else {
                numNomina.disabled = false;
                tipoNomina.disabled = true;
            }
        });
    </script>
@endsection
