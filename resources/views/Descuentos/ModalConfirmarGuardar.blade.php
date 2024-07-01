<!--MODAL CONFIRMAR GUARDAR EDICION DE PAQUETE-->
<div class="modal fade" id="ModalConfirmarGuardar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Paquete</h5>
            </div>
            <div class="modal-body">
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    ¿Desea Editar el Paquete?
                </p>
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    {{ $descuento->NomDescuento }}
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar </button>
                <button id="btnEditarPaquete" class="btn btn-sm btn-warning">Guardar </button>
            </div>
        </div>
    </div>
</div>
