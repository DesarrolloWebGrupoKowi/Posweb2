<div class="modal fade" id="ModalConfirmarEdit{{ $nMovimiento->PivotCliente->IdDatNotificacionesClienteCloud }}"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Ligar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>¿Desea Ligar el Cliente a la Solicitud de Factura?</h6>
            </div>
            <div class="modal-footer">
                <form action="#" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-warning">
                        <i class="fa fa-bookmark"></i> Ligar Cliente
                    </button>
                </form>
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>