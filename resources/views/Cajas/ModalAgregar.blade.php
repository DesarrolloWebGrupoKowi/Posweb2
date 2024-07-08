<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Caja</h5>
            </div>
            <div class="modal-body">
                <form action="/CrearCaja" method="GET">
                    <div class="mb-3">
                        <label for="" class="form-label">Número de Caja</label>
                        <input type="number" id="NumCaja" name="NumCaja" class="form-control rounded"
                            style="line-height: 18px" tabindex="1" required placeholder="Escribe el numero de caja">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar </button>
                <button type="submit" class="btn btn-sm btn-warning">Crear </button>
                </form>
            </div>
        </div>
    </div>
</div>
