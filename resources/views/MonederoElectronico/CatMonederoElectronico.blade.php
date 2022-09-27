@extends('plantillaBase.masterblade')
@section('title','Monedero Electrónico')
@section('contenido')
<div class="row d-flex justify-content-center mb-3">
    <div class="col-auto">
        <h2 class="card shadow p-1">Monedero Electrónico</h2>
    </div>
</div>
<div class="container">
    @include('Alertas.Alertas')
</div>
<div class="container">
    <table class="table table-responsive table-striped">
        <thead class="table-dark">
            <tr>
                <th>Maximo Acumulado</th>
                <th>Multiplo</th>
                <th>Pesos Por Multiplo</th>
                <th>Vigencia</th>
                <th>Grupo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($monederoElectronico as $monedero)
                <tr>
                    <td>${{ number_format($monedero->MaximoAcumulado, 2) }}</td>
                    <td>${{ number_format($monedero->MonederoMultiplo, 2) }}</td>
                    <td>${{ number_format($monedero->PesosPorMultiplo, 2) }}</td>
                    <td>{{ $monedero->VigenciaMonedero }} dias</td>
                    <td>
                        <select class="form-select" name="idGrupo" id="idGrupo">
                            @foreach ($grupos as $grupo)
                                <option {!! $grupo->IdGrupo == $monedero->IdGrupo ? 'selected' : '' !!} value="{{ $grupo->IdGrupo }}">{{ $grupo->NomGrupo }}</option>                                
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <button class="btn" data-bs-toggle="modal" data-bs-target="#ModalEditar{{ $monedero->IdCatMonederoElectronico }}">
                            <span class="material-icons">edit</span>
                        </button>
                        @include('MonederoElectronico.ModalEditarMonedero')
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection