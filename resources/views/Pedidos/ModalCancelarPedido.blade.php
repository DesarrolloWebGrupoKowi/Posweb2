<div class="modal fade" id="ModalCancelar{{ $pedido->IdPedido}}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalToggleLabel2">¿Seguro Desea Cancelar el Pedido del Cliente?</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/CancelarPedido/{{ $pedido->IdPedido }}" method="POST">
                    @csrf
                    <div class="d-flex justify-content-center">
                        <h5>{{ $pedido->Cliente }}</h5>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="fa fa-ban"></i> Cancelar Pedido
                </button>
                </form>
            </div>
        </div>
    </div>
</div>