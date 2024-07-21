<!-- Modal Agregar Tabla-->
<div class="modal fade" id="ModalAgregarTabla" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Tabla</h5>
            </div>
            <form action="/AgregarTablas" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-auto">
                            <label for="">Nombre</label>
                            <input class="form-control rounded" style="line-height: 18px" type="text" name="nomTabla"
                                id="nomTabla" placeholder="Nombre Tabla" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar </button>
                    <button class="btn btn-sm btn-warning">Agregar </button>
                </div>
            </form>
        </div>
    </div>
</div>
