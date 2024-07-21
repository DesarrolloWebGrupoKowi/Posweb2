<!-- Modal Agregar Cuenta Merma-->
<div class="modal fade" id="ModalEditarCuentaMerma{{ $cuentaMerma->IdCatCuentaMerma }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Cuenta Merma</h5>
            </div>
            <form action="/EditarCuentaMerma/{{ $idTipoMerma }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="d-flex justify-content-center mb-3">
                        <div class="col-auto">
                            <h2>{{ $cuentaMerma->NomTipoMerma }}</h2>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4">
                            <strong><label for="" class="form-label">Libro</label></strong>
                            <input type="text" class="form-control rounded" style="line-height: 18px" name="libro"
                                id="libro" placeholder="Libro" value="{{ $cuentaMerma->Libro }}" required>
                        </div>
                        <div class="col-4">
                            <strong><label for="" class="form-label">Cuenta</label></strong>
                            <input type="text" class="form-control rounded" style="line-height: 18px" name="cuenta"
                                id="cuenta" placeholder="Cuenta" value="{{ $cuentaMerma->Cuenta }}" required>
                        </div>
                        <div class="col-4">
                            <strong><label for="" class="form-label">Subcuenta</label></strong>
                            <input type="text" class="form-control rounded" style="line-height: 18px"
                                name="subCuenta" id="subCuenta" placeholder="Subcuenta"
                                value="{{ $cuentaMerma->SubCuenta }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <strong><label for="" class="form-label">Intercosto</label></strong>
                            <input type="text" class="form-control rounded" style="line-height: 18px"
                                name="intercosto" id="intercosto" placeholder="Intercosto"
                                value="{{ $cuentaMerma->InterCosto }}" required>
                        </div>
                        <div class="col-4">
                            <strong><label for="" class="form-label">Futuro</label></strong>
                            <input type="text" class="form-control rounded" style="line-height: 18px" name="futuro"
                                id="futuro" placeholder="Futuro" value="{{ $cuentaMerma->Futuro }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                        <i class="fa fa-close"></i> Cerrar
                    </button>
                    <button class="btn btn-sm btn-warning">
                        <i class="fa fa-edit"></i> Editar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
