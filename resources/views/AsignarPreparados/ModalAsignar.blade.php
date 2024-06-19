<!-- Modal Agregar-->
<div class="modal fade" id="ModalAsignar{{ $preparado->IdPreparado }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tiendas asignadas</h5>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-wrap gap-2 justify-content-between align-items-end mb-2">
                    <div>
                        <p class="m-0 text-left" style="line-height: 24px">
                            {{ strftime('%d %B %Y', strtotime($preparado->Fecha)) }}</p>
                        <p class="m-0 text-left fw-bold" style="line-height: 24px">
                            {{ $preparado->Nombre }}</p>
                    </div>
                    <form class="form-agregar-detalle d-flex gap-2"action="/AsignarTienda/{{ $preparado->IdPreparado }}"
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
                            <label class="form-label m-0" style="line-height: 16px">Cantidad envio</label>
                            <input class="form-control rounded form-control-codigo" style="line-height: 18px"
                                name="cantidad" type="number" min="1"
                                max="{{ $preparado->Cantidad - $preparado->CantidadAsignada }}" placeholder="Cantidad"
                                value="1" autofocus required>
                        </div>
                    </form>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Cantidad Envio</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('components.table-empty', [
                            'items' => $preparado->Tiendas,
                            'colspan' => 3,
                        ])
                        @foreach ($preparado->Tiendas as $detalle)
                            <tr>
                                <td>{{ $detalle->NomTienda }}</td>
                                <td>{{ $detalle->CantidadEnvio }}</td>
                                <td>
                                    <form class="d-inline-block"
                                        action="/EliminarTiendaAsignada/{{ $detalle->IdDatAsignacionPreparado }}"
                                        method="POST">
                                        @csrf
                                        <button class="btn-table text-danger" title="Eliminar tienda">
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
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
