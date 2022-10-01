<!-- Modal Agregar Tipo Usuario-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Tipo de Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/CrearTipoUsuario" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Tipo de Usuario</label>
                        <input type="text" id="NomTipoUsuario" name="NomTipoUsuario" class="form-control"
                            onkeyup="firstMayus(this)" tabindex="1" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-warning" value="Crear">
                </form>
            </div>
        </div>
    </div>
</div>
