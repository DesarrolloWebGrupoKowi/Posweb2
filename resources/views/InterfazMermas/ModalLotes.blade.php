<div class="modal fade" id="ModalLotes{{ $merma->CodArticulo }}" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Lotes Disponibles</h5>
            </div>
            <div class="modal-body">
                <p class="m-0 text-left fw-bold" style="line-height: 24px">
                    {{ $merma->NomArticulo }} - {{ number_format($merma->CantArticulo, 3) }}
                </p>
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
                        @foreach ($merma->Lotes as $lote)
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
                        <th style="text-align: center">Total: </th>
                        <th>{{ number_format($totalLote, 3) }}</th>
                    </tfoot>
                </table>
                @if ($merma->CantArticulo >= $totalLote)
                    <div class="d-flex justify-content-center">
                        <div class="col-auto">
                            <span class="tags-red">
                                <strong>Inventario Insuficiente</strong>
                            </span>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"> Cerrar </button>
            </div>
        </div>
    </div>
</div>
