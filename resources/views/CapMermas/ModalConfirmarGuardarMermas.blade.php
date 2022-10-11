<div class="modal fade" data-bs-backdrop="static" id="ModalConfirmarGuardarMermas" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog    ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Guardar Mermas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h3 class="titulo">¿Desea Mermar los Productos?</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <form action="/GuardarMermas" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning">
                        <i class="fa fa-check"></i> Mermar Productos
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
