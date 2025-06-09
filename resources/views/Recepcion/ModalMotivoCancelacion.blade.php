<!-- Modal Detalle Recepcion-->
<div class="modal fade" id="ModalMotivoCancelacion{{ $recepcion->IdCapRecepcion }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Motivo cancelación</h5>
            </div>
            <div class="modal-body">
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    {{ $recepcion->MotivoCancelacion }}
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
