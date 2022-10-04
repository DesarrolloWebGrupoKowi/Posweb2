<!-- Modal Confirmar Transaccion-->
<div class="modal fade" id="ModalConfirmarTransaccion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">¿Desea Generar la Transacción de Producto?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 style="text-align: center">Destino:</h4>
                <h4 style="text-align:center" id="destino"></h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <button id="btnGuardarTransaccion" type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
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
