<div class="modal fade" id="ModalDetalleTicketSolicitud" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
    tabindex="-1">
    <div class="modal-dialog modal-dialog modal-xl">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2"><i class="fa fa-info-circle"></i> Detalle Ticket :
                    {{ $ticket->IdTicket }}</h5>
            </div>
            <div class="modal-body">
                <div>
                    <table>
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Articulo</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Iva</th>
                                <th>Importe</th>
                                <th>Paquete</th>
                                <th>Pedido</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
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
                            </tr>
                            @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
