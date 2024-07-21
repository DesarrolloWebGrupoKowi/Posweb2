<div class="modal fade" id="ModalEliminar{{ $datPedido->IdDetPedidoTmp }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalToggleLabel2">¿Seguro Desea Eliminar Este Articulo?</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/EliminarArticuloPedido/{{ $datPedido->IdDetPedidoTmp }}" method="POST">
                    @csrf
                    <div class="d-flex justify-content-center">
                        <h5>{{ $datPedido->NomArticulo }}</h5>
                    </div>
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
</div>
