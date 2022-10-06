<div class="modal fade" data-bs-backdrop="static" id="ModalConfirmarCancelacion{{ $ticket->IdTicket }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog    ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Desea Cancelar El Ticket #{{ $ticket->IdTicket }}?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/CancelarTicket/{{ $idTienda }}/{{ $fechaVenta }}/{{ $numTicket }}" method="POST">
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
                        <i class="fa fa-ban"></i> Cancelar Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
