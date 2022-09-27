<!-- Modal Articulos Paquete-->
<div class="modal fade" data-bs-backdrop="static" id="ModalArticulos{{ $paquete->IdPaquete }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $paquete->NomPaquete }}</h5>
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
                        @foreach ($paquete->ArticulosPaquete as $aPaquete)
                            <tr>
                                <td>{{ $aPaquete->CodArticulo }}</td>
                                <td>{{ $aPaquete->NomArticulo }}</td>
                                <td>{{ number_format($aPaquete->CantArticulo, 2) }}</td>
                                <td>${{ number_format($aPaquete->PrecioArticulo, 2) }}</td>
                                <td>${{ number_format($aPaquete->ImporteArticulo, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
