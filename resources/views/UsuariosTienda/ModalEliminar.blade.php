<!--Modal Eliminar-->
<div class="modal fade" id="ModalEliminar{{$usuarioTienda->IdUsuarioTienda}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title-sm" id="exampleModalLabel">Eliminar Usuario Tienda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body-sm">
                <h5 style="text-align:center;">¿Seguro que Desea Eliminar el Usuario?</h5><h4 style="text-align:center; color:red;">{{$usuarioTienda->NomUsuario}}</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
                <form action="EliminarUsuarioTienda/{{$usuarioTienda->IdUsuarioTienda}}" method="POST">
                @csrf
                <button class="btn btn-danger">
                    <i class="fa fa-trash"></i> Eliminar
                </button>
                </form>
            </div>
        </div>
    </div>
</div>