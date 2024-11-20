<div class="modal fade" id="ModalTickets{{ $solicitud->Id }}" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
    tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title">Detalle de venta</h5>
            </div>
            <div class="modal-body">
                <table class="">
                    <thead class="">
                        <tr>
                            <th>Código</th>
                            <th>Articulo</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Iva</th>
                            <th>Importe</th>
                            <th>Paquete</th>
                            <th>Cliente Pedido</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($solicitud->DetalleTicket as $detalle)
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
                    </tbody>
                    <tfoot>
                        <tr>
                            <td style="text-align: right" colspan="5">Sub total</td>
                            <td>{{ number_format($solicitud->SubTotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: right" colspan="5">Total</td>
                            <td>{{ number_format($solicitud->ImporteVenta, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar </button>
            </div>
        </div>
    </div>
</div>
