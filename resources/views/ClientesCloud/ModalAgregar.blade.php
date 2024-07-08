<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Cliente Cloud</h5>
            </div>
            <div class="modal-body">
                <form action="/BuscarCustomer" target="ifrBuscarCustomer">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-4">
                                <input type="text" class="form-control rounded" style="line-height: 18px"
                                    name="txtFiltro" required>
                            </div>
                            <div class="col-2">
                                <button class="btn btn-dark-outline">
                                    @include('components.icons.search')
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                <iframe name="ifrBuscarCustomer" width="100%" height="300vh"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"> Cerrar </button>
            </div>
        </div>
    </div>
</div>
