<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregarDetalle{{ $rostisado->IdDatRosticero }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Detalle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/AgregarDetalleRosticero/{{ $rostisado->IdRosticero }}" method="POST">
                    @csrf
                    <div>
                        <label for="" class="form-label mb-0">Rostizado</label>
                        <input class="form-control" type="text" step="any" minlength="12" maxlength="12"
                            name="codigo" placeholder="Código rostizado" required autofocus>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-warning" value="Agregar Rostisado">
                </form>
            </div>
        </div>
    </div>
</div>
