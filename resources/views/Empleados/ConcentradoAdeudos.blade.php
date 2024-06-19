@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Concentrado de Adeudos')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Concentrado de Adeudos'])
                @if (!empty($fecha1) && !empty($fecha2))
                    <div class="col-auto">
                        <span class="card bg-dark text-white p-1">{{ strftime('%d %B %Y', strtotime($fecha1)) }} -
                            {{ strftime('%d %B %Y', strtotime($fecha2)) }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <form class="d-flex flex-wrap align-items-center justify-content-end gap-2 pb-2" action="/ConcentradoAdeudos">
                <div class="col-auto">
                    <input type="date" class="form-control rounded" style="line-height: 18px" name="fecha1"
                        id="fecha1" value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
                </div>
                <div class="col-auto">
                    <input type="date" class="form-control rounded" style="line-height: 18px" name="fecha2"
                        id="fecha2" value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                </div>
                <div class="col-auto">
                    <select {!! $chkNomina == 'on' ? 'disabled' : '' !!} class="form-select rounded" style="line-height: 18px" name="tipoNomina"
                        id="tipoNomina">
                        @foreach ($tiposNomina as $tipoNomina)
                            <option {!! $tipoNomina->TipoNomina == $idTipoNomina ? 'selected' : '' !!} value="{{ $tipoNomina->TipoNomina }}">
                                {{ $tipoNomina->NomTipoNomina }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <div class="input-group">
                        <span class="input-group-text card" style="line-height: 18px">Buscar Empleado</span>
                        <span class="input-group-text">
                            <input {!! !empty($chkNomina) ? 'checked' : '' !!} class="form-check-input mt-0" style="line-height: 18px"
                                type="checkbox" name="chkNomina" id="chkNomina">
                        </span>
                        <input {!! empty($chkNomina) ? 'disabled' : 'enabled' !!} class="form-control rounded" style="line-height: 18px" type="number"
                            name="numNomina" id="numNomina" placeholder="# Nómina" value="{{ $numNomina }}" required>
                    </div>
                </div>
                <div class="col-auto">
                    <button class="btn btn-dark-outline">
                        @include('components.icons.search')
                    </button>
                </div>
            </form>

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
                    @include('components.table-empty', ['items' => $concentradoAdeudos, 'colspan' => 4])
                    @foreach ($concentradoAdeudos as $adeudo)
                        <tr>
                            <td>{{ $adeudo->NumNomina }}</td>
                            <td>{{ $adeudo->Nombre }} {{ $adeudo->Apellidos }}</td>
                            <td>{{ $adeudo->NomTienda }}</td>
                            <td>$ {{ number_format($adeudo->ImporteCredito, 2) }}</td>
                        </tr>
                    @endforeach
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
