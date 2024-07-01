<!-- Modal Confirmacion Eliminar-->
<div class="modal fade" id="ModalEliminarConfirm{{ $descuento->IdEncDescuento }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Solicitud de Eliminación</h5>
            </div>
            <div class="modal-body">
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    ¿Seguro Desea Eliminar este Descuento?
                </p>
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    {{ $descuento->NomDescuento }}
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">Cerrar </button>
                <form class="d-flex" action="EliminarDescuento/{{ $descuento->IdEncDescuento }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-danger">Eliminar </button>
                </form>
            </div>
        </div>
    </div>
</div>
