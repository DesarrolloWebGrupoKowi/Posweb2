<!-- Modal Eliminar Tipo Articulo-->
<div class="modal fade" id="ModalEliminarTipoArticulo{{ $tipoArticulo->IdCatTipoArticulo }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar Tipo de Articulo</h5>
            </div>
            <form action="/EliminarTipoArticulo/{{ $tipoArticulo->IdCatTipoArticulo }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                        ¿Seguro que desea eliminar este tipo de articulo?
                    </p>
                    <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                        {{ $tipoArticulo->IdTipoArticulo }} - {{ $tipoArticulo->NomTipoArticulo }}
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">Cerrar </button>
                    <button class="btn btn-sm btn-danger">Eliminar </button>
                </div>
            </form>
        </div>
    </div>
</div>
