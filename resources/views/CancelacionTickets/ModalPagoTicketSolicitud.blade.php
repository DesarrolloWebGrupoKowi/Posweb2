<div class="modal fade" data-bs-backdrop="static" id="ModalPagoTicketSolicitud" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2"><i class="fa fa-money"></i> Tipo de Pago Ticket : {{ $ticket->IdTicket }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <table>
                        <thead>
                            <th>Tipo de Pago</th>
                            <th>Pago</th>
                            <th>Por Pagar</th>
                        </thead>
                        <tbody>
                            @foreach ($ticket->TipoPago as $tipoPago)
                                <tr>
                                    <td>{{ $tipoPago->NomTipoPago }}</td>
                                    <td>${{ number_format($tipoPago->PivotPago->Pago, 2) }}</td>
                                    <td>
                                        @if ($tipoPago->PivotPago->Restante < 0)
                                        {{ number_format($tipoPago->PivotPago->Restante, 2) }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            @foreach ($ticket->TipoPago as $tipoPago)
                                @if ($tipoPago->PivotPago->Restante >= 0)
                                <tr>
                                    <th style="text-align: center">Total: ${{ number_format($ticket->ImporteVenta, 2) }}</th>
                                    <th style="text-align: right">Cambio:</th>
                                    <th>${{ number_format($tipoPago->PivotPago->Restante, 2) }}</th>
                                </tr>
                                @endif
                            @endforeach
                        </tfoot>
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
