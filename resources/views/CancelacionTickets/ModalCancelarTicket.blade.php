<div class="modal fade" id="ModalCancelarTicket" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
    tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title">¿Desea Solicitar Cancelación para el Ticket #{{ $ticket->IdTicket }}?</h5>
            </div>
            <form action="/SolicitarCancelacion/{{ $ticket->IdEncabezado }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-auto">
                            <textarea class="form-control" name="motivoCancelacion" id="motivoCancelacion" cols="60" rows="5"
                                placeholder="Motivo de Cancelación" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                        <i class="fa fa-close"></i> Cerrar
                    </button>
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fa fa-ban"></i> Solicitar cancelación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
