<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregarPreparado" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/Preparados" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Preparado</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label mb-0">Nombre del preparado</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Nombre del preparado"
                            autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label mb-0">Cantidad</label>
                        <input type="number" name="cantidad" min="0" class="form-control"
                            placeholder="Cantidad">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Agregar preparado">
                </div>
            </form>
        </div>
    </div>
</div>
