<div class="modal hide fade" id="ModalConfirm" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">
                    @if ($cliente->count() == 0)
                        <i class="fa fa-exclamation-triangle"></i> No Se Encontro Ningun Cliente
                    @else
                        <i class="fa fa-id-badge"></i> Cliente Para Solicitud de Factura
                    @endif
                </h5>
            </div>
            <div class="modal-body">
                @if ($cliente->count() == 0)
                    <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                        ¿Estas seguro de agregar un nuevo cliente?
                    </p>
                @else
                    <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                        {{ $nomCliente->NomCliente }}
                    </p>
                @endif
            </div>
            <div class="modal-footer">
                @if ($cliente->count() == 0)
                    <button type="button" id="btnNuevoCliente" data-bs-dismiss="modal" class="btn btn-sm btn-warning">
                        <i class="fa fa-plus"></i> Agregar
                    </button>
                @else
                    <button type="button" id="btnClienteExistente" data-bs-dismiss="modal"
                        class="btn btn-sm btn-warning">
                        <i class="fa fa-check"></i> Aceptar
                    </button>
                @endif
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
