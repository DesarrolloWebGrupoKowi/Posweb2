<!--modalConfirmarRecepcion-->
<div class="modal fade" id="EliminarArticulo{{ $articuloLocal->IdCapRecepcionManual }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar Articulo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row d-flex justify-content-center">
                    <div class="col-auto">
                        <h4>¿Desea Eliminar el Articulo?</h4>
                    </div>
                </div>
                <div class="row d-flex justify-content-center">
                    <div class="col-auto">
                        <h5>{{ $articuloLocal->NomArticulo }}</h5>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <form action="/EliminarArticuloSinInternet/{{ $articuloLocal->IdCapRecepcionManual }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fa fa-trash"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>