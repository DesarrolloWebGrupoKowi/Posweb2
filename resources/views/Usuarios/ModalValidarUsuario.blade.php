<!--Modal Validar Usuario Para Eliminar Usuario (Borrado Logico)-->

<div class="modal fade" id="modalValidaUsuario{{ $usuario->IdUsuario }}" aria-hidden="true"
    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel2">Confirmar Usuario</h5>
            </div>
            <div class="modal-body">
                <form action="/ConfirmContrasena/{{ $usuario->IdUsuario }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Confirmar Contraseña</label>
                        <input type="password" id="passAdmin" name="passAdmin" class="form-control rounded"
                            style="line-height: 18px" tabindex="3" placeholder="Contraseña" required>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" data-bs-dismiss="modal">Cerrar </button>
                <button type="submit" class="btn btn-sm btn-danger">Eliminar Usuario </button>
                </form>
            </div>
        </div>
    </div>
</div>
