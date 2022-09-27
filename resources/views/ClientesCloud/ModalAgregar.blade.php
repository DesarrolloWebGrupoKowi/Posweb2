<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregar" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Cliente Cloud</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/BuscarCustomer" target="ifrBuscarCustomer">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-4">
                                <input type="text" class="form-control" name="txtFiltro" required>
                            </div>
                            <div class="col-2">
                                <button class="btn">
                                    <span class="material-icons">search</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                <iframe name="ifrBuscarCustomer" width="100%" height="300vh"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>