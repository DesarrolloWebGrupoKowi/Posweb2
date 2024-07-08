<!--MODAL AGREGAR SUB   TIPO DE MERMA-->
<div class="modal fade" id="ModalAgregarSubTipoMerma" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Sub Tipo de Merma</h5>
            </div>
            <form action="/CrearSubTipoMerma/{{ $idTipoMerma }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-auto">
                            <input type="text" class="form-control rounded" style="line-height: 18px"
                                name="nomSubTipoMerma" id="nomSubTipoMerma" placeholder="Nombre Sub Tipo de Merma"
                                required>
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
