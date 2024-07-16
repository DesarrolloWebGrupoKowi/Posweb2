<!-- Modal Agregar-->
<div class="modal fade" id="ModalShowDetails{{ $preparado->IdPreparado }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle del Preparado</h5>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-wrap gap-2 justify-content-between align-items-end mb-2">
                    <div>
                        <p class="m-0 text-left" style="line-height: 24px">
                            {{ strftime('%d %B %Y', strtotime($preparado->Fecha)) }}</p>
                        <p class="m-0 text-left fw-bold" style="line-height: 24px">
                            {{ $preparado->Nombre }}</p>
                    </div>
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
                            <label class="form-label m-0" style="line-height: 16px">Cantidad:</label>
                            <input class="form-control rounded form-control-codigo" style="line-height: 18px"
                                name="cantidad" type="number" min="0" placeholder="Cantidad" value="1"
                                step=".01" required>
                        </div>
                        <input type="submit" class="d-none" value="Agregar">
                    </form>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Fórmula</th>
                            <th>Importe</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($preparado->Detalle) == 0)
                            <tr>
                                <td colspan="4">No ahi productos agregados al preparado</td>
                            </tr>
                        @endif
                        @foreach ($preparado->Detalle as $detalle)
                            <tr>
                                <td>{{ $detalle->CodArticulo }}</td>
                                <td>{{ $detalle->NomArticulo }}</td>
                                <td>{{ $detalle->CantidadPaquete }}</td>
                                <td>{{ number_format($detalle->CantidadFormula, 3, '.', '.') }}</td>
                                <td>
                                    ${{ number_format($detalle->PrecioArticulo * $detalle->CantidadFormula, 2, '.', '.') }}
                                </td>
                                <td>
                                    <select name="IdListaPrecio" id="IdListaPrecio" class="form-select rounded"
                                        style="line-height: 18px" data-id="{{ $detalle->IdPreparado }}">
                                        @foreach ($listaPrecios as $lista)
                                            <option value="{{ $lista->IdListaPrecio }}"
                                                {{ $lista->IdListaPrecio == $detalle->IdListaPrecio ? 'selected' : '' }}>
                                                {{ $lista->NomListaPrecio }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <form class="d-inline-block"
                                        action="/EliminarArticuloDePreparados/{{ $detalle->IdDatPreparado }}"
                                        method="POST">
                                        @csrf
                                        <button class="btn-table text-danger" title="Eliminar articulo">
                                            @include('components.icons.delete')
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
