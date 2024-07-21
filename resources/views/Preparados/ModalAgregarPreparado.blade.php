<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregarPreparado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <form action="/Preparados" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Preparado</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label mb-0">Nombre del preparado</label>
                        <input type="text" name="nombre" class="form-control rounded" style="line-height: 18px"
                            placeholder="Nombre del preparado" autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label mb-0">Cantidad (PIEZAS)</label>
                        <input type="number" name="cantidad" min="0" step=".1" class="form-control rounded"
                            style="line-height: 18px" placeholder="Cantidad en piezas">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    <input type="submit" class="btn btn-sm btn-warning" value="Agregar preparado">
                </div>
            </form>
        </div>
    </div>
</div>
