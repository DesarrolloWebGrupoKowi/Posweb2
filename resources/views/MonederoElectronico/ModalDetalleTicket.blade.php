<!-- Modal Editar Monedero Electronico-->
<div class="modal fade" data-bs-backdrop="static" id="DetalleTicket{{ $eMon->IdEncabezado }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle del Ticket : {{ $eMon->IdTicket }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-responsive table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th>Articulo</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Importe</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($movimiento->Detalle as $dMon)
                            <tr>
                                <td>{{ $dMon->CodArticulo }}</td>
                                <td>{{ $dMon->NomArticulo }}</td>
                                <td>{{ number_format($dMon->CantArticulo, 3) }}</td>
                                <td>{{ number_format($dMon->PrecioArticulo, 2) }}</td>
                                <td>{{ number_format($dMon->ImporteArticulo, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
