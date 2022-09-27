<!-- Modal Editar Tipo Usuario-->
<div class="modal fade" id="ModalEditar{{$tipoUsuario->IdTipoUsuario}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Tipo de Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/EditarTipoUsuario/{{$tipoUsuario->IdTipoUsuario}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Tipo de Usuario</label>
                        <input type="text" id="NomTipoUsuario" name="NomTipoUsuario" class="form-control" value="{{$tipoUsuario->NomTipoUsuario}}" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-warning" value="Editar">
                </form>
            </div>
        </div>
    </div>
</div>