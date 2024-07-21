<div class="modal fade" id="ModalCancelarSolicitud{{ $solicitud->Id }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title">Cancelar solicitud</h5>
            </div>
            <form action="/SolicitudesFactura/Cancelar/{{ $solicitud->Id }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                        ¿Estas seguro de cancelar la solicitud de factura #{{ $solicitud->Id }}?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">Cerrar </button>
                    <button type="submit" class="btn btn-sm btn-danger">Cancelar Solicitud </button>
                </div>
            </form>
        </div>
    </div>
</div>
