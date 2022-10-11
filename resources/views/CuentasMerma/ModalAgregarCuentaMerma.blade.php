<!-- Modal Agregar Cuenta Merma-->
<div class="modal fade" id="ModalAgregarCuentaMerma" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Cuenta Merma</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/AgregarCuentaMerma/{{ $idTipoMerma }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-4">
                            <strong><label for="" class="form-label">Libro</label></strong>
                            <input type="text" class="form-control" name="libro" id="libro" placeholder="Libro"
                                required>
                        </div>
                        <div class="col-4">
                            <strong><label for="" class="form-label">Cuenta</label></strong>
                            <input type="text" class="form-control" name="cuenta" id="cuenta"
                                placeholder="Cuenta" required>
                        </div>
                        <div class="col-4">
                            <strong><label for="" class="form-label">Subcuenta</label></strong>
                            <input type="text" class="form-control" name="subCuenta" id="subCuenta"
                                placeholder="Subcuenta" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <strong><label for="" class="form-label">Intercosto</label></strong>
                            <input type="text" class="form-control" name="intercosto" id="intercosto" placeholder="Intercosto"
                                required>
                        </div>
                        <div class="col-4">
                            <strong><label for="" class="form-label">Futuro</label></strong>
                            <input type="text" class="form-control" name="futuro" id="futuro" placeholder="Futuro"
                                required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                        <i class="fa fa-close"></i> Cerrar
                    </button>
                    <button class="btn btn-sm btn-warning">
                        <i class="fa fa-save"></i> Agregar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
