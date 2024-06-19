<!-- Modal Agregar-->
<div class="modal fade" id="ModalMostrarDetalle{{ $rostisado->IdDatRosticero }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle Rostizado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-end mb-2">
                    <div>
                        <p class="m-0 text-left" style="line-height: 24px">
                            {{ strftime('%d %B %Y', strtotime($rostisado->Fecha)) }}</p>
                        <p class="m-0 text-left fw-bold" style="line-height: 24px">
                            {{ $rostisado->NomArticulo }}</p>
                    </div>
                    <form class="form-agregar-detalle d-flex gap-2"
                        action="/AgregarDetalleRosticero/{{ $rostisado->IdRosticero }}" method="POST">
                        @csrf
                        <label for="" class="form-label mb-0">Rostizado: </label>
                        <input class="form-control form-control-codigo" type="text" style="line-height: 16px"
                            minlength="12" maxlength="12" name="codigo" placeholder="Código rostizado" required>
                    </form>

                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Hora</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('components.table-empty', [
                            'items' => $rostisado->Detalle,
                            'colspan' => 6,
                        ])
                        @foreach ($rostisado->Detalle as $detalle)
                            <tr>
                                <td>{{ $detalle->CodArticulo }}</td>
                                <td>{{ $detalle->NomArticulo }}</td>
                                <td>{{ number_format($detalle->Cantidad, 3, '.', '.') }}</td>
                                <td>{{ strftime('%H:%M', strtotime($detalle->FechaCreacion)) }}</td>
                                <td>
                                    @if ($detalle->subir == 0)
                                        <span class="tags-red">Offline </span>
                                    @else
                                        <span class="tags-green"> Online </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <form class="form-mermar-rosticero d-flex d-none"
                                            action="RecalentadoRosticero/{{ $detalle->IdDatDetalleRosticero }}"
                                            method="POST">
                                            @csrf
                                            <input class="form-control" type="text" minlength="12" maxlength="12"
                                                name="codigo" required>
                                        </form>

                                        <button class="btn-mermar-rosticero btn-table-outline"
                                            title="Cambiar recalentado">
                                            @include('components.icons.switch')
                                        </button>

                                        <form class="d-flex"
                                            action="EliminarDetalleRosticero/{{ $detalle->IdDatDetalleRosticero }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-table-outline text-danger" type="submit"
                                                title="Eliminar rostizado">
                                                @include('components.icons.delete')
                                            </button>
                                        </form>
                                    </div>
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
