<!-- Modal -->
<div class="modal fade" id="ModalDatEncabezado{{ $pedido->IdPedido }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"><i class="fa fa-check"></i> Pedido Vendido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha de Venta</th>
                            <th># de Ticket</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($pedido))
                        @foreach ($pedido->Venta as $key => $encabezado)
                        <tr>
                            @if ($key == 0)
                            <td>{{ strftime("%d %B %Y, %H:%M", strtotime($encabezado->FechaVenta)) }}</td>
                            <td>{{ $encabezado->IdTicket }}</td>
                            @endif
                        </tr>
                        @endforeach
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