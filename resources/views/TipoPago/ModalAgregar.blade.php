<!-- Modal Agregar Tipo de Pago-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Tipo de Pago</h5>
            </div>
            <div class="modal-body">
                <form action="/AgregarTipoPago">
                    <div class="mb-3">
                        <label for="" class="form-label">Tipo de Pago</label>
                        <input type="text" id="NomTipoPago" name="NomTipoPago" class="form-control rounded"
                            style="line-height: 18px" onkeyup="mayusculas(this)" required
                            placeholder="Escribe el tipo de pago">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Clave Sat</label>
                        <input type="text" id="ClaveSat" name="ClaveSat" class="form-control rounded"
                            style="line-height: 18px" required placeholder="Escribe la clave Sat">
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar </button>
                <button class="btn btn-sm btn-warning">Agregar </button>
                </form>
            </div>
        </div>
    </div>
</div>
