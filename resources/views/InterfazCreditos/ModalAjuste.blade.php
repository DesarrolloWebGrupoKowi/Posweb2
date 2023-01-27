<div class="modal fade" data-bs-backdrop="static" id="ModalAjuste{{ $credito->IdEncabezado }}"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Ajuste de Deuda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/AjusteDeuda/{{ $credito->IdEncabezado }}/{{ $credito->ImporteCredito }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row d-flex justify-content-start">
                        <div class="col-auto">
                            <label for="">
                                <h6>Pago</h6>
                            </label>
                            <input class="form-control" step="any" type="number" name="pagoDeuda" id="pagoDeuda"
                                value="{{ number_format($credito->ImporteCredito, 2) }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                        <i class="fa fa-close"></i> Cerrar
                    </button>
                    <button id="btnAjustaDeuda" class="btn btn-sm btn-warning">
                        <i class="fa fa-check"></i> Ajustar Deuda
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
