<!-- Modal Detalle Recepcion-->
<div class="modal fade" data-bs-backdrop="static" id="ModalDetalleRecepcion{{ $recepcion->IdCapRecepcion }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle Recepción - {{ $recepcion->PackingList }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Articulo</th>
                            <th>Cant Enviada</th>
                            <th>Cant Recepcionada</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cRecibida = 0;
                            $cRecepcionada = 0;
                        @endphp
                        @foreach ($recepcion->DetalleRecepcion as $dRecepcion)
                            <tr>
                                <td>{{ $dRecepcion->CodArticulo }}</td>
                                <td>{{ $dRecepcion->NomArticulo }}</td>
                                <td>{{ number_format($dRecepcion->CantEnviada, 2) }}</td>
                                <td style="color: {!! $dRecepcion->CantRecepcionada == 0 ? 'red; font-weight:bold;' : '' !!}">
                                    {{ number_format($dRecepcion->CantRecepcionada, 2) }}</td>
                                <th style="color: {!! $dRecepcion->IdStatusRecepcion != 2 ? 'red; font-weight:bold;' : '' !!}">{{ $dRecepcion->NomStatusRecepcion }}</th>
                            </tr>
                            @php
                                $cRecibida = $cRecibida + $dRecepcion->CantEnviada;
                                $cRecepcionada = $cRecepcionada + $dRecepcion->CantRecepcionada;
                            @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th style="text-align: center">Totales: </th>
                            <th>{{ number_format($cRecibida, 2) }}</th>
                            <th>{{ number_format($cRecepcionada, 2) }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
