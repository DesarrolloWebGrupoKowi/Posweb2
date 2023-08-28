<div class="modal fade" data-bs-backdrop="static" id="ModalDetalleTicket{{ $solicitud->IdEncabezado }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2"><i class="fa fa-info-circle"></i> Detalle Ticket :
                    {{ $solicitud->Encabezado->IdTicket }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                            @foreach ($solicitud->Detalle as $detalle)
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
