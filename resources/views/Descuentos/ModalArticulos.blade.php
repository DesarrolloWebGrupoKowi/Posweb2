<!-- Modal Articulos Paquete-->
<div class="modal fade" id="ModalArticulos{{ $descuento->IdEncDescuento }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $descuento->NomDescuento }}</h5>
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
                        @include('components.table-empty', [
                            'items' => $descuento->ArticulosDescuento,
                            'colspan' => 5,
                        ])
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
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">Cerrar </button>
            </div>
        </div>
    </div>
</div>
