<div class="modal fade" id="ModalTipoPago{{ $ticket->IdTicket }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Tipo de Pago Ticket</h5>
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
                                <th>Tipo de Pago</th>
                                <th class="text-center">Pago</th>
                                <th class="text-center">Por Pagar</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: smaller">
                            @foreach ($ticket->TipoPago as $tipoPago)
                                <tr>
                                    <td>{{ $tipoPago->NomTipoPago }}</td>
                                    <td class="text-end">${{ number_format($tipoPago->PivotPago->Pago, 2) }}</td>
                                    <td class="text-end">
                                        @if ($tipoPago->PivotPago->Restante < 0)
                                            {{ number_format($tipoPago->PivotPago->Restante, 2) }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @foreach ($ticket->TipoPago as $tipoPago)
                                @if ($tipoPago->PivotPago->Restante >= 0)
                                    <tr>
                                        <td></td>
                                        <td class="text-end">Total: ${{ number_format($ticket->ImporteVenta, 2) }}</t>
                                        <td class="text-end">Cambio:
                                            ${{ number_format($tipoPago->PivotPago->Restante, 2) }}</t>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            {{-- @foreach ($ticket->TipoPago as $tipoPago)
                                @if ($tipoPago->PivotPago->Restante >= 0)
                                    <tr>
                                        <th style="text-align: center">Total:
                                            ${{ number_format($ticket->ImporteVenta, 2) }}</th>
                                        <th style="text-align: right">Cambio:</th>
                                        <th>${{ number_format($tipoPago->PivotPago->Restante, 2) }}</th>
                                    </tr>
                                @endif
                            @endforeach --}}
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
