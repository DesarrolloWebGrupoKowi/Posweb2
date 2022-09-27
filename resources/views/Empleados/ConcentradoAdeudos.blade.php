@extends('plantillaBase.masterblade')
@section('title', 'Concentrado de Adeudos')
@section('contenido')
    <div class="row d-flex justify-content-center mb-2">
        <div class="col-auto">
            <h2 class="card shadow p-1">Concentrado de Adeudos</h2>
        </div>
    </div>
    <div class="container-fluid mb-3">
        <form action="/ConcentradoAdeudos">
            <div class="row d-flex justify-content-center">
                <div class="col-auto">
                    <input type="date" class="form-control shadow" name="fecha1" id="fecha1"
                        value="{{ empty($fecha1) ? date('Y-m-d') : $fecha1 }}">
                </div>
                <div class="col-auto">
                    <input type="date" class="form-control shadow" name="fecha2" id="fecha2"
                        value="{{ empty($fecha2) ? date('Y-m-d') : $fecha2 }}">
                </div>
                <div class="col-auto">
                    <select {!! $chkNomina == 'on' ? 'disabled' : '' !!} class="form-select shadow" name="tipoNomina" id="tipoNomina">
                        @foreach ($tiposNomina as $tipoNomina)
                            <option {!! $tipoNomina->TipoNomina == $idTipoNomina ? 'selected' : '' !!} value="{{ $tipoNomina->TipoNomina }}">
                                {{ $tipoNomina->NomTipoNomina }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <div class="input-group">
                        <span class="input-group-text card shadow">Buscar Empleado</span>
                        <span class="input-group-text shadow">
                            <input {!! !empty($chkNomina) ? 'checked' : '' !!} class="form-check-input mt-0" type="checkbox" name="chkNomina" id="chkNomina">
                        </span>
                        <input {!! empty($chkNomina) ? 'disabled' : 'enabled' !!} class="form-control shadow" type="number" name="numNomina" id="numNomina" placeholder="# Nómina"
                         value="{{ $numNomina }}" required>
                    </div>
                </div>
                <div class="col-auto">
                    <button class="btn card shadow">
                        <span class="material-icons">search</span>
                    </button>
                </div>
                @if (!empty($fecha1) && !empty($fecha2))
                    <div class="col-auto mt-1">  
                        <span class="card bg-dark text-white shadow p-1">{{ strftime("%d %B %Y", strtotime( $fecha1 )) }} - {{ strftime("%d %B %Y", strtotime( $fecha2 )) }}</span>
                    </div>
                @endif
            </div>
        </form>
    </div>
    @if (!empty($fecha1) && !empty($fecha2))
    <div class="container mb-3">
        <table class="table table-responsive table-striped shadow">
            <thead class="table-dark">
                <tr>
                    <th>Nómina</th>
                    <th>Empleado</th>
                    <th>Tienda</th>
                    <th>Adeudo</th>
                </tr>
            </thead>
            <tbody>
                @if ($concentradoAdeudos->count() == 0)
                    <tr>
                        <th colspan="4"><i class="fa fa-exclamation-triangle"></i> No Se Encontraron Registros!</th>
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
    <script>
        const chkNomina = document.getElementById('chkNomina');
        const numNomina = document.getElementById('numNomina');
        const tipoNomina = document.getElementById('tipoNomina');

        chkNomina.addEventListener('click', (e) => {
            if(numNomina.disabled == false){
                numNomina.value = '';
                numNomina.disabled = true;
                tipoNomina.disabled = false;
            }
            else{
                numNomina.disabled = false;
                tipoNomina.disabled = true;
            }
        });
    </script>
@endsection
