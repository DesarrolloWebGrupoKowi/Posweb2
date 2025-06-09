@extends('plantillaBase.masterbladeNewStyle')
@section('title', $preparado->IdPreparado)
@section('dashboardWidth', 'width-general')
@section('contenido')
    <div class="container-fluid width-general d-flex flex-column gap-4 pt-4">
        <div class="card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-sm-between align-items-sm-end flex-column flex-sm-row">
                {{-- @include('components.title', ['titulo' => 'Preparado']) --}}
                @include('components.title', [
                    'titulo' => 'Preparado',
                    'options' => [['name' => 'Preparados', 'value' => '/AsignarPreparados']],
                ])
            </div>
            <div>
                @include('Alertas.Alertas')
            </div>
        </div>

        <div class="content-table content-table-flex-none card border-0 p-4" style="border-radius: 10px">
            <div class="d-flex justify-content-between mb-2">
                <div class="g-3">
                    <div>
                        <label class="form-label text-secondary m-0" style="font-weight:500">Folio: </label>
                        <span class="fw-500">{{ $preparado->preparado }}</span>
                    </div>
                    <div>
                        <label class="form-label text-secondary m-0" style="font-weight:500">Nombre: </label>
                        <span class="fw-500">{{ substr($preparado->Nombre, 0, -15) }}</span>
                    </div>
                    <div>
                        <label class="form-label text-secondary m-0" style="font-weight:500">Fecha: </label>
                        <span class="fw-500">
                            {{ ucfirst(\Carbon\Carbon::parse($preparado->Fecha)->locale('es')->isoFormat('dddd D \d\e MMMM \d\e\l Y')) }}
                        </span>
                    </div>
                    <div>
                        <label class="form-label text-secondary m-0" style="font-weight:500">Cantidad: </label>
                        <span class="fw-500">{{ $preparado->Cantidad }}</span>
                    </div>
                    <div>
                        <label class="form-label text-secondary m-0" style="font-weight:500">Cantidad Libre: </label>
                        <span
                            class="fw-500 {{ $preparado->Cantidad - $preparado->CantidadAsignada > 0 ? 'tags-green' : 'tags-red' }}">{{ $preparado->Cantidad - $preparado->CantidadAsignada }}
                            piezas</span>
                    </div>
                    {{-- <div>
                        <label class="form-label text-secondary m-0" style="font-weight:500">Costo: </label>
                        <span class="fw-500">${{ round($preparado->Total, 2) }}</span>
                    </div> --}}
                </div>

                <div class="d-flex align-items-top">
                    <div>
                        {{-- <button
                            class="{{ Session::get('id') == $preparado->preparado ? 'modalOpen' : '' }} btn-table  btn-table-show"
                            data-bs-toggle="modal" data-bs-target="#ModalAsignar{{ $preparado->IdPreparado }}"
                            title="Tiendas"
                            {{ count($preparado->Detalle) == 0 || !$preparado->Cantidad ? 'disabled' : '' }}>
                            @include('components.icons.house')
                        </button> --}}
                        {{-- @if (count($preparado->Tiendas) != 0) --}}
                        {{-- @if (!(count($preparado->Detalle) == 0 || !$preparado->Cantidad))
                            <button class="btn-table btn-table-success" data-bs-toggle="modal"
                                data-bs-target="#ModalFinalizar{{ $preparado->IdPreparado }}" title="Finalizar">
                                @include('components.icons.check')
                            </button>
                        @endif --}}
                        @if ($preparado->IdCatStatusPreparado != 3)
                            <button class="btn-table" data-bs-toggle="modal"
                                data-bs-target="#ModalEditar{{ $preparado->IdPreparado }}" title="Editar de preparado">
                                @include('components.icons.edit')
                            </button>
                            <button class="btn-table btn-table-delete" data-bs-toggle="modal"
                                data-bs-target="#ModalEliminarPreparado{{ $preparado->IdPreparado }}"
                                title="Eliminar preparado">
                                @include('components.icons.delete')
                            </button>
                        @endif
                    </div>
                    <div class="d-inline-block form-check form-switch ps-3">
                        <form id="form-finalizar" class="d-flex" action="/FinalizarPreparado/{{ $preparado->IdPreparado }}"
                            method="POST">
                            @csrf
                            <label class="form-check-label" style="font-weight: 500; line-height: 26px"
                                for="check-finalizar">Guardar</label>
                            <input class="form-check-input" type="checkbox" id="check-finalizar"
                                style="height: 20px; width: 60px; margin-left: 5px"
                                {{ !(count($preparado->Detalle) == 0 || !$preparado->Cantidad) ? '' : 'disabled' }}
                                {{ $preparado->IdCatStatusPreparado == 3 ? 'disabled' : '' }}
                                {{ $preparado->IdCatStatusPreparado == 3 ? 'checked' : '' }}>
                        </form>
                    </div>
                    <div>
                        @if ($preparado->Subir == 1)
                            <span class="ms-2 tags-green" title="En linea">
                                @include('components.icons.cloud-check')
                            </span>
                        @else
                            <span class="ms-2 tags-red" title="Fuera de linea">
                                @include('components.icons.cloud-slash')
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-2">
                <h5>Articulos</h5>
                @if ($preparado->IdCatStatusPreparado != 3)
                    <form class="form-agregar-detalle d-flex gap-2"
                        action="/AgregarArticuloDePreparados/{{ $preparado->IdPreparado }}" method="POST">
                        @csrf
                        <div class="d-flex flex-column">
                            <label for="codArticulo" class="text-secondary"
                                style="font-weight: 500; line-height: 16px">Articulo:</label>
                            <input class="form-control-codigo form-control rounded" style="line-height: 18px"
                                list="articulos" name="codigo" id="codigo" placeholder="Buscar articulo"
                                autocomplete="off" onkeypress="return event.keyCode != 13;" required>
                            <datalist id="articulos">
                                @foreach ($articulos as $articulo)
                                    <option class="prom{{ $articulo->CodArticulo }}"
                                        value="{{ $articulo->CodArticulo }}"">
                                        {{ $articulo->NomArticulo }}
                                    </option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="d-flex flex-column">
                            <label for="codArticulo" class="text-secondary"
                                style="font-weight: 500; line-height: 16px">Cantidad:</label>
                            <input class="form-control rounded form-control-codigo" style="line-height: 18px"
                                name="cantidad" type="number" min="0" placeholder="Cantidad" value="1"
                                step=".01" required>
                        </div>
                        {{-- <input type="submit" class="btn btn-dark-outline" value="Agregar"> --}}
                        <div class="d-flex align-items-end">
                            <button type="submit" class="btn btn-dark-outline">
                                @include('components.icons.plus-circle')
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            <table>
                <thead class="table-head">
                    <tr>
                        <th class="rounded-start">Código</th>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        <th>Fórmula</th>
                        <th>Importe</th>
                        <th></th>
                        <th class="rounded-end"></th>
                    </tr>
                </thead>
                <tbody>
                    @include('components.table-empty', ['items' => $preparado->Detalle, 'colspan' => 7])
                    @php
                        $costoPrep = 0;
                    @endphp
                    @foreach ($preparado->Detalle as $detalle)
                        <tr>
                            <td>{{ $detalle->CodArticulo }}</td>
                            <td>{{ $detalle->NomArticulo }}</td>
                            <td>{{ number_format($detalle->CantidadPaquete, 3) }}</td>
                            <td>{{ number_format($detalle->CantidadFormula, 3, '.', '.') }}</td>
                            <td>
                                ${{ number_format($detalle->PrecioArticulo * $detalle->CantidadFormula, 2, '.', '.') }}
                            </td>
                            <td>
                                <select name="IdListaPrecio" id="IdListaPrecio"
                                    class="form-select form-select-submit rounded" style="line-height: 18px"
                                    data-id="{{ $detalle->IdPreparado }}"
                                    {{ $preparado->IdCatStatusPreparado == 3 ? 'disabled' : '' }}>
                                    @foreach ($listaPrecios as $lista)
                                        <option value="{{ $lista->IdListaPrecio }}"
                                            {{ $lista->IdListaPrecio == $detalle->IdListaPrecio ? 'selected' : '' }}>
                                            {{ $lista->NomListaPrecio }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <form class="d-inline-block"
                                    action="/EliminarArticuloDePreparados/{{ $detalle->IdDatPreparado }}" method="POST">
                                    @csrf
                                    @if ($preparado->IdCatStatusPreparado != 3)
                                        <button class="btn-table text-danger" title="Eliminar articulo">
                                            @include('components.icons.delete')
                                        </button>
                                    @endif
                                </form>
                            </td>
                            @php
                                $costoPrep += $detalle->PrecioArticulo * $detalle->CantidadFormula;
                            @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4"></td>
                        <td>${{ number_format($costoPrep, 2, '.', '.') }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if (!(count($preparado->Detalle) == 0 || !$preparado->Cantidad))
            <div class="pb-4">
                <div class="content-table content-table-flex-none card border-0 p-4 mb-4" style="border-radius: 10px">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h5>Tiendas asignadas</h5>
                        @if ($preparado->Cantidad - $preparado->CantidadAsignada != 0)
                            <form class="d-flex gap-2" action="/AsignarTienda/{{ $preparado->IdPreparado }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="preparado" value="{{ $preparado->preparado }}">
                                <div class="d-flex flex-column">
                                    <label for="codArticulo" class="text-secondary"
                                        style="font-weight: 500; line-height: 16px">Tienda:</label>
                                    <select class="form-select rounded" style="line-height: 18px" name="idTienda">
                                        @foreach ($tiendas as $tienda)
                                            <option value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex flex-column">
                                    <label for="codArticulo" class="text-secondary"
                                        style="font-weight: 500; line-height: 16px">Cantidad envio</label>
                                    <input class="form-control rounded form-control-codigo" style="line-height: 18px"
                                        name="cantidad" type="number" min="1"
                                        max="{{ $preparado->Cantidad - $preparado->CantidadAsignada }}"
                                        placeholder="Cantidad" value="1" autofocus required>
                                </div>
                                <div class="d-flex align-items-end">
                                    <button type="submit" class="btn btn-dark-outline"
                                        {{ count($preparado->Detalle) == 0 || !$preparado->Cantidad ? 'disabled' : '' }}>
                                        @include('components.icons.plus-circle')
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                    <table>
                        <thead class="table-head">
                            <tr>
                                <th class="rounded-start">Nombre</th>
                                <th>Cantidad Envio</th>
                                <th>Cantidad Vendida</th>
                                <th>Recepción</th>
                                <th class="rounded-end"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @include('components.table-empty', [
                                'items' => $preparado->Tiendas,
                                'colspan' => 5,
                            ])
                            @foreach ($preparado->Tiendas as $detalle)
                                <tr>
                                    <td>{{ $detalle->IdDatAsignacionPreparado }} - {{ $detalle->NomTienda }}</td>
                                    <td>{{ $detalle->CantidadEnvio }}</td>
                                    <td>{{ intval($detalle->CantidadVendida) }}</td>
                                    <td>
                                        @if ($detalle->Subir == 1)
                                            <span class="tags-green" title="En linea"> @include('components.icons.cloud-check')
                                            </span>
                                        @else
                                            <span class="tags-red" title="Fuera de linea"> @include('components.icons.cloud-slash')
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <form class="d-inline-block"
                                            action="/EliminarTiendaAsignada/{{ $detalle->IdDatAsignacionPreparado }}"
                                            method="POST">
                                            @csrf
                                            <button class="btn-table text-danger" title="Eliminar tienda"
                                                {{ $detalle->IdTienda == $idTienda || $detalle->Subir == 1 ? 'disabled' : '' }}>
                                                @include('components.icons.delete')
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <form id="form-update" method="POST">
        @csrf
        <input type="hidden" name="IdListaPrecio" value="5">
    </form>

    @include('Preparados.ModalEditar')
    @include('AsignarPreparados.ModalAsignar')
    @include('AsignarPreparados.ModalFinalizar')
    @include('Preparados.ModalEliminarPreparado')
@endsection

@section('scripts')
    <script>
        document.addEventListener('change', e => {
            if (e.target.matches('.form-select-submit')) {
                document.querySelectorAll('.form-select-submit').forEach(element => {
                    element.value = e.target.value;
                });
                let form = document.getElementById('form-update');
                form.action = '/EditarListaPreciosPreparados/' + e.target.getAttribute('data-id');
                form.IdListaPrecio.value = e.target.value;
                form.submit();
            }

            if (e.target.matches('#check-finalizar')) {
                document.querySelector('#form-finalizar').submit();
            }
        })
    </script>
@endsection
