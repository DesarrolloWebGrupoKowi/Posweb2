<!-- Modal Agregar-->
<div class="modal fade" id="ModalAsignar{{ $preparado->IdPreparado }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tiendas asignasdas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Cantidad Envio</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($preparado->Tiendas) == 0)
                            <tr>
                                <td colspan="2">No tiene tiendas asignadas aun</td>
                            </tr>
                        @endif
                        @foreach ($preparado->Tiendas as $detalle)
                            <tr>
                                <td>{{ $detalle->NomTienda }}</td>
                                <td>{{ $detalle->CantidadEnvio }}</td>
                                <td>
                                    <form class="d-inline-block"
                                        action="/EliminarTiendaAsignada/{{ $detalle->IdDatAsignacionPreparado }}"
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
                <form action="/AsignarTienda/{{ $preparado->IdPreparado }}" method="POST">
                    @csrf
                    <div class="row">

                        <input type="hidden" name="preparado" value="{{ $preparado->preparado }}">
                        <div class="col-8">
                            <label class="form-label m-0">Tienda</label>
                            <select class="form-select" name="idTienda">
                                @foreach ($tiendas as $tienda)
                                    <option value="{{ $tienda->IdTienda }}">{{ $tienda->NomTienda }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4">
                            <label class="form-label m-0">Cantidad envio</label>
                            <input class="form-control" name="cantidad" type="number" min="1"
                                max="{{ $preparado->Cantidad - $preparado->CantidadAsignada }}" placeholder="Cantidad"
                                value="1" autofocus required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 pt-2">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                        <input type="submit" class="btn btn-warning" value="Asignar">
                    </div>
                </form>
            </div>
            {{-- <div class="modal-footer">

            </div> --}}

        </div>
    </div>
</div>
