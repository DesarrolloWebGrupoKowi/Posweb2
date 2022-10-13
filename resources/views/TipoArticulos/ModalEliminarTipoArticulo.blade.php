<!-- Modal Eliminar Tipo Articulo-->
<div class="modal fade" id="ModalEliminarTipoArticulo{{ $tipoArticulo->IdCatTipoArticulo }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar Tipo de Articulo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/EliminarTipoArticulo/{{ $tipoArticulo->IdCatTipoArticulo }}" method="POST">
                @csrf
                <div class="modal-body">
                    <h5 class="titulo">¿Desea eliminar este tipo de articulo?</h5>
                    <h4 class="titulo">{{ $tipoArticulo->IdTipoArticulo }} - {{ $tipoArticulo->NomTipoArticulo }}</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                        <i class="fa fa-close"></i> Cerrar
                    </button>
                    <button class="btn btn-sm btn-danger">
                        <i class="fa fa-trash-o"></i> Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>