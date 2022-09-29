<div class="modal fade" id="ModalGuardarPedido" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Guardar Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="guardarPedido" action="/GuardarPedido">
                    <div class="mb-3">
                        <label for="">Nombre de Cliente</label>
                        <input class="form-control" type="text" name="NomCliente" onkeyup="mayusculas(this)" required>
                    </div>
                    <div class="mb-3">
                        <label for="">Teléfono</label>
                        <input class="form-control" type="tel" name="Telefono"  maxlength="10" minlength="10" required>
                    </div>
                    <div class="mb-3">
                        <label for="">Fecha a Recoger</label>
                        <input class="form-control" type="date" name="FechaRecoger" min="{{ $fechaHoy }}" max="{{ $fechaHoy }}" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <button id="btnGuardarPreventa" type="submit" class="btn btn-sm btn-warning">
                    <i class="fa fa-save"></i> Guardar Pedido
                </button>
                </form>
            </div>
        </div>
    </div>
</div>