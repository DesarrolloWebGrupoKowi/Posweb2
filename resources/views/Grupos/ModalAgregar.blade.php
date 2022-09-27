<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Grupo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/CrearGrupo" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Grupo</label>
                        <input type="text" id="NomGrupo" name="NomGrupo" class="form-control" onkeyup="mayusculas(this)" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-primary" value="Crear Grupo">
                </form>
            </div>
        </div>
    </div>
</div>