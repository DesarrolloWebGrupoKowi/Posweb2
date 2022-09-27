<!-- Modal Agregar Banco-->
<div class="modal fade" data-bs-backdrop="static" id="ModalAgregarBanco" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Banco</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/AgregarBanco" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-auto">
                            <label for=""><strong>Banco</strong></label>
                            <input type="text" class="form-control" name="nomBanco" id="nomBanco" placeholder="Nombre" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                        <i class="fa fa-close"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-sm btn-warning">
                        <i class="fa fa-save"></i> Agregar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
