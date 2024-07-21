<!-- Modal Confirmacion Eliminar-->
<div class="modal fade" id="ModalEliminarConfirm{{ $item->IdCatProdDiez }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Solicitud de Eliminación</h5>
            </div>
            <div class="modal-body">
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    ¿Seguro Desea Eliminar Este Producto?
                </p>
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    {{ $item->NomArticulo }}
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <form class="d-flex" action="EliminarCatProdDiez/{{ $item->IdCatProdDiez }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-sm btn-danger">
                        <i class="fa fa-trash-o"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
