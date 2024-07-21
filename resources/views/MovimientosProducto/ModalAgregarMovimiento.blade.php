<!-- Modal Agregar Movimiento-->
<div class="modal fade" id="ModalAgregarMovimiento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Nuevo Movimiento de Producto</h5>
            </div>
            <form action="/AgregarMovimiento" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row d-flex justify-content-center">
                        <div class="col-auto">
                            <label for="">Agregar Nuevo Movimiento de Producto</label>
                            <input type="text" class="form-control rounded" style="line-height: 18px"
                                name="nomMovimiento" id="nomMovimiento" placeholder="Movimiento" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                        <i class="fa fa-close"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
