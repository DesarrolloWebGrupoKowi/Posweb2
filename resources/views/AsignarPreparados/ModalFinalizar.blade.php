<div class="modal fade" id="ModalFinalizar{{ $preparado->IdPreparado }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">El preparado quedara listo para ser asignado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p style="line-height: 1rem;">Preparado: {{ $preparado->Nombre }}</p>
            </div>
            <div class="modal-footer d-flex">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <form action="/FinalizarPreparado/{{ $preparado->IdPreparado }}" method="POST">
                    @csrf
                    <button class="btn btn-warning">
                        <i class="fa fa-reply-all"></i> Aceptar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
