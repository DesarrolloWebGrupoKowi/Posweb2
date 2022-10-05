<!-- Modal Paquete sin Nombre-->
<div class="modal fade" id="ModalPaqueteSinNombre" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-exclamation-circle"></i> Nombre del Paquete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 style="text-align: center">No Puede Dejar El Nombre del Paquete en Blanco</h4>
            </div>
            <div class="modal-footer">
                <button id="btnCerrar" type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('btnCerrar').addEventListener('click', (e) => {
        document.getElementById('nomPaquete').focus();
    });
</script>