<!-- Modal Articulos Paquete-->
<!-- Modal Articulos Paquete-->
<div
    class="modal fade"
    id="ModalArticulos{{ $descuento->IdEncDescuento }}"
    tabindex="-1"
    aria-labelledby="modalArticulosLabel{{ $descuento->IdEncDescuento }}"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg">
        <div
            class="modal-content border-0 shadow"
            style="border-radius: 10px;"
        >
            <div
                class="modal-header border-bottom-0 pb-0"
                style="border-radius: 10px 10px 0 0; background: linear-gradient(135deg, #38465E 0%, #2c3a4f 100%);"
            >
                <h5
                    class="text-white"
                    id="modalArticulosLabel{{ $descuento->IdEncDescuento }}"
                >
                    <div class="d-flex align-items-center gap-2">
                        <span>{{ $descuento->NomDescuento }}</span>
                    </div>
                </h5>
            </div>
            <div class="modal-body">
                <table class="table">
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
                            <tr
                                style="{{ $aDescuento->Status == 1 ? 'text-decoration: line-through; color: #9ca3af; opacity: 0.6' : '' }}">
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
                <button
                    type="button"
                    class="btn btn-sm btn-warning"
                    data-bs-dismiss="modal"
                >Cerrar </button>
            </div>
        </div>
    </div>
</div>
