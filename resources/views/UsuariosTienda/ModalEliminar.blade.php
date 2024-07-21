<!--Modal Eliminar-->
<div class="modal fade" id="ModalEliminar{{ $usuarioTienda->IdUsuarioTienda }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title-sm" id="exampleModalLabel">Eliminar Usuario Tienda</h5>
            </div>
            <div class="modal-body">
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    ¿Seguro que desea eliminar el usuario?
                </p>
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    {{ $usuarioTienda->NomUsuario }}
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">Cerrar </button>
                <form action="EliminarUsuarioTienda/{{ $usuarioTienda->IdUsuarioTienda }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-danger">Eliminar </button>
                </form>
            </div>
        </div>
    </div>
</div>
