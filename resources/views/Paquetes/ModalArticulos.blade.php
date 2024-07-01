<!-- Modal Articulos Paquete-->
<div class="modal fade" id="ModalArticulos{{ $paquete->IdPaquete }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $paquete->NomPaquete }}</h5>
            </div>
            <div class="modal-body">
                <table>
                    <thead>
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
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">Cerrar </button>
            </div>
        </div>
    </div>
</div>
