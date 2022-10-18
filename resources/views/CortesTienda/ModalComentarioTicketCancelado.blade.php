<div class="modal fade" data-bs-backdrop="static" id="ModalComentarioTicketCancelado{{ $ticket->IdEncabezado }}"
    aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2"><i class="fa fa-info-circle"></i> Ticket :
                    {{ $ticket->IdTicket }} - {{ strftime('%d %B %Y, %H:%M', strtotime($ticket->FechaVenta)) }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="d-flex justify-content-center">Ticket Cancelado Por:
                                {{ !empty($ticket->UsuarioCancelacion->NomUsuario) ? Str::upper($ticket->UsuarioCancelacion->NomUsuario) : '' }}
                            </h6 class="d-flex justify-content-center">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">
                                {{ !empty($ticket->MotivoCancel) ? Str::upper($ticket->MotivoCancel) : '' }}</h5>
                            </h5>
                        </div>
                        <div class="card-footer">
                            <p class="card-text d-flex justify-content-center">
                                <strong>{{ !empty($ticket->FechaCancelacion) ? strftime('%d %B %Y, %H:%M', strtotime($ticket->FechaCancelacion)) : '' }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
