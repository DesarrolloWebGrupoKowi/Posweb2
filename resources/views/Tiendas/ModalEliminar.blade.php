<!-- Modal Eliminar-->
<div class="modal fade" id="ModalEliminar{{ $tienda->IdTienda }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">¿Seguro que desea Eliminar?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="EliminarTienda/{{ $tienda->IdTienda }}" method="POST">
                    @csrf
                    <div>
                        <h5 style="color: red; text-align:center;">{{ $tienda->NomTienda }}</h5>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <button class="btn btn-danger">
                    <i class="fa fa-trash-o"></i> Elimnar
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
