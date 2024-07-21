<!--Modal Precios-->
<div class="modal fade" id="ModalPrecios{{ $listaCodEtiqueta->IdArticulo }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Precios del Articulo: {{ $listaCodEtiqueta->NomArticulo }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="">
                    <thead>
                        <tr>
                            @foreach ($listaCodEtiqueta->PrecioArticulo as $listaPrecio)
                                <th>{{ $listaPrecio->NomListaPrecio }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach ($listaCodEtiqueta->PrecioArticulo as $pArticulo)
                                <td><i class="fa fa-usd"></i>
                                    {{ number_format($pArticulo->PivotPrecio->PrecioArticulo, 2) }}</td>
                            @endforeach
                        </tr>
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
