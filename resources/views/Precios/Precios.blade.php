@extends('plantillaBase.masterblade')
@section('title', 'Módulo de Precios')
<link rel="stylesheet" href="{{ asset('css/stylePrecios.css') }}">
@section('contenido')
    <div class="container-fluid">
        <h2 class="titulo">Módulo de Precios</h2>
        <div>
            @include('Alertas.Alertas')
        </div>
        <div class="d-flex justify-content-start mb-3">
            <form id="formPrecios" action="/Precios">
                <div class="row">
                    <div class="col-auto">
                        <select class="form-select" name="IdListaPrecio" id="IdListaPrecio">
                            <option {!! $idListaPrecio != null ? 'disabled' : '' !!} value="">Seleccione Lista de Precios</option>
                            @foreach ($listaPrecios as $listaPrecio)
                                <option {!! $listaPrecio->IdListaPrecio == $idListaPrecio ? 'selected' : '' !!} value="{{ $listaPrecio->IdListaPrecio }}">
                                    {{ $listaPrecio->NomListaPrecio }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <select class="form-select" name="IdGrupo" id="IdGrupo">
                            <option value="0">TODOS</option>
                            @foreach ($grupos as $grupo)
                                <option {!! $idGrupo == $grupo->IdGrupo ? 'selected' : '' !!} value="{{ $grupo->IdGrupo }}">{{ $grupo->NomGrupo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <button class="btn"><span class="material-icons">search</span></button>
                    </div>
                    <div class="col-auto">
                        <a href="/Precios" class="btn"><span class="material-icons">visibility</span></a>
                    </div>
                </div>
            </form>
        </div>
        <form action="/ActualizarPrecios" method="POST">
            <input type="hidden" name="idListaPrecioHidden" value="{{ $idListaPrecio }}">
            @csrf
            <div class="col-6 card mb-3" style="float: left; height: 70%;">
                <div class="table-responsive">
                    <table id="tblPrecios" class="table table-responsive table-sm table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Codigo</th>
                                <th style="width: 45%">Nombre</th>
                                <th>Precio</th>
                                <th>Precio Final</th>
                                @if (empty($idListaPrecio))
                                    <th>Todos</th>
                                @else
                                    <th>Todos <input class="form-check-input" type="checkbox" name="allPrecios"
                                            id="allPrecios" onclick="seleccionarTodo()"></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if (empty($idListaPrecio))
                                <tr>
                                    <td colspan="5">Seleccione Lista de Precios</td>
                                </tr>
                            @else
                                @if (count($precios) <= 0)
                                    <tr>
                                        <td colspan="5">No Hay Precios</td>
                                    </tr>
                                @endif
                                <input type="hidden" name="length" id="length" value="{{ count($precios) }}">
                                @foreach ($precios as $precio)
                                    <tr>
                                        <td>{{ $precio->CodArticulo }}</td>
                                        <input type="hidden" name="codigos[]" id="codigos"
                                            value="{{ $precio->CodArticulo }}">
                                        <td>{{ $precio->NomArticulo }}</td>
                                        <td><span id="pArticulo">{{ $precio->PrecioArticulo }}</span></td>
                                        <td>
                                            <input class="modifcarPrecios" type="text" id="precios" name="precios[]"
                                                value="{{ $precio->PrecioArticulo }}">
                                        </td>
                                        <td>
                                            <input class="form-check-input" type="checkbox" id="codigosCheck"
                                                name="codigosSeleccionados[]" value="{{ $precio->CodArticulo }}">
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-5" style="float: left; margin-left: 5%;">
                <div class="input-group mb-3">
                    <div class="input-group-text">
                        <input class="form-check-input mt-0" type="checkbox" name="Movimientos" id="TodaLaLista"
                            onclick="enableMovimientos()">
                    </div>
                    <label class="input-group-text" for="">Actualizar Por Porcentaje O Cantidad</label>
                </div>
                <div id="divMovimientos" class="card" style="display: none;">
                    <table class="table table-sm">
                        <thead class="table-dark">
                            <tr>
                                <th>Seleccione Movimiento</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="row p-3">
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radio" id="porcentaje" value="porcentaje"
                                    onclick="enable()">
                                <input disabled class="form-range" type="range" name="rangePorcentaje"
                                    id="rangePorcentaje" min="1" max="100" step="1" value="1">
                                <!--<p id="porciento" style="text-align: center;"></p>-->
                            </div>
                        </div>
                        <div class="col-2">
                            <p id="porciento"></p>
                        </div>
                        <div class="col-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radio" id="pesos" value="pesos"
                                    onclick="enable()">
                                <button type="button" disabled class="btn btn-warning btn-sm" onclick="cantidad(3,0)"
                                    id="menos">-</button>
                                <input disabled style="text-align: center;" class="txtContador" id="3" name="txtPeso"
                                    type="text" value="1" size="1">
                                <button type="button" disabled class="btn btn-primary btn-sm" onclick="cantidad(3,1)"
                                    id="mas">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-warning" onclick="visualizarCambios()"><i
                                    class="fa fa-check-square-o"></i> Aplicar Precios</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="botones">
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <input class="form-check-input" type="radio" name="radioActualizar" id="radioActualizarPara"
                            value="FechaPara"></span>
                    <span class="input-group-text">Actualizar Para</span>
                    <input disabled type="date" class="form-control" name="FechaPara" id="Fecha"
                        min="{{ $tomorrow }}" required>
                    {{-- <span class="input-group-text">A</span>
                    <input disabled type="date" class="form-control" name="VigenciaHasta" id="VigenciaHasta"> --}}
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text"><input class="form-check-input" type="radio" name="radioActualizar"
                            id="radioActualizarAhora" value="Ahora"></span>
                    <span class="input-group-text">Actualizar Precios Ahora</span>
                </div>
                <div>
                    <button disabled id="btnActualizar" type="submit" class="btn btn-warning"><i class="fa fa-refresh"></i>
                        Actualizar</button>
                </div>
            </div>
        </form>
    </div>
    <script src="{{ asset('js/scriptPrecios.js') }}"></script>
@endsection

