<!-- Modal Agregar-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Grupo</h5>
            </div>
            <div class="modal-body">
                <form action="/CrearGrupo" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Grupo</label>
                        <input type="text" id="NomGrupo" name="NomGrupo" class="form-control rounded"
                            style="line-height: 18px" onkeyup="mayusculas(this)" required
                            placeholder="Escribe el nombre del grupo">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar</button>
                <input type="submit" class="btn btn-sm btn-warning" value="Crear Grupo">
                </form>
            </div>
        </div>
    </div>
</div>
