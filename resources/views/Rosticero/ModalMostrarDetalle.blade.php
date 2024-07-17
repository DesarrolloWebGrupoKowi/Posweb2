<!-- Modal Agregar-->
<div class="modal fade" id="ModalMostrarDetalle{{ $rostisado->IdDatRosticero }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle Rostizado</h5>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-end mb-2">
                    <div>
                        <p class="m-0 text-left" style="line-height: 24px">
                            {{ strftime('%d %B %Y', strtotime($rostisado->Fecha)) }}</p>
                        <p class="m-0 text-left fw-bold" style="line-height: 24px">
                            {{ $rostisado->NomArticulo }}</p>
                    </div>
                    @if ($rostisado->Finalizado != 1)
                        <form class="form-agregar-detalle d-flex gap-2"
                            action="/AgregarDetalleRosticero/{{ $rostisado->IdRosticero }}" method="POST">
                            @csrf
                            <label for="" class="form-label mb-0">Rostizado: </label>
                            <input class="form-control form-control-codigo" type="text" style="line-height: 16px"
                                minlength="12" maxlength="13" name="codigo" placeholder="Código rostizado" required>
                        </form>
                    @endif

                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Recalentado</th>
                            <th>Hora</th>
                            {{-- <th>Referencia</th> --}}
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('components.table-empty', [
                            'items' => $rostisado->Detalle,
                            'colspan' => 6,
                        ])
                        @php
                            $color = '';
                        @endphp
                        @foreach ($rostisado->newdetalle as $index => $detalle)
                            @php

                                $flat = false;
                                foreach ($rostisado->newdetalle as $d) {
                                    if (
                                        $d['CodigoEtiquetaRef'] == $detalle->IdDatDetalleRosticero ||
                                        $d['IdDatDetalleRosticero'] == $detalle->CodigoEtiquetaRef
                                    ) {
                                        if ($d['CodigoEtiquetaRef'] == $detalle->IdDatDetalleRosticero) {
                                            $color = 'background-color: #' . substr(str_shuffle('DEF'), 0, 6) . ';';
                                        }
                                        $flat = true;
                                        break;
                                    }
                                }
                                if (!$flat) {
                                    $color = '';
                                }
                            @endphp
                            <tr style="{{ $color }}">
                                {{-- <td>{{ $index . ' - ' . $detalle->IdDatDetalleRosticero }}</td> --}}
                                {{-- <td>{{ $detalle->CodArticulo }}</td> --}}
                                <td>{{ $detalle->CodigoEtiqueta }}</td>
                                <td>{{ $detalle->NomArticulo }}</td>
                                <td>{{ number_format($detalle->Cantidad, 3, '.', '.') }}</td>
                                <td>{{ number_format($detalle->CantMermaRecalentado, 3, '.', '.') }}</td>
                                <td>{{ strftime('%H:%M', strtotime($detalle->FechaCreacion)) }}</td>
                                {{-- <td>{{ $detalle->CodigoEtiquetaRef }}</td> --}}
                                <td>
                                    @if ($detalle->subir == 0)
                                        <span class="tags-red" title="Fuera de linea">
                                            @include('components.icons.cloud-slash')
                                        </span>
                                    @else
                                        <span class="tags-green" title="En linea">
                                            @include('components.icons.cloud-check')
                                        </span>
                                    @endif

                                    @if ($detalle->Status == 1)
                                        <span class="tags-red ms-2" title="Cancelado">
                                            @include('components.icons.x')
                                        </span>
                                    @endif

                                    @if ($detalle->Vendida == 0 && $detalle->FolioMerma == null)
                                        <span class="tags-green ms-2" title="Linea finalizada">
                                            @include('components.icons.check-all')
                                        </span>
                                    @endif

                                    @if ($detalle->Vendida == 0 && $detalle->FolioMerma != null)
                                        <span class="tags-red ms-2" title="Rostisado mermado">
                                            @include('components.icons.down') {{ $detalle->FolioMerma }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($detalle->Vendida == 1 && $detalle->Status == 0)
                                        <div class="d-flex">
                                            {{-- <form class="form-mermar-rosticero d-flex d-none"
                                                action="RecalentadoRosticero/{{ $detalle->IdDatDetalleRosticero }}"
                                                method="POST">
                                                @csrf
                                                <input class="form-control" type="text" minlength="12" maxlength="12"
                                                    name="codigo" required>
                                            </form> --}}

                                            {{-- <button class="btn-mermar-rosticero btn-table-outline"
                                                title="Cambiar recalentado">
                                                @include('components.icons.switch')
                                            </button> --}}

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
                                    @endif
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
