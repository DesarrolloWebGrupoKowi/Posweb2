<div class="modal fade" id="ModalConfirmarInterfazCreditos" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Interfazar Créditos</h5>
            </div>
            <div class="modal-body">
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    ¿Estas seguro de interfazar los créditos?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar </button>
                <form
                    action="InterfazarCreditos/{{ $fecha1 }}/{{ $fecha2 }}/{{ empty($idTipoNomina) ? 0 : $idTipoNomina }}/{{ empty($numNomina) ? 0 : $numNomina }}"
                    method="POST">
                    @csrf
                    <div class="d-flex justify-content-center">
                        <button id="btnExportar" class="btn btn-warning"> Interfazar Créditos </button>
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
