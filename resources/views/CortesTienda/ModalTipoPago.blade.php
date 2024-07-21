<div class="modal fade" id="ModalTipoPago{{ $ticket->IdDatEncabezado }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Tipo de Pago Ticket</h5>
            </div>
            <div class="modal-body">
                <div>
                    <p class="m-0 text-left" style="line-height: 24px">
                        {{ strftime('%d %B %Y', strtotime($ticket->FechaVenta)) }}
                    </p>
                    <p class="m-0 text-left fw-bold" style="line-height: 24px">
                        Ticket No. {{ $ticket->IdTicket }}
                    </p>
                </div>
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
                                        <th style="text-align: center">Total:
                                            ${{ number_format($ticket->ImporteVenta, 2) }}</th>
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
