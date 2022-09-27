<!--Modal Activar Usuario-->

<div class="modal fade" id="modalActivarUsuario{{$usuario->IdUsuario}}" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalToggleLabel2">Confirmar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="ActivarUsuario/{{$usuario->IdUsuario}}" method="POST">
            @csrf
       <div class="mb-3">
        <label for="passAdmin" class="form-label">Contraseña</label>
            <input type="password" id="passAdmin" name="passAdmin" class="form-control" tabindex="3" placeholder="Contraseña" required>
        </div>
      </div>
      <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            <input type="submit" class="btn btn-warning" value="Activar Usuario">
            </form>
        </div>
    </div>
  </div>
</div>