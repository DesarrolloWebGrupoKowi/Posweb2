<!-- Modal Empleados -->
<div class="modal fade" id="ModalConfirmarFrecuenteSocio" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"><i class="fa fa-user"></i> Ligar Frecuente/Socio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h3 style="text-align: center">
                    ¿Desea ligar el cliente Frecuente/Socio?
                </h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <button id="btnLigarSocioFrecuente" class="btn btn-warning shadow">
                    <i class="fa fa-save"></i> Ligar Socio/Frecuente
                </button>
                <button id="btnLigarndoSocioFrecuente" hidden class="btn btn-warning" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Ligando Socio/Frecuente...
                </button>
            </div>
        </div>
    </div>
</div>