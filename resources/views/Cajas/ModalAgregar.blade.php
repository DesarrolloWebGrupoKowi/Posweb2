<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Caja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/CrearCaja" method="GET">
                    <div class="mb-3">
                        <label for="" class="form-label">Número de Caja</label>
                        <input type="number" id="NumCaja" name="NumCaja" class="form-control" tabindex="1" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <button type="submit" class="btn btn-warning">
                    <i class="fa fa-save"></i> Crear
                </button>
                </form>
            </div>
        </div>
    </div>
</div>