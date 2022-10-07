<!--MODAL ELIMINAR ARTICULO TIPO DE MERMA-->
<div class="modal fade" data-bs-backdrop="static" id="ModalEliminarArticuloTipoMerma{{ $tipoMermaArticulo->CodArticulo }}"
    tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar Articulo Por Tipo de Merma</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/EliminarArticuloTipoMerma/{{ $idTipoMerma }}/{{ $tipoMermaArticulo->CodArticulo }}"
                method="POST">
                @csrf
                <div class="modal-body">
                    <h5 class="titulo">¿Desea Eliminar el articulo?</h5>
                    <h4 class="titulo">{{ $tipoMermaArticulo->NomArticulo }}</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                        <i class="fa fa-close"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fa fa-trash-o"></i> Eliminar
                    </button>
            </form>
        </div>
    </div>
</div>
