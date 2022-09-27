<!-- Modal Editar Articulo-->
<div class="modal fade" id="ModalEditar-{{ $familia->IdFamilia }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Familia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/#" method="POST">
                    @csrf
                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <button class="btn btn-warning">
                    <i class="fa fa-pencil-square-o"></i> Editar
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
