<!-- Modal Agregar Banco-->
<div class="modal fade" id="ModalAgregarBanco" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Banco</h5>
            </div>
            <form action="/AgregarBanco" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-auto">
                            <label>Banco</label>
                            <input type="text" class="form-control rounded" style="line-height: 18px" name="nomBanco"
                                id="nomBanco" placeholder="Nombre banco" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar </button>
                    <button type="submit" class="btn btn-sm btn-warning">Agregar </button>
                </div>
            </form>
        </div>
    </div>
</div>
