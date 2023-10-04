<!-- Modal Agregar Tabla-->
<div class="modal fade" data-bs-backdrop="static" id="ModalAgregarTabla" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Tabla</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/AgregarTablas" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-auto">
                            <label for="">Nombre</label>
                            <input class="form-control" type="text" name="nomTabla" id="nomTabla"
                                placeholder="Nombre Tabla" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                        <i class="fa fa-close"></i> Cerrar
                    </button>
                    <button class="btn btn-sm btn-warning">
                        <i class="fa fa-save"></i> Agregar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
