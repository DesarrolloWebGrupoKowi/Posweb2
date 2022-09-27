<!--Modal Validar Usuario Para Eliminar Usuario (Borrado Logico)-->

<div class="modal fade" id="modalValidaUsuario{{$usuario->IdUsuario}}" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalToggleLabel2">Confirmar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="/ConfirmContrasena/{{$usuario->IdUsuario}}" method="POST">
            @csrf
       <div class="mb-3">
        <label for="" class="form-label">Confirmar Contraseña</label>
            <input type="password" id="passAdmin" name="passAdmin" class="form-control" tabindex="3" placeholder="Contraseña" required>
        </div>

      </div>
      <div class="modal-footer">
            <button type="button" class="btn btn-warning" data-bs-dismiss="modal">
            <i class="fa fa-close"></i> Cerrar
            </button>
            <button type="submit" class="btn btn-danger">
              <i class="fa fa-trash-o"></i> Eliminar Usuario
            </button>
            </form>
        </div>
    </div>
  </div>
</div>