@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Módulo de Precios')
@section('dashboardWidth', 'width-95')
<link rel="stylesheet" href="{{ asset('css/stylePrecios.css') }}">
@section('contenido')
    <div class="container-fluid width-95 d-flex flex-column gap-4 pt-4">

        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                @include('components.title', ['titulo' => 'Módulo de Precios'])
                <div>
                    <a href="/ExportExcelDetallePrecios" class="input-group-text text-decoration-none btn-excel">
                        Exportar precios @include('components.icons.excel')
                    </a>
                </div>
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <form id="formPrecios" class="d-flex flex-wrap align-items-center justify-content-end gap-2 pb-2" action="/Precios">
            <div class="col-auto">
                <select class="form-select rounded" style="line-height: 18px" name="IdListaPrecio" id="IdListaPrecio">
                    <option {!! $idListaPrecio != null ? 'disabled' : '' !!} value="">Seleccione Lista de Precios</option>
                    @foreach ($listaPrecios as $listaPrecio)
                        <option {!! $listaPrecio->IdListaPrecio == $idListaPrecio ? 'selected' : '' !!} value="{{ $listaPrecio->IdListaPrecio }}">
                            {{ $listaPrecio->NomListaPrecio }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <select class="form-select rounded" style="line-height: 18px" name="IdGrupo" id="IdGrupo">
                    <option value="">TODOS</option>
                    @foreach ($grupos as $grupo)
                        <option {!! $idGrupo == $grupo->IdGrupo ? 'selected' : '' !!} value="{{ $grupo->IdGrupo }}">{{ $grupo->NomGrupo }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-dark-outline">
                @include('components.icons.search')
            </button>
        </form>

        <form id="formActualizarPrecios" action="/ActualizarPrecios" method="POST">
            <input type="hidden" name="idListaPrecioHidden" value="{{ $idListaPrecio }}">
            @csrf
            <div class="col-6" style="float: left;">
                <div class="content-table content-table-full card border-0 p-4" style="height: 70%; border-radius: 10px">
                    <div>
                        <div class="row mb-2">
                            @if (!empty($idListaPrecio))
                                <div class="col-auto d-flex justify-content-end">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">
                                            <input checked class="form-check-input mt-0" type="radio" name="radioFiltro"
                                                id="codigo">
                                        </span>
                                        <span class="input-group-text card">Código</span>
                                        <span class="input-group-text">
                                            <input class="form-check-input mt-0" type="radio" name="radioFiltro"
                                                id="nombre">
                                        </span>
                                        <span class="input-group-text card">Nombre</span>
                                        <input type="text" class="form-control" name="filtro" id="filtro"
                                            placeholder="Buscar articulo...">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <table id="tblPrecios">
                        <thead class="table-head">
                            <tr>
                                <th class="rounded-start">Codigo</th>
                                <th style="width: 45%">Nombre</th>
                                <th>Precio</th>
                                <th>Precio Final</th>
                                @if (empty($idListaPrecio))
                                    <th class="rounded-end">Todos</th>
                                @else
                                    <th class="d-flex align-items-center gap-2 rounded-end">Todos <input
                                            class="form-check-input" type="checkbox" name="allPrecios" id="allPrecios"
                                            onclick="seleccionarTodo()">
                                    </th>
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
                                        <td>{{ $precio->NomArticulo }}</td>
                                        <td><span id="pArticulo">{{ $precio->PrecioArticulo }}</span></td>
                                        <td>
                                            <input class="modifcarPrecios" type="text" id="precios"
                                                name="precios[{{ $precio->CodArticulo }}]"
                                                value="{{ $precio->PrecioArticulo }}">
                                        </td>
                                        <td class="d-flex align-items-center gap-2">
                                            <input class="form-check-input" type="checkbox" id="codigosCheck"
                                                value="{{ $precio->CodArticulo }}">
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
                <div id="divMovimientos" class="content-table content-table-full card p-4"
                    style="display: none; border-radius: 20px">
                    <table class="w-100">
                        <thead class="table-head">
                            <tr>
                                <th class="rounded-start rounded-end">Seleccione Movimiento</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="row p-3">
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radio" id="porcentaje"
                                    value="porcentaje" onclick="enable()">
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
                                <input class="form-check-input" type="radio" name="radio" id="pesos"
                                    value="pesos" onclick="enable()">
                                <button type="button" disabled class="btn btn-warning btn-sm" onclick="cantidad(3,0)"
                                    id="menos">-</button>
                                <input disabled style="text-align: center;" class="txtContador" id="3"
                                    name="txtPeso" type="text" value="1" size="1">
                                <button type="button" disabled class="btn btn-primary btn-sm" onclick="cantidad(3,1)"
                                    id="mas">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-warning" onclick="visualizarCambios()">
                                <i class="fa fa-check-square-o"></i> Aplicar Precios
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="botones">
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <input class="form-check-input" type="radio" name="radioActualizar" id="radioActualizarPara"
                            value="FechaPara">
                    </span>
                    <span class="input-group-text">Actualizar Para</span>
                    <input disabled type="date" class="form-control" name="FechaPara" id="Fecha"
                        min="{{ $tomorrow }}" required>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <input class="form-check-input" type="radio" name="radioActualizar" id="radioActualizarAhora"
                            value="Ahora">
                    </span>
                    <span class="input-group-text">
                        Actualizar Precios Ahora
                    </span>
                </div>
                <div>
                    <button disabled id="btnActualizar" type="submit" class="btn btn-warning">
                        <i class="fa fa-refresh"></i> Actualizar
                    </button>
                    <button id="btnActualizandoPrecios" hidden class="btn btn-warning" type="button">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Actualizando Precios...
                    </button>
                </div>
            </div>
        </form>
    </div>
    <script src="{{ asset('js/scriptPrecios.js') }}"></script>
    <script>
        document.getElementById('formActualizarPrecios').addEventListener('submit', function() {
            document.getElementById('btnActualizar').hidden = true;
            document.getElementById('btnActualizandoPrecios').hidden = false;
        })

        document.getElementById('filtro').addEventListener('keyup', (e) => {
            const radioCodigo = document.getElementById('codigo').checked;

            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("filtro");
            filter = input.value.toUpperCase();
            table = document.getElementById("tblPrecios");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = radioCodigo == true ? tr[i].getElementsByTagName("td")[0] : tr[i].getElementsByTagName("td")[
                    1];
                //td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        });
    </script>
@endsection
