<!--MODAL AGREGAR TIPO DE MERMA-->
<div class="modal fade" id="ModalAgregarTipoMerma" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Tipo de Merma</h5>
            </div>
            <form action="/CrearTipoMerma" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-auto">
                            <input type="text" class="form-control rounded" style="line-height: 18px"
                                name="nomTipoMerma" id="nomTipoMerma" placeholder="Nombre de Tipo de Merma" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar </button>
                    <button type="submit" class="btn btn-sm btn-warning">Crear </button>
            </form>
        </div>
    </div>
</div>
