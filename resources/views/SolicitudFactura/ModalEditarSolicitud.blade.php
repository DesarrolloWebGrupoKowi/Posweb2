<div class="modal hide fade" id="ModalEditarSolicitud{{ $solicitudFactura->IdSolicitudFactura }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Subir Constancia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/SubirConstanciaSolicitud/{{ $solicitudFactura->IdSolicitudFactura }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row px-4">
                        <label for="">Constancia Situación Fiscal</label>
                        <input type="file" class="form-control" name="cSituacionFiscal" id="cSituacionFiscal"
                            required>
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-warning">
                    <i class="fa fa-plus"></i> Guardar
                </button>
                </form>
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
