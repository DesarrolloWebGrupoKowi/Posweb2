<!-- Modal Cancelar Recepción-->
<div class="modal fade" id="ModalCancelarRecepcion{{ $rTienda->IdCapRecepcion }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cancelar Recepción - {{ $rTienda->PackingList }}</h5>
            </div>
            <form id="formCancelar" action="/CancelarRecepcion/{{ $rTienda->IdCapRecepcion }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label for=""><strong>Motivo de Cancelación</strong></label>
                            <textarea class="form-control" name="motivoCancelacion" id="motivoCancelacion" cols="30" rows="5" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                        <i class="fa fa-close"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fa fa-ban"></i> Cancelar Recepción
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
