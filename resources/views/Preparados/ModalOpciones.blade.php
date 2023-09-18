<div class="modal fade" id="ModalOpciones{{ $preparado->IdPreparado }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2"><i class="fa fa-bars"></i> Opciones Rapidas</h5>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-4 d-flex justify-content-center">
                        <button class="btn btn-default btn-sm" data-bs-toggle="modal"
                            data-bs-target="#ModalEditar{{ $preparado->IdPreparado }}">
                            <span class="material-icons" style="font-size: 30px; color: orange;">edit</span><br>
                            <label style="cursor: pointer;">Editar Preparado</label>
                        </button>
                    </div>
                    <div class="col-4 d-flex justify-content-center">
                        <button class="btn btn-default btn-sm" data-bs-toggle="modal"
                            data-bs-target="#ModalEliminarPreparado{{ $preparado->IdPreparado }}">
                            <span class="material-icons eliminar"
                                style="font-size: 30px; color: orange;">delete_forever</span><br>
                            <label style="cursor: pointer;">Eliminar Preparado</label>
                        </button>
                    </div>
                    <div class="col-4 d-flex justify-content-center">
                        <button class="btn btn-default btn-sm" data-bs-toggle="modal"
                            data-bs-target="#ModalEnviar{{ $preparado->IdPreparado }}"
                            {{ !$preparado->Cantidad ? 'disabled' : '' }}>
                            <span class="material-icons send" style="font-size: 30px; color: orange;">send</span><br>
                            <label style="cursor: pointer;">Enviar</label>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
