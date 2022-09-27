<div class="modal fade" id="ModalConsultar" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2"><i class="fa fa-search"></i> Consultar Precio Articulo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/iframeConsultarArticulo" target="ifrConsultarArticulo">
                    <div class="row d-flex justify-content-center mb-3 mt-3">
                        <div class="col-auto mt-2">
                            <input class="form-check-input" type="radio" name="radioBuscar" id="radioCodigo" value="codigo">
                            <label class="form-check-label" for="radioCodigo">
                                Código
                            </label>
                        </div>
                        <div class="col-auto mt-2">
                            <input class="form-check-input" type="radio" name="radioBuscar" id="radioNombre" value="nombre" checked>
                            <label class="form-check-label" for="radioNombre">
                                Nombre
                            </label>
                        </div>
                        <div class="col-auto">
                            <div class="mb-3">
                                <input class="form-control" id="filtroArticulo" type="text" name="filtroArticulo" placeholder="Buscar Articulo" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button class="btn"><span class="material-icons">search</span></button>
                        </div>
                    </div>
                </form>
                <iframe name="ifrConsultarArticulo" width="100%" height="350vh" frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>