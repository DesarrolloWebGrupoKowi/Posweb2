<!-- Modal Agregar Producto Manual-->
<div class="modal fade" data-bs-backdrop="static" id="ModalAgregarProductoManual" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="margin-bottom: 64px">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Producto Manual</h5>
                <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
            </div>
            <form action="/AgregarProductoManual" target="ifrProductoManual">
                <div class="modal-body">
                    <div class="row d-flex justify-content-center">
                        <div class="col-auto mt-2">
                            <input class="form-check-input" type="radio" name="radioBuscar" id="radioCodigo"
                                value="codigo" checked>
                            <label class="form-check-label" for="radioCodigo">
                                Código
                            </label>
                        </div>
                        <div class="col-auto mt-2">
                            <input class="form-check-input" type="radio" name="radioBuscar" id="radioNombre"
                                value="nombre">
                            <label class="form-check-label" for="radioNombre">
                                Nombre
                            </label>
                        </div>
                        <div class="col-auto">
                            <div class="mb-3">
                                <input class="form-control" id="filtroArticulo" type="text" name="filtroArticulo"
                                    placeholder="Buscar Articulo" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button class="btn"><span class="material-icons">search</span></button>
                        </div>
                    </div>
                    <iframe name="ifrProductoManual" width="100%" height="200vh" frameborder="0"></iframe>
                    <iframe src="/CapturaManualTmp" name="ifrGuardarTmp" frameborder="0" width="100%"
                        height="200vh"></iframe>
                </div>
            </form>
            <div class="modal-footer">
                <!--<form action="/EliminarTodosCapturaManual" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning">
                        <i class="fa fa-close"></i> Cancelar
                    </button>
                </form>-->
                <form action="/RecepcionProducto">
                    <input type="hidden" name="idRecepcion" value="{{ $idRecepcion }}">
                    <button type="submit" class="btn btn-sm btn-warning">
                        <i class="fa fa-plus-circle"></i> Aceptar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
