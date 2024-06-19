<!-- Modal Confirmacion Eliminar-->
<div class="modal fade" id="ModalEliminarConfirm{{ $rostisado->IdDatRosticero }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Solicitud de Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <h5>¿Seguro Desea Eliminar Este Rostizado?</h5>
                </div>
                <div class="d-flex justify-content-center">
                    <h3>{{ $rostisado->NomArticulo }}</h3>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <form class="d-flex" action="EliminarRosticero/{{ $rostisado->IdDatRosticero }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">
                        <i class="fa fa-trash-o"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
