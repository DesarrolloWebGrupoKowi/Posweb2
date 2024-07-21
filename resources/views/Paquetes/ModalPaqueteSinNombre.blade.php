<!-- Modal Paquete sin Nombre-->
<div class="modal fade" id="ModalPaqueteSinNombre" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nombre del Paquete</h5>
            </div>
            <div class="modal-body">
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    No Puede Dejar El Nombre Del Paquete En Blanco
                </p>
            </div>
            <div class="modal-footer">
                <button id="btnCerrar" type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar
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
