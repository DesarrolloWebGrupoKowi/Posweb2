<div class="modal fade" id="ModalComentarioTicketCancelado{{ $ticket->IdDatEncabezado }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2"> Motivo de cancelación </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    Ticket cancelado por:
                    {{ !empty($ticket->UsuarioCancelacion->NomUsuario) ? Str::upper($ticket->UsuarioCancelacion->NomUsuario) : '' }}
                </p>
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    {{ !empty($ticket->MotivoCancel) ? Str::upper($ticket->MotivoCancel) : '' }}
                </p>
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    {{ !empty($ticket->FechaCancelacion) ? strftime('%d %B %Y', strtotime($ticket->FechaCancelacion)) : '' }}
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal"> Cerrar </button>
            </div>
        </div>
    </div>
