<!-- Modal Agregar Tipo de Pago-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Tipo de Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/AgregarTipoPago">
                    <div class="mb-3">
                        <label for="" class="form-label">Tipo de Pago</label>
                        <input type="text" id="NomTipoPago" name="NomTipoPago" class="form-control" onkeyup="mayusculas(this)" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Clave Sat</label>
                        <input type="text" id="ClaveSat" name="ClaveSat" class="form-control" required>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <button class="btn btn-warning">
                    <i class="fa fa-save"></i> Agregar
                </button>
                </form>
            </div>
        </div>
    </div>
</div>