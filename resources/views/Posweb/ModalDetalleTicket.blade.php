<div class="modal fade" data-bs-backdrop="static" id="ModalDetalleTicket{{ $ticket->IdTicket }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-xl">
        <div class="modal-content">
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
                                <th>Cliente Pedido</th>
                                <th>Recorte</th>
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
                                <td>
                                    @if ($detalle->Recorte == 0)
                                        <i style="color: green; font-size: 20px" class="fa fa-check-circle-o"></i>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            </tr>
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
