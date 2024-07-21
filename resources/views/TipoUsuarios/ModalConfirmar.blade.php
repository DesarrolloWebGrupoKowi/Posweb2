<!-- Modal Confirmar-->
<div class="modal fade" id="ModalConfirmar{{ $tipoUsuario->IdTipoUsuario }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar Tipo de Usuario</h5>
            </div>
            <div class="modal-body">
                <form action="/EliminarTipoUsuario/{{ $tipoUsuario->IdTipoUsuario }}" method="POST">
                    @csrf
                    <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                        ¿Seguro Desea Eliminar Tipo de Usuario?
                    </p>
                    <p class="fs-6 text-center fw-normal text-secondary m-0" style="line-height: 24px">
                        {{ $tipoUsuario->NomTipoUsuario }}
                    </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-sm btn-danger" value="Eliminar">
                </form>
            </div>
        </div>
    </div>
</div>
