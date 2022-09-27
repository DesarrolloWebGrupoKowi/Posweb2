<div class="modal fade" id="ModalEditar{{ $lCredito->IdCatLimiteCredito }}"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Editar Tipo Nómina: {{ $lCredito->NomTipoNomina }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/EditarLimiteCredito/{{ $lCredito->TipoNomina }}">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for=""><strong>Límite Crédito</strong></label>
                            <input type="number" class="form-control" name="limiteCredito" id="limiteCredito" value="{{ $lCredito->Limite }}" required>
                        </div>
                        <div class="col-6">
                            <label for=""><strong>Ventas Diarias</strong></label>
                            <input type="number" class="form-control" name="totalVentasDiaria" id="totalVentasDiaria" value="{{ $lCredito->TotalVentaDiaria }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-warning">
                        <i class="fa fa-edit"></i> Editar
                    </button>
                </form>
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>