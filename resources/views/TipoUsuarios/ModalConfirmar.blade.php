<!-- Modal Confirmar-->
<div class="modal fade" id="ModalConfirmar{{$tipoUsuario->IdTipoUsuario}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar Tipo de Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/EliminarTipoUsuario/{{$tipoUsuario->IdTipoUsuario}}" method="POST">
                    @csrf
                    <h5 style="text-align: center">¿Seguro Desea Eliminar Tipo de Usuario?</h5>
                    <h4 style="text-align: center; color: red;">{{$tipoUsuario->NomTipoUsuario}}</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-danger" value="Eliminar">
                </form>
            </div>
        </div>
    </div>
</div>