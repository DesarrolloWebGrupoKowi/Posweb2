<div class="modal fade" data-bs-backdrop="static" id="ModalConfirmarCancelacionSolicitudFE{{ $ticket->IdTicket }}"
    aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog    ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Desea Cancelar El Ticket #{{ $ticket->IdTicket }}?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <h4 class="titulo">El ticket tiene solicitud de factura</h4>
                </div>
                <div class="row">
                    <h4 class="titulo">¿Desea cancelarlo?</h4>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#ModalConfirmarCancelacion{{ $ticket->IdTicket }}">
                    <i class="fa fa-ban"></i> Cancelar Ticket
                </button>
            </div>
        </div>
    </div>
</div>
