<div class="modal fade" id="mensajeError{{ $detalleCorte->IdCortesTienda }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Mensaje de Error</h5>
            </div>
            <div class="modal-body">
                <p class="fs-6 fw-normal m-0 text-danger" style="line-height: 24px; text-wrap: wrap;">
                    {{ $detalleCorte->MENSAJE_ERROR }}
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal"> Cerrar </button>
            </div>
        </div>
    </div>
</div>
