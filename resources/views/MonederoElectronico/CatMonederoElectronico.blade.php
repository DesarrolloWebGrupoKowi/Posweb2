@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Monedero Electrónico')
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Monedero Electrónico'])
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>
        <div class="content-table content-table-full card border-0 p-4" style="border-radius: 10px">
            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Maximo Acumulado</th>
                        <th>Multiplo</th>
                        <th>Pesos Por Multiplo</th>
                        <th>Vigencia</th>
                        <th>Grupo</th>
                        <th class="rounded-end">Acciones</th>
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
                                <select class="form-select rounded" style="line-height: 18px" name="idGrupo" id="idGrupo">
                                    @foreach ($grupos as $grupo)
                                        <option {!! $grupo->IdGrupo == $monedero->IdGrupo ? 'selected' : '' !!} value="{{ $grupo->IdGrupo }}">{{ $grupo->NomGrupo }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <button class="btn-table" data-bs-toggle="modal"
                                    data-bs-target="#ModalEditar{{ $monedero->IdCatMonederoElectronico }}">
                                    @include('components.icons.edit')
                                </button>
                                @include('MonederoElectronico.ModalEditarMonedero')
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
