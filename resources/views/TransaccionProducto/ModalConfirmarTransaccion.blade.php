<!-- Modal Confirmar Transaccion-->
<div class="modal fade" id="ModalConfirmarTransaccion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirmar transacción</h5>
            </div>
            <div class="modal-body">
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    ¿Estas seguro de generar la transacción?
                </p>
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px" id="destino"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <button id="btnGuardarTransaccion" type="button" class="btn btn-sm btn-warning"
                    data-bs-dismiss="modal">
                    <i class="fa fa-save"></i> Generar Transacción
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('btnGuardarTransaccion').addEventListener('click', (e) => {
        document.getElementById('formTransaccion').submit();
    });
</script>
