@extends('PlantillaBase.masterbladeNewStyle')
@section('title', 'Creación de preparados')
@section('dashboardWidth', 'width-95')
@section('contenido')

    <div class="container-fluid pt-4 width-95">
        <div class="d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row pb-2">
            @include('components.title', ['titulo' => 'Creación de Preparados'])
        </div>

        <div>
            @include('Alertas.Alertas')
        </div>

        <div class="container-fluid my-3">
            <div class="row">
                <div class="col-6">
                    <form class="d-flex align-items-center justify-content-start pb-4 gap-2" action="/Preparados"
                        method="POST">
                        @csrf
                        <div class="input-group" style="max-width: 300px">
                            <input type="text" name="nombre" class="form-control" placeholder="Nombre del preparado"
                                autofocus>
                        </div>
                        <div class="input-group" style="max-width: 300px">
                            <input type="number" name="cantidad" min="0" class="form-control"
                                placeholder="Cantidad">
                        </div>
                        <div class="col-1">
                            <button type="submit" class="btn btn-dark-outline" data-bs-toggle="modal"
                                data-bs-target="#ModalAgregar">
                                <span class="material-icons">add_circle_outline</span>
                            </button>
                        </div>
                    </form>

                    <div style="height: 70vh">
                        <div class="content-table content-table-full card p-4 pt-3" style="border-radius: 20px">
                            <h5 class="pb-1" style="text-align: center">Preparados en Proceso</h5>
                            <table>
                                <thead class="table-head">
                                    <tr>
                                        <th class="rounded-start">Nombre</th>
                                        <th>Cantidad</th>
                                        <th>Editar</th>
                                        <th>Ver</th>
                                        <th>Eliminar</th>
                                        <th class="rounded-end">Enviar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($preparados) == 0)
                                        <tr>
                                            <td colspan="6">No se encuentra ningun preparado en proceso</td>
                                        </tr>
                                    @else
                                        @foreach ($preparados as $preparado)
                                            <tr class="{{ $preparado->IdPreparado == $idPreparado ? 'table-active' : '' }}">
                                                <td>{{ $preparado->Nombre }}</td>
                                                <td>{{ $preparado->Cantidad }}</td>
                                                <td class="bg-opacity-75">
                                                    <button class="btn btn-default btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#ModalEditar{{ $preparado->IdPreparado }}"><span
                                                            class="material-icons">edit</span>
                                                    </button>
                                                    @include('Preparados.ModalEditar')
                                                </td>
                                                <td class="bg-opacity-75">
                                                    <form class="d-inline-block" action="/Preparados">
                                                        <input type="hidden" name="idPreparado"
                                                            value="{{ $preparado->IdPreparado }}">
                                                        <button class="btn btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#ModalEliminar"><span
                                                                class="material-icons">visibility</span></button>
                                                    </form>
                                                </td>
                                                <td class="bg-opacity-75">
                                                    <button class="btn btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#ModalEliminarPreparado{{ $preparado->IdPreparado }}"><span
                                                            class="material-icons eliminar">delete_forever</span></button>
                                                    @include('Preparados.ModalEliminarPreparado')
                                                </td>
                                                <td class="bg-opacity-75">
                                                    <button class="btn btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#ModalEnviar{{ $preparado->IdPreparado }}"
                                                        {{ !$preparado->Cantidad ? 'disabled' : '' }}><span
                                                            class="material-icons send">send</span></button>
                                                    @include('Preparados.ModalEnviar')
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    @if ($idPreparado)
                        <form class="d-flex align-items-center justify-content-start pb-4 gap-2"
                            action="/AgregarArticuloDePreparados/{{ $idPreparado }}" method="POST">
                            @csrf
                            <div class="input-group" style="max-width: 300px">
                                <input class=" form-control" list="articulos" name="codigo" id="codigo"
                                    placeholder="Buscar articulo" autocomplete="off"
                                    onkeypress="return event.keyCode != 13;" required>
                            </div>
                            <datalist id="articulos">
                                @foreach ($articulos as $articulo)
                                    <option class="prom{{ $articulo->CodArticulo }}" value="{{ $articulo->CodArticulo }}"">
                                        {{ $articulo->NomArticulo }}
                                    </option>
                                @endforeach
                            </datalist>
                            <div class="input-group" style="max-width: 300px">
                                <input type="number" name="cantidad" min="1" class="form-control"
                                    placeholder="Cantidad" required>
                            </div>
                            <button type="submit" class="btn btn-dark-outline" data-bs-toggle="modal"
                                data-bs-target="#ModalAgregar">
                                <span class="material-icons">add_circle_outline</span>
                            </button>
                        </form>
                    @else
                        <div class="col-12 pb-4">
                            <p class="fw-bold">Selecciona un preparado para agregar articulos</p>
                        </div>
                    @endif
                    <div style="height: 70vh">
                        <div class="content-table content-table-full card p-3" style="border-radius: 20px">
                            <h5 class="pb-1" style="text-align: center">Detalle de Preparado</h5>
                            <table>
                                <thead class="table-head">
                                    <tr>
                                        <th class="rounded-start">Codigo</th>
                                        <th>Nombre</th>
                                        <th>Cantidad de preparado</th>
                                        <th>Cantidad formula</th>
                                        <th class="rounded-end"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!$idPreparado)
                                        <tr>
                                            <td colspan="4">Selecciona un preparado</td>
                                        </tr>
                                    @elseif (count($detallePreparado) == 0)
                                        <tr>
                                            <td colspan="4">No ahi productos agregados al preparado</td>
                                        </tr>
                                    @endif
                                    @foreach ($detallePreparado as $detalle)
                                        <tr>
                                            <td>{{ $detalle->CodArticulo }}</td>
                                            <td>{{ $detalle->NomArticulo }}</td>
                                            <td>{{ $detalle->CantidadPaquete }}</td>
                                            <td>{{ number_format($detalle->CantidadFormula, 3, '.', '.') }}</td>
                                            <td>
                                                <form class="d-inline-block"
                                                    action="/EliminarArticuloDePreparados/{{ $detalle->IdDatPreparado }}"
                                                    method="POST">
                                                    @csrf
                                                    <button class="btn btn-sm">
                                                        <span class="material-icons eliminar">delete_forever</span>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
