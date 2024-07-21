<!--MODAL ELIMINAR ARTICULO TIPO DE MERMA-->
<div class="modal fade" id="ModalEliminarArticuloTipoMerma{{ $tipoMermaArticulo->CodArticulo }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar Artículo Por Tipo de Merma</h5>
            </div>
            <form action="/EliminarArticuloTipoMerma/{{ $idTipoMerma }}/{{ $tipoMermaArticulo->CodArticulo }}"
                method="POST">
                @csrf
                <div class="modal-body">
                    <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                        ¿Desea Eliminar El Artículo?
                    </p>
                    <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                        {{ $tipoMermaArticulo->NomArticulo }}
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">Cerrar </button>
                    <button type="submit" class="btn btn-sm btn-danger">Eliminar </button>
            </form>
        </div>
    </div>
</div>
