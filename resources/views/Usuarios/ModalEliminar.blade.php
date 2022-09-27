<!-- Modal Eliminar -->
<div class="modal fade" id="ModalEliminar{{$usuario->IdUsuario}}-{{$usuario->NomUsuario}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Eliminar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5 style="text-align:center;">¿Seguro que Desea Eliminar el Usuario?</h5><h4 style="text-align:center; color:red;">{{$usuario->NomUsuario}}</h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="fa fa-close"></i> Cerrar
        </button>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" href="#modalValidaUsuario{{$usuario->IdUsuario}}" role="button">
        <i class="fa fa-check"></i>  Aceptar
        </button>
      </div>
    </div>
  </div>
</div>