<div class="modal fade" id="ModalRegresar{{ $preparado->IdPreparado }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">El preparado sera devuelto a preparación</h5>
            </div>
            <div class="modal-body">
                <p style="line-height: 1rem;">Preparado: {{ $preparado->Nombre }}</p>
                <p style="line-height: 1rem;">Cantidad:
                    {{ number_format($preparado->Cantidad, 2) }} piezas</p>
            </div>
            <div class="modal-footer d-flex">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <form action="/RegresarPreparado/{{ $preparado->IdPreparado }}" method="POST">
                    @csrf
                    <button class="btn btn-warning">
                        <i class="fa fa-reply-all"></i> Regresar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
