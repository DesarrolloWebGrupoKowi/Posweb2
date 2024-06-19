<div class="modal fade" id="ModalConfirmarGuardarMermas" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
    tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title">Guardar Mermas</h5>
            </div>
            <div class="modal-body">
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    ¿Estas seguro de mermar los productos?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"> Cerrar </button>
                <form action="/GuardarMermas" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning"> Mermar Productos </button>
                </form>
            </div>
        </div>
    </div>
</div>
