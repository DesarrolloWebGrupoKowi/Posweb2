<!-- Modal -->
<div class="modal fade" id="ModalDatEncabezado{{ $pedido->IdPedido }}" tabindex="-1" aria-labelledby="staticBackdropLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"><i class="fa fa-check"></i> Pedido Vendido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table>
                    <thead>
                        <tr>
                            <th>Fecha de Venta</th>
                            <th># de Ticket</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($pedido))
                            <tr>
                                <td>{{ strftime('%d %B %Y, %H:%M', strtotime($pedido->Venta->FechaVenta)) }}</td>
                                <td>{{ $pedido->Venta->IdTicket }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
