<div class="modal hide fade" id="ModalConfirmarSolicitudCliente" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">
                    @if (!empty($nomCliente))
                        Confirmar Solicitud
                    @else
                        Confirmar Solicitud
                    @endif
                </h5>
            </div>
            <div class="modal-body">
                @if (!empty($nomCliente))
                    <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                        {{ $nomCliente->NomCliente }}
                    </p>
                @else
                    <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                        ¿Desea agregar el cliente y solicitar la factura?
                    </p>
                @endif
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-warning"> Confirmar </button>
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"> Cerrar </button>
            </div>
        </div>
    </div>
</div>
