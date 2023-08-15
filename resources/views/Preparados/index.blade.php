@extends('plantillaBase.masterblade')
@section('title', 'Creación de preparados')
@section('contenido')

    <h2 class="titulo my-2">Creación de Preparados</h2>
    <div class="container-fluid my-3">
        <div>
            @include('Alertas.Alertas')
        </div>

        <div class="row">
            <div class="col-6">
                <div class="row">
                    <form class="col-12 d-flex gap-2" action="/Preparados" method="POST">
                        @csrf
                        <div class="col-5">
                            <input type="text" name="nombre" class="form-control" placeholder="Nombre del preparado"
                                autofocus>
                        </div>
                        <div class="col-5">
                            <input type="number" name="cantidad" min="0" class="form-control"
                                placeholder="Cantidad">
                        </div>
                        <div class="col-1">
                            <button type="submit" class="btn btn-default Agregar" data-bs-toggle="modal"
                                data-bs-target="#ModalAgregar">
                                <span class="material-icons">add_circle_outline</span>
                            </button>
                        </div>
                    </form>
                </div>
                <div style="height: 70vh" class="container cuchi table-responsive">
                    <div>
                        <h5 style="text-align: center">Preparados en Proceso</h5>
                    </div>
                    <div class="table table-responsive table-sm">
                        <table class="table table-responsive table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Cantidad</th>
                                    <th>Editar</th>
                                    <th>Ver</th>
                                    <th>Eliminar</th>
                                    <th>Enviar</th>
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
                <div class="row">
                    @if ($idPreparado)
                        <form class="col-12 d-flex gap-2" action="/AgregarArticuloDePreparados/{{ $idPreparado }}"
                            method="POST">
                            @csrf
                            <div class="col-5">
                                {{-- <input type="text" name="nombre" class="form-control" placeholder="Producto" autofocus> --}}
                                <input class="input-article form-control" list="articulos" name="codigo" id="codigo"
                                    placeholder="Buscar articulo" autocomplete="off"
                                    onkeypress="return event.keyCode != 13;" required>
                                <datalist id="articulos">
                                    @foreach ($articulos as $articulo)
                                        <option class="prom{{ $articulo->CodArticulo }}"
                                            value="{{ $articulo->CodArticulo }}"">
                                            {{ $articulo->NomArticulo }}
                                        </option>
                                    @endforeach
                                </datalist>
                            </div>
                            <div class="col-5">
                                <input type="number" name="cantidad" min="1" class="form-control"
                                    placeholder="Cantidad" required>
                            </div>
                            <div class="col-1">
                                <button type="submit" class="btn btn-default Agregar" data-bs-toggle="modal"
                                    data-bs-target="#ModalAgregar">
                                    <span class="material-icons">add_circle_outline</span>
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="col-12 pt-2">
                            <p class="fw-bold">Selecciona un preparado para agregar articulos</p>
                        </div>
                    @endif
                </div>
                <div style="height: 70vh" class="container cuchi table-responsive">
                    <div>
                        <h5 style="text-align: center">Detalle de Preparado</h5>
                    </div>
                    <div class="table-sm table-responsive">
                        <table class="table table-sm table-responsive table-striped">
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Cantidad de preparado</th>
                                    <th>Cantidad formula</th>
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

@endsection
