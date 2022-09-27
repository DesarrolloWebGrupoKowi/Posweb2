<div class="modal hide fade" data-bs-backdrop="static" id="ModalConfirmarSolicitudCliente" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">
                    @if (!empty($nomCliente))
                    <i class="fa fa-info-circle"></i> ¿Desea Solicitar la Factura para el Cliente?
                    @else
                    <i class="fa fa-info-circle"></i> Nuevo Cliente
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if (!empty($nomCliente))
                    <h5 class="titulo">{{ $nomCliente->NomCliente }}</h5>
                @else
                    <h5 class="titulo">¿Desea Agregar el Cliente & Solicitar La Factura?</h5>
                @endif
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-warning">
                    <i class="fa fa-plus"></i> Guardar
                </button>
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>