<!-- Modal Agregar-->
<div class="modal fade" id="ModalShowTiendas{{ $preparado->IdPreparado }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <td colspan="3">No tiene tiendas asignadas aun</td>
                            </tr>
                        @endif
                        @foreach ($preparado->Tiendas as $detalle)
                            <tr>
                                <td>{{ $detalle->NomTienda }}</td>
                                <td>{{ $detalle->CantidadEnvio }}</td>
                                <td>
                                    @if ($preparado->IdCatStatusPreparado == 2)
                                        <form class="d-inline-block"
                                            action="/EliminarTiendaAsignada/{{ $detalle->IdDatAsignacionPreparado }}"
                                            method="POST">
                                            @csrf
                                            <button class="btn btn-sm">
                                                <span class="material-icons eliminar">delete_forever</span>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
