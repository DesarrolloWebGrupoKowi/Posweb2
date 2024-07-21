<!-- Modal Agregar-->
<div class="modal fade" id="ModalShowDetails{{ $transferencia->IdTransferencia }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle del transacción</h5>
            </div>
            <div class="modal-body">
                <table>
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Articulo</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($transferencia->Detalle) == 0)
                            <tr>
                                <td colspan="4">No ahi productos agregados al preparado</td>
                            </tr>
                        @endif
                        @foreach ($transferencia->Detalle as $detalle)
                            <tr>
                                {{-- @dump($detalle) --}}
                                <td>{{ $detalle->CodArticulo }}</td>
                                <td>{{ $detalle->NomArticulo }}</td>
                                <td>{{ $detalle->CantidadTrasferencia }}</td>
                                {{-- <td>{{ number_format($detalle->CantidadTrasferencia, 3, '.', '.') }}</td> --}}
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
