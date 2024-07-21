<!-- Modal Agregar Tipo Articulo-->
<div class="modal fade" id="ModalAgregarTipoArticulo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Tipo de Articulo</h5>
            </div>
            <form action="/AgregarTipoArticulo" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="" class="form-label">Unidad de Negocio (CLAVE)</label>
                        <input type="text" class="form-control rounded" style="line-height: 18px"
                            name="idTipoArticulo" id="idTipoArticulo" placeholder="Unidad de Negocio" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Nombre Tipo de Articulo</label>
                        <input type="text" class="form-control rounded" style="line-height: 18px"
                            name="nomTipoArticulo" id="nomTipoArticulo" placeholder="Nombre" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar </button>
                    <button class="btn btn-sm btn-warning">Agregar </button>
                </div>
            </form>
        </div>
    </div>
</div>
