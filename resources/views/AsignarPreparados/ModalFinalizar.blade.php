<div class="modal fade" id="ModalFinalizar{{ $preparado->IdPreparado }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title">Finalizar preparado</h5>
            </div>
            <div class="modal-body">
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">¿Estas seguro de finalizar el preparado?</p>
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">{{$preparado->Nombre}}</p>
            </div>
            <div class="modal-footer d-flex">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <form action="/FinalizarPreparado/{{ $preparado->IdPreparado }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-warning">
                        <i class="fa fa-reply-all"></i> Aceptar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
