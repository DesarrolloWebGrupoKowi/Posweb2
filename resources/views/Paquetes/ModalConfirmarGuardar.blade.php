<!--MODAL CONFIRMAR GUARDAR EDICION DE PAQUETE-->
<div class="modal fade" data-bs-backdrop="static" id="ModalConfirmarGuardar" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Paquete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <div class="col-auto">
                        <h5>¿Desea Editar el Paquete?</h5>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <div class="col-auto">
                        <h4>{{ $nomPaquete }}</h4>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <button id="btnEditarPaquete" class="btn btn-sm btn-warning">
                    <i class="fa fa-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>
