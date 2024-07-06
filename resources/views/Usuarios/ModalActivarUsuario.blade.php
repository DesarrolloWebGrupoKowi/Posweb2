<!--Modal Activar Usuario-->

<div class="modal fade" id="modalActivarUsuario{{ $usuario->IdUsuario }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Activar usuario</h5>
            </div>
            <div class="modal-body">
                <form action="ActivarUsuario/{{ $usuario->IdUsuario }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="passAdmin" class="form-label">Contraseña</label>
                        <input type="password" id="passAdmin" name="passAdmin" class="form-control rounded"
                            style="line-height: 18px" tabindex="3" placeholder="Contraseña" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-sm btn-warning" value="Activar Usuario">
                </form>
            </div>
        </div>
    </div>
</div>
