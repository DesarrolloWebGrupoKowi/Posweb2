<div class="modal fade" id="ModalEnviar{{ $preparado->IdPreparado }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">El preparado quedara listo para asignarse</h5>
            </div>
            <div class="modal-body">
                <p style="font-size:20px">Preparado: {{ $preparado->Nombre }}</p>
                <p style="font-size:20px">Cantidad: {{ number_format($preparado->Cantidad, 2) }} piezas</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <form class="d-flex" action="/EnviarPreparados/{{ $preparado->IdPreparado }}" method="POST">
                    @csrf
                    <button class="btn btn-primary">
                        <i class="fa fa-paper-plane"></i> Enviar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
