<!-- Modal Detalle -->
<div class="modal fade" id="ModalDetalle{{ $pedido->IdPedido }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Detalle del Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table>
                    <thead>
                        <tr>
                            <th>Articulo</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>SubTotal</th>
                            <th>Iva</th>
                            <th>Importe</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedido->ArticuloDetalle as $detalle)
                        <tr>
                            <td>{{ $detalle->NomArticulo }}</td>
                            <td>{{ number_format($detalle->DetallePedido->CantArticulo, 3) }}</td>
                            <td>${{ number_format($detalle->DetallePedido->PrecioArticulo, 2) }}</td>
                            <td>${{ number_format($detalle->DetallePedido->SubTotalArticulo, 2) }}</td>
                            @if($detalle->DetallePedido->IvaArticulo == 0)
                                <td></td>
                            @else
                                <td>{{ number_format($detalle->DetallePedido->IvaArticulo, 2) }}</td>
                            @endif
                            <td>${{ number_format($detalle->DetallePedido->ImporteArticulo, 2)}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <th class="hiddenCell"></th>
                            <th class="hiddenCell"></th>
                            <th class="hiddenCell"></th>
                            <th class="hiddenCell"></th>
                            <th style="text-align: right">SUBTOTAL:</th>
                            <th>${{ number_format($pedido->SubTotalPedido, 2) }}</th>
                        <tr>
                            <th class="hiddenCell"></th>
                            <th class="hiddenCell"></th>
                            <th class="hiddenCell"></th>
                            <th class="hiddenCell"></th>
                            <th style="text-align: right">IVA:</th>
                            <th>${{ number_format($pedido->IvaPedido, 2) }}</th>
                        </tr>
                        <tr>
                            <th class="hiddenCell"></th>
                            <th class="hiddenCell"></th>
                            <th class="hiddenCell"></th>
                            <th class="hiddenCell"></th>
                            <th style="text-align: right">TOTAL:</th>
                            <th>${{ number_format($pedido->ImportePedido, 2) }}</th>
                        </tr>
                    </tfoot>
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
