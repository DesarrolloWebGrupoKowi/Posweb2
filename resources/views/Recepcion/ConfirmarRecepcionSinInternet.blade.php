<!--modalConfirmarRecepcion-->
<div class="modal fade" id="confirmarRecepcionSinInternet" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">¿Desea Recepcionar el Producto Local?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/RecepcionarProductoSinInternet" method="POST">
                @csrf
                <div class="modal-body">
                    <label for=""><strong>Origen de la Recepción</strong></label>
                    <input type="text" class="form-control" name="origen" placeholder="Origen" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                        <i class="fa fa-close"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-sm btn-warning">
                        <i class="fa fa-save"></i> Recepcionar Producto Local
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
