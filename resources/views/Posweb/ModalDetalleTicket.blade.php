<div class="modal fade" id="ModalDetalleTicket{{ $ticket->IdTicket }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-xl">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Detalle Ticket</h5>
            </div>
            <div class="modal-body">
                <div>
                    <div>
                        <p class="m-0 text-left" style="line-height: 24px">
                            {{ strftime('%d %B %Y', strtotime($ticket->FechaVenta)) }}
                        </p>
                        <p class="m-0 text-left fw-bold" style="line-height: 24px">
                            Ticket No. {{ $ticket->IdTicket }}
                        </p>
                    </div>
                    <table>
                        <thead class="table-head-secondary">
                            <tr>
                                <th>Código</th>
                                <th>Articulo</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Iva</th>
                                <th>Importe</th>
                                <th>Paquete</th>
                                <th>Cliente Pedido</th>
                                <th>Recorte</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ticket->detalle as $detalle)
                                <tr>
                                    <td>{{ $detalle->CodArticulo }}</td>
                                    <td>{{ $detalle->NomArticulo }}</td>
                                    <td>{{ number_format($detalle->CantArticulo, 3) }}</td>
                                    <td>{{ number_format($detalle->PrecioArticulo, 2) }}</td>
                                    <td>{{ number_format($detalle->IvaArticulo, 2) }}</td>
                                    <td>{{ number_format($detalle->ImporteArticulo, 2) }}</td>
                                    <td>{{ $detalle->NomPaquete }}</td>
                                    <td>{{ $detalle->Cliente }}</td>
                                    <td>
                                        @if ($detalle->Recorte == 0)
                                            <i style="color: green; font-size: 20px" class="fa fa-check-circle-o"></i>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
