<div class="modal fade" data-bs-backdrop="static" id="ModalEnviar{{ $preparado->IdPreparado }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">El preparado quedara listo para asignarse</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="titulo">Preparado: {{ $preparado->Nombre }}</p>
                <p class="titulo">Cantidad: {{ number_format($preparado->Cantidad, 2) }} piezas</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <form action="/EnviarPreparados/{{ $preparado->IdPreparado }}" method="POST">
                    @csrf
                    <button class="btn btn-primary">
                        <i class="fa fa-paper-plane"></i> Enviar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
