<div class="modal fade" id="ModalLotes{{ $rostisado->IdDatRosticero }}" aria-labelledby="exampleModalToggleLabel2"
    tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Lotes Disponibles</h5>
            </div>
            <div class="modal-body">
                <div>
                    <p class="m-0 text-left fw-bold" style="line-height: 24px">
                        {{ $rostisado->NomArticulo }} - {{ number_format($rostisado->CantidadMatPrima, 3) }}
                    </p>
                </div>
                <table class="">
                    <thead>
                        <tr>
                            <th>Lote</th>
                            <th>Caducidad</th>
                            <th>Inventario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalLote = 0;
                        @endphp
                        @foreach ($rostisado->Lotes as $lote)
                            <tr>
                                <td>{{ $lote->LOT_NUMBER }}</td>
                                <td>{{ strftime('%d %B %Y', strtotime($lote->EXPIRATION)) }}</td>
                                <td>{{ number_format($lote->TOTAL, 3) }}</td>
                            </tr>
                            @php
                                $totalLote = $totalLote + $lote->TOTAL;
                            @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <th></th>
                        <th style="text-align: right">Total: </th>
                        <th>{{ number_format($totalLote, 3) }}</th>
                    </tfoot>
                </table>
                @if ($rostisado->CantidadMatPrima >= $totalLote)
                    <div class="d-flex justify-content-center">
                        <div class="col-auto">
                            <span class="bg-danger text-white rounded-2 p-1"><i class="fa fa-exclamation-circle"></i>
                                <strong>Inventario Insuficiente</strong> <i class="fa fa-exclamation-circle"></i></span>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar </button>
            </div>
        </div>
    </div>
</div>
