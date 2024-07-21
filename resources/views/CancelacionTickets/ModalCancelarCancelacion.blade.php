<div class="modal fade" id="ModalCancelarCancelacion{{ $solicitud->IdEncabezado }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog    ">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title">¿Desea cancelar la solicitud del Ticket #{{ $solicitud->Encabezado->IdTicket }}?
                </h5>
            </div>
            <form action="/CancelarTicket/Cancelar/{{ $solicitud->IdEncabezado }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-auto">
                            <textarea class="form-control" name="motivoCancelacion" id="motivoCancelacion" cols="60" rows="5"
                                placeholder="Motivo de Cancelación" required>{{ $solicitud->MotivoCancelacion }}
                            </textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">Cerrar </button>
                    <button type="submit" class="btn btn-sm btn-danger"> Cancelar Solicitud </button>
                </div>
            </form>
        </div>
    </div>
</div>
