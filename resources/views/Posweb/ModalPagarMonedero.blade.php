<!-- Modal Pagar Monedero-->
<div class="modal fade" data-bs-backdrop="static" id="ModalPagarMonedero" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Pagar Con Monedero Electrónico</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="/PagoMonedero" method="POST">
                @csrf
                <div class="row">
                    <div class="col-auto">
                        <div class="input-group mb-3">
                            <input type="number" class="form-control" name="pagoMonedero" value="{{ $monederoEmpleado }}" required>
                            <button class="input-group-text btn-warning">Pagar Con Monedero</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" data-bs-dismiss="modal">
              <i class="fa fa-close"></i> Cerrar
            </button>
        </div>
      </div>
    </div>
  </div>