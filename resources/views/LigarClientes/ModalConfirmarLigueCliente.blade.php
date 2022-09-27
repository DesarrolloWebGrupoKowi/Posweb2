<div class="modal hide fade" data-bs-backdrop="static" id="ModalConfirmarLigueCliente" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2"><i class="fa fa-address-book"></i> Ligar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 class="d-flex justify-content-center">¿Desea Ligar el Cliente a la Solicitud de Factura?</h4>
            </div>
            <div class="modal-footer">
                <form action="/GuardarLigueCliente/{{ $solicitudFactura->IdSolicitudFactura }}/{{ $cOracle->BILL_TO }}" method="POST">
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