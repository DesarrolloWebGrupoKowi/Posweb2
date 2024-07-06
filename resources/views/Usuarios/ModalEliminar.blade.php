<!-- Modal Eliminar -->
<div class="modal fade" id="ModalEliminar{{ $usuario->IdUsuario }}-{{ $usuario->NomUsuario }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Desactivar Usuario</h5>
            </div>
            <div class="modal-body">
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    ¿Seguro que desea desacticar el usuario?
                </p>
                <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                    {{ $usuario->NomUsuario }}
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar </button>
                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                    href="#modalValidaUsuario{{ $usuario->IdUsuario }}" role="button">Aceptar </button>
            </div>
        </div>
    </div>
</div>
