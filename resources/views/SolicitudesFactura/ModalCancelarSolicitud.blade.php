<div class="modal fade" data-bs-backdrop="static" id="ModalCancelarSolicitud{{ $solicitud->Id }}"
    aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog    ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Desea cancelar la solicitud de Factura #{{ $solicitud->Id }}?
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/SolicitudesFactura/Cancelar/{{ $solicitud->Id }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-auto">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                        <i class="fa fa-close"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fa fa-ban"></i> Cancelar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
