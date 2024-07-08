<!-- Modal Editar Tipo Usuario-->
<div class="modal fade" id="ModalEditar{{ $tipoUsuario->IdTipoUsuario }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Tipo de Usuario</h5>
            </div>
            <div class="modal-body">
                <form action="/EditarTipoUsuario/{{ $tipoUsuario->IdTipoUsuario }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Tipo de Usuario</label>
                        <input type="text" id="NomTipoUsuario" name="NomTipoUsuario" class="form-control rounded"
                            style="line-height: 18px" value="{{ $tipoUsuario->NomTipoUsuario }}" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-sm btn-warning" value="Editar">
                </form>
            </div>
        </div>
    </div>
</div>
