<div class="modal fade" data-bs-backdrop="static" id="ModalEliminarPreparado{{ $preparado->IdPreparado }}"
    aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Desea eliminar el preparado y sus articulos agregados?</h5>
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
                <form action="/EliminarPreparados/{{ $preparado->IdPreparado }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-danger">
                        <i class="fa fa-trash-o"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
