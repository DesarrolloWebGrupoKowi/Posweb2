<div class="modal fade" data-bs-backdrop="static" id="ModalEliminarMermaTmp{{ $merma->IdTmpMerma }}"
    aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Desea Eliminar la Merma?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h3 class="titulo">{{ $merma->NomArticulo }} - {{ number_format($merma->CantArticulo, 2) }}</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <form action="/EliminarMermaTmp/{{ $merma->IdTmpMerma }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-danger">
                        <i class="fa fa-trash-o"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>