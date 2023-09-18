<div class="modal fade" id="ModalEliminarPreparado{{ $preparado->IdPreparado }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="white-space: wrap">¿Desea eliminar el preparado y sus articulos agregados?
                </h5>
            </div>
            <div class="modal-body">
                <p class="titulo">Preparado: {{ $preparado->Nombre }}</p>
                <p class="titulo">Cantidad: {{ number_format($preparado->Cantidad, 2) }} piezas</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <form class="d-flex" action="/EliminarPreparados/{{ $preparado->IdPreparado }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-danger">
                        <i class="fa fa-trash-o"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
