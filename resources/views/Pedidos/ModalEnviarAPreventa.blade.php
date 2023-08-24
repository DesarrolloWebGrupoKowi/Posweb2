<div class="modal fade" id="ModalEnviarAPreventa{{ $pedido->IdPedido }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalToggleLabel2">Enviar Pedido a PreVenta</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/EnviarAPreventa/{{ $pedido->IdPedido }}" method="POST">
                    @csrf
                    <div class="d-flex justify-content-center">
                        <h5>{{ $pedido->Cliente }} - ${{ number_format($pedido->ImportePedido, 2) }}</h5>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <button type="submit" class="btn btn-warning">
                    <i class="fa fa-share-square-o"></i> Enviar a Preventa
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
