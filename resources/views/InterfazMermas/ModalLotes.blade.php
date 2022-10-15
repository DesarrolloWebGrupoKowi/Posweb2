<div class="modal fade" data-bs-backdrop="static" id="ModalLotes{{ $merma->CodArticulo }}"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Lotes Disponibles</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span class="bg-dark text-white rounded-2 p-1">{{ $merma->NomArticulo }} -
                    {{ number_format($merma->CantArticulo, 3) }}</span>
                <table class="table table-striped table-responsive">
                    <thead class="table-dark">
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
                            <span class="bg-danger text-white rounded-2 p-1"><i class="fa fa-exclamation-circle"></i>
                                <strong>No Cuentas Con Inventario Suficiente</strong> <i
                                    class="fa fa-exclamation-circle"></i></span>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
