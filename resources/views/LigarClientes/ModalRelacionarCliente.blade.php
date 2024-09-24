<div class="modal fade" id="ModalRelacionarCliente{{ $cliente->IdCatCliente }}" aria-labelledby="exampleModalToggleLabel2"
    tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Ligar Cliente</h5>
            </div>
            <div class="modal-body">
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    ¿Estas seguro de ligar la solicitud de factura?
                </p>
                <p class="fs-6 text-center fw-normal pt-1 text-secondary m-0" style="line-height: 24px"><span style="font-weight: 500">SITIO: </span>{{ $cliente->Sitio }}</p>
                <p class="fs-6 text-center fw-normal pt-1 text-secondary m-0" style="line-height: 24px"><span style="font-weight: 500">CLIENTE: </span>{{ $cliente->IdClienteCloud }}</p>
                <p class="fs-6 text-center fw-normal pt-1 text-secondary m-0" style="line-height: 24px"><span style="font-weight: 500">SHIP TO: </span>{{ $cliente->Ship_To }}</p>
                <p class="fs-6 text-center fw-normal pt-1 text-secondary m-0" style="line-height: 24px"><span style="font-weight: 500">BILL TO: </span>{{ $cliente->Bill_To }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <a href="{{ '/SolicitudesFactura/Relacionar/' . $solicitud->Id . '/' . $cliente->Bill_To }}"
                    class="btn btn-sm btn-warning">
                    <i class="fa fa-bookmark"></i> Ligar Cliente
                </a>
                {{-- <form action="#" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-warning">
                        <i class="fa fa-bookmark"></i> Ligar Cliente
                    </button>
                </form> --}}
            </div>
        </div>
    </div>
</div>
