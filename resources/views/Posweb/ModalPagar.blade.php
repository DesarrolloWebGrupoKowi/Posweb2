<div class="modal fade" id="ModalPagar" data-bs-backdrop="static" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2"><i class="fa fa-dollar"></i> Tipo de Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row d-flex justify-content-center">
                    <div id="total" class="col-6 card p-4 border border-2 mb-3">
                        <h4>TOTAL</h4>
                        <h2>${{ $totalPreventa }}</h2>
                    </div>
                </div>
                <div class="content-table content-table-full mb-4"
                    style="display: {!! $banderaMultiPago > 0 ? 'block' : 'none' !!};">
                    <table>
                        <thead class="table-head text-white">
                            <tr>
                                <th  class="rounded-start">Tipo Pago</th>
                                <th>Pagado</th>
                                <th class="rounded-end">Por Pagar</th>
                            </tr>
                        </thead>
                        <tbody class="border">
                            @foreach ($datTipoPago as $pagoRestante)
                                <tr>
                                    <td>{{ $pagoRestante->NomTipoPago }}</td>
                                    <td>{{ number_format($pagoRestante->Pago, 2) }}</td>
                                    <td style="font-weight: bold; color: red;">
                                        {{ number_format($pagoRestante->Restante, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <form id="formPago" action="/GuardarVenta">
                    <input type="hidden" name="nNominaEmpleado"
                        value="{{ empty($cliente->NumNomina) ? '' : $cliente->NumNomina }}">
                    <div class="mb-3">
                        <select {!! $preventa->count() == 0 ? 'disabled' : '' !!} class="form-select" name="tipoPago" id="tipoPago" required>
                            @if ($tiposPago->count() == 0)
                                <option value="">No Hay Tipo de Pago para la Tienda</option>
                            @else
                                @foreach ($tiposPago as $tipoPago)
                                    @foreach ($tipoPago->TiposPago as $tPago)
                                        <option {!! $tPago->IdTipoPago == 7 && $monederoEmpleado == 0 ? 'disabled' : '' !!} {!! $tPago->IdTipoPago == 7 && empty($cliente->NumNomina) && empty($frecuenteSocio) ? 'disabled' : '' !!} {!! $tPago->IdTipoPago == 2 && empty($cliente->NumNomina) ? 'disabled' : '' !!}
                                            {!! $tPago->IdTipoPago == 2 && $creditoDisponible == 0 ? 'disabled' : '' !!} {!! $tPago->IdTipoPago == 3 && !empty($cliente->NumNomina) ? 'disabled' : '' !!}
                                            value="{{ $tPago->IdTipoPago }}">{{ $tPago->NomTipoPago }}</option>
                                    @endforeach
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div style="display: none" id="DivTarjeta">
                        <select class="form-select mb-3" name="idBanco" id="idBanco"
                            oninvalid="this.setCustomValidity('Seleccione Banco de la Tarjeta')"
                            oninput="this.setCustomValidity('')">
                            <option value="">Seleccione Banco</option>
                            @foreach ($bancos as $banco)
                                <option value="{{ $banco->IdBanco }}">{{ $banco->NomBanco }}</option>
                            @endforeach
                        </select>
                        <div class="row mb-3">
                            <div class="col-4">
                                <input class="form-control" type="text" name="numTarjeta" id="numTarjeta"
                                    minlength="4" maxlength="4" placeholder="4 Dígitos"
                                    oninvalid="this.setCustomValidity('Ingrese Cuenta de Banco')"
                                    oninput="this.setCustomValidity('')">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-center col-4">
                            <input {!! $tiposPago->count() == 0 ? 'disabled' : '' !!} {!! $preventa->count() == 0 ? 'disabled' : '' !!} type="number" step="any"
                                class="form-control" name="txtPago" id="txtPago" placeholder="$" autocomplete="off"
                                required oninvalid="this.setCustomValidity('Ingrese Monto a Pagar')"
                                oninput="this.setCustomValidity('')">
                        </div>
                    </div>
                    <input hidden id="hacerSubmit" type="submit" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <button hidden id="modalPagoTarjeta" class="btn btn-primary" data-bs-target="#ModalPagoTarjeta"
                    data-bs-toggle="modal"></button>
            </div>
        </div>
    </div>
</div>
@include('Posweb.ModalPagarMonedero')
