<!-- Modal Articulos Paquete-->
<div class="modal fade" data-bs-backdrop="static" id="ModalArticulos{{ $descuento->IdEncDescuento }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $descuento->NomDescuento }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Articulo</th>
                            <th>Lista de precio</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($descuento->ArticulosDescuento as $aDescuento)
                            <tr>
                                <td>{{ $aDescuento->CodArticulo }}</td>
                                <td>{{ $aDescuento->NomArticulo }}</td>
                                <td>{{ $aDescuento->NomListaPrecio }}</td>
                                <td>{{ number_format($aDescuento->CantArticulo, 2) }}</td>
                                <td>${{ number_format($aDescuento->PrecioDescuento, 2) }}</td>
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
