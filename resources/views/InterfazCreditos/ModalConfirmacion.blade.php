<div class="modal fade" data-bs-backdrop="static" id="ModalConfirmarInterfazCreditos"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Interfazar Créditos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 class="titulo">¿Desea interfazar los créditos?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <form
                    action="InterfazarCreditos/{{ $fecha1 }}/{{ $fecha2 }}/{{ empty($idTipoNomina) ? 0 : $idTipoNomina }}/{{ empty($numNomina) ? 0 : $numNomina }}"
                    method="POST">
                    @csrf
                    <div class="d-flex justify-content-center">
                        <button id="btnExportar" class="btn btn-warning">
                            <i class="fa fa-check"></i> Interfazar Créditos
                        </button>
                        <button id="btnCargandoDatos" hidden class="btn btn-warning" type="button" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Interfazando créditos...
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
